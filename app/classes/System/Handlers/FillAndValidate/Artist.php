<?php namespace System\Handlers\FillAndValidate;

use System\Form\Data;
use System\Form\Validation\ArtistValidator;

/**
 * Trait Artist
 * @package System\Handlers
 */
trait Artist
{
    private Data $formData;

    public function executePostHandler(): void
    {
        if (isset($_POST['submit'])) {
            //Set form data
            $this->formData = new Data($_POST);

            //Override object with new variables
            $this->artist->name = $this->formData->getPostVar('name');

            //Actual validation
            $validator = new ArtistValidator($this->artist);
            $validator->validate();
            $this->errors = $validator->getErrors();
        }
    }
}
