<?php

namespace Application\Presentation\Controllers\Front;

use Infrastructure\HTTP\Response\HtmlResponse;

class LoginController extends FrontController
{
    public function index(): HtmlResponse
    {
        return HtmlResponse::fromView(__DIR__ . '/../../Views/login.php', []);
    }

    public function login(): HtmlResponse
    {
        // Code for authenticating admin and redirecting to dashboard
    }
}
