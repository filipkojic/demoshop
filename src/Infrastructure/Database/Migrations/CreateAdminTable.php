<?php

namespace Infrastructure\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateAdminTable
 *
 * This migration creates the 'admins' table with fields for ID, username, password,
 * token, and timestamps. The username field is unique, and the token field is nullable.
 * The `up` method is used to apply the migration, and the `down` method is used to
 * roll back the migration by dropping the table if it exists.
 */
class CreateAdminTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Capsule::schema()->create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('token')->nullable();
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
        Capsule::schema()->dropIfExists('admins');
    }

    /**
     * Get the table name associated with the migration.
     *
     * @return string
     */
    public function getTableName(): string
    {
        return 'admins';
    }
}
