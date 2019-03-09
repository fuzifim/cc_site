<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DbPrimary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //$sql_dump = File::get('public/data/cungcap.sql');
        //DB::connection()->getPdo()->exec($sql_dump);
        DB::unprepared(file_get_contents('public/data/cungcap.sql'));
        DB::disconnect(); 

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
