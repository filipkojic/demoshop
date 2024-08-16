<?php

namespace Application\Presentation\Controllers\Front;

use Application\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use Application\Integration\Utility\PathHelper;
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
        if (SessionManager::getInstance()->get('user_id')) {
            return HtmlResponse::fromView(PathHelper::view('dashboard.php'));
        }

        return HtmlResponse::fromView(PathHelper::view('login.php'));
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
        $username = $request->getBodyParam('username', '');
        $password = $request->getBodyParam('password', '');
        $keepLoggedIn = $request->getBodyParam('keepLoggedIn', false);

        $loginSuccessful = $this->loginService->login($username, $password, $keepLoggedIn);

        if ($loginSuccessful) {
            return HtmlResponse::fromView(PathHelper::view('dashboard.php'));
        }

        return HtmlResponse::fromView(PathHelper::view('login.php'), [
            'error' => 'Invalid username or password. Please try again.'
        ]);
    }


    /**
     * Test for admin middleware
     *
     * @param HttpRequest $request The HTTP request object.
     * @return HtmlResponse The HTML response with the appropriate view.
     */
    public function test(HttpRequest $request): HtmlResponse
    {
        return HtmlResponse::fromView(PathHelper::view('dashboard.php'));
    }
}
