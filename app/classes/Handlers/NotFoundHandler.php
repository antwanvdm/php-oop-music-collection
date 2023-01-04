<?php namespace MusicCollection\Handlers;

use MusicCollection\Translation\Translator as T;

/**
 * Class NotFoundHandler
 * @package MusicCollection\Handlers
 */
class NotFoundHandler extends BaseHandler
{
    protected function index(): void
    {
        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => T::__('notfound.pageTitle')
        ]);
    }
}
