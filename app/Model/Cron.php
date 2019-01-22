<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cron extends Model
{
    protected $table = 'cron_job';
	public $timestamps = false;
}
