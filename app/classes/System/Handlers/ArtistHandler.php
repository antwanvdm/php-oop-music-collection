<?php namespace System\Handlers;

use System\Databases\Objects\Artist;

/**
 * Class ArtistHandler
 * @package System\Handlers
 */
class ArtistHandler extends BaseHandler
{
    use FillAndValidate\Artist;

    private Artist $artist;

    protected function index(): void
    {
        //Get all artists
        $artists = Artist::getAll();

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => 'Artists',
            'artists' => $artists,
            'totalArtists' => count($artists)
        ]);
    }

    protected function add(): void
    {
        //If not logged in, redirect to login
        if (!$this->session->keyExists('user')) {
            header('Location: ' . BASE_PATH . 'user/login?location=artists/add');
            exit;
        }

        //Set default empty artist & execute POST logic
        $this->artist = new Artist();
        $this->executePostHandler();

        //Database magic when no errors are found
        if (isset($this->formData) && empty($this->errors)) {
            //Set user id in Artist
            $this->artist->user_id = $this->session->get('user')->id;

            //Save the record to the db
            if ($this->artist->save()) {
                $success = "Your new artist has been added to the database!";
                //Override to see a new empty form
                $this->artist = new Artist();
            } else {
                $this->errors[] = "Whoops, something went wrong adding the artist";
            }
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => 'Add artist',
            'artist' => $this->artist,
            'success' => $success ?? false,
            'errors' => $this->errors
        ]);
    }

    /**
     * @param string $id
     */
    protected function edit(string $id): void
    {
        try {
            //Get the record from the db & execute POST logic
            $this->artist = Artist::getById($id);
            $this->executePostHandler();

            //Database magic when no errors are found
            if (isset($this->formData) && empty($this->errors)) {
                //Save the record to the db
                if ($this->artist->save()) {
                    $success = "Your artist has been updated in the database!";
                } else {
                    $this->errors[] = "Whoops, something went wrong updating the artist";
                }
            }

            $pageTitle = 'Edit ' . $this->artist->name;
        } catch (\Exception $e) {
            $this->logger->error($e);
            $this->artist = new Artist();
            $this->errors[] = "Whoops: " . $e->getMessage();
            $pageTitle = 'Artist does\'t exist';
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $pageTitle,
            'artist' => $this->artist,
            'success' => $success ?? false,
            'errors' => $this->errors
        ]);
    }

    /**
     * @param string $id
     */
    protected function detail(string $id): void
    {
        try {
            //Get the records from the db
            $artist = Artist::getById($id);

            //Default page title
            $pageTitle = $artist->name;
        } catch (\Exception $e) {
            //Something went wrong on this level
            $this->errors[] = $e->getMessage();
            $pageTitle = 'Artist does\'t exist';
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $pageTitle,
            'artist' => $artist ?? false,
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
            $artist = Artist::getById($id);

            //Database magic when no errors are found
            if (Artist::delete($id)) {
                //Redirect to homepage after deletion & exit script
                header("Location: " . BASE_PATH . "artists");
                exit;
            }
        } catch (\Exception $e) {
            //There is no delete template, always redirect.
            $this->logger->error($e);
            header("Location: " . BASE_PATH . "artists");
            exit;
        }
    }
}
