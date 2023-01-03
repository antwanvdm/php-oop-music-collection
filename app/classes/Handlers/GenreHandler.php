<?php namespace MusicCollection\Handlers;

use MusicCollection\Databases\Objects\Genre;

/**
 * Class GenreHandler
 * @package MusicCollection\Handlers
 */
class GenreHandler extends BaseHandler
{
    use FillAndValidate\Genre;

    private Genre $genre;

    protected function initialize(): void
    {
        if ($this->session->get('errors')) {
            $this->errors = array_merge($this->session->get('errors'), $this->errors);
        }

        $this->session->delete('errors');
    }

    protected function index(): void
    {
        //Get all genres
        $genres = Genre::getAll();

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $this->t->_('genre.index.pageTitle'),
            'genres' => $genres,
            'totalGenres' => count($genres)
        ]);
    }

    protected function create(): void
    {
        //Set default empty genre
        $this->genre = new Genre();

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $this->t->_('genre.create.pageTitle'),
            'genre' => $this->genre,
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
        try {
            //Get the record from the db & execute POST logic
            $this->genre = Genre::getById($id);
            $pageTitle = $this->t->_('genre.edit.pageTitle', ['NAME' => $this->genre->name]);
        } catch (\Exception $e) {
            $this->logger->error($e);
            $this->genre = new Genre();
            $this->errors[] = $this->t->_('general.errors.general');
            $pageTitle = $this->t->_('artist.notExists');
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $pageTitle,
            'genre' => $this->genre,
            'success' => $this->session->get('success'),
            'errors' => $this->errors
        ]);

        $this->session->delete('success');
    }

    protected function save(): void
    {
        try {
            //Get the record from the db & execute POST logic
            $this->genre = new Genre();
            $this->executePostHandler();

            //Database magic when no errors are found
            if (isset($this->formData) && empty($this->errors)) {
                //Save the record to the db
                $state = $this->genre->id === 0 ? 'create' : 'edit';
                if ($this->genre->save()) {
                    $this->session->set('success', $this->t->_('genre.' . $state . '.success'));
                } else {
                    $this->errors[] = $this->t->_('general.errors.dbSave');
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e);
            $this->errors[] = $this->t->_('general.errors.general');
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
            $genre = Genre::getById($id);

            //Default page title
            $pageTitle = $genre->name;
        } catch (\Exception $e) {
            //Something went wrong on this level
            $this->errors[] = $this->t->_('general.errors.general');
            $pageTitle = $this->t->_('genre.notExists');
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $pageTitle,
            'genre' => $genre ?? false,
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
            $genre = Genre::getById($id);

            //Only execute delete when confirmed
            if (isset($_GET['continue'])) {
                //Delete genre
                if (Genre::delete($id)) {
                    //Redirect to genre list after deletion & exit script
                    header('Location: ' . BASE_PATH . 'genres');
                    exit;
                }
            }

            //Return formatted data
            $this->renderTemplate([
                'pageTitle' => $this->t->_('genre.delete.title'),
                'genre' => $genre,
                'errors' => $this->errors
            ]);
        } catch (\Exception $e) {
            //There is no delete template, always redirect.
            $this->logger->error($e);
            header('Location: ' . BASE_PATH . 'genres');
            exit;
        }
    }
}
