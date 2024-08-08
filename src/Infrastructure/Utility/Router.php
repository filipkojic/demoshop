<?php

namespace Infrastructure\Utility;

use Infrastructure\HTTP\HttpRequest;
use Infrastructure\HTTP\Response\HtmlResponse;
use Infrastructure\HTTP\Response\HttpResponse;
use Infrastructure\HTTP\Response\JsonResponse;

/*
 * Class Router
 *
 * This class handles user requests.
 */
class Router extends Singleton
{
    protected array $routes = [];

    /**
     * Add routes to the $routes
     *
     * @param string $method
     * @param string $url
     * @param callable $target
     */
    public function addRoute(string $method, string $url, callable $target): void
    {
        $this->routes[$method][$url] = $target;
    }

    /**
     * Match the request URL with the registered routes
     *
     * @param HttpRequest $request
     * @return HttpResponse
     */
    public function matchRoute(HttpRequest $request): HttpResponse
    {
        $method = $request->getMethod();
        $url = $request->getUri();

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routeUrl => $target) {
                $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $routeUrl);
                if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    return call_user_func_array($target, $params);
                }
            }
        }
        throw new \Exception('Route not found');
    }
}
