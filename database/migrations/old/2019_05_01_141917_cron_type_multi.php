<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CronTypeMulti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_type_multi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type',100)->nullable();
            $table->string('from',100)->nullable();
            $table->mediumText('value')->nullable();
            $table->timestamp('craw_at')->nullable();
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
        Schema::dropIfExists('cron_type_multi');
    }
}
