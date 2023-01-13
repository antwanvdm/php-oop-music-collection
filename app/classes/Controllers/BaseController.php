<?php namespace MusicCollection\Controllers;

use MusicCollection\Responses\Json;
use MusicCollection\Responses\View;
use MusicCollection\Routing\Router;
use MusicCollection\Utils\Request;
use MusicCollection\Utils\Session;

/**
 * Class BaseController
 * @package MusicCollection\Controllers
 */
abstract class BaseController
{
    /**
     * @var string[]
     */
    protected array $errors = [];

    /**
     * BaseController constructor.
     *
     * @param Session $session
     * @param Router $router
     * @param Request $request
     * @param View $view
     * @param Json $json
     */
    public function __construct(
        protected Session $session,
        protected Router $router,
        protected Request $request,
        protected View $view,
        protected Json $json,
    ) {
        //Give child classes opportunity to hook into the construct
        if (method_exists($this, 'initialize')) {
            $this->initialize();
        }
    }

    /**
     * @param string $name
     * @param array<int, mixed> $arguments
     * @return self
     * @throws \Exception
     */
    public function __call(string $name, array $arguments): self
    {
        //Actual __call magic to call child protected method if it exists
        if (method_exists($this, $name)) {
            call_user_func_array([$this, $name], $arguments);
        } else {
            throw new \Exception("Route does not exist or invalid function ($name) was called on " . get_called_class());
        }

        return $this;
    }

    /**
     * Get response based on content type header, always fallback to HTML as the default
     *
     * @return string
     */
    public function getResponse(): string
    {
        return match ($this->request->requestedContentType()) {
            'application/json' => $this->json,
            default => $this->view,
        };
    }
}
