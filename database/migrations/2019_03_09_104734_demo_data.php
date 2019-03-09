<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DemoData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection('mysql')->table('users')->insert([
            [
                'id'=>1,
                'name'=> Config::get('base.user_name'),
                'email'=> Config::get('base.user_email'),
                'phone'=> Config::get('base.user_phone'),
                'password'=> Config::get('base.user_password'),
                'confirmation_code'=>''
            ]
        ]);
        DB::connection('mysql')->table('role_user')->insert([
            [
                'user_id'=> Config::get('base.channel_user_id_master'),
                'role_id'=> 1
            ]
        ]);
        // MASTER
        DB::connection('mysql')->table('channel')->insert([
            [
                'id'=>Config::get('base.channel_id_master'),
                'channel_name'=> Config::get('base.channel_name_master'),
                'channel_description'=> Config::get('base.channel_description_master'),
                'user_id'=> Config::get('base.channel_user_id_master'),
                'channel_keywords'=>'',
                'channel_view'=>0,
                'channel_currency'=>0,
                'notify'=>0,
                'insert_by'=>'',
                'update_user'=>0,
                'channel_status'=> Config::get('base.channel_status_master'),
                'channel_parent_id'=> Config::get('base.channel_parent_master'),
                'channel_date_end'=>Config::get('base.channel_date_end_master')
            ]
        ]);
        DB::connection('mysql')->table('channel_role')->insert([
            [
                'parent_id'=> Config::get('base.channel_id_master'),
                'user_id'=> Config::get('base.channel_user_id_master'),
                'role_id'=> 1
            ]
        ]);
        DB::connection('mysql')->table('domains_z')->insert([
            [
                'id'=>Config::get('base.domain_id_master'),
                'domain'=> Config::get('base.domain_master'),
                'domain_title'=>'',
                'domain_description'=>'',
                'domain_keywords'=>'',
                'domain_image'=>'',
                'path_data'=>'',
                'content'=>'',
                'post_search_by'=>'',
                'video_search_by'=>'',
                'rank'=>'',
                'rank_country'=>'',
                'country_code'=>'',
                'view'=>0,
                'domain_location'=>'',
                'registrar'=>'',
                'domain_id_registrar'=>0,
                'domain_detail'=>'',
                'notify'=>0,
                'crawler'=>0,
                'craw_replay'=>0,
                'craw_by'=>'',
                'insert_by'=>'',
                'level'=>0,
                'domain_encode'=> Config::get('base.domain_encode_master'),
                'domain_primary'=> Config::get('base.domain_primary_master'),
                'user_id'=> Config::get('base.channel_user_id_master')
            ]
        ]);
        DB::connection('mysql')->table('domain_join_channel')->insert([
            [
                'domain_id'=> Config::get('base.domain_id_master'),
                'channel_id'=> Config::get('base.channel_id_master')
            ]
        ]);
        DB::connection('mysql')->table('channel_join_region')->insert([
            [
                'region_id'=> 704,
                'channel_id'=> Config::get('base.channel_id_master')
            ]
        ]);
        DB::connection('mysql')->table('channel_join_subregion')->insert([
            [
                'subregion_id'=> 2979,
                'channel_id'=> Config::get('base.channel_id_master')
            ]
        ]);
        DB::connection('mysql')->table('channel_join_district')->insert([
            [
                'district_id'=> 76,
                'channel_id'=> Config::get('base.channel_id_master')
            ]
        ]);
        DB::connection('mysql')->table('channel_join_ward')->insert([
            [
                'ward_id'=> 443,
                'channel_id'=> Config::get('base.channel_id_master')
            ]
        ]);
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
