<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClassSchedulesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('class_type', ['recurring', 'one-time'])->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('repeat_on')->nullable();
            $table->time('start_time')->nullable();
            $table->string('duration')->nullable();
            $table->integer('location_id')->unsigned()->index();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade')->nullable();
            $table->integer('class_id')->unsigned()->index();
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade')->nullable();
            $table->bigInteger('trainer_id')->unsigned()->index();
            $table->foreign('trainer_id')->references('id')->on('trainer_users')->onDelete('cascade')->nullable();
            $table->string('cp_spots')->nullable();
            $table->string('capacity')->nullable();
            \App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('class_schedules');
    }

}
