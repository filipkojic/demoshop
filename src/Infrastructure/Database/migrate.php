<?php

require '../../../vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Infrastructure\Database\Migrations\CreateAdminTable;
use Infrastructure\Database\Migrations\CreateCategoriesTable;
use Infrastructure\Database\Migrations\CreateProductsTable;
use Infrastructure\Database\Migrations\CreateStatisticsTable;

// Inicijalizacija .env konfiguracije
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

// Proveri da li baza postoji i kreiraj je ako ne postoji
try {
    $dbName = getenv('DB_DATABASE');
    $databaseExists = Capsule::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName]);

    if (empty($databaseExists)) {
        Capsule::statement("CREATE DATABASE IF NOT EXISTS $dbName");
        echo "Baza podataka '$dbName' je kreirana." . PHP_EOL;
    } else {
        echo "Baza podataka '$dbName' već postoji." . PHP_EOL;
    }

    Capsule::statement("USE $dbName");

    // Izvrši migracije
    $migrations = [
        CreateAdminTable::class,
        CreateCategoriesTable::class,
        CreateProductsTable::class,
        CreateStatisticsTable::class,
    ];

    foreach ($migrations as $migrationClass) {
        $migration = new $migrationClass();
        $migration->up();
        echo "Migracija za " . get_class($migration) . " je uspešno izvršena." . PHP_EOL;
    }

    // Proveri prisutne tabele
    $tables = Capsule::select('SHOW TABLES');
    echo "Prisutne tabele u bazi '" . getenv('DB_DATABASE') . "':" . PHP_EOL;
    foreach ($tables as $table) {
        $tableName = array_values((array) $table)[0];
        echo $tableName . PHP_EOL;
    }

} catch (\Exception $e) {
    echo 'Došlo je do greške: ' . $e->getMessage();
}

