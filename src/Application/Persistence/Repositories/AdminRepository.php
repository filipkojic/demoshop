<?php

namespace Application\Persistence\Repositories;

use Application\Persistence\Entities\Admin;
use Application\Business\Interfaces\RepositoryInterfaces\AdminRepositoryInterface;
use Application\Business\DomainModels\DomainAdmin;

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

        if ($admin) {
            return new DomainAdmin(
                id: $admin->id,
                username: $admin->username,
                password: $admin->password,
                token: $admin->token
            );
        }

        return null;
    }
}
