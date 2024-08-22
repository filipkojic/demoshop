<?php

namespace Application\Business\Services;

use Application\Business\DomainModels\DomainProduct;
use Application\Business\Interfaces\RepositoryInterfaces\ProductRepositoryInterface;
use Application\Business\Interfaces\ServiceInterfaces\ProductServiceInterface;

/**
 * Class ProductService
 *
 * This class implements the ProductServiceInterface interface.
 */
class ProductService implements ProductServiceInterface
{
    /**
     * @param ProductRepositoryInterface $productRepository Repository for product manipulation.
     */
    public function __construct(protected ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * Get total number of products
     *
     * @return int The number of products.
     */
    public function getProductsCount(): int
    {
        return $this->productRepository->getProductsCount();
    }

    /**
     * Get the most viewed product
     *
     * @return DomainProduct The most viewed product.
     */
    public function getMostViewedProduct(): DomainProduct
    {
        return $this->productRepository->getMostViewedProduct();
    }

    /**
     * Get all products as domain models.
     *
     * @return DomainProduct[] An array of domain product models.
     */
    public function getAllProducts(): array
    {
        return $this->productRepository->getAllProducts();
    }

    /**
     * Toggle the enabled state for multiple products.
     *
     * @param array $productIds Array of product IDs.
     * @param bool $isEnabled The new enabled state.
     * @return bool Returns true if the operation was successful, false otherwise.
     */
    public function toggleProductsEnabled(array $productIds, bool $isEnabled): bool
    {
        return $this->productRepository->updateProductsEnabledState($productIds, $isEnabled);
    }

}
