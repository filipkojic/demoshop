<?php

namespace Application\Integration\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateStatisticsTable
 *
 * This migration creates the 'statistics' table with fields for ID, home view count,
 * and timestamps. This table is intended to store data related to site-wide statistics.
 * The `up` method is used to apply the migration, and the `down` method is used to
 * roll back the migration by dropping the table if it exists.
 */
class CreateStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Capsule::schema()->create('statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('home_view_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Capsule::schema()->dropIfExists('statistics');
    }

    /**
     * Get the table name associated with the migration.
     *
     * @return string
     */
    public function getTableName(): string
    {
        return 'statistics';
    }
}
