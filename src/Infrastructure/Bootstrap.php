<?php

namespace Infrastructure;

use Application\Business\Interfaces\RepositoryInterfaces\AdminRepositoryInterface;
use Application\Business\Interfaces\RepositoryInterfaces\CategoryRepositoryInterface;
use Application\Business\Interfaces\RepositoryInterfaces\ProductRepositoryInterface;
use Application\Business\Interfaces\RepositoryInterfaces\StatisticsRepositoryInterface;
use Application\Business\Interfaces\ServiceInterfaces\CategoryServiceInterface;
use Application\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use Application\Business\Interfaces\ServiceInterfaces\ProductServiceInterface;
use Application\Business\Interfaces\ServiceInterfaces\StatisticsServiceInterface;
use Application\Business\Services\CategoryService;
use Application\Business\Services\LoginService;
use Application\Business\Services\ProductService;
use Application\Business\Services\StatisticsService;
use Application\Persistence\Repositories\AdminRepository;
use Application\Persistence\Repositories\CategoryRepository;
use Application\Persistence\Repositories\ProductRepository;
use Application\Persistence\Repositories\StatisticsRepository;
use Application\Presentation\Controllers\Admin\CategoryController;
use Application\Presentation\Controllers\Admin\DashboardController;
use Application\Presentation\Controllers\Front\LoginController;
use Exception;
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
        // Register repositories, services, and controllers
        self::registerRepos();
        self::registerServices();
        self::registerControllers();
    }

    /**
     * Registers repository instances with the service registry.
     * @return void
     */
    protected static function registerRepos(): void
    {
        ServiceRegistry::getInstance()->register(AdminRepositoryInterface::class, new AdminRepository());
        ServiceRegistry::getInstance()->register(CategoryRepositoryInterface::class, new CategoryRepository());
        ServiceRegistry::getInstance()->register(ProductRepositoryInterface::class, new ProductRepository());
        ServiceRegistry::getInstance()->register(StatisticsRepositoryInterface::class, new StatisticsRepository());
    }

    /**
     * Registers service instances with the service registry.
     * @return void
     *
     * @throws Exception
     */
    protected static function registerServices(): void
    {
        ServiceRegistry::getInstance()->register(LoginServiceInterface::class, new LoginService(
            ServiceRegistry::getInstance()->get(AdminRepositoryInterface::class)
        ));
        ServiceRegistry::getInstance()->register(CategoryServiceInterface::class, new CategoryService(
            ServiceRegistry::getInstance()->get(CategoryRepositoryInterface::class)
        ));
        ServiceRegistry::getInstance()->register(ProductServiceInterface::class, new ProductService(
            ServiceRegistry::getInstance()->get(ProductRepositoryInterface::class)
        ));
        ServiceRegistry::getInstance()->register(StatisticsServiceInterface::class, new StatisticsService(
            ServiceRegistry::getInstance()->get(StatisticsRepositoryInterface::class)
        ));
    }

    /**
     * Registers controller instances with the service registry.
     * @return void
     *
     * @throws Exception
     */
    protected static function registerControllers(): void
    {
        ServiceRegistry::getInstance()->register(LoginController::class, new LoginController(
            ServiceRegistry::getInstance()->get(LoginServiceInterface::class)
        ));

        ServiceRegistry::getInstance()->register(DashboardController::class, new DashboardController(
            ServiceRegistry::getInstance()->get(CategoryServiceInterface::class),
            ServiceRegistry::getInstance()->get(ProductServiceInterface::class),
            ServiceRegistry::getInstance()->get(StatisticsServiceInterface::class)
        ));

        ServiceRegistry::getInstance()->register(CategoryController::class, new CategoryController(
            ServiceRegistry::getInstance()->get(CategoryServiceInterface::class)
        ));
    }
}
