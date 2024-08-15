<?php

namespace Application\Integration\Middleware;

use Application\Integration\Exceptions\UnauthorizedException;
use Infrastructure\HTTP\HttpRequest;
use Infrastructure\Utility\SessionManager;

/**
 * Class AdminMiddleware
 *
 * Middleware that checks if the current user is authorized (i.e., logged in as an admin).
 * If the user is not authorized, it throws an UnauthorizedException.
 */
class AdminMiddleware extends AbstractMiddleware
{
    /**
     * Handles the incoming request by checking if the user is logged in as an admin.
     *
     * @param HttpRequest $request The HTTP request object to be processed.
     * @throws UnauthorizedException If the user is not logged in or authorized.
     */
    public function handle(HttpRequest $request): void
    {
        $userId = SessionManager::getInstance()->get('user_id');

        if (!$userId) {
            throw new UnauthorizedException('You are not authorized to access this page!');
        }

        // Call the next middleware in the chain, if any
        parent::handle($request);
    }
}
