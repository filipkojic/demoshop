<?php

namespace Application\Presentation\Controllers\Admin;

use Application\Business\Interfaces\ServiceInterfaces\CategoryServiceInterface;
use Infrastructure\HTTP\HttpRequest;
use Infrastructure\HTTP\Response\HtmlResponse;
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
     * @param HttpRequest $request The HTTP request object containing the category data in JSON format.
     *
     * @return JsonResponse
     */
    public function getAllCategories(HttpRequest $request): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();
        return new JsonResponse(200, [], array_map(fn($category) => $category->toArray(), $categories));
    }

    /**
     * Handles the request to add a new category.
     *
     * @param HttpRequest $request The HTTP request object containing the category data in JSON format.
     *
     * @return JsonResponse
     */
    public function addCategory(HttpRequest $request): JsonResponse
    {
        $success = $this->categoryService->createCategory($request->getJsonBody());

        if (!$success) {
            return new JsonResponse(400, [], ['success' => false, 'message' => $this->categoryService->getLastError()]);
        }

        return new JsonResponse(200, [], ['success' => true, 'message' => 'Category added successfully.']);
    }
}