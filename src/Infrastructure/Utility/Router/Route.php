<?php

namespace Infrastructure\Utility\Router;

/**
 * Class Route
 *
 * Represents a single route with its associated method, URL, controller, and action.
 */
readonly class Route
{
    public function __construct(
        private string $method,
        private string $url,
        private string $controller,
        private string $action
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
}
