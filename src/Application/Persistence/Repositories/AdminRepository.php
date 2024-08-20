<?php

namespace Application\Persistence\Repositories;

use Application\Persistence\Entities\Admin;
use Application\Business\Interfaces\RepositoryInterfaces\AdminRepositoryInterface;
use Application\Business\DomainModels\DomainAdmin;

/**
 * Class AdminRepository
 *
 * This class implements the AdminRepositoryInterface interface using SQL database storage.
 */
class AdminRepository implements AdminRepositoryInterface
{
    /**
     * Find an admin by their username.
     *
     * @param string $username
     * @return DomainAdmin|null
     */
    public function findByUsername(string $username): ?DomainAdmin
    {
        $admin = Admin::where('username', $username)->first();

        if (!$admin) {
            return null;
        }

        return new DomainAdmin(
            id: $admin->id,
            username: $admin->username,
            password: $admin->password,
            token: $admin->token
        );
    }
}
