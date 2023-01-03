<?php namespace MusicCollection\Routing;

/**
 * Class Route
 * @package MusicCollection\Routing
 */
class Route
{
    public string $method;
    public string $path;
    public string $className;
    public string $action;
    public array $params = [];
    public ?string $name = null;

    /**
     * Route constructor.
     *
     * @param string $method
     * @param string $path
     * @param string $className
     * @param string $action
     */
    public function __construct(string $method, string $path, string $className, string $action)
    {
        $this->method = $method;
        $this->path = $path;
        $this->className = $className;
        $this->action = $action;

        /*
         * @TODO Add possibility for nested routes with multiple params (now only 1 works)
         */
        if (preg_match("/\{([a-zA-Z0-9]+)\}/", $path, $matches)) {
            $this->params[] = $matches[1];
        }
    }

    /**
     * @param string $name
     */
    public function name(string $name): void
    {
        $this->name = $name;
    }
}
