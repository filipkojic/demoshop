<?php

namespace Application\Presentation\Controllers\Admin;

use Infrastructure\HTTP\Response\HtmlResponse;
use Infrastructure\HTTP\Response\JsonResponse;

class CategoryController extends AdminController
{
    public function index(): HtmlResponse
    {
        // Code for managing product categories
    }

    public function newCategory(): JsonResponse
    {
        // Code for handling AJAX request to create a new category
    }

    public function updateCategory(int $id): JsonResponse
    {
        // Code for handling AJAX request to update a category
    }

    public function deleteCategory(int $id): JsonResponse
    {
        // Code for handling AJAX request to delete a category
    }
}