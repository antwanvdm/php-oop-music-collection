<?php namespace MusicCollection\Handlers;

use MusicCollection\Translation\Translator as T;

/**
 * Class HomeHandler
 * @package MusicCollection\Handlers
 */
class HomeHandler extends BaseHandler
{
    protected function index(): void
    {
        $this->renderTemplate([
            'pageTitle' => T::__('home.pageTitle')
        ]);
    }
}
