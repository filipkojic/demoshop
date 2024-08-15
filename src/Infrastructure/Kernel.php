<?php

namespace Infrastructure;

use Application\Integration\Exceptions\UnauthorizedException;
use Exception;
use Infrastructure\Database\DatabaseManager;
use Infrastructure\HTTP\HttpRequest;
use Infrastructure\HTTP\Response\HtmlResponse;
use Infrastructure\Utility\Router\Router;
use Infrastructure\Utility\Router\RouteRegistry;

class Kernel
{
    /**
     * Initialize the application and handle the incoming HTTP request.
     */
    public static function init(): void
    {
        try {
            // Initialize Bootstrap to register services and controllers
            Bootstrap::initialize();

            // Initialize Eloquent ORM
            DatabaseManager::initialize();

            // Register routes
            RouteRegistry::registerRoutes();

            // Handle the HTTP request and send the response
            self::handleRequest();
        } catch (Exception $e) {
            self::handleError(500, "An unexpected error occurred: " . $e->getMessage());
        }
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
        } catch (UnauthorizedException $e) {
            self::handleError(403, $e->getMessage());
        } catch (Exception $e) {
            self::handleError(404, 'Page not found!');
        }
    }

    /**
     * Handle errors by sending an appropriate HTTP response.
     *
     * @param int $statusCode The HTTP status code to return.
     * @param string $message The error message to display.
     */
    protected static function handleError(int $statusCode, string $message): void
    {
        $response = new HtmlResponse($statusCode, [], $message);
        $response->send();
    }
}
