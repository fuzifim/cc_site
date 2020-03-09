<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostToSocial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_to_social', function (Blueprint $table) {
            $table->increments('id');
            $table->string('post_type',20)->nullable();
            $table->string('post_id',100)->nullable();
            $table->string('social',30)->nullable();
            $table->timestamp('post_at')->nullable();
            $table->timestamps();
            $table->index(['post_type', 'post_id','social']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_to_social');
    }
}
