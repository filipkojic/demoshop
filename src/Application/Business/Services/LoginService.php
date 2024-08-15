<?php

namespace Application\Business\Services;

use Application\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use Application\Business\Interfaces\RepositoryInterfaces\AdminRepositoryInterface;
use Application\Integration\Exceptions\UnauthorizedException;
use Infrastructure\Utility\SessionManager;

/**
 * Class LoginService
 *
 * This class implements the LoginServiceInterface interface.
 */
class LoginService implements LoginServiceInterface
{
    public function __construct(protected AdminRepositoryInterface $adminRepository)
    {
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

        $cookieLifetime = $keepLoggedIn ? time() + (24 * 60 * 60) : 0; // 1 dan
        SessionManager::getInstance()->setCookie(session_name(), session_id(), $cookieLifetime);

        return true;
    }
}
