<?php namespace MusicCollection\Controllers\Web;

use MusicCollection\Controllers\BaseController;
use MusicCollection\Responses\View;
use MusicCollection\Translation\Translator as T;

/**
 * Class NotFoundController
 * @package MusicCollection\Controllers\Web
 */
class NotFoundController extends BaseController
{
    protected function index(): View
    {
        return $this->view->render('notfound.index', [
            'pageTitle' => T::__('notfound.pageTitle')
        ]);
    }
}
