<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMembershipsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('memberships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('membership_details_id')->unsigned()->index();
            $table->foreign('membership_details_id')->references('id')->on('membership_details')->onDelete('cascade');
            $table->enum('fee_type', ['monthly', 'quaterly', 'half_yearly', 'yearly'])->nullable();
            $table->bigInteger('fee')->nullable();
            \App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('memberships');
    }

}
