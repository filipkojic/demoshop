<?php

namespace Application\Business\Interfaces\RepositoryInterfaces;

use Application\Business\DomainModels\DomainAdmin;

/**
 * Interface AdminRepositoryInterface
 *
 * This interface defines the contract for admin repository.
 */
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
