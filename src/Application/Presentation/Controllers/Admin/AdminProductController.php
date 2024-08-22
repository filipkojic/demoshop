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

        if ($success) {
            return new JsonResponse(200, [], ['success' => true, 'message' => 'Products updated successfully.']);
        } else {
            return new JsonResponse(400, [], ['success' => false, 'message' => 'Failed to update products.']);
        }
    }
}
