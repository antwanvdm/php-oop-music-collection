<?php namespace System\Handlers;

/**
 * Class HomeHandler
 * @package System\Handlers
 */
class HomeHandler extends BaseHandler
{
    protected function index()
    {
        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => "Welkom bij deze muziekcollectie!"
        ]);
    }
}
