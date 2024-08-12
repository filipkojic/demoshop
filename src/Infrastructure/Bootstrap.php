<?php

namespace Infrastructure;

use Application\Business\Services\LoginService;
use Application\Persistence\Repositories\AdminRepository;
use Application\Presentation\Controllers\Admin\AdminProductController;
use Application\Presentation\Controllers\Admin\CategoryController;
use Application\Presentation\Controllers\Admin\DashboardController;
use Application\Presentation\Controllers\Front\HomeController;
use Application\Presentation\Controllers\Front\LoginController;
use Application\Presentation\Controllers\Front\ProductController;
use Application\Presentation\Controllers\Front\ProductSearchController;
use Dotenv\Dotenv;
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
        $dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../');
        $dotenv->load();

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
        ServiceRegistry::getInstance()->register(LoginController::class, new LoginController(
            new LoginService(new AdminRepository())
        ));

        // Admin Controllers
        ServiceRegistry::getInstance()->register(DashboardController::class, new DashboardController());
        ServiceRegistry::getInstance()->register(CategoryController::class, new CategoryController());
        ServiceRegistry::getInstance()->register(AdminProductController::class, new AdminProductController());
    }

}
