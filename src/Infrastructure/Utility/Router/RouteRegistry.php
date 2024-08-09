<?php

namespace Infrastructure\Utility\Router;

use Application\Presentation\Controllers\Front\LoginController;

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
        // register routes
        Router::getInstance()->addRoute(
            new Route('GET', '/src/admin', LoginController::class, 'index')
        );

    }
}
