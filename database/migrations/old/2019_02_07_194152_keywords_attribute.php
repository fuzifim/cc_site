<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KeywordsAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keywords_attribute', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('keyword_id');
            $table->string('type',255)->nullable();
            $table->longText('content')->nullable();
            $table->enum('status', ['active','delete']);
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
        Schema::dropIfExists('keywords_attribute');
    }
}
