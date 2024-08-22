<?php

namespace Application\Business\Interfaces\RepositoryInterfaces;

use Application\Business\DomainModels\DomainProduct;

/**
 * Interface ProductRepositoryInterface
 *
 * This interface defines the contract for product repository.
 */
interface ProductRepositoryInterface
{
    /**
     * Get total number of products
     *
     * @return int The number of products.
     */
    public function getProductsCount(): int;

    /**
     * Get the most viewed product.
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
     * Update the enabled state for multiple products.
     *
     * @param array $productIds Array of product IDs.
     * @param bool $isEnabled The new enabled state.
     * @return bool Returns true if the operation was successful, false otherwise.
     */
    public function updateProductsEnabledState(array $productIds, bool $isEnabled): bool;

    /**
     * Find domain products by their IDs.
     *
     * @param array $productIds Array of product IDs.
     * @return DomainProduct[] Returns an array of domain product models.
     */
    public function findDomainProductsByIds(array $productIds): array;

    /**
     * Delete products by their IDs.
     *
     * @param array $productIds Array of product IDs.
     * @return bool Returns true if the operation was successful, false otherwise.
     */
    public function deleteProducts(array $productIds): bool;
}
