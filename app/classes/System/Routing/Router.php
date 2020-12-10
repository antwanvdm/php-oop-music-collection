<?php namespace System\Routing;

/**
 * Class Router
 * @package System\Routing
 */
class Router
{
    /**
     * @var Route[] array
     */
    private array $routes = [];

    /**
     * @param string $path
     * @param string $controllerAction
     * @return Route
     */
    public function addRoute(string $path, string $controllerAction): Route
    {
        list ($className, $action) = explode("@", $controllerAction);
        $fullClassName = '\\System\\Handlers\\' . $className;
        $this->routes[$path] = new Route($path, $fullClassName, $action);
        return $this->routes[$path];
    }

    /**
     * @param string $name
     * @return string
     * @throws \Exception
     */
    public function getFullPathByName(string $name): string
    {
        foreach ($this->routes as $route) {
            if ($name == $route->name) {
                return BASE_PATH . $route->path;
            }
        }
        throw new \Exception('Route name not found');
    }

    /**
     * @param string $path
     * @return bool
     */
    public function hasRoute(string $path): bool
    {
        return isset($this->routes[$path]);
    }

    /**
     * @param string $path
     * @return Route|null
     */
    public function getRoute(string $path): ?Route
    {
        return $this->routes[$path] ?? null;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
