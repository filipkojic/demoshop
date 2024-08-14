<?php

namespace Infrastructure\Middleware;

use Infrastructure\HTTP\HttpRequest;

/**
 * Class AbstractMiddleware
 *
 * Provides a base implementation of the MiddlewareInterface with support for chaining middleware.
 */
abstract class AbstractMiddleware implements MiddlewareInterface
{
    /**
     * @var MiddlewareInterface|null The next middleware in the chain, or null if this is the last middleware.
     */
    protected ?MiddlewareInterface $next = null;

    /**
     * Sets the next middleware in the chain of responsibility.
     *
     * @param MiddlewareInterface $next The next middleware to be executed.
     * @return MiddlewareInterface The next middleware in the chain.
     */
    public function setNext(MiddlewareInterface $next): MiddlewareInterface
    {
        $this->next = $next;
        return $next;
    }

    /**
     * Handles the request and passes it to the next middleware if it exists.
     *
     * @param HttpRequest $request The HTTP request object to be processed.
     */
    public function handle(HttpRequest $request): void
    {
        if ($this->next) {
            $this->next->handle($request);
        }
    }
}
