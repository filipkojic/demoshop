<?php

namespace Application\Business\Interfaces\RepositoryInterfaces;

/**
 * Interface CategoryRepositoryInterface
 *
 * This interface defines the contract for category repository.
 */
interface CategoryRepositoryInterface
{

    /**
     * Get total number of categories
     *
     * @return int The number of categories.
     */
    public function getCategoriesCount(): int;
}