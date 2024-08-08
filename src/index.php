<?php

use Infrastructure\Bootstrap;
use Infrastructure\HTTP\HttpRequest;
use Infrastructure\HTTP\Response\HtmlResponse;
use Infrastructure\Utility\Router;
use Infrastructure\Utility\ServiceRegistry;

require '../vendor/autoload.php';

// Initialize and register all necessary services and controllers
Bootstrap::initialize();

$router = ServiceRegistry::getInstance()->get(Router::class);

$router->addRoute('GET', '/src/', function () {
    return new HtmlResponse(200, [], "Welcome to the home page!");
});

$router->addRoute('GET', '/src/products', function () {
    return new HtmlResponse(200, [], "Product list page!");
});

$router->addRoute('GET', '/src/products/:id', function ($id) {
    return new HtmlResponse(200, [], "Product detail page for product with ID: $id");
});

try {
    $request = new HttpRequest();
    $response = $router->matchRoute($request);
    $response->send();
} catch (Exception $e) {
    http_response_code(404);
    echo $e->getMessage();
}
