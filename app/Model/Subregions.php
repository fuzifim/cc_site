<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subregions extends Model
{
    protected $table = 'subregions';

    protected $fillable =  ['id','region_id', 'subregions_name', 'SolrID','timezone','create_at','update_at','subregions_status'];
    public $timestamps = false;
}
