<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServicesAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_attribute', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_by');
            $table->integer('parent_id');
            $table->string('name',255)->nullable();
            $table->string('attribute_type',255)->nullable();
            $table->mediumText('attribute_value')->nullable();
            $table->string('price_order',255)->nullable();
            $table->string('price_re_order',255)->nullable();
            $table->string('price_sale',45)->nullable();
            $table->integer('order_unit_month');
            $table->string('per',25)->nullable();
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
        Schema::dropIfExists('services_attribute');
    }
}
