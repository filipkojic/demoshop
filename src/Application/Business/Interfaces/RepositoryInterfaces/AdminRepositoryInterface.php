<?php

namespace Application\Business\Interfaces\RepositoryInterfaces;

use Application\Business\DomainModels\DomainAdmin;

interface AdminRepositoryInterface
{
    /**
     * Find an admin by their username.
     *
     * @param string $username
     * @return DomainAdmin|null
     */
    public function findByUsername(string $username): ?DomainAdmin;
}
