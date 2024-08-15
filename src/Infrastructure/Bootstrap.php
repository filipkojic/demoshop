<?php

namespace Infrastructure;

use Application\Business\Interfaces\RepositoryInterfaces\AdminRepositoryInterface;
use Application\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use Application\Business\Services\LoginService;
use Application\Persistence\Repositories\AdminRepository;
use Application\Presentation\Controllers\Front\LoginController;
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
    }
}
