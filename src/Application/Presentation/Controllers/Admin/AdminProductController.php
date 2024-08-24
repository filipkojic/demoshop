<?php

namespace Application\Presentation\Controllers\Admin;

use Application\Business\Interfaces\ServiceInterfaces\ProductServiceInterface;
use Infrastructure\HTTP\HttpRequest;
use Infrastructure\HTTP\Response\HtmlResponse;
use Infrastructure\HTTP\Response\JsonResponse;

/**
 * Class AdminProductController
 *
 * Handles admin requests for manipulating products.
 */
class AdminProductController extends AdminController
{
    /**
     * AdminProductController constructor.
     *
     * @param ProductServiceInterface $productService The service responsible for handling categories logic.
     */
    public function __construct(
        protected ProductServiceInterface $productService,
    )
    {
    }

    /**
     * Get all products.
     *
     * @param HttpRequest $request The HTTP request object.
     * @return JsonResponse The JSON response containing the list of products.
     */
    public function getAllProducts(HttpRequest $request): JsonResponse
    {
        $products = $this->productService->getAllProducts();

        return new JsonResponse(200, [], array_map(fn($product) => $product->toArray(), $products));
    }

    /**
     * Toggle the enabled state for multiple products.
     *
     * @param HttpRequest $request The HTTP request object.
     * @return JsonResponse The JSON response indicating success or failure.
     */
    public function toggleProductsEnabled(HttpRequest $request): JsonResponse
    {
        $data = $request->getJsonBody();
        $productIds = $data['productIds'] ?? [];
        $isEnabled = $data['isEnabled'] ?? false;

        $success = $this->productService->toggleProductsEnabled($productIds, $isEnabled);

        if (!$success) {
            return new JsonResponse(400, [], ['success' => false, 'message' => 'Failed to update products.']);
        }

        return new JsonResponse(200, [], ['success' => true, 'message' => 'Products updated successfully.']);
    }

    /**
     * Delete selected products.
     *
     * @param HttpRequest $request The HTTP request object.
     * @return JsonResponse The JSON response indicating success or failure.
     */
    public function deleteProducts(HttpRequest $request): JsonResponse
    {
        $data = $request->getJsonBody();
        $productIds = $data['productIds'] ?? [];

        $success = $this->productService->deleteProducts($productIds);

        if (!$success) {
            return new JsonResponse(400, [], ['success' => false, 'message' => 'Failed to delete products.']);
        }

        return new JsonResponse(200, [], ['success' => true, 'message' => 'Products deleted successfully.']);
    }

    /**
     * Handle the request to add a new product.
     *
     * @param HttpRequest $request The HTTP request object.
     * @return JsonResponse The JSON response indicating success or failure.
     */
    public function addProduct(HttpRequest $request): JsonResponse
    {
        $productData = $request->bodyParams;
        $imageFile = $request->getFile('image');

        $success = $this->productService->createProduct($productData, $imageFile);

        if (!$success) {
            return new JsonResponse(400, [], ['success' => false, 'message' => $this->productService->getLastError()]);
        }

        return new JsonResponse(200, [], ['success' => true, 'message' => 'Product added successfully.']);
    }

    /**
     * Get paginated, filtered, and sorted products.
     *
     * @param HttpRequest $request The HTTP request object.
     * @return JsonResponse The JSON response containing the paginated, sorted, and filtered list of products.
     */
    public function getFilteredAndPaginatedProducts(HttpRequest $request): JsonResponse
    {
        $page = (int)$request->getQueryParam('page', 1);
        $sort = $request->getQueryParam('sort', 'asc');
        $filter = (int)$request->getQueryParam('filter', null);
        $search = $request->getQueryParam('search', null);

        $result = $this->productService->getFilteredAndPaginatedProducts($page, $sort, $filter, $search);

        return new JsonResponse(200, [], [
            'products' => array_map(fn($product) => $product->toArray(), $result['products']),
            'total' => $result['total'],
            'per_page' => 3, // or whatever number of products per page you're using
        ]);
    }

}
