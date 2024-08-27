<?php

namespace Application\Presentation\Controllers\Admin;

use Application\Business\Interfaces\ServiceInterfaces\CategoryServiceInterface;
use Infrastructure\HTTP\HttpRequest;
use Infrastructure\HTTP\Response\JsonResponse;

/**
 * Class CategoryController
 *
 * Handles admin requests for manipulating categories.
 */
class CategoryController extends AdminController
{

    /**
     * CategoryController constructor.
     *
     * @param CategoryServiceInterface $categoryService The service responsible for handling categories logic.
     */
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
            return new JsonResponse(400, [],
                ['success' => false, 'message' => $this->categoryService->getLastError()]);
        }

        return new JsonResponse(200, [], ['success' => true, 'message' => 'Category added successfully.']);
    }

    /**
     * Handles the request to delete a category.
     *
     * @param HttpRequest $request The HTTP request object containing the category data in JSON format.
     *
     * @return JsonResponse
     */
    public function deleteCategory(HttpRequest $request): JsonResponse
    {
        $success = $this->categoryService->deleteCategory($request->getJsonBody());

        if (!$success) {
            return new JsonResponse(400, [],
                ['success' => false, 'message' => 'Category cannot be deleted because it has products.']);
        }

        return new JsonResponse(200, [],
            ['success' => true, 'message' => 'Category deleted successfully.']);
    }

    /**
     * Update a category by its ID.
     *
     * @param HttpRequest $request The HTTP request object.
     * @return JsonResponse The JSON response indicating success or failure.
     */
    public function updateCategory(HttpRequest $request): JsonResponse
    {
        $data = $request->getJsonBody();
        $id = $data['id'] ?? null;

        if (!$id) {
            return new JsonResponse(400, [],
                ['success' => false, 'message' => 'Category ID is required.']);
        }

        $success = $this->categoryService->updateCategory($id, $data);

        if (!$success) {
            return new JsonResponse(400, [],
                ['success' => false, 'message' => $this->categoryService->getLastError()]);
        }

        return new JsonResponse(200, [],
            ['success' => true, 'message' => 'Category updated successfully.']);
    }
}
