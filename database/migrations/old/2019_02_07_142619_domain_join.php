<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DomainJoin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain_join', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table',255)->nullable();
            $table->integer('table_parent_id');
            $table->integer('domain_parent_id')->unsigned();
            $table->foreign('domain_parent_id')->references('id')->on('domain')->onDelete('cascade');
            $table->enum('status', ['pending', 'active','delete']);
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
        Schema::dropIfExists('domain_join');
    }
}
