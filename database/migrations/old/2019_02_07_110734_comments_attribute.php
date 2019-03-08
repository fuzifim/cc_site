<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommentsAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_attribute', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id');
            $table->enum('from', ['user', 'ip']);
            $table->string('attribute_type',255)->nullable();
            $table->string('attribute_value',500)->nullable();
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
        Schema::dropIfExists('comments_attribute');
    }
}
