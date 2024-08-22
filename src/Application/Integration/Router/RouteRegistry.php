<?php

namespace Application\Integration\Router;

use Application\Integration\Middleware\AdminMiddleware;
use Application\Presentation\Controllers\Admin\AdminProductController;
use Application\Presentation\Controllers\Admin\CategoryController;
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
    // ADD MIDDLEWARES AFTER TESTING
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

        Router::getInstance()->addRoute(
            (new Route('GET', '/admin/products', LoginController::class, 'index'))
                ->addMiddleware(new AdminMiddleware())
        );

        Router::getInstance()->addRoute(
            (new Route('GET', '/admin/categories', LoginController::class, 'index'))
                ->addMiddleware(new AdminMiddleware())
        );


        // Dashboard controller routes
        Router::getInstance()->addRoute(
            (new Route('GET', '/getStatistics', DashboardController::class, 'getStatistics'))
        );

        // Category controller routes
        Router::getInstance()->addRoute(
            (new Route('GET', '/getCategories', CategoryController::class, 'getAllCategories')
            ));

        Router::getInstance()->addRoute(
            new Route('POST', '/addCategory', CategoryController::class, 'addCategory')
        );

        Router::getInstance()->addRoute(
            new Route('DELETE', '/deleteCategory', CategoryController::class, 'deleteCategory')
        );

        Router::getInstance()->addRoute(
            new Route('PUT', '/updateCategory', CategoryController::class, 'updateCategory')
        );

        // AdminProductsController routes
        Router::getInstance()->addRoute(
            new Route('GET', '/getAllProducts', AdminProductController::class, 'getAllProducts')
        );

        Router::getInstance()->addRoute(
            new Route('PATCH', '/toggleProductsEnabled', AdminProductController::class, 'toggleProductsEnabled')
        );

        Router::getInstance()->addRoute(
            new Route('DELETE', '/deleteProducts', AdminProductController::class, 'deleteProducts')
        );

        // Middleware test
        Router::getInstance()->addRoute(
            (new Route('GET', '/admin/test', LoginController::class, 'test'))
                ->addMiddleware(new AdminMiddleware())
        );

    }
}
