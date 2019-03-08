<?php
$attributeValueColorMaster=array(
    'channel_menu'=>'#3b5997',
    'channel_menu_text'=>'#ffffff',
    'channel_title'=>'#f8f8f8',
    'channel_title_text'=>'#333333',
    'channel_footer'=>'#333333',
    'channel_footer_text'=>'#ffffff'
);
$attributeValueLogoMaster=array(
    'media_id'=>0,
    'url'=>'//'.env('APP_URL').'/img/logo.svg',
    'url_thumb'=>'//'.env('APP_URL').'/img/logo.svg',
    'url_small'=>'//'.env('APP_URL').'/img/logo.svg',
    'url_xs'=>'//'.env('APP_URL').'/img/logo.svg',
    'url_icon'=>'//'.env('APP_URL').'/img/logo.svg'
);
return [
    'user_name'=>env('USER_NAME','Fuzifim'),
    'user_email'=>env('USER_EMAIL','fuzifim@gmail.com'),
    'user_phone'=>env('USER_PHONE','+84963913349'),
    'user_password'=>env('USER_PASSWORD','$2y$10$Z1pzREkS1TuRS2.SOFXQau7i6tyot2U9KHwxic99APmbDn75rLfau'),
    'channel_name_master'=> env('CHANNEL_NAME_MASTER', 'Cung Cấp'),
    'channel_description_master'=> env('CHANNEL_DESCRIPTION_MASTER', 'đến mọi người ⭐ ⭐ ⭐ ⭐ ⭐'),
    'channel_status_master'=> env('CHANNEL_STATUS_MASTER', 'active'),
    'channel_user_id_master'=>env('CHANNEL_USER_ID_MASTER', 1),
    'channel_parent_master'=>env('CHANNEL_PARENT_MASTER', 0),
    'channel_date_end_master'=> env('CHANNEL_DATE_END_MASTER', \Carbon\Carbon::now()->addDays(30)),
    'channel_type_color_master'=> env('CHANNEL_TYPE_COLOR_MASTER', \App\Model\Channel::ATTRIBUTE_COLOR),
    'channel_value_color_master'=> env('CHANNEL_VALUE_COLOR_MASTER', json_encode($attributeValueColorMaster)),
    'channel_status_color_master'=> env('CHANNEL_STATUS_COLOR_MASTER', 'active'),
    'channel_type_logo_master'=> env('CHANNEL_TYPE_LOGO_MASTER', \App\Model\Channel::ATTRIBUTE_LOGO),
    'channel_value_logo_master'=> env('CHANNEL_VALUE_LOGO_MASTER', json_encode($attributeValueLogoMaster)),
    'channel_status_logo_master'=> env('CHANNEL_STATUS_LOGO_MASTER', 'active'),
    'domain_master'=> env('CONFIG_DOMAIN','cungcap.local'),
    'domain_encode_master'=>env('DOMAIN_ENCODE_MASTER','Y3VuZ2NhcC5sb2NhbA=='),
    'domain_primary_master'=>env('DOMAIN_PRIMARY_MASTER','default'),
    'domain_id_master'=>env('DOMAIN_ID_MASTER',1),
    'channel_id_master'=>env('CHANNEL_ID_MASTER',1),
    //USER
    'channel_name_user'=> env('CHANNEL_NAME_MASTER', 'Channel Test'),
    'channel_description_user'=> env('CHANNEL_DESCRIPTION_MASTER', 'Channel Description Test'),
    'channel_status_user'=> env('CHANNEL_STATUS_MASTER', 'active'),
    'channel_user_id_user'=>env('CHANNEL_USER_ID_MASTER', 1),
    'channel_parent_user'=>env('CHANNEL_PARENT_MASTER', 1),
    'channel_date_end_user'=> env('CHANNEL_DATE_END_MASTER', \Carbon\Carbon::now()->addDays(30)),
    'domain_user'=> env('CONFIG_DOMAIN','test.cungcap.local'),
    'domain_encode_user'=>env('DOMAIN_ENCODE_MASTER','dGVzdC5jdW5nY2FwLmxvY2Fs'),
    'domain_primary_user'=>env('DOMAIN_PRIMARY_MASTER','default'),
    'domain_id_user'=>env('DOMAIN_ID_MASTER',2),
    'channel_id_user'=>env('CHANNEL_ID_MASTER',2),
];
