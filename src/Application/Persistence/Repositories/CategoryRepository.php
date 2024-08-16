<?php

namespace Application\Persistence\Repositories;

use Application\Business\DomainModels\DomainCategory;
use Application\Business\DomainModels\DomainProduct;
use Application\Persistence\Entities\Category;
use Application\Business\Interfaces\RepositoryInterfaces\CategoryRepositoryInterface;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Class CategoryRepository
 *
 * This class implements the CategoryRepositoryInterface interface using SQL database storage.
 */
class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get total number of categories
     *
     * @return int The number of categories.
     */
    public function getCategoriesCount(): int
    {
        return Capsule::table('categories')->count();
    }

    /**
     * Get all categories as domain models.
     *
     * @return DomainCategory[] An array of domain category models.
     */
    public function getAllCategories(): array
    {
        $categories = Category::with('subcategories.products')->whereNull('parent_id')->get();

        return $categories->map(function ($category) {
            return $this->mapToDomainModel($category);
        })->toArray();
    }

    /**
     * Map the Eloquent model to a DomainCategory model, including subcategories and products.
     *
     * @param Category $category
     * @return DomainCategory
     */
    private function mapToDomainModel(Category $category): DomainCategory
    {
        $domainCategory = new DomainCategory(
            $category->id,
            $category->parent_id,
            $category->code,
            $category->title,
            $category->description
        );

        // Add subcategories recursively
        foreach ($category->subcategories as $subcategory) {
            $domainCategory->addSubcategory($this->mapToDomainModel($subcategory));
        }

        // Add products
//        foreach ($category->products as $product) {
//            $domainProduct = new DomainProduct(
//                $product->id,
//                $product->category_id,
//                $product->sku,
//                $product->title,
//                $product->brand,
//                $product->price,
//                $product->short_description,
//                $product->description,
//                $product->image,
//                $product->enabled,
//                $product->featured,
//                $product->view_count
//            );
//
//            $domainCategory->addProduct($domainProduct);
//        }

        return $domainCategory;
    }
}
