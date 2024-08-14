<?php

namespace Infrastructure\Middleware;

namespace Infrastructure\Middleware;

use Infrastructure\HTTP\HttpRequest;

/**
 * Interface MiddlewareInterface
 *
 * Defines the contract for middleware components in the application.
 */
interface MiddlewareInterface
{
    /**
     * Sets the next middleware in the chain of responsibility.
     *
     * @param MiddlewareInterface $next The next middleware to be executed.
     * @return MiddlewareInterface The next middleware in the chain.
     */
    public function setNext(MiddlewareInterface $next): MiddlewareInterface;

    /**
     * Handles the request and performs the middleware's action.
     *
     * @param HttpRequest $request The HTTP request object to be processed.
     */
    public function handle(HttpRequest $request): void;
}
