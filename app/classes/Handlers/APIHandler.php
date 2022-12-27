<?php namespace MusicCollection\Handlers;

/**
 * Class APIHandler
 * @package System\Handlers
 * @TODO Remove this later, it's to demo the working of JSON response in the system
 */
class APIHandler extends BaseHandler
{
    protected function index(): void
    {
        $this->setJSON(['api' => 'example']);
    }
}
