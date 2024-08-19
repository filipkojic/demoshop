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
     * @return JsonResponse
     */
    public function getAllCategories(HttpRequest $request): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();
        return new JsonResponse(200, [], array_map(fn($category) => $category->toArray(), $categories));
    }

    public function addCategory(HttpRequest $request): JsonResponse
    {
        $requestData = $request->getJsonBody();
        $this->categoryService->createCategory([
            'parent_id' => $requestData['parentId'] ?? null,
            'code' => $requestData['code'],
            'title' => $requestData['title'],
            'description' => $requestData['description'],
        ]);

        return new JsonResponse(200, [], []);
    }
}