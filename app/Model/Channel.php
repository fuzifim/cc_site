<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;
class Channel extends Model
{
	//protected $connection = 'mysqlzcom';
    protected $table = 'channel';
    public $timestamps = false; 
	public function admin(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
	public function author(){
        return $this->hasMany('App\Model\Channel_attribute', 'channel_parent_id', 'id')->where('channel_attribute_type','=','author');
    }
	public function lang(){
        return $this->hasOne('App\Model\Channel_attribute', 'channel_parent_id', 'id')->where('channel_attribute_type','=','channel_lang');
    }
	public function role(){
        return $this->hasMany('App\Model\Channel_role', 'parent_id', 'id');
    }
	public function channelCurrency(){
        return $this->hasOne('App\Model\Regions', 'id', 'channel_currency');
    }
	public function channelParent(){
        return $this->hasOne('App\Model\Channel', 'id', 'channel_parent_id')->where('channel_status','=','active');
    }
	public function joinAddress(){
        return $this->hasMany('App\Model\Channel_join_address', 'channel_id', 'id');
    }
	public function joinEmail(){
        return $this->hasMany('App\Model\Channel_join_email', 'channel_id', 'id');
    }
	public function joinPhone(){
        return $this->hasMany('App\Model\Channel_join_phone', 'channel_id', 'id');
    }
	public function joinCategory(){
        return $this->hasMany('App\Model\Category_join_channel', 'channel_id', 'id')->orderBy('order_by', 'DESC');
    }
	public function joinPosts(){
        return $this->hasMany('App\Model\Posts_join_channel', 'channel_id', 'id');
    }
	public function joinMedia(){
        return $this->hasMany('App\Model\Media_join_channel', 'channel_id', 'id');
    }
	public function reseller(){
        return $this->hasMany('App\Model\Channel_attribute', 'channel_parent_id', 'id')->where('channel_attribute_type','=','reseller');
    }
	public function getSeo(){
        return $this->hasOne('App\Model\Channel_attribute', 'channel_parent_id', 'id')->where('channel_attribute_type','=','seo');
    }
	public function channelAttributeColor(){
        return $this->hasOne('App\Model\Channel_attribute', 'channel_parent_id', 'id')->where('channel_attribute_type','=','channel_color');
    }
	public function channelAttributeTheme(){
        return $this->hasOne('App\Model\Channel_attribute', 'channel_parent_id', 'id')->where('channel_attribute_type','=','theme');
    }
	public function channelAttributeBackground(){
        return $this->hasOne('App\Model\Channel_attribute', 'channel_parent_id', 'id')->where('channel_attribute_type','=','background');
    }
	public function channelAttributeLogo(){
        return $this->hasOne('App\Model\Channel_attribute', 'channel_parent_id', 'id')->where('channel_attribute_type','=','logo');
    }
	public function channelAttributeBanner(){
        return $this->hasMany('App\Model\Channel_attribute', 'channel_parent_id', 'id')->where('channel_attribute_type','=','banner');
    }
	public function companyJoin(){
        return $this->hasOne('App\Model\Company_join_channel', 'channel_id', 'id');
    }
	public function phoneJoin(){
        return $this->hasOne('App\Model\Phone_join', 'table_parent_id', 'id')->where('phone_join_table','=','channel');
    }
	public function emailJoin(){
        return $this->hasOne('App\Model\Email_join', 'table_parent_id', 'id')->where('email_join_table','=','channel');
    }
	public function userJoin(){
        return $this->hasOne('App\Model\Users_join', 'table_parent_id', 'id')->where('user_join_table','=','channel');
    }
	public function channelJoinRegion(){
        return $this->hasOne('App\Model\Channel_join_region', 'channel_id', 'id');
    }
	public function channelJoinSubRegion(){
        return $this->hasOne('App\Model\Channel_join_subregion', 'channel_id', 'id');
    }
	public function channelJoinDistrict(){
        return $this->hasOne('App\Model\Channel_join_district', 'channel_id', 'id');
    }
	public function channelJoinWard(){
        return $this->hasOne('App\Model\Channel_join_ward', 'channel_id', 'id');
    }
	public function domainAll(){
        return $this->hasMany('App\Model\Domain_join_channel', 'channel_id', 'id');
    }
	public function domainJoinPrimary(){
        return $this->hasOne('App\Model\Domain_join_channel', 'channel_id', 'id');
    }
	public function channelService(){
        return $this->hasOne('App\Model\Services_attribute', 'id', 'service_attribute_id');
    }
	
	public function fields(){
        return $this->hasMany('App\Model\Channel_join_field', 'channel_id', 'id');
    }
	public function getCategory(){
        return $this->hasMany('App\Model\Category_join', 'table_parent_id', 'id')->where('category_join_table','=','channel')->orderBy('order_by','desc');
    }
	public function getPosts(){
        return $this->hasMany('App\Model\Posts_join_channel', 'channel_id', 'id');
    }
	public function getCartOrderChannel(){
        return $this->hasOne('App\Model\Cart_order', 'table_parent_id', 'id')->where('status','!=','active');
    }
	
}
