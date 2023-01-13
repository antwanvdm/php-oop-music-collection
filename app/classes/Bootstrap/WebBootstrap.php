<?php namespace MusicCollection\Bootstrap;

use MusicCollection\Controllers\BaseController;
use MusicCollection\DI\Container;
use MusicCollection\Responses\Json;
use MusicCollection\Responses\View;
use MusicCollection\Routing\Route;
use MusicCollection\Routing\Router;
use MusicCollection\Translation\Translator as T;
use MusicCollection\Utils\Logger;
use MusicCollection\Utils\Request;
use MusicCollection\Utils\Session;

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
        'router' => Router::class,
        'request' => Request::class,
        'view' => View::class,
        'json' => Json::class,
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
            $router = $this->di->get('router');
            assert($router instanceof Router);
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

            $controller = $this->di->set('controller', $this->activeRoute->className);
            assert($controller instanceof BaseController);
            return $controller->{$this->activeRoute->action}(...$this->activeRoute->params)->getResponse();
        } catch (\Throwable $e) {
            Logger::error($e);
            http_response_code(500);
            die(T::__('general.errors.die'));
        }
    }
}
