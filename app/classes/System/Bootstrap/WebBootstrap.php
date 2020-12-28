<?php namespace System\Bootstrap;

use System\DI\Container;
use System\Handlers\BaseHandler;
use System\Routing\Route;
use System\Routing\Router;
use System\Utils\Session;
use System\Utils\Logger;

/**
 * Class WebBootstrap
 * @package System\Bootstrap
 */
class WebBootstrap implements BootstrapInterface
{
    private Container $di;
    private Route $activeRoute;

    public function __construct()
    {
        session_start();
        $this->setup();
    }

    /**
     * Setup the route based on current path
     */
    public function setup(): void
    {
        //Use the Dependency Injector container for the classes we need throughout the application
        $this->di = new Container();
        $this->di->set('session', new Session($_SESSION));
        $this->di->set('logger', new Logger());
        $this->di->set('router', new Router());

        //Routing magic with dynamic file that has $router available
        $router = $this->di->get('router');
        require_once INCLUDES_PATH . "config/routes.php";
        $this->activeRoute = $router->getRoute();
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
            /** @var $page BaseHandler */
            $page = $this->di->set('bootstrap', $this->activeRoute->className);
            return $page->{$this->activeRoute->action}(...$this->activeRoute->params)->getResponse();
        } catch (\Exception $e) {
            $this->di->get('logger')->error($e);
            die("Oops, something went wrong, please contact the site administrator.");
        }
    }
}
