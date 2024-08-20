<?php

namespace Application\Persistence\Repositories;

use Application\Business\DomainModels\DomainCategory;
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
        $productCount = $category->products->count(); // Count the number of products

        $domainCategory = new DomainCategory(
            $category->id,
            $category->parent_id,
            $category->code,
            $category->title,
            $category->description,
            $productCount
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

    /**
     * Create new category
     *
     * @param array $data Data in JSON from HTTP request object.
     *
     * @return bool Indicator if creating category was successfull.
     */
    public function createCategory(array $data): bool
    {
        $category = new Category($data);
        return $category->save();
    }

    /**
     * Get total number of categories
     *
     * @param string $code Category code that needs to be unique.
     *
     * @return bool Indicator if the code is unique.
     */
    public function isUniqueCode(string $code): bool
    {
        return Category::where('code', $code)->doesntExist();
    }

    /**
     * Find category by id
     *
     * @param int $id Category id.
     *
     * @return DomainCategory|null Category with the given id.
     */
    public function findCategoryById(int $id): ?DomainCategory
    {
        $category = Category::withCount('products')->find($id);

        if (!$category) {
            return null;
        }

        return new DomainCategory(
            $category->id,
            $category->parent_id,
            $category->code,
            $category->title,
            $category->description,
            $category->products_count
        );
    }


    /**
     * Delete a category by its ID.
     *
     * @param int $categoryId The ID of the category to delete.
     * @return bool Returns true if the deletion was successful, false otherwise.
     */
    public function deleteCategory(int $categoryId): bool
    {
        return Category::destroy($categoryId) > 0;
    }

    /**
     * Reassign parent ID for subcategories of a deleted category.
     *
     * @param int $categoryId The ID of the category to delete.
     * @param int|null $newParentId The new parent ID for the subcategories.
     * @return void
     */
    public function reassignSubcategories(int $categoryId, ?int $newParentId): void
    {
        Category::where('parent_id', $categoryId)->update(['parent_id' => $newParentId]);
    }
}
