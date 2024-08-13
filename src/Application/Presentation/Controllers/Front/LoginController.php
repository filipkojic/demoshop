<?php

namespace Application\Presentation\Controllers\Front;

use Application\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use Infrastructure\HTTP\HttpRequest;
use Infrastructure\HTTP\Response\HtmlResponse;
use Infrastructure\Utility\SessionManager;

class LoginController extends FrontController
{
    public function __construct(protected LoginServiceInterface $loginService)
    {
    }

    public function index(HttpRequest $request): HtmlResponse
    {
        if (SessionManager::getInstance()->get('user_id')) {
            return HtmlResponse::fromView(__DIR__ . '/../../Views/dashboard.php', []);
        }

        return HtmlResponse::fromView(__DIR__ . '/../../Views/login.php', []);
    }

    public function login(HttpRequest $request): HtmlResponse
    {
        $username = $request->getBodyParam('username', '');
        $password = $request->getBodyParam('password', '');
        $keepLoggedIn = $request->getBodyParam('keepLoggedIn', false);

        $loginResult = $this->loginService->login($username, $password);

        if (!$loginResult['success']) {
            return HtmlResponse::fromView(__DIR__ . '/../../Views/login.php', [
                'error' => $loginResult['message']
            ]);
        }

        SessionManager::getInstance()->set('user_id', $loginResult['userId']);

        $cookieLifetime = $keepLoggedIn ? time() + (24 * 60 * 60) : 0; // 1 day
        SessionManager::getInstance()->setCookie(session_name(), session_id(), $cookieLifetime);

        return HtmlResponse::fromView(__DIR__ . '/../../Views/dashboard.php', []);
    }
}
