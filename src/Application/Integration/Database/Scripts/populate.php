<?php

/**
 * Data Population Script
 *
 * This script initializes the database connection using Eloquent ORM
 * and populates the 'categories' and 'products' tables with sample data.
 * Categories and subcategories are inserted recursively.
 */

require '../../../../../vendor/autoload.php';

use Application\Integration\Utility\PathHelper;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Carbon;

// Initialize .env configuration
$dotenv = Dotenv::createUnsafeImmutable(PathHelper::env());
$dotenv->load();

// Initialize Eloquent ORM
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => getenv('DB_CONNECTION'),
    'host' => getenv('DB_HOST'),
    'database' => getenv('DB_DATABASE'),
    'username' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Create database
try {
    $dbName = getenv('DB_DATABASE');
    Capsule::statement("USE $dbName");

    // Sample categories data with nested subcategories
    $categories = [
        [
            'code' => 'ELEC',
            'title' => 'Electronics',
            'description' => 'Electronic devices and gadgets',
            'subcategories' => [
                [
                    'code' => 'MOB',
                    'title' => 'Mobile Phones',
                    'description' => 'Smartphones and accessories',
                    'subcategories' => [
                        ['code' => 'IOS', 'title' => 'iOS Phones', 'description' => 'Apple iPhones', 'subcategories' => []],
                        ['code' => 'AND', 'title' => 'Android Phones', 'description' => 'Phones running Android OS', 'subcategories' => []],
                    ]
                ],
                [
                    'code' => 'LAP',
                    'title' => 'Laptops',
                    'description' => 'Laptops and accessories',
                    'subcategories' => [
                        ['code' => 'GAMING', 'title' => 'Gaming Laptops', 'description' => 'High-performance laptops for gaming', 'subcategories' => []],
                        ['code' => 'ULTRA', 'title' => 'Ultrabooks', 'description' => 'Lightweight and powerful laptops', 'subcategories' => []],
                    ]
                ],
            ],
        ],
        [
            'code' => 'HOME',
            'title' => 'Home Appliances',
            'description' => 'Appliances for home use',
            'subcategories' => [
                [
                    'code' => 'KIT',
                    'title' => 'Kitchen Appliances',
                    'description' => 'Appliances for kitchen use',
                    'subcategories' => []
                ],
                [
                    'code' => 'WASHERS',
                    'title' => 'Washing Machines',
                    'description' => 'Machines for washing clothes',
                    'subcategories' => []
                ],
                [
                    'code' => 'DRYERS',
                    'title' => 'Dryers',
                    'description' => 'Machines for drying clothes',
                    'subcategories' => []
                ],
            ],
        ],
        [
            'code' => 'FURN',
            'title' => 'Furniture',
            'description' => 'Furniture for home and office',
            'subcategories' => [
                ['code' => 'CHAIRS', 'title' => 'Chairs', 'description' => 'Various types of chairs', 'subcategories' => []],
                ['code' => 'DESKS', 'title' => 'Desks', 'description' => 'Various types of desks', 'subcategories' => []],
            ],
        ],
    ];



    /**
     * Recursive function to insert categories and subcategories
     *
     * @param array $categories Array of categories to insert
     * @param int|null $parentId The ID of the parent category (null for root categories)
     * @return void
     */
    function insertCategoryWithSubcategories(array $categories, int $parentId = null): void
    {
        foreach ($categories as $category) {
            $categoryId = Capsule::table('categories')->insertGetId([
                'code' => $category['code'],
                'title' => $category['title'],
                'description' => $category['description'],
                'parent_id' => $parentId,
                'created_at' => Carbon::now('Europe/Belgrade'),
                'updated_at' => Carbon::now('Europe/Belgrade'),
            ]);

            if (!empty($category['subcategories'])) {
                insertCategoryWithSubcategories($category['subcategories'], $categoryId);
            }
        }
    }

    insertCategoryWithSubcategories($categories);

    // Insert products
    $products = [
        ['sku' => 'IPH13', 'title' => 'iPhone 13', 'brand' => 'Apple', 'price' => 999.99, 'category_code' => 'IOS', 'view_count' => 120],
        ['sku' => 'GALAXY21', 'title' => 'Samsung Galaxy S21', 'brand' => 'Samsung', 'price' => 899.99, 'category_code' => 'AND', 'view_count' => 90],
        ['sku' => 'MBP13', 'title' => 'MacBook Pro', 'brand' => 'Apple', 'price' => 1999.99, 'category_code' => 'GAMING', 'view_count' => 75],
        ['sku' => 'DELLXPS13', 'title' => 'Dell XPS 13', 'brand' => 'Dell', 'price' => 1299.99, 'category_code' => 'ULTRA', 'view_count' => 60],
        ['sku' => 'BLENDER100', 'title' => 'Blender', 'brand' => 'Blendtec', 'price' => 49.99, 'category_code' => 'KIT', 'view_count' => 45],
        ['sku' => 'MICRO800', 'title' => 'Microwave', 'brand' => 'LG', 'price' => 89.99, 'category_code' => 'KIT', 'view_count' => 80],
        ['sku' => 'WASH5000', 'title' => 'Washing Machine', 'brand' => 'Bosch', 'price' => 499.99, 'category_code' => 'WASHERS', 'view_count' => 50],
        ['sku' => 'DRY4000', 'title' => 'Dryer', 'brand' => 'Bosch', 'price' => 399.99, 'category_code' => 'DRYERS', 'view_count' => 65],
        ['sku' => 'CHAIR300', 'title' => 'Ergonomic Chair', 'brand' => 'Herman Miller', 'price' => 899.99, 'category_code' => 'CHAIRS', 'view_count' => 110],
        ['sku' => 'DESK200', 'title' => 'Standing Desk', 'brand' => 'Ikea', 'price' => 299.99, 'category_code' => 'DESKS', 'view_count' => 95],
    ];

    foreach ($products as $product) {
        $category = Capsule::table('categories')->where('code', $product['category_code'])->first();

        if (!$category) {
            echo "Error: Category with code {$product['category_code']} not found for product {$product['sku']}" . PHP_EOL;
            continue;
        }

        Capsule::table('products')->insert([
            'sku' => $product['sku'],
            'title' => $product['title'],
            'brand' => $product['brand'],
            'price' => $product['price'],
            'category_id' => $category->id,
            'enabled' => true,
            'featured' => false,
            'view_count' => $product['view_count'],
            'created_at' => Carbon::now('Europe/Belgrade'),
            'updated_at' => Carbon::now('Europe/Belgrade'),
        ]);
    }


    // Insert home page view count into statistics table
    Capsule::table('statistics')->insert([
        'home_view_count' => 2500,
        'created_at' => Carbon::now('Europe/Belgrade'),
        'updated_at' => Carbon::now('Europe/Belgrade'),
    ]);

    echo "Database populated with categories and products." . PHP_EOL;

} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
