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
}
