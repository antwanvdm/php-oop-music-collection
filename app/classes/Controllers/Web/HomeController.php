<?php namespace MusicCollection\Controllers\Web;

use MusicCollection\Controllers\BaseController;
use MusicCollection\Translation\Translator as T;

/**
 * Class HomeController
 * @package MusicCollection\Controllers\Web
 */
class HomeController extends BaseController
{
    protected function index(): void
    {
        $this->renderTemplate([
            'pageTitle' => T::__('home.pageTitle')
        ]);
    }
}
