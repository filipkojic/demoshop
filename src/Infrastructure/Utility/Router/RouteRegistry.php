<?php

namespace Infrastructure\Utility\Router;

use Application\Presentation\Controllers\Front\LoginController;
use Application\Presentation\Controllers\HtmlController;
use Application\Presentation\Controllers\ProductController;
use Infrastructure\HTTP\Response\HtmlResponse;
use Infrastructure\Utility\ServiceRegistry;
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
        $router = ServiceRegistry::getInstance()->get(Router::class);

        $router->addRoute(new Route('GET', '/src/admin', LoginController::class, 'index'));

    }
}
