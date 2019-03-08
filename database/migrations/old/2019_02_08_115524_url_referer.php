<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UrlReferer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_referer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url',500)->nullable();
            $table->string('url_encode',500)->nullable();
            $table->enum('status', ['pending','active','delete','faild']);
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
        Schema::dropIfExists('url_referer');
    }
}
