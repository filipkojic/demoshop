<?php

namespace Infrastructure\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticsTable extends Migration
{
    public function up(): void
    {
        Capsule::schema()->create('statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('home_view_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('statistics');
    }
}
