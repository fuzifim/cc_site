<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cron_job extends Model
{
    protected $table = 'cron_job';
    protected $fillable =  ['content','type','date_add_update'];
    public $timestamps = false;
}
