<?php namespace MusicCollection\Controllers\Web;

use MusicCollection\Controllers\BaseController;
use MusicCollection\Translation\Translator as T;

/**
 * Class LanguageController
 * @package MusicCollection\Controllers\Web
 */
class LanguageController extends BaseController
{
    protected function change(): void
    {
        $language = $this->request->input('language');
        T::setLanguage($language);

        header('Location: ' . $this->request->previousPath());
        exit;
    }
}
