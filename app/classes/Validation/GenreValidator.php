<?php namespace MusicCollection\Validation;

use MusicCollection\Databases\Models\Genre;
use MusicCollection\Translation\Translator as T;

/**
 * Class GenreValidator
 * @package MusicCollection\Validation
 */
class GenreValidator implements Validator
{
    /**
     * @var string[]
     */
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
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
