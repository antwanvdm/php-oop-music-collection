<?php namespace System\Form\Validation;

use System\Databases\Objects\Album;
use System\Translation\Translator;

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
     *
     * @param Translator $t
     */
    public function validate(Translator $t): void
    {
        //Check if data is valid & generate error if not so
        if ($this->album->artist == '') {
            $this->errors[] = $t->album->validation->artist;
        }
        if ($this->album->name == '') {
            $this->errors[] = $t->album->validation->name;
        }
        if (empty($this->album->genres)) {
            $this->errors[] = $t->album->validation->genre;
        }
        if ($this->album->year == '') {
            $this->errors[] = $t->album->validation->year;
        }
        if (!is_numeric($this->album->year) || strlen($this->album->year) != 4) {
            $this->errors[] = $t->album->validation->yearFormat;
        }
        if ($this->album->tracks == '') {
            $this->errors[] = $t->album->validation->tracks;
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
