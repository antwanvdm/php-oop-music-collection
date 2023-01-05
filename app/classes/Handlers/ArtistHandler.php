<?php namespace MusicCollection\Handlers;

use MusicCollection\Translation\Translator as T;
use MusicCollection\Utils\Logger;
use MusicCollection\Databases\Objects\Artist;

/**
 * Class ArtistHandler
 * @package MusicCollection\Handlers
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

    /**
     * @throws \Exception
     */
    protected function index(): void
    {
        //Get all artists
        $artists = Artist::getAll();

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => T::__('artist.index.pageTitle'),
            'artists' => $artists,
            'totalArtists' => count($artists)
        ]);
    }

    /**
     * @throws \Exception
     */
    protected function create(): void
    {
        //If not logged in, redirect to login page
        if (!$this->session->keyExists('user')) {
            $location = $this->router->getFullPathByName('artist.create');
            header('Location: ' . BASE_PATH . 'user/login?location=' . $location);
            exit;
        }

        //Set default empty artist
        $this->artist = new Artist();

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => T::__('artist.create.pageTitle'),
            'artist' => $this->artist,
            'success' => $this->session->get('success'),
            'errors' => $this->errors
        ]);

        $this->session->delete('success');
    }

    /**
     * @param int $id
     * @throws \Exception
     */
    protected function edit(int $id): void
    {
        //If not logged in, redirect to login page
        if (!$this->session->keyExists('user')) {
            $location = $this->router->getFullPathByName('artist.edit', ['id' => $id]);
            header('Location: ' . BASE_PATH . 'user/login?location=' . $location);
            exit;
        }

        try {
            //Get the record from the db & execute POST logic
            $this->artist = Artist::getById($id);
            $pageTitle = T::__('artist.edit.pageTitle', ['NAME' => $this->artist->name]);
        } catch (\Exception $e) {
            Logger::error($e);
            $this->artist = new Artist();
            $this->errors[] = T::__('general.errors.general');
            $pageTitle = T::__('artist.notExists');
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
                $state = $this->artist->id === 0 ? 'create' : 'edit';
                if ($this->artist->save()) {
                    $this->session->set('success', T::__('artist.' . $state . '.success'));
                } else {
                    $this->errors[] = T::__('general.errors.dbSave');
                }
            }
        } catch (\Exception $e) {
            Logger::error($e);
            $this->errors[] = T::__('general.errors.general');
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
            Logger::error($e);
            $this->errors[] = T::__('general.errors.general');
            $pageTitle = T::__('artist.notExists');
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
                'pageTitle' => T::__('artist.delete.title'),
                'artist' => $artist,
                'errors' => $this->errors
            ]);
        } catch (\Exception $e) {
            //There is no delete template, always redirect.
            Logger::error($e);
            header('Location: ' . BASE_PATH . 'artists');
            exit;
        }
    }
}
