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
    private string $currentPath;
    private array $pathSegments;

    /**
     * Router constructor.
     *
     * @param $currentPath
     */
    public function __construct($currentPath)
    {
        $this->currentPath = $currentPath;
        $this->pathSegments = explode("/", $currentPath);
    }

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
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public function getFullPathByName(string $name, array $params = []): string
    {
        foreach ($this->routes as $route) {
            if ($name == $route->name) {
                $returnPath = BASE_PATH;
                if (!empty($params)) {
                    foreach ($params as $key => $value) {
                        if (preg_match("/\{$key\}/", $route->path, $matches)) {
                            $returnPath = ($returnPath . str_replace($matches[0], $value, $route->path));
                        }
                    }
                } else {
                    $returnPath .= $route->path;
                }
                return $returnPath;
            }
        }
        throw new \Exception('Route name not found');
    }

    /**
     * @param string $path
     * @return Route|null
     */
    public function getRoute(string $path): ?Route
    {
        if (isset($this->routes[$path])) {
            return $this->routes[$path];
        } else {
            foreach ($this->routes as $route) {
                //Get a path except last segment
                $segments = $this->pathSegments;
                $lastSegment = array_pop($segments);
                $strippedRoutePath = preg_quote(implode("/", $segments) . '/', "/");

                //Check if the path without last segment + dynamic naming exists
                if (preg_match("/$strippedRoutePath\{([a-zA-Z0-9]+)\}/", $route->path, $matches)) {
                    //Replace dynamic parameter with value
                    $index = array_search($matches[1], $route->params);
                    $route->params[$index] = $lastSegment;
                    return $route;
                }
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
