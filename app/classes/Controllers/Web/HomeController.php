<?php namespace MusicCollection\Controllers\Web;

use MusicCollection\Controllers\BaseController;
use MusicCollection\Responses\View;
use MusicCollection\Translation\Translator as T;

/**
 * Class HomeController
 * @package MusicCollection\Controllers\Web
 */
class HomeController extends BaseController
{
    protected function index(): View
    {
        return $this->view->render('home.index', [
            'pageTitle' => T::__('home.pageTitle')
        ]);
    }
}
