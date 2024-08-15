<?php

namespace Infrastructure\Utility\Router;

use Application\Integration\Middleware\MiddlewareInterface;

/**
 * Class Route
 *
 * Represents a single route with its associated method, URL, controller, and action.
 */
class Route
{
    /**
     * Route constructor.
     *
     * Initializes a new route with the specified HTTP method, URL, controller, action, and optional middleware.
     *
     * @param string $method The HTTP method (e.g., GET, POST) for this route.
     * @param string $url The URL pattern that this route should match.
     * @param string $controller The controller class that will handle the request.
     * @param string $action The specific method in the controller that will be executed.
     * @param array $middlewares Optional array of middleware that should be applied to this route.
     */
    public function __construct(
        private string $method,
        private string $url,
        private string $controller,
        private string $action,
        private array  $middlewares = []
    )
    {
    }

    /**
     * Get the HTTP method (GET, POST, etc.).
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get the URL endpoint.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get the controller class name.
     *
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * Get the action (method) to be called on the controller.
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Add middleware to the route.
     *
     * @param MiddlewareInterface $middleware The middleware to add.
     * @return $this
     */
    public function addMiddleware(MiddlewareInterface $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * Get the list of middleware associated with the route.
     *
     * @return MiddlewareInterface[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
