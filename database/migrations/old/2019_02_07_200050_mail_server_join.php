<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MailServerJoin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_server_join', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table',255)->nullable();
            $table->integer('table_parent_id');
            $table->integer('mail_server_parent_id');
            $table->enum('status', ['active','delete','pending','disabled']);
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
        Schema::dropIfExists('mail_server_join');
    }
}
