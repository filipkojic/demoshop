<?php

/**
 * Migration Script
 *
 * This script initializes the database connection using Eloquent ORM,
 * creates the specified database if it doesn't exist, runs the defined
 * migrations, and inserts a new admin user into the 'admins' table if
 * the username is not already taken.
 */

require '../../../vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Infrastructure\Database\Migrations\CreateAdminTable;
use Infrastructure\Database\Migrations\CreateCategoriesTable;
use Infrastructure\Database\Migrations\CreateProductsTable;
use Infrastructure\Database\Migrations\CreateStatisticsTable;

// Initialize .env configuration
$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../../');
$dotenv->load();

// Initialize Eloquent ORM
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => getenv('DB_CONNECTION'),
    'host' => getenv('DB_HOST'),
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
    $databaseExists = Capsule::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName]);

    if (empty($databaseExists)) {
        Capsule::statement("CREATE DATABASE IF NOT EXISTS $dbName");
        echo "Database '$dbName' created." . PHP_EOL;
    } else {
        echo "Database '$dbName' already exists." . PHP_EOL;
    }

    Capsule::statement("USE $dbName");

    // Migrations
    $migrations = [
        CreateAdminTable::class,
        CreateCategoriesTable::class,
        CreateProductsTable::class,
        CreateStatisticsTable::class,
    ];

    foreach ($migrations as $migrationClass) {
        $migration = new $migrationClass();

        // Check if table already exists
        $tableName = (new $migrationClass)->getTableName();
        $tableExists = Capsule::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?", [$dbName, $tableName]);

        if (empty($tableExists)) {
            $migration->up();
            echo "Migration for " . get_class($migration) . " successfull." . PHP_EOL;
        } else {
            echo "Table '$tableName' already exists, skipping migration." . PHP_EOL;
        }
    }

    // Print tables
    $tables = Capsule::select('SHOW TABLES');
    echo "Tables in database '" . getenv('DB_DATABASE') . "':" . PHP_EOL;
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo $tableName . PHP_EOL;
    }

    // Create admin
    $username = readline("Enter username (default 'admin'): ");
    $password = readline("Enter password (default 'admin'): ");

    $username = $username ?: 'admin';
    $password = $password ?: 'admin';

    // Check if username is taken
    $existingUser = Capsule::table('admins')->where('username', $username)->first();

    if ($existingUser) {
        echo "User with username '$username' already exists." . PHP_EOL;
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert admin
        Capsule::table('admins')->insert([
            'username' => $username,
            'password' => $hashedPassword,
        ]);

        echo "User '$username' successfully added in table 'admins'." . PHP_EOL;
    }

} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

