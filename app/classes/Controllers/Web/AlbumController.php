<?php namespace MusicCollection\Controllers\Web;

use MusicCollection\Controllers\BaseController;
use MusicCollection\Databases\Models\Album;
use MusicCollection\Databases\Models\Artist;
use MusicCollection\Databases\Models\Enums\AlbumRecording;
use MusicCollection\Databases\Models\Genre;
use MusicCollection\Databases\Models\User;
use MusicCollection\Responses\View;
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
     * @return View
     * @throws \Exception
     */
    protected function index(): View
    {
        //Get all albums
        $albums = Album::getAll(['artist', 'genres']);

        //Return formatted data
        return $this->view->render('album.index', [
            'pageTitle' => T::__('album.index.pageTitle'),
            'albums' => $albums,
            'totalAlbums' => count($albums)
        ]);
    }

    /**
     * @return View
     * @throws \Exception
     */
    protected function create(): View
    {
        //Set default empty album & execute POST logic
        $this->album = $this->session->get('album') ?? new Album();

        $success = $this->session->get('success');
        $this->session->delete('success');
        $this->session->delete('album');

        //Return formatted data
        return $this->view->render('album.create', [
            'pageTitle' => T::__('album.create.pageTitle'),
            'album' => $this->album,
            'artists' => Artist::getAll(),
            'recordingCases' => AlbumRecording::cases(),
            'genres' => Genre::getAll(),
            'genreIds' => $this->album->getGenresIds(),
            'success' => $success,
            'errors' => $this->errors
        ]);
    }

    /**
     * @param int $id
     * @return View
     * @throws \Exception
     */
    protected function edit(int $id): View
    {
        try {
            //Get the record from the db & execute POST logic
            if ($this->session->keyExists('album')) {
                $this->album = $this->session->get('album');
            } else {
                $this->album = Album::getById($id, ['artist', 'genres']);
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

        $success = $this->session->get('success');
        $this->session->delete('success');
        $this->session->delete('album');

        //Return formatted data
        return $this->view->render('album.edit', [
            'pageTitle' => $pageTitle,
            'album' => $this->album,
            'artists' => Artist::getAll(),
            'recordingCases' => AlbumRecording::cases(),
            'genres' => Genre::getAll(),
            'genreIds' => $this->album->getGenresIds(),
            'success' => $success,
            'errors' => $this->errors
        ]);
    }

    protected function save(): never
    {
        try {
            //Prepare a new object & execute POST logic
            $id = (int)$this->request->input('id');
            $this->album = $id === 0 ? new Album() : Album::getById($id);
            $this->saveValidate();

            //Database magic when no errors are found
            if (empty($this->errors)) {
                //If image is not empty, process the new image file
                if ($this->request->file('image')['error'] != 4 && $id !== 0) {
                    //Remove old image
                    $this->image->delete($this->album->image);

                    //Store new image & retrieve name for database saving (override current image name)
                    $this->album->image = $this->image->save($this->request->file('image'));
                } elseif ($id === 0) {
                    //Store image & retrieve name for database saving
                    $this->album->image = $this->image->save($this->request->file('image'));
                }

                //Set user id in Album
                $this->album->user_id = $this->session->get('user')->id;

                //Save the record to the db
                $state = $id === 0 ? 'create' : 'edit';
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
            $this->album->artist_id = (int)$this->request->input('artist-id');
            $this->album->name = $this->request->input('name');
            $this->album->recording = AlbumRecording::from($this->request->input('recording'));
            $this->album->year = $this->request->input('year');
            $this->album->tracks = (int)$this->request->input('tracks');
            $this->album->image = $this->request->input('current-image');
            $this->album->setGenresIds($this->request->input('genre-ids'));

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
     * @return View
     */
    protected function detail(int $id): View
    {
        try {
            //Get the records from the db
            $album = Album::getById($id, ['artist', 'genres']);

            //Default page title
            $pageTitle = T::__('album.madeBy', [
                'NAME' => $album->name,
                'ARTIST' => $album->artist->name
            ]);

            $isLoggedIn = $this->session->keyExists('user');
            if ($isLoggedIn) {
                $user = User::getById($this->session->get('user')->id, ['favoriteAlbums']);
                $isFavorite = in_array($album->id, array_map(fn (Album $album) => $album->id, $user->favoriteAlbums));
            }
        } catch (\Exception $e) {
            //Something went wrong on this level
            Logger::error($e);
            $this->errors[] = T::__('general.errors.general');
            $pageTitle = T::__('album.notExists');
        }

        //Return formatted data
        return $this->view->render('album.detail', [
            'pageTitle' => $pageTitle,
            'album' => $album ?? false,
            'isLoggedIn' => $isLoggedIn ?? false,
            'isFavorite' => $isFavorite ?? false,
            'errors' => $this->errors
        ]);
    }

    /**
     * @param int $id
     * @return View
     */
    protected function delete(int $id): View
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
            return $this->view->render('album.delete', [
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
