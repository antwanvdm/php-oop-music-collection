<?php namespace MusicCollection\Handlers;

use MusicCollection\Form\Data;
use MusicCollection\Translation\Translator as T;

/**
 * Class LanguageHandler
 * @package MusicCollection\Handlers
 */
class LanguageHandler extends BaseHandler
{
    protected function change(): void
    {
        $postData = new Data($_POST);
        $language = $postData->getPostVar('language');
        T::setLanguage($language);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
