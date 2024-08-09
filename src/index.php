<?php

use Infrastructure\Bootstrap;
use Infrastructure\HTTP\HttpRequest;
use Infrastructure\HTTP\Response\HtmlResponse;
use Infrastructure\Utility\Router\Router;
use Infrastructure\Utility\ServiceRegistry;

require '../vendor/autoload.php';

// Initialize and register all necessary services and controllers
Bootstrap::initialize();

$router = ServiceRegistry::getInstance()->get(Router::class);


try {
    $request = new HttpRequest();
    $response = $router->matchRoute($request);
    $response->send();
} catch (Exception $e) {
    $response = new HtmlResponse(404, [], 'Page not found');
    $response->send();
}
