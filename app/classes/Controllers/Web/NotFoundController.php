<?php namespace MusicCollection\Controllers\Web;

use MusicCollection\Controllers\BaseController;
use MusicCollection\Translation\Translator as T;

/**
 * Class NotFoundController
 * @package MusicCollection\Controllers\Web
 */
class NotFoundController extends BaseController
{
    protected function index(): void
    {
        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => T::__('notfound.pageTitle')
        ]);
    }
}
