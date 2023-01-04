<?php namespace MusicCollection\Handlers\FillAndValidate;

use MusicCollection\Translation\Translator as T;
use MusicCollection\Form\Data;
use MusicCollection\Form\Validation\AlbumValidator;

/**
 * Trait Album
 * @package MusicCollection\Handlers
 */
trait Album
{
    private Data $formData;

    public function executePostHandler(): void
    {
        if (isset($_POST['submit'])) {
            //Set form data
            $this->formData = new Data($_POST);

            //Override object with new variables
            $this->album->id = (int)$this->formData->getPostVar('id');
            $this->album->artist_id = (int)$this->formData->getPostVar('artist-id');
            $this->album->name = $this->formData->getPostVar('name');
            $this->album->year = $this->formData->getPostVar('year');
            $this->album->tracks = (int)$this->formData->getPostVar('tracks');
            $this->album->image = $this->formData->getPostVar('current-image');
            $this->album->setGenreIds($this->formData->getPostVar('genre-ids'));

            //Actual validation
            $validator = new AlbumValidator($this->album);
            $validator->validate();
            $this->errors = $validator->getErrors();

            if ($this->album->id === 0 && $_FILES['image']['error'] == 4) {
                $this->errors[] = T::__('album.validation.image');
            }
        }
    }
}
