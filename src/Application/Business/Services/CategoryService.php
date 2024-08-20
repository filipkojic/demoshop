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
     * Get all categories as domain models
     *
     * @param array $data Data in JSON from HTTP request object.
     *
     * @return bool Indicator if creating category was successfull.
     */
    public function createCategory(array $data): bool
    {
        if (empty($data['title']) || empty($data['code'])) {
            $this->lastError = 'Title and code are required.';
            return false;
        }

        if (!$this->categoryRepository->isUniqueCode($data['code'])) {
            $this->lastError = 'Category code must be unique.';
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

        if (!$category) {
            return false;
        }

        if ($category->getProductCount() > 0) {
            return false;
        }

        $this->categoryRepository->reassignSubcategories($data['id'], $category->getParentId());

        return $this->categoryRepository->deleteCategory($data['id']);
    }
}
