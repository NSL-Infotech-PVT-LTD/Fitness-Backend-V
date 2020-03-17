<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTournamentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->text('image')->nullable();
            $table->string('price')->nullable();
            $table->text('description')->nullable();
            \App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('tournaments');
    }

}
