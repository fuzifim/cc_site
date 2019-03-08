<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostsAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts_attribute', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('posts_parent_id');
            $table->string('posts_attribute_type',300)->nullable();
            $table->mediumText('posts_attribute_value')->nullable();
            $table->enum('posts_attribute_status', ['pending','active','delete']);
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
        Schema::dropIfExists('posts_attribute');
    }
}
