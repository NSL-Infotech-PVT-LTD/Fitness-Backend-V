<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBookingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('model_type', ['events', 'class_schedules','trainer_users'])->nullable();
            $table->string('model_id')->nullable();
            $table->float('rating')->nullable();
            $table->text('review')->nullable();
            $table->enum('payment_status', ['pending', 'completed', 'canceled'])->default('pending')->nullable();
            $table->text('payment_params')->nullable();
            $table->bigInteger('created_by')->nullable()->unsigned()->index();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('bookings');
    }

}
