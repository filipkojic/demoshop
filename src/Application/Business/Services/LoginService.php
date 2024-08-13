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
     * @return array An array containing the result and a message.
     */
    public function login(string $username, string $password): array
    {
        $admin = $this->adminRepository->findByUsername($username);

        if (!$admin) {
            return [
                'success' => false,
                'message' => 'Invalid username or password. Please try again.'
            ];
        }

        if (password_verify($password, $admin->getPassword())) {
            return [
                'success' => true,
                'message' => 'Login successful.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Invalid username or password. Please try again.'
        ];
    }
}
