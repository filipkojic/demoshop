<?php

namespace Application\Business\Interfaces\ServiceInterfaces;

use Application\Business\DomainModels\DomainCategory;

interface CategoryServiceInterface
{
    /**
     * Get total number of categories
     *
     * @return int The number of categories.
     */
    public function getCategoriesCount(): int;

    /**
     * Get all categories as domain models
     *
     * @return DomainCategory[] An array of domain category models.
     */
    public function getAllCategories(): array;

    public function createCategory(array $data): void;
}