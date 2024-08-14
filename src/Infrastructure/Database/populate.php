<?php

/**
 * Data Population Script
 *
 * This script initializes the database connection using Eloquent ORM
 * and populates the 'categories' and 'products' tables with sample data.
 * It creates two main categories, each with two subcategories, and adds
 * 10 products distributed across these categories.
 */

require '../../../vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Carbon;

// Initialize .env configuration
$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../../');
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

    // Sample categories data
    $categories = [
        [
            'code' => 'ELEC',
            'title' => 'Electronics',
            'description' => 'Electronic devices and gadgets',
            'subcategories' => [
                ['code' => 'MOB', 'title' => 'Mobile Phones', 'description' => 'Smartphones and accessories'],
                ['code' => 'LAP', 'title' => 'Laptops', 'description' => 'Laptops and accessories'],
            ],
        ],
        [
            'code' => 'HOME',
            'title' => 'Home Appliances',
            'description' => 'Appliances for home use',
            'subcategories' => [
                ['code' => 'KIT', 'title' => 'Kitchen Appliances', 'description' => 'Appliances for kitchen use'],
                ['code' => 'LAUN', 'title' => 'Laundry Appliances', 'description' => 'Laundry-related appliances'],
            ],
        ],
    ];

    // Insert categories and subcategories
    foreach ($categories as $category) {
        $categoryId = Capsule::table('categories')->insertGetId([
            'code' => $category['code'],
            'title' => $category['title'],
            'description' => $category['description'],
            'parent_id' => null,
            'created_at' => Carbon::now('Europe/Belgrade'),
            'updated_at' => Carbon::now('Europe/Belgrade'),
        ]);

        foreach ($category['subcategories'] as $subcategory) {
            Capsule::table('categories')->insert([
                'code' => $subcategory['code'],
                'title' => $subcategory['title'],
                'description' => $subcategory['description'],
                'parent_id' => $categoryId,
                'created_at' => Carbon::now('Europe/Belgrade'),
                'updated_at' => Carbon::now('Europe/Belgrade'),
            ]);
        }
    }

    // Sample products data
    $products = [
        ['sku' => 'IPH13', 'title' => 'iPhone 13', 'brand' => 'Apple', 'price' => 999.99, 'category_code' => 'MOB'],
        ['sku' => 'GALAXY21', 'title' => 'Samsung Galaxy S21', 'brand' => 'Samsung', 'price' => 899.99, 'category_code' => 'MOB'],
        ['sku' => 'MBP13', 'title' => 'MacBook Pro', 'brand' => 'Apple', 'price' => 1999.99, 'category_code' => 'LAP'],
        ['sku' => 'DELLXPS13', 'title' => 'Dell XPS 13', 'brand' => 'Dell', 'price' => 1299.99, 'category_code' => 'LAP'],
        ['sku' => 'BLENDER100', 'title' => 'Blender', 'brand' => 'Blendtec', 'price' => 49.99, 'category_code' => 'KIT'],
        ['sku' => 'MICRO800', 'title' => 'Microwave', 'brand' => 'LG', 'price' => 89.99, 'category_code' => 'KIT'],
        ['sku' => 'WASH5000', 'title' => 'Washing Machine', 'brand' => 'Bosch', 'price' => 499.99, 'category_code' => 'LAUN'],
        ['sku' => 'DRY4000', 'title' => 'Dryer', 'brand' => 'Bosch', 'price' => 399.99, 'category_code' => 'LAUN'],
        ['sku' => 'FRIDGE200', 'title' => 'Refrigerator', 'brand' => 'Whirlpool', 'price' => 699.99, 'category_code' => 'KIT'],
        ['sku' => 'AC6000', 'title' => 'Air Conditioner', 'brand' => 'Samsung', 'price' => 599.99, 'category_code' => 'LAUN'],
    ];

    // Insert products
    foreach ($products as $product) {
        $categoryId = Capsule::table('categories')->where('code', $product['category_code'])->first()->id;

        Capsule::table('products')->insert([
            'sku' => $product['sku'],
            'title' => $product['title'],
            'brand' => $product['brand'],
            'price' => $product['price'],
            'category_id' => $categoryId,
            'enabled' => true,
            'featured' => false,
            'view_count' => 0,
            'created_at' => Carbon::now('Europe/Belgrade'),
            'updated_at' => Carbon::now('Europe/Belgrade'),
        ]);
    }

    echo "Database populated with categories and products." . PHP_EOL;

} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
