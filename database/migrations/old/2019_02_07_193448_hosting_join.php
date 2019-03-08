<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HostingJoin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosting_join', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table',255)->nullable();
            $table->integer('table_parent_id');
            $table->integer('hosting_parent_id');
            $table->enum('status', ['active','delete','pending','disabled']);
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
        Schema::dropIfExists('hosting_join');
    }
}
