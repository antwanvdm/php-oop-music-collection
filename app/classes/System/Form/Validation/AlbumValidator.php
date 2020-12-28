<?php namespace System\Form\Validation;

use System\Databases\Objects\Album;

/**
 * Class AlbumValidator
 * @package System\Form\Validation
 */
class AlbumValidator implements Validator
{
    private array $errors = [];
    private Album $album;

    /**
     * AlbumValidator constructor.
     *
     * @param Album $album
     */
    public function __construct(Album $album)
    {
        $this->album = $album;
    }

    /**
     * Validate the data
     * @TODO how to get the Translator object at this location (not using ugly parameter passing..)
     */
    public function validate(): void
    {
        //Check if data is valid & generate error if not so
        if ($this->album->artist == "") {
            $this->errors[] = 'Artist cannot be empty';
        }
        if ($this->album->name == "") {
            $this->errors[] = 'Name cannot be empty';
        }
        if (empty($this->album->genres)) {
            $this->errors[] = 'Genre cannot be empty';
        }
        if ($this->album->year == "") {
            $this->errors[] = 'Year cannot be empty';
        }
        if (!is_numeric($this->album->year) || strlen($this->album->year) != 4) {
            $this->errors[] = 'Year needs to be a number with the length of 4';
        }
        if ($this->album->tracks == "") {
            $this->errors[] = 'Tracks cannot be empty';
        }
        if (!is_numeric($this->album->tracks)) {
            $this->errors[] = 'Tracks need to be a number';
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
