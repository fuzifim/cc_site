<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PhoneJoin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_join', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone_join_table',255)->nullable();
            $table->integer('table_parent_id');
            $table->integer('phone_parent_id')->unsigned();
            $table->foreign('phone_parent_id')->references('id')->on('phone')->onDelete('cascade');
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
        Schema::dropIfExists('phone_join');
    }
}
