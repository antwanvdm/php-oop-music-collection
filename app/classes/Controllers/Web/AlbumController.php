<?php namespace MusicCollection\Controllers\Web;

use MusicCollection\Controllers\BaseController;
use MusicCollection\Databases\Models\Album;
use MusicCollection\Databases\Models\Artist;
use MusicCollection\Databases\Models\Genre;
use MusicCollection\Translation\Translator as T;
use MusicCollection\Utils\Image;
use MusicCollection\Utils\Logger;
use MusicCollection\Validation\AlbumValidator;

/**
 * Class AlbumController
 * @package MusicCollection\Controllers\Web
 */
class AlbumController extends BaseController
{
    private Album $album;
    private Image $image;

    /**
     * Called from the parent constructor
     */
    protected function initialize(): void
    {
        $this->image = new Image();

        if ($this->session->get('errors')) {
            $this->errors = array_merge($this->session->get('errors'), $this->errors);
        }

        $this->session->delete('errors');
    }

    /**
     * @throws \Exception
     */
    protected function index(): void
    {
        //Get all albums
        $albums = Album::getAll();

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => T::__('album.index.pageTitle'),
            'albums' => $albums,
            'totalAlbums' => count($albums)
        ]);
    }

    /**
     * @throws \Exception
     */
    protected function create(): void
    {
        //Set default empty album & execute POST logic
        $this->album = $this->session->get('album') ?? new Album();

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => T::__('album.create.pageTitle'),
            'album' => $this->album,
            'artists' => Artist::getAll(),
            'genres' => Genre::getAll(),
            'genreIds' => $this->album->getGenreIds(),
            'success' => $this->session->get('success'),
            'errors' => $this->errors
        ]);

        $this->session->delete('success');
        $this->session->delete('album');
    }

    /**
     * @param int $id
     * @throws \Exception
     */
    protected function edit(int $id): void
    {
        try {
            //Get the record from the db & execute POST logic
            $this->album = Album::getById($id);
            $this->album->setGenreIds(array_map(fn(Genre $genre) => $genre->id, $this->album->genres()));

            //Overwrite values from previous POST (form had errors)
            //TODO make this more beautiful because this stinks.
            if ($this->session->keyExists('album')) {
                $this->album->artist_id = $this->session->get('album')->artist_id;
                $this->album->name = $this->session->get('album')->name;
                $this->album->setGenreIds($this->session->get('album')->getGenreIds());
                $this->album->year = $this->session->get('album')->year;
                $this->album->tracks = $this->session->get('album')->tracks;
            }

            $pageTitle = T::__('album.edit.pageTitle', [
                'ALBUM' =>
                    T::__('album.madeBy', [
                        'NAME' => $this->album->name,
                        'ARTIST' => $this->album->artist->name
                    ])
            ]);
        } catch (\Exception $e) {
            Logger::error($e);
            $this->album = new Album();
            $this->errors[] = T::__('general.errors.general');
            $pageTitle = T::__('album.notExists');
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $pageTitle,
            'album' => $this->album,
            'artists' => Artist::getAll(),
            'genres' => Genre::getAll(),
            'genreIds' => $this->album->getGenreIds(),
            'success' => $this->session->get('success'),
            'errors' => $this->errors
        ]);

        $this->session->delete('success');
        $this->session->delete('album');
    }

    protected function save(): void
    {
        try {
            //Prepare a new object & execute POST logic
            $this->album = new Album();
            $this->saveValidate();
            $isNew = $this->album->id === 0;

            //Database magic when no errors are found
            if (empty($this->errors)) {
                //If image is not empty, process the new image file
                if ($this->request->file('image')['error'] != 4 && !$isNew) {
                    //Remove old image
                    $this->image->delete($this->album->image);

                    //Store new image & retrieve name for database saving (override current image name)
                    $this->album->image = $this->image->save($this->request->file('image'));
                } elseif ($isNew) {
                    //Store image & retrieve name for database saving
                    $this->album->image = $this->image->save($this->request->file('image'));
                }

                //Set user id in Album
                $this->album->user_id = $this->session->get('user')->id;

                //Save the record to the db
                $state = $this->album->id === 0 ? 'create' : 'edit';
                if ($this->album->save()) {
                    if ($this->album->saveGenres()) {
                        $this->session->set('success', T::__('album.' . $state . '.success'));
                    } else {
                        $this->errors[] = T::__('general.errors.dbSave');
                    }
                } else {
                    $this->errors[] = T::__('general.errors.dbSave');
                }
            }
        } catch (\Exception $e) {
            Logger::error($e);
            $this->errors[] = T::__('general.errors.general');
        }

        $this->session->set('errors', $this->errors);
        if (!empty($this->errors)) {
            $this->session->set('album', $this->album);
        }

        header('Location: ' . $this->request->previousPath());
        exit;
    }

    public function saveValidate(): void
    {
        if ($this->request->hasInput('submit')) {
            //Override object with new variables
            $this->album->id = (int)$this->request->input('id');
            $this->album->artist_id = (int)$this->request->input('artist-id');
            $this->album->name = $this->request->input('name');
            $this->album->year = $this->request->input('year');
            $this->album->tracks = (int)$this->request->input('tracks');
            $this->album->image = $this->request->input('current-image');
            $this->album->setGenreIds($this->request->input('genre-ids'));

            //Actual validation
            $validator = new AlbumValidator($this->album);
            $validator->validate();
            $this->errors = $validator->getErrors();

            if ($this->album->id === 0 && $this->request->file('image')['error'] == 4) {
                $this->errors[] = T::__('album.validation.image');
            }
        }
    }

    /**
     * @param int $id
     */
    protected function detail(int $id): void
    {
        try {
            //Get the records from the db
            $album = Album::getById($id);

            //Default page title
            $pageTitle = T::__('album.madeBy', [
                'NAME' => $album->name,
                'ARTIST' => $album->artist->name
            ]);
        } catch (\Exception $e) {
            //Something went wrong on this level
            Logger::error($e);
            $this->errors[] = T::__('general.errors.general');
            $pageTitle = T::__('album.notExists');
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $pageTitle,
            'album' => $album ?? false,
            'errors' => $this->errors
        ]);
    }

    /**
     * @param int $id
     */
    protected function delete(int $id): void
    {
        try {
            //Get the record from the db
            $album = Album::getById($id);

            //Only execute delete when confirmed
            if ($this->request->hasQuery('continue')) {
                //Delete genre
                if (Album::delete($id)) {
                    //Remove image
                    $this->image->delete($album->image);

                    //Redirect to homepage after deletion & exit script
                    header('Location: ' . BASE_PATH . 'albums');
                    exit;
                }
            }

            //Return formatted data
            $this->renderTemplate([
                'pageTitle' => T::__('album.delete.title'),
                'album' => $album,
                'errors' => $this->errors
            ]);
        } catch (\Exception $e) {
            //There is no delete template, always redirect.
            Logger::error($e);
            header('Location: ' . BASE_PATH . 'albums');
            exit;
        }
    }
}
