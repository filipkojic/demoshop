<?php

namespace Application\Integration\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateCategoriesTable
 *
 * This migration creates the 'categories' table with fields for ID, parent category ID,
 * code, title, description, and timestamps. The `parent_id` field references the `id` field
 * in the same table to establish a self-referencing foreign key, representing category
 * hierarchies. The `up` method is used to apply the migration, and the `down` method is
 * used to roll back the migration by dropping the table if it exists.
 */
class CreateCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Capsule::schema()->create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Capsule::schema()->dropIfExists('categories');
    }

    /**
     * Get the table name associated with the migration.
     *
     * @return string
     */
    public function getTableName(): string
    {
        return 'categories';
    }
}
