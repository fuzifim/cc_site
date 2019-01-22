<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cp_customers_template extends Model
{
    protected $table = 'cp_customers_template';
    protected $fillable =  ['customers_name','customers_company', 'customers_images', 'address', 'customers_address_ward', 'customers_address_district','customers_address_country','customers_address_city','tax_code','ngay_cap','admin_name','admin_phone','admin_email','members','website','about','SolrID','customers_create_at','customers_views','customers_op_at','customers_show','customers_status','template_setting_id'];
    public $timestamps = false;
}
