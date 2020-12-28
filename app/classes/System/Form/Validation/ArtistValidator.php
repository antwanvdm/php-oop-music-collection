<?php namespace System\Form\Validation;

use System\Databases\Objects\Artist;
use System\Translation\Translator;

/**
 * Class ArtistValidator
 * @package System\Form\Validation
 */
class ArtistValidator implements Validator
{
    private array $errors = [];
    private Artist $artist;

    /**
     * ArtistValidator constructor.
     *
     * @param Artist $artist
     */
    public function __construct(Artist $artist)
    {
        $this->artist = $artist;
    }

    /**
     * Validate the data
     *
     * @param Translator $t
     */
    public function validate(Translator $t): void
    {
        //Check if data is valid & generate error if not so
        if ($this->artist->name == "") {
            $this->errors[] = $t->artist->validation->name;
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
