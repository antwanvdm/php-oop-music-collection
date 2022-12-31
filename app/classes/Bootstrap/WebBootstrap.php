<?php namespace MusicCollection\Bootstrap;

use MusicCollection\DI\Container;
use MusicCollection\Handlers\BaseHandler;
use MusicCollection\Routing\Route;

/**
 * Class WebBootstrap
 * @package System\Bootstrap
 */
class WebBootstrap implements BootstrapInterface
{
    private Container $di;
    private array $diClasses = [
        'session' => '\\MusicCollection\\Utils\\Session',
        'template' => '\\MusicCollection\\Utils\\Template',
        'logger' => '\\MusicCollection\\Utils\\Logger',
        'router' => '\\MusicCollection\\Routing\\Router',
        't' => '\\MusicCollection\\Translation\\Translator'
    ];
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
        foreach ($this->diClasses as $key => $diClass) {
            $this->di->set($key, $diClass);
        }

        //Routing magic with dynamic file that has $router available
        $router = $this->di->get('router');
        require_once INCLUDES_PATH . 'config/routes.php';
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
            /** @var BaseHandler $page */
            $page = $this->di->set('handler', $this->activeRoute->className);

            return $page->{$this->activeRoute->action}(...$this->activeRoute->params)->getResponse();
        } catch (\Exception $e) {
            $this->di->get('logger')->error($e);
            http_response_code(500);
            die($this->di->get('t')->_('general.errors.die'));
        }
    }
}
