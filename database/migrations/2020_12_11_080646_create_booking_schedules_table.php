<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('booking_id')->unsigned()->nullable();
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->integer('trainer_user_id')->unsigned()->nullable();
            $table->foreign('trainer_user_id')->references('id')->on('trainer_users')->onDelete('cascade');
            $table->date('schedule_date')->nullable();
            App\Helpers\DbExtender::defaultParams($table);  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_schedules');
    }
}
