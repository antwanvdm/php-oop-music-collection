<?php namespace MusicCollection\Handlers;

use MusicCollection\Translation\Translator as T;

/**
 * Class LanguageHandler
 * @package MusicCollection\Handlers
 */
class LanguageHandler extends BaseHandler
{
    protected function change(): void
    {
        $language = $this->request->input('language');
        T::setLanguage($language);

        header('Location: ' . $this->request->previousPath());
        exit;
    }
}
