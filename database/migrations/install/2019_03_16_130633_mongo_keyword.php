<?php

use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MongoKeyword extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'mongodb';
    public function up()
    {
        Schema::connection($this->connection)
            ->table('mongo_keyword', function (Blueprint $collection)
            {
                $collection->index('base_64');
                $collection->index('craw_next');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($this->connection)
            ->table('mongo_keyword', function (Blueprint $collection)
            {
                $collection->drop();
            });
    }
}
