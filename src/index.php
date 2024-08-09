<?php

use Infrastructure\Kernel;
use Infrastructure\HTTP\HttpRequest;
use Infrastructure\HTTP\Response\HtmlResponse;
use Infrastructure\Utility\Router\Router;

require '../vendor/autoload.php';

Kernel::init();

try {
    $request = new HttpRequest();
    $response = Router::getInstance()->matchRoute($request);
    $response->send();
} catch (Exception $e) {
    $response = new HtmlResponse(404, [], 'Page not found');
    $response->send();
}

