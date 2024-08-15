<?php

namespace Infrastructure\Database;

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

class DatabaseManager
{
    public static function initialize(): void
    {
        // Load environment variables
        $dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../../');
        $dotenv->load();

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
    }
}
