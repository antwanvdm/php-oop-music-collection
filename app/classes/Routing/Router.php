<?php namespace MusicCollection\Routing;

use MusicCollection\Controllers\Web\NotFoundController;
use MusicCollection\DI\Container;
use MusicCollection\Utils\Request;

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

    /**
     * @param Request $request
     * @param Container $di
     */
    public function __construct(private readonly Request $request, private readonly Container $di)
    {
        $this->currentPath = $this->request->currentPath();
    }

    /**
     * @param string $path
     * @param string[] $controllerAction
     * @param string $method
     * @return Route
     * @throws \Exception
     */
    public function addRoute(string $path, array $controllerAction, string $method): Route
    {
        if (!in_array($method, $this->allowedMethods)) {
            throw new \Exception("Method $method is not allowed");
        }

        $newRoute = new Route($method, $path, $controllerAction[0], $controllerAction[1]);
        $this->routes[] = $newRoute;

        return $newRoute;
    }

    /**
     * @param string $path
     * @param string[] $controllerAction
     * @return Route
     * @throws \Exception
     */
    public function get(string $path, array $controllerAction): Route
    {
        return $this->addRoute($path, $controllerAction, 'GET');
    }

    /**
     * @param string $path
     * @param string[] $controllerAction
     * @return Route
     * @throws \Exception
     */
    public function post(string $path, array $controllerAction): Route
    {
        return $this->addRoute($path, $controllerAction, 'POST');
    }

    /**
     * Wrapper to create all paths for a web based overview
     *
     * @param string $name
     * @param string $controller
     * @return Router
     * @throws \Exception
     * @example $router->resource('genres', 'GenreController'); creates
     *              /genres points at 'index' method [GET]
     *              /genres/{id}/detail points at 'detail' method [GET]
     *              /genres/create points at 'create' method [GET]
     *              /genres/{id}/edit points at 'edit' method [GET]
     *              /genres/save points at 'save' method [POST]
     *              /genres/{id}/delete points at 'delete' method [GET]
     */
    public function resource(string $name, string $controller): self
    {
        $this->addRoute($name, [$controller, 'index'], 'GET')->name($name . '.index');
        $this->addRoute($name . '/{id}', [$controller, 'detail'], 'GET')->name($name . '.detail');
        $this->addRoute($name . '/create', [$controller, 'create'], 'GET')->name($name . '.create');
        $this->addRoute($name . '/{id}/edit', [$controller, 'edit'], 'GET')->name($name . '.edit');
        $this->addRoute($name . '/save', [$controller, 'save'], 'POST')->name($name . '.save');
        $this->addRoute($name . '/{id}/delete', [$controller, 'delete'], 'GET')->name($name . '.delete');
        return $this;
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
        foreach ($this->routes as $route) {
            if ($route->path === $this->currentPath) {
                $matchedRoute = $route;
                break;
            }
        }

        //If the simple match isn't found, let's try a more complex version
        if (!$matchedRoute) {
            $matchedRoute = $this->getRouteMatch();
        }

        if ($matchedRoute === false || $matchedRoute->method !== $this->request->requestedMethod()) {
            return $this->notFoundRoute();
        }

        foreach ($matchedRoute->middleware as $middleware) {
            $this->di->set($middleware, $middleware)->handle();
        }

        return $matchedRoute;
    }

    /**
     * @return false|Route
     */
    private function getRouteMatch(): false|Route
    {
        $matchedRoute = false;

        foreach ($this->routes as $route) {
            //First check a 1-on-1 match for simple routes
            if ($route->path === $this->currentPath) {
                $matchedRoute = $route;
                break;
            }

            //Since we are making modifications, we need to assure the rest of the application can work as intended
            $route = clone($route);

            //If no replacement strings for parameters are found, we can continue
            if (!str_contains($route->path, '{')) {
                continue;
            }

            //Build a new RegEx that can match the current URL (replace params with regEx lookup)
            $routeParts = array_map(function ($routePart) {
                return str_contains($routePart, '{') ? '([a-zA-Z0-9]+)' : $routePart;
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

        return $matchedRoute;
    }

    /**
     * @return Route
     * @throws \Exception
     */
    private function notFoundRoute(): Route
    {
        header('HTTP/1.0 404 Not Found');

        return new Route('GET', $this->currentPath, NotFoundController::class, 'index');
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param string|null $prefix
     * @param class-string|class-string[]|null $middleware
     * @param callable $callback
     * @return Router
     * @throws \Exception
     */
    public function group(string|null $prefix, string|array|null $middleware, callable $callback): self
    {
        $currentTotalRoutes = count($this->routes);
        $callback($this);
        for ($i = $currentTotalRoutes; $i < count($this->routes); $i++) {
            if (!is_null($prefix)) {
                $this->routes[$i]->prefix($prefix);
            }
            if (!is_null($middleware)) {
                $this->routes[$i]->middleware($middleware);
            }
        }
        return $this;
    }
}
