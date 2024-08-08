<?php

namespace Infrastructure\Utility;

use Infrastructure\HTTP\Response\HtmlResponse;

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

        $router->addRoute('GET', '/src/', function () {
            return new HtmlResponse(200, [], "Welcome to the home page!");
        });

        $router->addRoute('GET', '/src/products', function () {
            return new HtmlResponse(200, [], "Product list page!");
        });

        $router->addRoute('GET', '/src/products/:id', function ($id) {
            return new HtmlResponse(200, [], "Product detail page for product with ID: $id");
        });
    }
}
