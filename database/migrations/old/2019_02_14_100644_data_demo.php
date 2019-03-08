<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DataDemo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('users')->insert([
            [
                'name'=> Config::get('base.user_name'),
                'email'=> Config::get('base.user_email'),
                'phone'=> Config::get('base.user_phone'),
                'password'=> Config::get('base.user_password')
            ]
        ]);
        // MASTER
        DB::table('channel')->insert([
            [
                'channel_name'=> Config::get('base.channel_name_master'),
                'channel_description'=> Config::get('base.channel_description_master'),
                'user_id'=> Config::get('base.channel_user_id_master'),
                'channel_status'=> Config::get('base.channel_status_master'),
                'channel_parent_id'=> Config::get('base.channel_parent_master'),
                'channel_date_end'=>Config::get('base.channel_date_end_master')
            ]
        ]);

        DB::table('channel_attribute')->insert([
            [
                'channel_parent_id'=> Config::get('base.channel_id_master'),
                'channel_attribute_type'=> Config::get('base.channel_type_color_master'),
                'channel_attribute_value'=> Config::get('base.channel_value_color_master'),
                'channel_attribute_status'=> Config::get('base.channel_status_color_master'),
            ]
        ]);
        DB::table('channel_attribute')->insert([
            [
                'channel_parent_id'=> Config::get('base.channel_id_master'),
                'channel_attribute_type'=> Config::get('base.channel_type_logo_master'),
                'channel_attribute_value'=> Config::get('base.channel_value_logo_master'),
                'channel_attribute_status'=> Config::get('base.channel_status_logo_master'),
            ]
        ]);
        DB::table('domains_z')->insert([
            [
                'domain'=> Config::get('base.domain_master'),
                'domain_encode'=> Config::get('base.domain_encode_master'),
                'domain_primary'=> Config::get('base.domain_primary_master'),
                'user_id'=> Config::get('base.channel_user_id_master')
            ]
        ]);
        DB::table('domain_join_channel')->insert([
            [
                'domain_id'=> Config::get('base.domain_id_master'),
                'channel_id'=> Config::get('base.channel_id_master')
            ]
        ]);
        //USER
        DB::table('channel')->insert([
            [
                'channel_name'=> Config::get('base.channel_name_user'),
                'channel_description'=> Config::get('base.channel_description_user'),
                'user_id'=> Config::get('base.channel_user_id_user'),
                'channel_status'=> Config::get('base.channel_status_user'),
                'channel_parent_id'=> Config::get('base.channel_parent_user'),
                'channel_date_end'=>Config::get('base.channel_date_end_user')
            ]
        ]);
        DB::table('domains_z')->insert([
            [
                'domain'=> Config::get('base.domain_user'),
                'domain_encode'=> Config::get('base.domain_encode_user'),
                'domain_primary'=> Config::get('base.domain_primary_user'),
                'user_id'=> Config::get('base.channel_user_id_user')
            ]
        ]);
        DB::table('domain_join_channel')->insert([
            [
                'domain_id'=> Config::get('base.domain_id_user'),
                'channel_id'=> Config::get('base.channel_id_user')
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
