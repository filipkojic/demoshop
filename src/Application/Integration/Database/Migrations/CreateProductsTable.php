<?php

namespace Application\Integration\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateProductsTable
 *
 * This migration creates the 'products' table with fields for ID, category ID, SKU,
 * title, brand, price, descriptions, image, enabled/featured flags, view count, and
 * timestamps. The `category_id` field references the `id` field in the `categories` table
 * to establish a foreign key relationship. The `up` method is used to apply the migration,
 * and the `down` method is used to roll back the migration by dropping the table if it exists.
 */

class CreateProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Capsule::schema()->create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->string('sku')->unique();
            $table->string('title');
            $table->string('brand');
            $table->decimal('price', 8, 2);
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('enabled')->default(true);
            $table->boolean('featured')->default(false);
            $table->integer('view_count')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Capsule::schema()->dropIfExists('products');
    }

    /**
     * Get the table name associated with the migration.
     *
     * @return string
     */
    public function getTableName(): string
    {
        return 'products';
    }
}
