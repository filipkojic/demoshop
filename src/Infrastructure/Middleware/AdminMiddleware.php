<?php

namespace Infrastructure\Middleware;

use Infrastructure\HTTP\HttpRequest;
use Infrastructure\Utility\SessionManager;
use Infrastructure\Exceptions\UnauthorizedException;

class AdminMiddleware extends AbstractMiddleware
{
    /**
     * @throws UnauthorizedException
     */
    public function handle(HttpRequest $request): void
    {
        $userId = SessionManager::getInstance()->get('user_id');

        if (!$userId) {
            throw new UnauthorizedException('You are not authorized to access this page!');
        }

        parent::handle($request);
    }
}
