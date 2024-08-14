<?php

namespace Infrastructure\Middleware;

use Infrastructure\HTTP\HttpRequest;

abstract class AbstractMiddleware implements MiddlewareInterface
{
    protected ?MiddlewareInterface $next = null;

    public function setNext(MiddlewareInterface $next): MiddlewareInterface
    {
        $this->next = $next;
        return $next;
    }

    public function handle(HttpRequest $request): void
    {
        if ($this->next) {
            $this->next->handle($request);
        }
    }
}
