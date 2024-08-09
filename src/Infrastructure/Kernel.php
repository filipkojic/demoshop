<?php

namespace Infrastructure;

use Illuminate\Database\Capsule\Manager as Capsule;
use Infrastructure\Utility\Router\RouteRegistry;

class Kernel
{
    public static function init(): void
    {
        // Initialize Bootstrap to register services and controllers
        Bootstrap::initialize();

        // Initialize Eloquent ORM
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => getenv('DB_CONNECTION'),
            'host' => getenv('DB_HOST'),
            'database' => getenv('DB_DATABASE'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        // Register routes
        RouteRegistry::registerRoutes();
    }
}
