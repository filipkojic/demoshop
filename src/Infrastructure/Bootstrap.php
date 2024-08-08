<?php

namespace Infrastructure;

use Exception;
use Infrastructure\Utility\Router;
use Infrastructure\Utility\RouteRegistry;
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
        /*ServiceRegistry::getInstance()->register(AuthorController::class, new AuthorController(
            ServiceRegistry::getInstance()->get(AuthorServiceInterface::class)
        ));
        ServiceRegistry::getInstance()->register(BookController::class, new BookController(
            ServiceRegistry::getInstance()->get(BookServiceInterface::class)
        ));*/
    }
    protected static function registerRoutes(): void
    {
        RouteRegistry::registerRoutes();
    }

}
