<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmailJoin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_join', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email_join_table',255)->nullable();
            $table->integer('table_parent_id');
            $table->integer('email_parent_id')->unsigned();
            $table->foreign('email_parent_id')->references('id')->on('email')->onDelete('cascade');
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
        Schema::dropIfExists('email_join');
    }
}
