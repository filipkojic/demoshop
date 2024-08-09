<?php

namespace Infrastructure;

use Application\Presentation\Controllers\Admin\AdminProductController;
use Application\Presentation\Controllers\Admin\CategoryController;
use Application\Presentation\Controllers\Admin\DashboardController;
use Application\Presentation\Controllers\Front\HomeController;
use Application\Presentation\Controllers\Front\LoginController;
use Application\Presentation\Controllers\Front\ProductController;
use Application\Presentation\Controllers\Front\ProductSearchController;
use Exception;
use Infrastructure\Utility\Router\Router;
use Infrastructure\Utility\Router\RouteRegistry;
use Infrastructure\Utility\ServiceRegistry;


/**
 * Class Bootstrap
 *
 * This class initializes and registers all the necessary services and controllers.
 */
class Bootstrap
{
    /**
     * Initialize and register all services and controllers.
     *
     * @throws Exception
     */
    public static function initialize(): void
    {
        // Load environment variables
        //$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../');
        //$dotenv->load();

        self::registerRepos();
        self::registerServices();
        self::registerControllers();
        self::registerRoutes();
    }

    /**
     * Registers repository instances with the service registry.
     * @return void
     */
    protected static function registerRepos(): void
    {
        /*ServiceRegistry::getInstance()->register(AuthorRepositoryInterface::class, new SqlAuthorRepository());
        ServiceRegistry::getInstance()->register(BookRepositoryInterface::class, new SqlBookRepository());*/
    }

    /**
     * Registers service instances with the service registry.
     * @return void
     *
     * @throws Exception
     */
    protected static function registerServices(): void
    {
        ServiceRegistry::getInstance()->register(Router::class, Router::getInstance());
        /*ServiceRegistry::getInstance()->register(AuthorServiceInterface::class, new AuthorService(
            ServiceRegistry::getInstance()->get(AuthorRepositoryInterface::class),
            ServiceRegistry::getInstance()->get(BookRepositoryInterface::class)
        ));
        ServiceRegistry::getInstance()->register(BookServiceInterface::class, new BookService(
            ServiceRegistry::getInstance()->get(BookRepositoryInterface::class),
            ServiceRegistry::getInstance()->get(AuthorRepositoryInterface::class)
        ));*/
    }

    /**
     * Registers controller instances with the service registry.
     * @return void
     *
     * @throws Exception
     */
    protected static function registerControllers(): void
    {

        // Front Controllers
        ServiceRegistry::getInstance()->register(HomeController::class, new HomeController());
        ServiceRegistry::getInstance()->register(ProductSearchController::class, new ProductSearchController());
        ServiceRegistry::getInstance()->register(ProductController::class, new ProductController());
        ServiceRegistry::getInstance()->register(LoginController::class, new LoginController());

        // Admin Controllers
        ServiceRegistry::getInstance()->register(DashboardController::class, new DashboardController());
        ServiceRegistry::getInstance()->register(CategoryController::class, new CategoryController());
        ServiceRegistry::getInstance()->register(AdminProductController::class, new AdminProductController());
    }


    protected static function registerRoutes(): void
    {
        RouteRegistry::registerRoutes();
    }

}
