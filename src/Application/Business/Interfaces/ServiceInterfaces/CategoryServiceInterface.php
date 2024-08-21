<?php

namespace Application\Business\Interfaces\ServiceInterfaces;

use Application\Business\DomainModels\DomainCategory;

/**
 * Interface CategoryServiceInterface
 *
 * This interface defines the contract for category service.
 */
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

    /**
     * Get all categories as domain models
     *
     * @param array $data Data in JSON from HTTP request object.
     *
     * @return bool Indicator if creating category was successfull.
     */
    public function createCategory(array $data): bool;

    /**
     * Delete a category by its ID.
     *
     * @param array $data Data in JSON from HTTP request object.
     * @return bool Returns true if the deletion was successful, false otherwise.
     */
    public function deleteCategory(array $data): bool;

    /**
     * Update an existing category.
     *
     * @param int $id The ID of the category to update.
     * @param array $data Data for updating the category.
     * @return bool Returns true if the update was successful, false otherwise.
     */
    public function updateCategory(int $id, array $data): bool;
}
