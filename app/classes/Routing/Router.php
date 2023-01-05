<?php namespace MusicCollection\Routing;

use MusicCollection\Utils\URL;

/**
 * Class Router
 * @package MusicCollection\Routing
 */
class Router
{
    private string $currentPath;
    /**
     * @var Route[]
     */
    private array $routes = [];
    /**
     * @var string[]
     */
    private array $allowedMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    public function __construct()
    {
        $this->currentPath = URL::getCurrentPath();
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
     * @param array<string, string|int> $params
     * @return string
     * @throws \Exception
     */
    public function getFullPathByName(string $name, array $params = []): string
    {
        foreach ($this->routes as $route) {
            if ($name == $route->name) {
                $returnPath = BASE_PATH . $route->path;

                if (!empty($params) && count($params) === count($route->params)) {
                    foreach ($params as $key => $value) {
                        if (!in_array($key, $route->params)) {
                            throw new \Exception("Parameter '$key' is not valid for route '$route->name'");
                        }
                        if (preg_match("/\{$key\}/", $route->path, $matches) > 0) {
                            $returnPath = str_replace($matches[0], $value, $returnPath);
                        }
                    }
                } elseif (count($params) !== count($route->params)) {
                    throw new \Exception("Not all parameters are given for route '$route->name'");
                }

                return $returnPath;
            }
        }

        throw new \Exception('Route name not found');
    }

    /**
     * @return Route
     * @throws \Exception
     */
    public function getRoute(): Route
    {
        $matchedRoute = false;

        //First check a 1-on-1 match for simple routes
        if (isset($this->routes[$this->currentPath])) {
            $matchedRoute = $this->routes[$this->currentPath];
        } else {
            //We need to check all the routes for dynamic parameters
            foreach ($this->routes as $route) {
                //Since we are making modifications, we need to assure the rest of the application can work as intended
                $route = clone($route);

                //If no replacement strings for parameters are found, we can continue
                if (!str_contains($route->path, '{')) {
                    continue;
                }

                //Build a new RegEx that can match the current URL (replace params with regEx lookup)
                $routeParts = array_map(function ($routePart) {
                    return str_contains($routePart, '{') ? "([a-zA-Z0-9]+)" : $routePart;
                }, explode('/', $route->path));
                $matchRegEx = implode('\/', $routeParts);

                //Check if matches are found based on the current visited path
                if (preg_match_all("/$matchRegEx/", $this->currentPath, $matches) > 0) {
                    //Replace the dynamic parameters with actual values
                    array_shift($matches);
                    $newPath = $route->path;
                    foreach ($matches as $index => $match) {
                        $newPath = str_replace('{' . $route->params[$index] . '}', $match[0], $newPath);
                        $route->params[$index] = $match[0];
                    }

                    //One last check to make sure the URL is now a 1-on-1 match
                    if ($newPath === $this->currentPath) {
                        $matchedRoute = $route;
                        break;
                    }
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
     * @throws \Exception
     */
    private function notFoundRoute(): Route
    {
        header('HTTP/1.0 404 Not Found');

        return new Route('GET', $this->currentPath, '\\MusicCollection\\Handlers\\NotFoundHandler', 'index');
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
