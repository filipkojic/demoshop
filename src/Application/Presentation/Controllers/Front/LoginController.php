<?php

namespace Application\Presentation\Controllers\Front;

use Application\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use Infrastructure\HTTP\Response\HtmlResponse;
use Infrastructure\Utility\SessionManager;

class LoginController extends FrontController
{
    protected SessionManager $sessionManager;

    public function __construct(protected LoginServiceInterface $loginService)
    {
        $this->sessionManager = SessionManager::getInstance();
    }

    public function index(): HtmlResponse
    {
        if ($this->sessionManager->get('user_id')) {
            return HtmlResponse::fromView(__DIR__ . '/../../Views/dashboard.php', []);
        }

        return HtmlResponse::fromView(__DIR__ . '/../../Views/login.php', []);
    }

    public function login(): HtmlResponse
    {
        // Dobavljanje podataka iz POST zahteva
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $keepLoggedIn = isset($_POST['keepLoggedIn']);

        // Pokušaj prijave i dobijanje rezultata
        $loginResult = $this->loginService->login($username, $password);

        if ($loginResult['success']) {
            // Postavljanje sesije
            $this->sessionManager->set('user_id', $loginResult['userId']);

            if ($keepLoggedIn) {
                // Ako je opcija 'Keep me logged in' odabrana, postavljanje dugotrajnog session cookie-a
                $cookieLifetime = time() + (10 * 365 * 24 * 60 * 60); // 10 godina
                setcookie(session_name(), session_id(), $cookieLifetime, "/");
            } else {
                // Sesija traje samo tokom trenutne sesije browsera
                setcookie(session_name(), session_id(), 0, "/");
            }

            // Uspešna prijava, preusmeravanje na dashboard
            return HtmlResponse::fromView(__DIR__ . '/../../Views/dashboard.php', []);
        } else {
            // Neuspešna prijava, vraćanje na login stranicu sa porukom o grešci
            return HtmlResponse::fromView(__DIR__ . '/../../Views/login.php', [
                'error' => $loginResult['message'],
                'username' => $username // Vraćanje korisničkog imena za ponovno popunjavanje forme
            ]);
        }
    }
}
