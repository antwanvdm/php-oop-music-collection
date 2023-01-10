<?php namespace MusicCollection\Middleware;

use MusicCollection\Utils\Request;
use MusicCollection\Utils\Session;

/**
 * Base class to inject dependencies that are required
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
