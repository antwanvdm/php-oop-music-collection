<?php namespace System\Handlers\FillAndValidate;

use System\Databases\Objects\Genre;
use System\Form\Data;
use System\Form\Validation\AlbumValidator;

/**
 * Trait Album
 * @package System\Handlers
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
            $this->album->artist_id = $this->formData->getPostVar('artist');
            $this->album->name = $this->formData->getPostVar('name');
            $this->album->year = $this->formData->getPostVar('year');
            $this->album->tracks = $this->formData->getPostVar('tracks');

            $genres = $this->formData->getPostVar('genre');
            $this->album->genres = [];
            foreach ($genres as $genre_id) {
                $this->album->genres[] = Genre::getById($genre_id);
            }

            //Actual validation
            $validator = new AlbumValidator($this->album);
            $validator->validate($this->t);
            $this->errors = $validator->getErrors();
        }
    }
}
