<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActivityPlansTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('activity_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('price')->nullable();
            $table->string('image')->nullable();
            $table->string('description')->nullable();
            \App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('activity_plans');
    }

}
