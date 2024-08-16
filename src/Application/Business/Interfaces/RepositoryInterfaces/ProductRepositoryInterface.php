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
}