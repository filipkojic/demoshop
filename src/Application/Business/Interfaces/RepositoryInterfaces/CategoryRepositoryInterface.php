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

    /**
     * Create new category
     *
     * @param array $data Data in JSON from HTTP request object.
     *
     * @return bool Indicator if creating category was successfull.
     */
    public function createCategory(array $data): bool;

    /**
     * Get total number of categories
     *
     * @param string $code Category code that needs to be unique.
     *
     * @return bool Indicator if the code is unique.
     */
    public function isUniqueCode(string $code): bool;
}