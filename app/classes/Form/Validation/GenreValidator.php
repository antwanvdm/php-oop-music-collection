<?php namespace MusicCollection\Form\Validation;

use MusicCollection\Databases\Objects\Genre;
use MusicCollection\Translation\Translator;

/**
 * Class GenreValidator
 * @package System\Form\Validation
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
     *
     * @param Translator $t
     */
    public function validate(Translator $t): void
    {
        //Check if data is valid & generate error if not so
        if ($this->genre->name == '') {
            $this->errors[] = $t->_('genre.validation.name');
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
