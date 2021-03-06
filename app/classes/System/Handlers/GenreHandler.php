<?php namespace System\Handlers;

use System\Databases\Objects\Genre;

/**
 * Class GenreHandler
 * @package System\Handlers
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
            'pageTitle' => $this->t->genre->index->pageTitle,
            'genres' => $genres,
            'totalGenres' => count($genres)
        ]);
    }

    protected function add(): void
    {
        //Set default empty genre
        $this->genre = new Genre();

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $this->t->genre->add->pageTitle,
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
            $pageTitle = "{$this->t->genre->edit->pageTitlePrefix} {$this->genre->name}";
        } catch (\Exception $e) {
            $this->logger->error($e);
            $this->genre = new Genre();
            $this->errors[] = $this->t->general->errors->general;
            $pageTitle = $this->t->artist->notExists;
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
                $state = $this->genre->id === 0 ? 'add' : 'edit';
                if ($this->genre->save()) {
                    $this->session->set('success', $this->t->genre->{$state}->success);
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
     * @param string $id
     */
    protected function detail(string $id): void
    {
        try {
            //Get the records from the db
            $genre = Genre::getById($id);

            //Default page title
            $pageTitle = $genre->name;
        } catch (\Exception $e) {
            //Something went wrong on this level
            $this->errors[] = $this->t->general->errors->general;
            $pageTitle = $this->t->genre->notExists;
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $pageTitle,
            'genre' => $genre ?? false,
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
            $genre = Genre::getById($id);

            //Database magic when no errors are found
            if (Genre::delete($id)) {
                //Redirect to homepage after deletion & exit script
                header('Location: ' . BASE_PATH . 'genres');
                exit;
            }
        } catch (\Exception $e) {
            //There is no delete template, always redirect.
            $this->logger->error($e);
            header('Location: ' . BASE_PATH . 'genres');
            exit;
        }
    }
}
