<?php

namespace Application\Business\Services;

use Application\Business\DomainModels\DomainCategory;
use Application\Business\Interfaces\RepositoryInterfaces\CategoryRepositoryInterface;
use Application\Business\Interfaces\ServiceInterfaces\CategoryServiceInterface;

/**
 * Class CategoryService
 *
 * This class implements the CategoryServiceInterface interface.
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * @var string Error message.
     */
    protected string $lastError;

    /**
     * @param CategoryRepositoryInterface $categoryRepository Repository for category manipulation.
     */
    public function __construct(protected CategoryRepositoryInterface $categoryRepository)
    {
    }

    /**
     * Get total number of categories
     *
     * @return int The number of categories.
     */
    public function getCategoriesCount(): int
    {
        return $this->categoryRepository->getCategoriesCount();
    }

    /**
     * Get all categories as domain models
     *
     * @return DomainCategory[] An array of domain category models.
     */
    public function getAllCategories(): array
    {
        return $this->categoryRepository->getAllCategories();
    }

    /**
     * Validate the data for creating a category.
     *
     * @param array $data The data to validate.
     * @return bool True if the data is valid, false otherwise.
     */
    private function validateCategoryData(array $data): bool
    {
        if (empty($data['title']) || empty($data['code'])) {
            $this->lastError = 'Title and code are required.';
            return false;
        }

        if (!$this->categoryRepository->isUniqueCode($data['code'])) {
            $this->lastError = 'Category code must be unique.';
            return false;
        }

        return true;
    }

    /**
     * Get all categories as domain models
     *
     * @param array $data Data in JSON from HTTP request object.
     *
     * @return bool Indicator if creating category was successfull.
     */
    public function createCategory(array $data): bool
    {
        if (!$this->validateCategoryData($data)) {
            return false;
        }

        return $this->categoryRepository->createCategory($data);
    }

    /**
     * Get error message for JSON response
     *
     * @return string Indicator if creating category was successfull.
     */
    public function getLastError(): string
    {
        return $this->lastError ?? '';
    }

    /**
     * Delete a category by its ID.
     *
     * @param array $data Data in JSON from HTTP request object.
     * @return bool Returns true if the deletion was successful, false otherwise.
     */
    public function deleteCategory(array $data): bool
    {
        $category = $this->categoryRepository->findCategoryById($data['id']);

        if (!$category || $category->getProductCount() > 0) {
            return false;
        }

        $this->categoryRepository->reassignSubcategories($data['id'], $category->getParentId());

        return $this->categoryRepository->deleteCategory($data['id']);
    }

    /**
     * Update an existing category.
     *
     * @param int $id The ID of the category to update.
     * @param array $data Data for updating the category.
     * @return bool Returns true if the update was successful, false otherwise.
     */
    public function updateCategory(int $id, array $data): bool
    {
        $category = $this->categoryRepository->findCategoryById($id);

        if (!$category) {
            $this->lastError = 'Category not found.';
            return false;
        }

        if (empty($data['title']) || empty($data['code'])) {
            $this->lastError = 'Title and code are required.';
            return false;
        }

        if ($category->getCode() !== $data['code'] && !$this->categoryRepository->isUniqueCode($data['code'])) {
            $this->lastError = 'Category code must be unique.';
            return false;
        }

        $newParentId = $data['parent_id'] ?? null;

        if ($newParentId !== $category->getParentId()) {
            // check if new parent is descendent of current category
            if ($this->isDescendant($newParentId, $id)) {
                // two-way reassigning
                $this->categoryRepository->reassignParent($newParentId, $category->getParentId());
                $this->categoryRepository->reassignParent($id, $newParentId);
            } else {
                // one-way reassigning
                $this->categoryRepository->reassignParent($id, $newParentId);
            }
            // $category->setParentId($newParentId);
        }

        return $this->categoryRepository->updateCategory($id, $data);
    }

    /**
     * Checks if a category is a descendant of another category.
     *
     * @param int|null $parentId The ID of the potential parent category.
     * @param int $categoryId The ID of the category to check.
     * @return bool Returns true if the category is a descendant, false otherwise.
     */
    private function isDescendant(?int $parentId, int $categoryId): bool
    {
        if ($parentId === null) {
            return false;
        }

        $currentParentId = $parentId;

        while ($currentParentId !== null) {
            if ($currentParentId === $categoryId) {
                return true;
            }
            $parentCategory = $this->categoryRepository->findCategoryById($currentParentId);
            $currentParentId = $parentCategory?->getParentId();
        }

        return false;
    }
}
