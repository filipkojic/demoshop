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
     * Update the enabled state for multiple products.
     *
     * @param array $productIds Array of product IDs.
     * @param bool $isEnabled The new enabled state.
     * @return bool Returns true if the operation was successful, false otherwise.
     */
    public function updateProductsEnabledState(array $productIds, bool $isEnabled): bool
    {
        $result = Capsule::table('products')
            ->whereIn('id', $productIds)
            ->update(['enabled' => $isEnabled]);

        return $result > 0;
    }

    /**
     * Find domain products by their IDs.
     *
     * @param array $productIds Array of product IDs.
     * @return DomainProduct[] Returns an array of domain product models.
     */
    public function findDomainProductsByIds(array $productIds): array
    {
        $products = Product::whereIn('id', $productIds)->get();

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
                $product->view_count
            );
        })->toArray();
    }

    /**
     * Checks if a given SKU is already taken by another product.
     *
     * @param string $sku The SKU to check.
     * @return bool True if the SKU is already taken, false otherwise.
     */
    public function isSkuTaken(string $sku): bool
    {
        return Capsule::table('products')->where('sku', $sku)->exists();
    }


    /**
     * Creates a new product in the database.
     *
     * @param array $data Product data.
     * @return bool Indicator of whether the creation was successful.
     */
    public function createProduct(array $data): bool
    {
        $product = new Product($data);
        return $product->save();
    }

    /**
     * Delete products by their IDs.
     *
     * @param array $productIds Array of product IDs.
     * @return bool Returns true if the operation was successful, false otherwise.
     */
    public function deleteProducts(array $productIds): bool
    {
        return Product::whereIn('id', $productIds)->delete() > 0;
    }

    /**
     * Get paginated products with filtering, sorting, and searching.
     *
     * @param int $page The current page number.
     * @param string $sort The sort direction ('asc' or 'desc').
     * @param int|null $filter The category ID to filter by.
     * @param string|null $search The search term to filter products by title.
     * @return DomainProduct[] The paginated, sorted, and filtered list of products.
     */
    public function getFilteredAndPaginatedProducts(int $page, string $sort = 'asc', ?int $filter = null, ?string $search = null): array
    {
        $query = Product::query();

        // Apply category filter if provided and valid
        if ($filter && is_int($filter)) {
            $query->where('category_id', $filter);
        }

        // Apply search filter if provided
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        // Apply sorting
        $query->orderBy('price', $sort);

        // Number of products per page
        $productsPerPage = 3;

        // Calculate the offset
        $offset = ($page - 1) * $productsPerPage;

        // Get total count before pagination
        $totalProducts = $query->count();

        // Get paginated products
        $products = $query->skip($offset)->take($productsPerPage)->get();

        $domainProducts = $products->map(function ($product) {
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
                $product->category->title ?? ''
            );
        })->toArray();

        return [
            'products' => $domainProducts,
            'total' => $totalProducts,
        ];
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
