<?php namespace MusicCollection\Controllers\Web;

use MusicCollection\Controllers\BaseController;
use MusicCollection\Databases\Models\Artist;
use MusicCollection\Responses\View;
use MusicCollection\Translation\Translator as T;
use MusicCollection\Utils\Logger;
use MusicCollection\Validation\ArtistValidator;

/**
 * Class ArtistController
 * @package MusicCollection\Controllers\Web
 */
class ArtistController extends BaseController
{
    private Artist $artist;

    protected function initialize(): void
    {
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
        //Get all artists
        $artists = Artist::getAll(['albums']);

        //Return formatted data
        return $this->view->render('artist.index', [
            'pageTitle' => T::__('artist.index.pageTitle'),
            'artists' => $artists,
            'totalArtists' => count($artists)
        ]);
    }

    /**
     * @return View
     */
    protected function create(): View
    {
        //Set default empty artist
        $this->artist = new Artist();

        $success = $this->session->get('success');
        $this->session->delete('success');

        //Return formatted data
        return $this->view->render('artist.create', [
            'pageTitle' => T::__('artist.create.pageTitle'),
            'artist' => $this->artist,
            'success' => $success,
            'errors' => $this->errors
        ]);
    }

    /**
     * @param int $id
     * @return View
     */
    protected function edit(int $id): View
    {
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

        $success = $this->session->get('success');
        $this->session->delete('success');

        //Return formatted data
        return $this->view->render('artist.edit', [
            'pageTitle' => $pageTitle,
            'artist' => $this->artist,
            'success' => $success,
            'errors' => $this->errors
        ]);
    }

    protected function save(): never
    {
        try {
            //Prepare a new object & execute POST logic
            $id = (int)$this->request->input('id');
            $this->artist = $id === 0 ? new Artist() : Artist::getById($id);
            $this->saveValidate();

            //Database magic when no errors are found
            if (empty($this->errors)) {
                //Set user id in Artist
                $this->artist->user_id = $this->session->get('user')->id;

                //Save the record to the db
                $state = $id === 0 ? 'create' : 'edit';
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
        header('Location: ' . $this->request->previousPath());
        exit;
    }

    private function saveValidate(): void
    {
        if ($this->request->hasInput('submit')) {
            //Override object with new variables
            $this->artist->name = $this->request->input('name');

            //Actual validation
            $validator = new ArtistValidator($this->artist);
            $validator->validate();
            $this->errors = $validator->errors;
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
            $artist = Artist::getById($id, ['albums']);

            //Default page title
            $pageTitle = $artist->name;
        } catch (\Exception $e) {
            //Something went wrong on this level
            Logger::error($e);
            $this->errors[] = T::__('general.errors.general');
            $pageTitle = T::__('artist.notExists');
        }

        //Return formatted data
        return $this->view->render('artist.detail', [
            'pageTitle' => $pageTitle,
            'artist' => $artist ?? false,
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
            $artist = Artist::getById($id);

            //Only execute delete when confirmed
            if ($this->request->hasQuery('continue')) {
                //Delete genre
                if (Artist::delete($id)) {
                    //Redirect to homepage after deletion & exit script
                    header('Location: ' . BASE_PATH . 'artists');
                    exit;
                }
            }

            //Return formatted data
            return $this->view->render('artist.delete', [
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
