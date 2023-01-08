<?php namespace MusicCollection\Middleware;

use MusicCollection\Handlers\Utils\Request;
use MusicCollection\Utils\Session;

/**
 * @package MusicCollection\Middleware
 */
abstract class BaseMiddleware
{
    public function __construct(
        protected Request $request,
        protected Session $session
    ) {
        $this->handle();
    }

    abstract public function handle(): void;
}
