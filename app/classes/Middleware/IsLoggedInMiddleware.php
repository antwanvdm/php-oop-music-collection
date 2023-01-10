<?php namespace MusicCollection\Middleware;

/**
 * Redirect users to relevant login page if not logged in yet
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
