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
        // Dobavljanje podataka iz POST zahteva
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Provera da li je prijava uspešna
        if ($this->loginService->login($username, $password)) {
            // Uspešna prijava, preusmeravanje na dashboard
            return HtmlResponse::fromView(__DIR__ . '/../../Views/dashboard.php', []);
        } else {
            // Neuspešna prijava, vraćanje na login stranicu sa greškom
            return HtmlResponse::fromView(__DIR__ . '/../../Views/login.php', [
                'error' => 'Invalid username or password. Please try again.',
            ]);
        }
    }
}
