<?php namespace MusicCollection\Validation;

use MusicCollection\Databases\Models\Album;
use MusicCollection\Translation\Translator as T;

/**
 * Class AlbumValidator
 * @package MusicCollection\Validation
 */
class AlbumValidator implements Validator
{
    /**
     * @var string[]
     */
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
     */
    public function validate(): void
    {
        //Check if data is valid & generate error if not so
        if ($this->album->artist_id == '') {
            $this->errors[] = T::__('album.validation.artist');
        }
        if ($this->album->name == '') {
            $this->errors[] = T::__('album.validation.name');
        }
        if (empty($this->album->getGenreIds())) {
            $this->errors[] = T::__('album.validation.genre');
        }
        if ($this->album->year == '') {
            $this->errors[] = T::__('album.validation.year');
        }
        if (!is_numeric($this->album->year) || strlen($this->album->year) != 4) {
            $this->errors[] = T::__('album.validation.yearFormat');
        }
        if ($this->album->tracks == '' || $this->album->tracks < 1) {
            $this->errors[] = T::__('album.validation.tracks');
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
