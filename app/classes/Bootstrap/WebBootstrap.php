<?php namespace MusicCollection\Bootstrap;

use MusicCollection\DI\Container;
use MusicCollection\Handlers\BaseHandler;
use MusicCollection\Routing\Route;
use MusicCollection\Routing\Router;
use MusicCollection\Utils\Logger;
use MusicCollection\Translation\Translator as T;
use MusicCollection\Utils\Session;
use MusicCollection\Utils\Template;

/**
 * Class WebBootstrap
 * @package MusicCollection\Bootstrap
 */
class WebBootstrap implements BootstrapInterface
{
    private Container $di;
    /**
     * @var class-string[]
     */
    private array $diClasses = [
        'session' => Session::class,
        'template' => Template::class,
        'router' => Router::class,
    ];
    private Route $activeRoute;

    public function __construct()
    {
        session_start();
        $this->setup();
    }

    /**
     * Set up the route based on current path
     */
    public function setup(): void
    {
        try {
            //Use the Dependency Injector container for the classes we need throughout the application
            $this->di = new Container();
            foreach ($this->diClasses as $key => $diClass) {
                $this->di->set($key, $diClass);
            }

            //Routing magic with dynamic file that has $router available
            /** @var Router $router */
            $router = $this->di->get('router');
            require_once INCLUDES_PATH . 'config/routes.php';
            $this->activeRoute = $router->getRoute();
        } catch (\Throwable $e) {
            Logger::error($e);
            http_response_code(500);
            die(T::__('general.errors.die'));
        }
    }

    /**
     * Initialize controller, call current action & get the rendered data
     *
     * @return string
     */
    public function render(): string
    {
        try {
            if (!class_exists($this->activeRoute->className)) {
                throw new \Exception('Class ' . $this->activeRoute->className . ' does not exist!');
            }
            /** @var BaseHandler $page */
            $page = $this->di->set('handler', $this->activeRoute->className);

            return $page->{$this->activeRoute->action}(...$this->activeRoute->params)->getResponse();
        } catch (\Throwable $e) {
            Logger::error($e);
            http_response_code(500);
            die(T::__('general.errors.die'));
        }
    }
}
