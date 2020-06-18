<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTrainerUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('trainer_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('emergency_contact_no')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('image')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('emirates_id')->nullable();
            $table->text('about')->nullable();
            $table->text('services')->nullable();
            $table->text('address_house')->nullable();
            $table->text('address_street')->nullable();
            $table->text('address_city')->nullable();
            $table->text('address_country')->nullable();
            $table->text('address_postcode')->nullable();
            \App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('trainer_users');
    }

}
