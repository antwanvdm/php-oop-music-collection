<?php namespace System\Routing;

/**
 * Class Route
 * @package System\Routing
 */
class Route
{
    public string $path;
    public string $className;
    public string $action;
    public ?string $name = null;

    /**
     * Route constructor.
     *
     * @param string $path
     * @param string $className
     * @param string $action
     */
    public function __construct(string $path, string $className, string $action)
    {
        $this->path = $path;
        $this->className = $className;
        $this->action = $action;
    }

    /**
     * @param string $name
     */
    public function name(string $name)
    {
        $this->name = $name;
    }
}
