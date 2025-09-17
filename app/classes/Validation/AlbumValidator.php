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
    private array $errorList = [];
    public array $errors {
        get => $this->errorList;
    }

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
            $this->errorList[] = T::__('album.validation.artist');
        }
        if ($this->album->name == '') {
            $this->errorList[] = T::__('album.validation.name');
        }
        if (empty($this->album->getGenresIds())) {
            $this->errorList[] = T::__('album.validation.genre');
        }
        if ($this->album->year == '') {
            $this->errorList[] = T::__('album.validation.year');
        }
        if (!is_numeric($this->album->year) || strlen($this->album->year) != 4) {
            $this->errorList[] = T::__('album.validation.yearFormat');
        }
        if ($this->album->tracks == 0 || $this->album->tracks < 1) {
            $this->errorList[] = T::__('album.validation.tracks');
        }
    }
}
