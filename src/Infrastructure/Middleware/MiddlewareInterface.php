<?php

namespace Infrastructure\Middleware;

use Infrastructure\HTTP\HttpRequest;

interface MiddlewareInterface
{
    public function setNext(MiddlewareInterface $next): MiddlewareInterface;
    public function handle(HttpRequest $request): void;
}
