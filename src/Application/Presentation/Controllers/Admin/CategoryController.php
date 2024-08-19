<?php

namespace Application\Presentation\Controllers\Admin;

use Application\Business\Interfaces\ServiceInterfaces\CategoryServiceInterface;
use Infrastructure\HTTP\Response\JsonResponse;

class CategoryController extends AdminController
{

    public function __construct(
        protected CategoryServiceInterface $categoryService,
    )
    {
    }

    /**
     * Get all categories
     *
     * @return JsonResponse
     */
    public function getAllCategories(): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();
        return new JsonResponse(200, [], array_map(fn($category) => $category->toArray(), $categories));
    }
}