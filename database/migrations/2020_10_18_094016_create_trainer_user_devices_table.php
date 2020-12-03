<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainerUserDevicesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('trainer_user_devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('trainer_user_id')->unsigned()->nullable();
            $table->foreign('trainer_user_id')->references('id')->on('trainer_users')->onDelete('cascade');
            $table->enum('type', ['ios', 'android'])->nullable();
            $table->text('token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('user_devices');
    }

}
