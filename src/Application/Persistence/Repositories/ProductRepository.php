<?php

namespace Application\Persistence\Repositories;

use Application\Business\DomainModels\DomainProduct;
use Application\Business\Interfaces\RepositoryInterfaces\ProductRepositoryInterface;
use Application\Persistence\Entities\Product;
use Illuminate\Database\Capsule\Manager as Capsule;

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

    /**
     * Get all products as domain models.
     *
     * @return DomainProduct[] An array of domain product models.
     */
    public function getAllProducts(): array
    {
        $products = Capsule::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.title as category_name')
            ->get();

        return $products->map(function ($product) {
            return new DomainProduct(
                $product->id,
                $product->category_id,
                $product->sku,
                $product->title,
                $product->brand,
                $product->price,
                $product->short_description,
                $product->description,
                $product->image,
                $product->enabled,
                $product->featured,
                $product->view_count,
                $product->category_name
            );
        })->toArray();
    }

    /**
     * Map the Eloquent model to a DomainProduct model.
     *
     * @param Product $product
     * @return DomainProduct
     */
    private function mapToDomainModel(Product $product): DomainProduct
    {
        return new DomainProduct(
            $product->id,
            $product->category_id,
            $product->sku,
            $product->title,
            $product->brand,
            $product->price,
            $product->short_description,
            $product->description,
            $product->image,
            $product->enabled,
            $product->featured,
            $product->view_count
        );
    }
}
