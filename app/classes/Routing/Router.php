<?php namespace MusicCollection\Routing;

use MusicCollection\Utils\URL;

/**
 * Class Router
 * @package MusicCollection\Routing
 */
class Router
{
    /**
     * @var Route[] array
     */
    private array $routes = [];
    private string $currentPath;
    private array $pathSegments;
    private array $allowedMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    public function __construct()
    {
        $this->currentPath = URL::getCurrentPath();
        $this->pathSegments = explode('/', $this->currentPath);
    }

    /**
     * @param string $path
     * @param string $controllerAction
     * @param string $method
     * @return Route
     * @throws \Exception
     */
    public function addRoute(string $path, string $controllerAction, string $method): Route
    {
        if (!in_array($method, $this->allowedMethods)) {
            throw new \Exception("Method $method is not allowed");
        }

        list($className, $action) = explode('@', $controllerAction);
        $fullClassName = '\\MusicCollection\\Handlers\\' . $className;
        $this->routes[$path] = new Route($method, $path, $fullClassName, $action);

        return $this->routes[$path];
    }

    /**
     * @param string $path
     * @param string $controllerAction
     * @return Route
     * @throws \Exception
     */
    public function get(string $path, string $controllerAction): Route
    {
        return $this->addRoute($path, $controllerAction, 'GET');
    }

    /**
     * @param string $path
     * @param string $controllerAction
     * @return Route
     * @throws \Exception
     */
    public function post(string $path, string $controllerAction): Route
    {
        return $this->addRoute($path, $controllerAction, 'POST');
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
     * @return Route
     */
    public function getRoute(): Route
    {
        $matchedRoute = false;
        if (isset($this->routes[$this->currentPath])) {
            $matchedRoute = $this->routes[$this->currentPath];
        } else {
            foreach ($this->routes as $route) {
                //Get a path except last segment
                $segments = $this->pathSegments;
                $lastSegment = array_pop($segments);
                $strippedRoutePath = preg_quote(implode('/', $segments) . '/', '/');

                //Check if the path without last segment + dynamic naming exists
                //@TODO Triple check all possible 404 scenarios to fix exceptions
                if (count($this->pathSegments) > 1 && preg_match("/$strippedRoutePath\{([a-zA-Z0-9]+)\}/", $route->path, $matches)) {
                    //Replace dynamic parameter with value
                    $index = array_search($matches[1], $route->params);
                    $route->params[$index] = $lastSegment;
                    $matchedRoute = $route;

                    break;
                }
            }
        }

        if ($matchedRoute === false || $matchedRoute->method !== $_SERVER['REQUEST_METHOD']) {
            return $this->notFoundRoute();
        }

        return $matchedRoute;
    }

    /**
     * @return Route
     */
    private function notFoundRoute(): Route
    {
        header('HTTP/1.0 404 Not Found');

        return new Route('GET', $this->currentPath, '\\MusicCollection\\Handlers\\NotFoundHandler', 'index');
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
