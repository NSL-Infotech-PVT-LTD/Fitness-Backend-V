<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMembershipDetailsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('membership_details', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('user_type', ['gym', 'pool'])->nullable();
            $table->enum('category', ['single', 'couple', 'family_with_1', 'family_with_2'])->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            \App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('memberships_details');
    }

}
