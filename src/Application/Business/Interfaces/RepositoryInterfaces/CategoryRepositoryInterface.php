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

    /**
     * Delete a category by its ID.
     *
     * @param int $categoryId The ID of the category to delete.
     * @return bool Returns true if the deletion was successful, false otherwise.
     */
    public function deleteCategory(int $categoryId): bool;

    /**
     * Reassign parent ID for subcategories of a deleted category.
     *
     * @param int $categoryId The ID of the category to delete.
     * @param int|null $newParentId The new parent ID for the subcategories.
     * @return void
     */
    public function reassignSubcategories(int $categoryId, ?int $newParentId): void;

    /**
     * Update a category in the database.
     *
     * @param int $id The ID of the category to update.
     * @param array $data The data to update the category with.
     * @return bool Returns true if the update was successful, false otherwise.
     */
    public function updateCategory(int $id, array $data): bool;
}
