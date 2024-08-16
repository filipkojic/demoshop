<?php

namespace Application\Persistence\Repositories;

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
}
