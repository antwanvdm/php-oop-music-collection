<?php namespace System\Bootstrap;

use System\Handlers\BaseHandler;
use System\Routing\Router;
use System\Utils\Session;
use System\Utils\Logger;
use System\Utils\URL;

/**
 * Class WebBootstrap
 * @package System\Bootstrap
 */
class WebBootstrap implements BootstrapInterface
{
    private array $handler = [
        'className' => '',
        'action' => '',
        'params' => [],
    ];
    private Session $session;
    private Logger $logger;
    private Router $router;

    public function __construct()
    {
        session_start();
        $this->session = new Session($_SESSION);
        $this->logger = new Logger();
        $this->setup();
    }

    /**
     * Setup the route based on current path
     */
    public function setup(): void
    {
        //Get the url from the nginx config & check existence (if not: 404!)
        $path = URL::getCurrentPath();
        $this->router = new Router($path);
        //@TODO see if this can be implement more elegant instead of creating a local variable..
        $router = $this->router;
        require_once INCLUDES_PATH . "config/routes.php";

        //Check existence of route & initiate correct Handler & actions based on route
        if (($route = $this->router->getRoute($path)) !== null) {
            $this->handler['className'] = $route->className;
            $this->handler['action'] = $route->action;
            $this->handler['params'] = $route->params;
        } else {
            header('HTTP/1.0 404 Not Found');
            $this->handler['className'] = '\\System\\Handlers\\NotFoundHandler';
            $this->handler['action'] = 'index';
        }
    }

    /**
     * Initialize controller, set dynamic properties, call current action & get the rendered data
     *
     * @return string
     */
    public function render(): string
    {
        try {
            if (!class_exists($this->handler['className'])) {
                throw new \Exception('Class ' . $this->handler['className'] . ' does not exist!');
            }
            /** @var $page BaseHandler */
            $page = new $this->handler['className']($this->handler['action']);
            $page->session = $this->session;
            $page->logger = $this->logger;
            $page->router = $this->router;
            return $page->{$this->handler['action']}(...$this->handler['params'])->getHTML();
        } catch (\Exception $e) {
            $this->logger->error($e);
            die("Oops, something went wrong, please contact the site administrator.");
        }
    }
}
