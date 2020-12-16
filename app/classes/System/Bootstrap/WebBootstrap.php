<?php namespace System\Bootstrap;

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
    private Session $session;
    private Logger $logger;
    private Router $router;
    private Route $activeRoute;

    public function __construct()
    {
        session_start();
        $this->session = new Session($_SESSION);
        $this->logger = new Logger();
        $this->router = new Router();
        $this->setup();
    }

    /**
     * Setup the route based on current path
     */
    public function setup(): void
    {
        //@TODO see if this can be implement more elegant instead of creating a local variable..
        $router = $this->router;
        require_once INCLUDES_PATH . "config/routes.php";

        //Set route based on path or fallback to 404
        $this->activeRoute = $this->router->getRoute();
    }

    /**
     * Initialize controller, set dynamic properties, call current action & get the rendered data
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
            $page = new $this->activeRoute->className($this->activeRoute->action);
            $page->session = $this->session;
            $page->logger = $this->logger;
            $page->router = $this->router;
            return $page->{$this->activeRoute->action}(...$this->activeRoute->params)->getHTML();
        } catch (\Exception $e) {
            $this->logger->error($e);
            die("Oops, something went wrong, please contact the site administrator.");
        }
    }
}
