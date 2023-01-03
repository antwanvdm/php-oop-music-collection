<?php namespace MusicCollection\Form\Validation;

use MusicCollection\Databases\Objects\Album;
use MusicCollection\Translation\Translator;

/**
 * Class AlbumValidator
 * @package System\Form\Validation
 */
class AlbumValidator implements Validator
{
    private array $errors = [];

    /**
     * AlbumValidator constructor.
     *
     * @param Album $album
     */
    public function __construct(private readonly Album $album)
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
        if ($this->album->artist_id == '') {
            $this->errors[] = $t->_('album.validation.artist');
        }
        if ($this->album->name == '') {
            $this->errors[] = $t->_('album.validation.name');
        }
        if (empty($this->album->getGenreIds())) {
            $this->errors[] = $t->_('album.validation.genre');
        }
        if ($this->album->year == '') {
            $this->errors[] = $t->_('album.validation.year');
        }
        if (!is_numeric($this->album->year) || strlen($this->album->year) != 4) {
            $this->errors[] = $t->_('album.validation.yearFormat');
        }
        if ($this->album->tracks == '') {
            $this->errors[] = $t->_('album.validation.tracks');
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
