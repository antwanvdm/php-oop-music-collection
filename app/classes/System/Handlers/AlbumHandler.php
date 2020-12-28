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
    protected function initialize()
    {
        $this->image = new Image();
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

    protected function add(): void
    {
        //If not logged in, redirect to login
        if (!$this->session->keyExists('user')) {
            header('Location: ' . BASE_PATH . 'user/login?location=albums/add');
            exit;
        }

        //Set default empty album & execute POST logic
        $this->album = new Album();
        $this->album->genres = []; //@TODO Blegh
        $this->executePostHandler();

        //Special check for add form only
        if (isset($this->formData) && $_FILES['image']['error'] == 4) {
            $this->errors[] = $this->t->album->validation->image;
        }

        //Database magic when no errors are found
        if (isset($this->formData) && empty($this->errors)) {
            //Store image & retrieve name for database saving
            $this->album->image = 'images/' . $this->image->save($_FILES['image']);

            //Set user id in Album
            $this->album->user_id = $this->session->get('user')->id;

            //Save the record to the db
            if ($this->album->save()) {
                $success = $this->t->album->edit->success;
                //Override to see a new empty form
                $this->album = new Album();
                $this->album->genres = []; //@TODO Blegh
            } else {
                $this->errors[] = $this->t->general->errors->dbSave;
            }
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $this->t->album->add->pageTitle,
            'album' => $this->album,
            'artists' => Artist::getAll(),
            'albumGenreIds' => array_map(function ($genre) {
                return $genre->id;
            }, $this->album->genres),
            'genres' => Genre::getAll(),
            'success' => $success ?? false,
            'errors' => $this->errors
        ]);
    }

    /**
     * @param string $id
     * @throws \Exception
     */
    protected function edit(string $id): void
    {
        try {
            //Get the record from the db & execute POST logic
            $this->album = Album::getById($id);
            $this->album->genres(); //@TODO blegh
            $this->executePostHandler();

            //Database magic when no errors are found
            if (isset($this->formData) && empty($this->errors)) {
                //If image is not empty, process the new image file
                if ($_FILES['image']['error'] != 4) {
                    //Remove old image
                    $this->image->delete($this->album->image);

                    //Store new image & retrieve name for database saving (override current image name)
                    $this->album->image = 'images/' . $this->image->save($_FILES['image']);
                }

                //Save the record to the db
                if ($this->album->save()) {
                    $success = $this->t->album->edit->success;
                } else {
                    $this->errors[] = $this->t->general->errors->dbSave;
                }
            }

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
            'success' => $success ?? false,
            'errors' => $this->errors
        ]);
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
                header("Location: " . BASE_PATH . 'albums');
                exit;
            }
        } catch (\Exception $e) {
            //There is no delete template, always redirect.
            $this->logger->error($e);
            header("Location: " . BASE_PATH . 'albums');
            exit;
        }
    }
}
