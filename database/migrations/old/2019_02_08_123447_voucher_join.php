<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VoucherJoin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_join', function (Blueprint $table) {
            $table->increments('id');
            $table->string('join_table',255)->nullable();
            $table->integer('table_parent_id');
            $table->integer('voucher_parent_id')->unsigned();
            $table->foreign('voucher_parent_id')->references('id')->on('voucher')->onDelete('cascade');
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
        Schema::dropIfExists('voucher_join');
    }
}
