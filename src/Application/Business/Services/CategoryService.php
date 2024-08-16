<?php

namespace Application\Business\Services;

use Application\Business\Interfaces\RepositoryInterfaces\CategoryRepositoryInterface;
use Application\Business\Interfaces\ServiceInterfaces\CategoryServiceInterface;

/**
 * Class CategoryService
 *
 * This class implements the CategoryServiceInterface interface.
 */
class CategoryService implements CategoryServiceInterface
{
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
}
