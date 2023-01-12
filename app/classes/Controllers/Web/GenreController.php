<?php namespace MusicCollection\Controllers\Web;

use MusicCollection\Controllers\BaseController;
use MusicCollection\Databases\Models\Genre;
use MusicCollection\Responses\View;
use MusicCollection\Translation\Translator as T;
use MusicCollection\Utils\Logger;
use MusicCollection\Validation\GenreValidator;

/**
 * Class GenreController
 * @package MusicCollection\Controllers\Web
 */
class GenreController extends BaseController
{
    private Genre $genre;

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
        //Get all genres
        $genres = Genre::getAll();

        //Return formatted data
        return $this->view->render('genre.index', [
            'pageTitle' => T::__('genre.index.pageTitle'),
            'genres' => $genres,
            'totalGenres' => count($genres)
        ]);
    }

    /**
     * @return View
     */
    protected function create(): View
    {
        //Set default empty genre
        $this->genre = new Genre();

        $success = $this->session->get('success');
        $this->session->delete('success');

        //Return formatted data
        return $this->view->render('genre.create', [
            'pageTitle' => T::__('genre.create.pageTitle'),
            'genre' => $this->genre,
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
            $this->genre = Genre::getById($id);
            $pageTitle = T::__('genre.edit.pageTitle', ['NAME' => $this->genre->name]);
        } catch (\Exception $e) {
            Logger::error($e);
            $this->genre = new Genre();
            $this->errors[] = T::__('general.errors.general');
            $pageTitle = T::__('artist.notExists');
        }

        $success = $this->session->get('success');
        $this->session->delete('success');

        //Return formatted data
        return $this->view->render('genre.edit', [
            'pageTitle' => $pageTitle,
            'genre' => $this->genre,
            'success' => $success,
            'errors' => $this->errors
        ]);
    }

    protected function save(): void
    {
        try {
            //Prepare a new object & execute POST logic
            $this->genre = new Genre();
            $this->saveValidate();

            //Database magic when no errors are found
            if (empty($this->errors)) {
                //Save the record to the db
                $state = $this->genre->id === 0 ? 'create' : 'edit';
                if ($this->genre->save()) {
                    $this->session->set('success', T::__('genre.' . $state . '.success'));
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
            $this->genre->id = (int)$this->request->input('id');
            $this->genre->name = $this->request->input('name');

            //Actual validation
            $validator = new GenreValidator($this->genre);
            $validator->validate();
            $this->errors = $validator->getErrors();
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
            $genre = Genre::getById($id);

            //Default page title
            $pageTitle = $genre->name;
        } catch (\Exception $e) {
            //Something went wrong on this level
            Logger::error($e);
            $this->errors[] = T::__('general.errors.general');
            $pageTitle = T::__('genre.notExists');
        }

        //Return formatted data
        return $this->view->render('genre.detail', [
            'pageTitle' => $pageTitle,
            'genre' => $genre ?? false,
            'errors' => $this->errors
        ]);
    }

    /**
     * @param int $id
     */
    protected function delete(int $id): View
    {
        try {
            //Get the record from the db
            $genre = Genre::getById($id);

            //Only execute delete when confirmed
            if ($this->request->hasQuery('continue')) {
                //Delete genre
                if (Genre::delete($id)) {
                    //Redirect to genre list after deletion & exit script
                    header('Location: ' . BASE_PATH . 'genres');
                    exit;
                }
            }

            //Return formatted data
            return $this->view->render('genre.delete', [
                'pageTitle' => T::__('genre.delete.title'),
                'genre' => $genre,
                'errors' => $this->errors
            ]);
        } catch (\Exception $e) {
            //There is no delete template, always redirect.
            Logger::error($e);
            header('Location: ' . BASE_PATH . 'genres');
            exit;
        }
    }
}
