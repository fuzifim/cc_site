<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CategoryJoin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_join', function (Blueprint $table) {
            $table->increments('category_join_id');
            $table->string('category_join_table',255)->nullable();
            $table->integer('table_parent_id');
            $table->integer('category_parent_id');
            $table->enum('category_join_status', ['active', 'pending','delete']);
            $table->integer('order_by');
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
        Schema::dropIfExists('category_join');
    }
}
