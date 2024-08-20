<?php

namespace Application\Business\Services;

use Application\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use Application\Business\Interfaces\RepositoryInterfaces\AdminRepositoryInterface;
use Infrastructure\Utility\SessionManager;

/**
 * Class LoginService
 *
 * This class implements the LoginServiceInterface interface.
 */
class LoginService implements LoginServiceInterface
{
    private const COOKIE_LIFETIME = 24 * 60 * 60;

    /**
     * @param AdminRepositoryInterface $adminRepository Repository for admin manipulation.
     */
    public function __construct(protected AdminRepositoryInterface $adminRepository)
    {
    }

    /**
     * Check if the user is logged in by verifying the presence of 'user_id' in the session.
     *
     * @return bool True if the user is logged in, false otherwise.
     */
    public function isUserLoggedIn(): bool
    {
        return SessionManager::getInstance()->get('user_id') !== null;
    }

    /**
     * Attempt to log in with the given username and password.
     *
     * @param string $username
     * @param string $password
     * @param bool $keepLoggedIn
     * @return bool The indicator of successfully login.
     */
    public function login(string $username, string $password, bool $keepLoggedIn): bool
    {
        $admin = $this->adminRepository->findByUsername($username);

        if (!($admin && password_verify($password, $admin->getPassword()))) {
            return false;
        }

        SessionManager::getInstance()->set('user_id', $admin->getId());

        $cookieLifetime = $keepLoggedIn ? time() + self::COOKIE_LIFETIME : 0;
        SessionManager::getInstance()->setCookie(session_name(), session_id(), $cookieLifetime);

        return true;
    }
}
