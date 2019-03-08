<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersJoin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_join', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type',25)->nullable();
            $table->text('value')->nullable();
            $table->string('user_join_table',255)->nullable();
            $table->integer('table_parent_id');
            $table->integer('user_parent_id')->unsigned();
            $table->foreign('user_parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_join');
    }
}
