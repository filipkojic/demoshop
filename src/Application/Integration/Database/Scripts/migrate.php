<?php

/**
 * Migration Script
 *
 * This script initializes the database connection using Eloquent ORM,
 * creates the specified database if it doesn't exist, runs the defined
 * migrations, and inserts a new admin user into the 'admins' table if
 * the username is not already taken.
 */

require '../../../../../vendor/autoload.php';

use Application\Integration\Database\Migrations\CreateAdminTable;
use Application\Integration\Database\Migrations\CreateCategoriesTable;
use Application\Integration\Database\Migrations\CreateProductsTable;
use Application\Integration\Database\Migrations\CreateStatisticsTable;
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
            echo "Migration for " . get_class($migration) . " successful." . PHP_EOL;
        } else {
            echo "Table '$tableName' already exists, skipping migration." . PHP_EOL;
        }
    }

    // Validate username
    do {
        $username = readline("Enter username (default 'admin'): ");
        $username = $username ?: 'admin';

        // Check if username is taken
        $existingUser = Capsule::table('admins')->where('username', $username)->first();

        if ($existingUser) {
            echo "User with username '$username' already exists. Please choose a different username." . PHP_EOL;
        }
    } while ($existingUser);

    // Password validation instructions
    echo "Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character." . PHP_EOL;

    // Validate password
    do {
        $password = readline("Enter password (default 'Admin123#'): ");
        $password = $password ?: 'Admin123#';

        // Password validation
        if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[!@#$%^&*]/', $password)) {
            echo "Invalid password. Please ensure it meets the criteria." . PHP_EOL;
        }
    } while (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[!@#$%^&*]/', $password));

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert admin
    Capsule::table('admins')->insert([
        'username' => $username,
        'password' => $hashedPassword,
        'created_at' => Carbon::now('Europe/Belgrade'),
        'updated_at' => Carbon::now('Europe/Belgrade'),
    ]);

    echo "User '$username' successfully added in table 'admins'." . PHP_EOL;

} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
