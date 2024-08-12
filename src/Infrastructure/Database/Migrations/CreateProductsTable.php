<?php

namespace Infrastructure\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
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

    public function down(): void
    {
        Capsule::schema()->dropIfExists('products');
    }
}
