<?php namespace MusicCollection\Handlers;

use MusicCollection\Databases\Objects\Artist;

/**
 * Class ArtistHandler
 * @package System\Handlers
 */
class ArtistHandler extends BaseHandler
{
    use FillAndValidate\Artist;

    private Artist $artist;

    protected function initialize(): void
    {
        if ($this->session->get('errors')) {
            $this->errors = array_merge($this->session->get('errors'), $this->errors);
        }

        $this->session->delete('errors');
    }

    protected function index(): void
    {
        //Get all artists
        $artists = Artist::getAll();

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $this->t->artist->index->pageTitle,
            'artists' => $artists,
            'totalArtists' => count($artists)
        ]);
    }

    /**
     * @throws \Exception
     */
    protected function add(): void
    {
        //If not logged in, redirect to login
        if (!$this->session->keyExists('user')) {
            $location = $this->router->getFullPathByName('artist.add');
            header('Location: ' . BASE_PATH . 'user/login?location=' . $location);
            exit;
        }

        //Set default empty artist
        $this->artist = new Artist();

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $this->t->artist->add->pageTitle,
            'artist' => $this->artist,
            'success' => $this->session->get('success'),
            'errors' => $this->errors
        ]);

        $this->session->delete('success');
    }

    /**
     * @param string $id
     */
    protected function edit(string $id): void
    {
        //If not logged in, redirect to login
        if (!$this->session->keyExists('user')) {
            $location = $this->router->getFullPathByName('artist.edit', ['id' => $id]);
            header('Location: ' . BASE_PATH . 'user/login?location=' . $location);
            exit;
        }

        try {
            //Get the record from the db & execute POST logic
            $this->artist = Artist::getById($id);
            $pageTitle = "{$this->t->artist->edit->pageTitlePrefix} {$this->artist->name}";
        } catch (\Exception $e) {
            $this->logger->error($e);
            $this->artist = new Artist();
            $this->errors[] = $this->t->general->errors->general;
            $pageTitle = $this->t->artist->notExists;
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $pageTitle,
            'artist' => $this->artist,
            'success' => $this->session->get('success'),
            'errors' => $this->errors
        ]);

        $this->session->delete('success');
    }

    protected function save(): void
    {
        try {
            //Get the record from the db & execute POST logic
            $this->artist = new Artist();
            $this->executePostHandler();

            //Database magic when no errors are found
            if (isset($this->formData) && empty($this->errors)) {
                //Set user id in Artist
                $this->artist->user_id = $this->session->get('user')->id;

                //Save the record to the db
                $state = $this->artist->id === 0 ? 'add' : 'edit';
                if ($this->artist->save()) {
                    $this->session->set('success', $this->t->artist->{$state}->success);
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
     * @param int $id
     */
    protected function detail(int $id): void
    {
        try {
            //Get the records from the db
            $artist = Artist::getById($id);

            //Default page title
            $pageTitle = $artist->name;
        } catch (\Exception $e) {
            //Something went wrong on this level
            $this->errors[] = $this->t->general->errors->general;
            $pageTitle = $this->t->artist->notExists;
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $pageTitle,
            'artist' => $artist ?? false,
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
            $artist = Artist::getById($id);

            //Only execute delete when confirmed
            if (isset($_GET['continue'])) {
                //Delete genre
                if (Artist::delete($id)) {
                    //Redirect to homepage after deletion & exit script
                    header('Location: ' . BASE_PATH . 'artists');
                    exit;
                }
            }

            //Return formatted data
            $this->renderTemplate([
                'pageTitle' => $this->t->artist->delete->title,
                'artist' => $artist,
                'errors' => $this->errors
            ]);
        } catch (\Exception $e) {
            //There is no delete template, always redirect.
            $this->logger->error($e);
            header('Location: ' . BASE_PATH . 'artists');
            exit;
        }
    }
}
