<?php namespace MusicCollection\Handlers;

use MusicCollection\Databases\Objects\Album;
use MusicCollection\Databases\Objects\Artist;
use MusicCollection\Databases\Objects\Genre;
use MusicCollection\Utils\Image;

/**
 * Class AlbumHandler
 * @package MusicCollection\Handlers
 */
class AlbumHandler extends BaseHandler
{
    use FillAndValidate\Album;

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

    protected function index(): void
    {
        //Get all albums
        $albums = Album::getAll();

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $this->t->_('album.index.pageTitle'),
            'albums' => $albums,
            'totalAlbums' => count($albums)
        ]);
    }

    /**
     * @throws \Exception
     */
    protected function create(): void
    {
        //If not logged in, redirect to login
        if (!$this->session->keyExists('user')) {
            $location = $this->router->getFullPathByName('album.create');
            header('Location: ' . BASE_PATH . 'user/login?location=' . $location);
            exit;
        }

        //Set default empty album & execute POST logic
        $this->album = $this->session->get('album') ?? new Album();

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $this->t->_('album.create.pageTitle'),
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
     * @param string $id
     * @throws \Exception
     */
    protected function edit(string $id): void
    {
        //If not logged in, redirect to login
        if (!$this->session->keyExists('user')) {
            $location = $this->router->getFullPathByName('album.edit', ['id' => $id]);
            header('Location: ' . BASE_PATH . 'user/login?location=' . $location);
            exit;
        }

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

            $pageTitle = $this->t->_('album.edit.pageTitle', [
                'ALBUM' =>
                    $this->t->_('album.madeBy', [
                        'NAME' => $this->album->name,
                        'ARTIST' => $this->album->artist->name
                    ])
            ]);
        } catch (\Exception $e) {
            $this->logger->error($e);
            $this->album = new Album();
            $this->errors[] = $this->t->_('general.errors.general');
            $pageTitle = $this->t->_('album.notExists');
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
            //Get the record from the db & execute POST logic
            $this->album = new Album();
            $this->executePostHandler();
            $isNew = $this->album->id === 0;

            //Database magic when no errors are found
            if (isset($this->formData) && empty($this->errors)) {
                //If image is not empty, process the new image file
                if ($_FILES['image']['error'] != 4 && !$isNew) {
                    //Remove old image
                    $this->image->delete($this->album->image);

                    //Store new image & retrieve name for database saving (override current image name)
                    $this->album->image = $this->image->save($_FILES['image']);
                } elseif ($isNew) {
                    //Store image & retrieve name for database saving
                    $this->album->image = $this->image->save($_FILES['image']);
                }

                //Set user id in Album
                $this->album->user_id = $this->session->get('user')->id;

                //Save the record to the db
                $state = $this->album->id === 0 ? 'create' : 'edit';
                if ($this->album->save()) {
                    if ($this->album->saveGenres()) {
                        $this->session->set('success', $this->t->_('album.' . $state . '.success'));
                    } else {
                        $this->errors[] = $this->t->_('general.errors.dbSave');
                    }
                } else {
                    $this->errors[] = $this->t->_('general.errors.dbSave');
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e);
            $this->errors[] = $this->t->_('general.errors.general');
        }

        $this->session->set('errors', $this->errors);
        $this->session->set('album', $this->album);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
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
            $pageTitle = $this->t->_('album.madeBy', [
                'NAME' => $album->name,
                'ARTIST' => $album->artist->name
            ]);
        } catch (\Exception $e) {
            //Something went wrong on this level
            $this->errors[] = $this->t->_('general.errors.general');
            $pageTitle = $this->t->_('album.notExists');
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
            if (isset($_GET['continue'])) {
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
                'pageTitle' => $this->t->_('album.delete.title'),
                'album' => $album,
                'errors' => $this->errors
            ]);
        } catch (\Exception $e) {
            //There is no delete template, always redirect.
            $this->logger->error($e);
            header('Location: ' . BASE_PATH . 'albums');
            exit;
        }
    }
}
