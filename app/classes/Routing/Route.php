<?php namespace MusicCollection\Routing;

use MusicCollection\Middleware\BaseMiddleware;

/**
 * Class Route
 * @package MusicCollection\Routing
 */
class Route
{
    /**
     * @var string[]
     */
    public array $params = [];
    public ?string $name = null;
    /**
     * @var class-string<BaseMiddleware>[]
     */
    public array $middleware = [];

    /**
     * Route constructor.
     *
     * @param string $method
     * @param string $path
     * @param string $className
     * @param string $action
     * @throws \Exception
     */
    public function __construct(
        public string $method,
        public string $path,
        public string $className,
        public string $action
    ) {
        if (preg_match_all("/\{([a-zA-Z0-9]+)\}/", $path, $matches)) {
            foreach ($matches[1] as $match) {
                if (in_array($match, $this->params)) {
                    throw new \Exception("Route for '$path' cannot have the same parameter name ($match) more than once");
                }
                $this->params[] = $match;
            }
        }
    }

    /**
     * @param string $name
     * @return Route
     */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param class-string|class-string[] $middleware
     * @return Route
     */
    public function middleware(string|array $middleware): self
    {
        if (is_string($middleware)) {
            $this->middleware[] = $middleware;
        } else {
            foreach ($middleware as $middlewareString) {
                $this->middleware[] = $middlewareString;
            }
        }
        return $this;
    }

    /**
     * @param string $prefix
     * @return Route
     * @throws \Exception
     */
    public function prefix(string $prefix): self
    {
        if (is_null($this->name)) {
            throw new \Exception("You cannot add a prefix if the route '$this->path' doesn't have a name");
        }

        $this->path = $this->path === '' ? $prefix : $prefix . '/' . $this->path;
        $this->name = $prefix . '.' . $this->name;
        return $this;
    }
}
