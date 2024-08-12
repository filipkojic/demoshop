<?php

namespace Application\Business\Services;

use Application\Business\Interfaces\ServiceInterfaces\LoginServiceInterface;
use Application\Business\Interfaces\RepositoryInterfaces\AdminRepositoryInterface;

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
     * @return bool True if login is successful, false otherwise.
     */
    public function login(string $username, string $password): bool
    {
        $admin = $this->adminRepository->findByUsername($username);

        if ($admin && password_verify($password, $admin->getPassword())) {
            // Set session, generate token, etc.
            return true;
        }

        return false;
    }
}
