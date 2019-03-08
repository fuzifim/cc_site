<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VoucherAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_attribute', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id');
            $table->string('attribute_type',255)->nullable();
            $table->string('attribute_value',255)->nullable();
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
        Schema::dropIfExists('voucher_attribute');
    }
}
