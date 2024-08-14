<?php

namespace Infrastructure\Utility\Router;

use Application\Presentation\Controllers\Front\LoginController;
use Infrastructure\Middleware\AdminMiddleware;

/**
 * Class for registering routes
 */
class RouteRegistry
{
    /**
     * Registers all routes with the router.
     */
    public static function registerRoutes(): void
    {
        // Login controller routes
        Router::getInstance()->addRoute(
            new Route('GET', '/src/admin', LoginController::class, 'index')
        );

        Router::getInstance()->addRoute(
            new Route('POST', '/src/admin', LoginController::class, 'login')
        );

        // middleware test
        Router::getInstance()->addRoute(
            (new Route('GET', '/src/admin/test', LoginController::class, 'test'))
                ->addMiddleware(new AdminMiddleware())
        );

    }
}
