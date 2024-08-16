<?php

namespace Application\Persistence\Repositories;

use Application\Business\DomainModels\DomainProduct;
use Application\Business\Interfaces\RepositoryInterfaces\ProductRepositoryInterface;

/**
 * Class ProductRepository
 *
 * This class implements the ProductRepositoryInterface interface using SQL database storage.
 */
class ProductRepository implements ProductRepositoryInterface
{

    /**
     * Get total number of products
     *
     * @return int The number of products.
     */
    public function getProductsCount(): int
    {
        return Capsule::table('products')->count();
    }

    /**
     * Get the most viewed product.
     *
     * @return DomainProduct The most viewed product.
     */
    public function getMostViewedProduct(): DomainProduct
    {
        $productData = Capsule::table('products')
            ->orderBy('view_count', 'desc')
            ->first();

        return new DomainProduct(
            $productData->id,
            $productData->category_id,
            $productData->sku,
            $productData->title,
            $productData->brand,
            $productData->price,
            $productData->short_description,
            $productData->description,
            $productData->image,
            $productData->enabled,
            $productData->featured,
            $productData->view_count
        );
    }
}
