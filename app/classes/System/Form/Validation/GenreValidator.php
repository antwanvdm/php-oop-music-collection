<?php namespace System\Form\Validation;

use System\Databases\Objects\Genre;

/**
 * Class GenreValidator
 * @package System\Form\Validation
 */
class GenreValidator implements Validator
{
    private array $errors = [];
    private Genre $genre;

    /**
     * GenreValidator constructor.
     *
     * @param Genre $genre
     */
    public function __construct(Genre $genre)
    {
        $this->genre = $genre;
    }

    /**
     * Validate the data
     */
    public function validate(): void
    {
        //Check if data is valid & generate error if not so
        if ($this->genre->name == "") {
            $this->errors[] = 'Genre name cannot be empty';
        }
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
