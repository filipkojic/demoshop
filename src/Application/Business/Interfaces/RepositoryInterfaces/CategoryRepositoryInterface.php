<?php

namespace Application\Business\Interfaces\RepositoryInterfaces;

use Application\Business\DomainModels\DomainCategory;

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

    /**
     * Get all categories as domain models.
     *
     * @return DomainCategory[] An array of domain category models.
     */
    public function getAllCategories(): array;
}