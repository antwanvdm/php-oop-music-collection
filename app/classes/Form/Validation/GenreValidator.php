<?php namespace MusicCollection\Form\Validation;

use MusicCollection\Databases\Objects\Genre;
use MusicCollection\Translation\Translator as T;

/**
 * Class GenreValidator
 * @package MusicCollection\Form\Validation
 */
class GenreValidator implements Validator
{
    private array $errors = [];

    /**
     * GenreValidator constructor.
     *
     * @param Genre $genre
     */
    public function __construct(private readonly Genre $genre)
    {
    }

    /**
     * Validate the data
     */
    public function validate(): void
    {
        //Check if data is valid & generate error if not so
        if ($this->genre->name == '') {
            $this->errors[] = T::__('genre.validation.name');
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
