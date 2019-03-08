<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServicesJoin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_join', function (Blueprint $table) {
            $table->increments('id');
            $table->string('services_by',255)->nullable();
            $table->string('join_table',255)->nullable();
            $table->integer('table_parent_id');
            $table->integer('services_parent_id');
            $table->enum('status', ['pending','active','delete']);
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
        Schema::dropIfExists('services_join');
    }
}
