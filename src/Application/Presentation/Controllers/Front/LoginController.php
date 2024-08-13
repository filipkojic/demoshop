<?php

namespace Application\Presentation\Controllers\Front;

use Application\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use Infrastructure\HTTP\HttpRequest;
use Infrastructure\HTTP\Response\HtmlResponse;
use Infrastructure\Utility\SessionManager;

/**
 * Class LoginController
 *
 * Handles user login and dashboard redirection.
 */
class LoginController extends FrontController
{
    /**
     * LoginController constructor.
     *
     * @param LoginServiceInterface $loginService The service responsible for handling login logic.
     */
    public function __construct(protected LoginServiceInterface $loginService)
    {
    }

    /**
     * Display the login page or redirect to the dashboard if the user is already logged in.
     *
     * @param HttpRequest $request The HTTP request object.
     * @return HtmlResponse The HTML response with the appropriate view.
     */
    public function index(HttpRequest $request): HtmlResponse
    {
        // If the user is already logged in, redirect to the dashboard
        if (SessionManager::getInstance()->get('user_id')) {
            return HtmlResponse::fromView(__DIR__ . '/../../Views/dashboard.php', []);
        }

        // Otherwise, show the login page
        return HtmlResponse::fromView(__DIR__ . '/../../Views/login.php', []);
    }

    /**
     * Handle the login process.
     *
     * Validates user credentials, sets session data, and determines cookie lifetime.
     *
     * @param HttpRequest $request The HTTP request object containing the login form data.
     * @return HtmlResponse The HTML response with either the dashboard view or the login view with an error message.
     */
    public function login(HttpRequest $request): HtmlResponse
    {
        // Retrieve form data from the request
        $username = $request->getBodyParam('username', '');
        $password = $request->getBodyParam('password', '');
        $keepLoggedIn = $request->getBodyParam('keepLoggedIn', false);

        // Attempt to log in using the provided credentials
        $loginResult = $this->loginService->login($username, $password);

        // If login fails, return to the login page with an error message
        if (!$loginResult['success']) {
            return HtmlResponse::fromView(__DIR__ . '/../../Views/login.php', [
                'error' => $loginResult['message']
            ]);
        }

        // If login is successful, store the user ID in the session
        SessionManager::getInstance()->set('user_id', $loginResult['userId']);

        // Set the session cookie lifetime based on the 'Keep me logged in' option
        $cookieLifetime = $keepLoggedIn ? time() + (24 * 60 * 60) : 0; // 1 day
        SessionManager::getInstance()->setCookie(session_name(), session_id(), $cookieLifetime);

        // Redirect to the dashboard after successful login
        return HtmlResponse::fromView(__DIR__ . '/../../Views/dashboard.php', []);
    }
}
