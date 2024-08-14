<?php

namespace Infrastructure;

use Exception;
use Infrastructure\HTTP\HttpRequest;
use Infrastructure\HTTP\Response\HtmlResponse;
use Infrastructure\Utility\Router\Router;
use Illuminate\Database\Capsule\Manager as Capsule;
use Infrastructure\Utility\Router\RouteRegistry;

class Kernel
{
    /**
     * Initialize the application and handle the incoming HTTP request.
     */
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

        // Handle the HTTP request and send the response
        self::handleRequest();
    }

    /**
     * Handle the incoming HTTP request, route it, and send the response.
     */
    protected static function handleRequest(): void
    {
        try {
            $request = new HttpRequest();
            $response = Router::getInstance()->matchRoute($request);
            $response->send();
        } catch (Exception $e) {
            $response = new HtmlResponse(404, [], 'Page not found');
            $response->send();
        }
    }
}
