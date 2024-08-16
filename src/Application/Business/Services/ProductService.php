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
}
