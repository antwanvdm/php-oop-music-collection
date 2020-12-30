<?php namespace System\Handlers;

use System\Databases\Objects\Artist;
use System\Databases\Objects\Genre;
use System\Databases\Objects\Album;
use System\Utils\Image;

/**
 * Class AlbumHandler
 * @package System\Handlers
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
            'pageTitle' => $this->t->album->index->pageTitle,
            'albums' => $albums,
            'totalAlbums' => count($albums)
        ]);
    }

    /**
     * @throws \Exception
     */
    protected function add(): void
    {
        //If not logged in, redirect to login
        if (!$this->session->keyExists('user')) {
            $location = $this->router->getFullPathByName('album.add');
            header('Location: ' . BASE_PATH . 'user/login?location=' . $location);
            exit;
        }

        //Set default empty album & execute POST logic
        $this->album = new Album();
        $this->album->genres = []; //@TODO Blegh

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $this->t->album->add->pageTitle,
            'album' => $this->album,
            'artists' => Artist::getAll(),
            'albumGenreIds' => array_map(function ($genre) {
                return $genre->id;
            }, $this->album->genres),
            'genres' => Genre::getAll(),
            'success' => $this->session->get('success'),
            'errors' => $this->errors
        ]);

        $this->session->delete('success');
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
            $this->album->genres(); //@TODO blegh
            $pageTitle = "{$this->t->album->edit->pageTitlePrefix} '{$this->album->name} {$this->t->album->madeBy} {$this->album->artist->name}'";
        } catch (\Exception $e) {
            $this->logger->error($e);
            $this->album = new Album();
            $this->album->genres = []; //@TODO Blegh
            $this->errors[] = $this->t->general->errors->general;
            $pageTitle = $this->t->album->notExists;
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $pageTitle,
            'album' => $this->album,
            'artists' => Artist::getAll(),
            'albumGenreIds' => array_map(function ($genre) {
                return $genre->id;
            }, $this->album->genres),
            'genres' => Genre::getAll(),
            'success' => $this->session->get('success'),
            'errors' => $this->errors
        ]);

        $this->session->delete('success');
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
                    $this->album->image = 'images/' . $this->image->save($_FILES['image']);
                } elseif ($isNew) {
                    //Store image & retrieve name for database saving
                    $this->album->image = 'images/' . $this->image->save($_FILES['image']);
                }

                //Set user id in Album
                $this->album->user_id = $this->session->get('user')->id;

                //Save the record to the db
                $state = $this->album->id === 0 ? 'add' : 'edit';
                if ($this->album->save()) {
                    $this->session->set('success', $this->t->album->{$state}->success);
                } else {
                    $this->errors[] = $this->t->general->errors->dbSave;
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e);
            $this->errors[] = $this->t->general->errors->general;
        }

        $this->session->set('errors', $this->errors);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    /**
     * @param string id (@TODO see if we can somehow make this an int dynamically)
     */
    protected function detail(string $id): void
    {
        try {
            //Get the records from the db
            $album = Album::getById($id);

            //Default page title
            $pageTitle = "{$album->name} {$this->t->album->madeBy} {$album->artist->name}";
        } catch (\Exception $e) {
            //Something went wrong on this level
            $this->errors[] = $this->t->general->errors->general;
            $pageTitle = $this->t->album->notExists;
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $pageTitle,
            'album' => $album ?? false,
            'errors' => $this->errors
        ]);
    }

    /**
     * @param string $id
     */
    protected function delete(string $id): void
    {
        try {
            //Get the record from the db
            $album = Album::getById($id);

            //Database magic when no errors are found
            if (Album::delete($id)) {
                //Remove image
                $this->image->delete($album->image);

                //Redirect to homepage after deletion & exit script
                header('Location: ' . BASE_PATH . 'albums');
                exit;
            }
        } catch (\Exception $e) {
            //There is no delete template, always redirect.
            $this->logger->error($e);
            header('Location: ' . BASE_PATH . 'albums');
            exit;
        }
    }
}
