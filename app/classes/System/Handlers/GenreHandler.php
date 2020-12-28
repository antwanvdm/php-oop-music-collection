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
        //Set default empty genre & execute POST logic
        $this->genre = new Genre();
        $this->executePostHandler();

        //Database magic when no errors are found
        if (isset($this->formData) && empty($this->errors)) {
            //Save the record to the db
            if ($this->genre->save()) {
                $success = $this->t->genre->add->success;
                //Override to see a new empty form
                $this->genre = new Genre();
            } else {
                $this->errors[] = $this->t->general->errors->dbSave;
            }
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $this->t->genre->add->pageTitle,
            'genre' => $this->genre,
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
            $this->genre = Genre::getById($id);
            $this->executePostHandler();

            //Database magic when no errors are found
            if (isset($this->formData) && empty($this->errors)) {
                //Save the record to the db
                if ($this->genre->save()) {
                    $success = $this->t->genre->edit->success;
                } else {
                    $this->errors[] = $this->t->general->errors->dbSave;
                }
            }

            $pageTitle = "{$this->t->genre->edit->pageTitlePrefix} {$this->genre->name}";
        } catch (\Exception $e) {
            $this->logger->error($e);
            $this->genre = new Genre();
            $this->errors[] = $this->t->general->errors->prefix . $e->getMessage();
            $pageTitle = $this->t->artist->notExists;
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => $pageTitle,
            'genre' => $this->genre,
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
            $genre = Genre::getById($id);

            //Default page title
            $pageTitle = $genre->name;
        } catch (\Exception $e) {
            //Something went wrong on this level
            $this->errors[] = $e->getMessage();
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
                header("Location: " . BASE_PATH . "genres");
                exit;
            }
        } catch (\Exception $e) {
            //There is no delete template, always redirect.
            $this->logger->error($e);
            header("Location: " . BASE_PATH . "genres");
            exit;
        }
    }
}
