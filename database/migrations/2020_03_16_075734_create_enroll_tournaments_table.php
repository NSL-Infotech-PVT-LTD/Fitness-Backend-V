<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrollTournamentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('enroll_tournaments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('token');
            $table->string('size');
            $table->string('price');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on
                    ('tournaments')->onDelete('cascade');
            $table->bigInteger('customer_id')->unsigned()->index();
            $table->foreign('customer_id')->references('id')->on
                    ('users')->onDelete('cascade');
            $table->text('payment_details')->nullable();
            $table->string('payment_id')->nullable();
            $table->enum('status', ['0','1'])->default('0');
            \App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('enroll_tournaments');
    }

}
