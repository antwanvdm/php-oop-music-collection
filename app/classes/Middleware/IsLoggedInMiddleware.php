<?php namespace MusicCollection\Middleware;

/**
 * @package MusicCollection\Middleware
 */
class IsLoggedInMiddleware extends BaseMiddleware
{
    public function handle(): void
    {
        if (!$this->session->keyExists('user')) {
            $location = $this->request->currentPath();
            header('Location: ' . BASE_PATH . 'user/login?location=' . BASE_PATH . $location);
            exit;
        }
    }
}
