<?php

namespace Application\Presentation\Controllers\Admin;

use Infrastructure\HTTP\Response\HtmlResponse;
use Infrastructure\HTTP\Response\JsonResponse;

class AdminProductController extends AdminController
{
    public function index(): HtmlResponse
    {
        // Code for rendering the product list
    }

    public function editProduct(int $id): HtmlResponse
    {
        // Code for rendering the edit product form
    }

    public function newProduct(): HtmlResponse
    {
        // Code for rendering the new product form
    }

    public function enableSelectedProducts(): JsonResponse
    {
        // Code for enabling selected products
    }

    public function disableSelectedProducts(): JsonResponse
    {
        // Code for disabling selected products
    }

    public function deleteSelectedProducts(): JsonResponse
    {
        // Code for deleting selected products
    }

    public function deleteOneProduct(int $id): JsonResponse
    {
        // Code for deleting a single product
    }

    public function createProduct(): JsonResponse
    {
        // Code for handling the creation of a new product
    }
}