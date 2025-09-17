<?php namespace MusicCollection\Validation;

use MusicCollection\Databases\Models\Artist;
use MusicCollection\Translation\Translator as T;

/**
 * Class ArtistValidator
 * @package MusicCollection\Validation
 */
class ArtistValidator implements Validator
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
     * ArtistValidator constructor.
     *
     * @param Artist $artist
     */
    public function __construct(private readonly Artist $artist)
    {
    }

    /**
     * Validate the data
     */
    public function validate(): void
    {
        //Check if data is valid & generate error if not so
        if ($this->artist->name == '') {
            $this->errorList[] = T::__('artist.validation.name');
        }
    }
}
