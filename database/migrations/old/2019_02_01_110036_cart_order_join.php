<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CartOrderJoin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_order_join', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table',255)->nullable();
            $table->integer('table_parent_id');
            $table->integer('cart_order_parent_id')->unsigned();
            $table->foreign('cart_order_parent_id')->references('id')->on('cart_order')->onDelete('cascade');
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
        Schema::dropIfExists('cart_order_join');
    }
}
