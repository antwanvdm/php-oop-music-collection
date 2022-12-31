<?php namespace MusicCollection\Form\Validation;

use MusicCollection\Databases\Objects\Artist;
use MusicCollection\Translation\Translator;

/**
 * Class ArtistValidator
 * @package System\Form\Validation
 */
class ArtistValidator implements Validator
{
    private array $errors = [];

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
     *
     * @param Translator $t
     */
    public function validate(Translator $t): void
    {
        //Check if data is valid & generate error if not so
        if ($this->artist->name == '') {
            $this->errors[] = $t->_('artist.validation.name');
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
