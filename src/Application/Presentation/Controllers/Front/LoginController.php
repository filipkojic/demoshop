<?php

namespace Application\Presentation\Controllers\Front;

use Application\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use Infrastructure\HTTP\Response\HtmlResponse;

class LoginController extends FrontController
{
    public function __construct(protected LoginServiceInterface $loginService)
    {
    }

    public function index(): HtmlResponse
    {
        return HtmlResponse::fromView(__DIR__ . '/../../Views/login.php', []);
    }

    public function login(): HtmlResponse
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $loginResult = $this->loginService->login($username, $password);

        if ($loginResult['success']) {
            return HtmlResponse::fromView(__DIR__ . '/../../Views/dashboard.php', []);
        } else {
            return HtmlResponse::fromView(__DIR__ . '/../../Views/login.php', [
                'error' => $loginResult['message'],
                'username' => $username
            ]);
        }
    }
}
