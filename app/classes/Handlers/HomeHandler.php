<?php namespace MusicCollection\Handlers;

/**
 * Class HomeHandler
 * @package System\Handlers
 */
class HomeHandler extends BaseHandler
{
    protected function index(): void
    {
        $this->renderTemplate([
            'pageTitle' => $this->t->_('home.pageTitle')
        ]);
    }
}
