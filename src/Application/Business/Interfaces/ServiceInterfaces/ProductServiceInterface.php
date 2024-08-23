<?php

namespace Application\Business\Interfaces\ServiceInterfaces;

use Application\Business\DomainModels\DomainProduct;

/**
 * Interface ProductServiceInterface
 *
 * This interface defines the contract for product service.
 */
interface ProductServiceInterface
{
    /**
     * Get total number of products
     *
     * @return int The number of products.
     */
    public function getProductsCount(): int;

    /**
     * Get the most viewed product
     *
     * @return DomainProduct The most viewed product.
     */
    public function getMostViewedProduct(): DomainProduct;

    /**
     * Get all products as domain models.
     *
     * @return DomainProduct[] An array of domain product models.
     */
    public function getAllProducts(): array;

    /**
     * Toggle the enabled state for multiple products.
     *
     * @param array $productIds Array of product IDs.
     * @param bool $isEnabled The new enabled state.
     * @return bool Returns true if the operation was successful, false otherwise.
     */
    public function toggleProductsEnabled(array $productIds, bool $isEnabled): bool;

    /**
     * Delete products by their IDs.
     *
     * @param array $productIds Array of product IDs.
     * @return bool Returns true if the operation was successful, false otherwise.
     */
    public function deleteProducts(array $productIds): bool;

    /**
     * Creates a new product after validating the provided data.
     *
     * @param array $data Product data.
     * @return bool Indicator of whether the creation was successful.
     */
    public function createProduct(array $data, ?array $imageFile): bool;

    /**
     * Get paginated products with filtering, sorting, and searching.
     *
     * @param int $page The current page number.
     * @param string $sort The sort direction ('asc' or 'desc').
     * @param int|null $filter The category ID to filter by.
     * @param string|null $search The search term to filter products by title.
     * @return DomainProduct[] The paginated, sorted, and filtered list of products as domain models.
     */
    public function getFilteredAndPaginatedProducts(int $page, string $sort = 'asc', ?int $filter = null, ?string $search = null): array;
}
