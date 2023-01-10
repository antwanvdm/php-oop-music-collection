<?php namespace MusicCollection\Controllers\Api;

use MusicCollection\Controllers\BaseController;

/**
 * Class APIController
 * @package MusicCollection\Controllers\Api
 * @TODO Remove this later, it's to demo the working of JSON response in the system
 */
class IndexController extends BaseController
{
    protected function index(): void
    {
        $this->setJSON(['api' => 'example']);
    }
}
