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
    private array $errorList = [];
    public array $errors {
        get {
            return $this->errorList;
        }
    }

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
            $this->errorList[] = T::__('genre.validation.name');
        }
    }
}
