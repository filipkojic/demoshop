<?php

namespace Application\Integration\Router;

use Application\Integration\Middleware\AdminMiddleware;
use Application\Presentation\Controllers\Admin\DashboardController;
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
        // Login controller routes
        Router::getInstance()->addRoute(
            new Route('GET', '/', LoginController::class, 'index')
        );

        Router::getInstance()->addRoute(
            new Route('GET', '/admin', LoginController::class, 'index')
        );

        Router::getInstance()->addRoute(
            new Route('POST', '/admin', LoginController::class, 'login')
        );

        // Dashboard controller routes
        Router::getInstance()->addRoute(
            new Route('GET', '/admin/dashboard', DashboardController::class, 'index')
        );



        // middleware test
        Router::getInstance()->addRoute(
            (new Route('GET', '/admin/test', LoginController::class, 'test'))
                ->addMiddleware(new AdminMiddleware())
        );

    }
}
