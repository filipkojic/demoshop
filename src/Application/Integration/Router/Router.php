<?php

namespace Application\Integration\Router;

use Infrastructure\HTTP\HttpRequest;
use Infrastructure\HTTP\Response\HttpResponse;
use Infrastructure\Utility\ServiceRegistry;
use Infrastructure\Utility\Singleton;

/**
 * Class Router
 *
 * This class handles user requests.
 */
class Router extends Singleton
{
    /**
     * @var Route[] An array that holds all registered routes.
     *
     * This array stores routes grouped by HTTP method (e.g., GET, POST).
     * Each method key in the array contains a list of Route objects that are registered for that method.
     */
    protected array $routes = [];

    /**
     * Add a route to the router.
     *
     * @param Route $route
     */
    public function addRoute(Route $route): void
    {
        $this->routes[$route->getMethod()][] = $route;
    }

    /**
     * Match the request URL with the registered routes and execute the corresponding controller action.
     *
     * @param HttpRequest $request
     * @return HttpResponse
     * @throws \Exception
     */
    public function matchRoute(HttpRequest $request): HttpResponse
    {
        $method = $request->getMethod();
        $url = $request->getUri();

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route) {
                $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $route->getUrl());
                if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    $controllerClass = $route->getController();
                    $action = $route->getAction();
                    $controller = ServiceRegistry::getInstance()->get($controllerClass);

                    foreach ($route->getMiddlewares() as $middleware) {
                        $middleware->handle($request);
                    }

                    return call_user_func_array([$controller, $action], array_merge([$request], $params));
                }
            }
        }

        throw new \Exception('Route not found');
    }
}

