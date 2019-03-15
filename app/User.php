<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
class User extends Authenticatable implements MustVerifyEmail
	{
		use EntrustUserTrait;
        use Notifiable;
		protected $table = 'users';
		protected $fillable = ['name', 'email', 'password'];
		protected $hidden = ['password', 'remember_token']; 
		public function joinOauthFacebook(){
			return $this->hasOne('App\Model\User_oauth_identities', 'user_id', 'id')->where('provider','=','facebook');
		}
		public function joinEmail(){
			return $this->hasMany('App\Model\Users_join_email', 'user_id', 'id');
		}
		public function joinPhone(){
			return $this->hasMany('App\Model\Users_join_phone', 'user_id', 'id');
		}
		public function joinAddress(){
			return $this->hasOne('App\Model\Users_join_address', 'user_id', 'id');
		}
		public function joinHistory(){
			return $this->hasMany('App\Model\Pay_history_join_user', 'user_id', 'id')->orderBy('id','desc');
		}
		public function joinOrder(){
			return $this->hasMany('App\Model\Order_join_user', 'user_id', 'id')->orderBy('id','desc');
		}
		public function emailJoin(){
			return $this->hasOne('App\Model\Email_join', 'table_parent_id', 'id')->where('email_join_table','=','users');
		}
		public function phoneJoin(){
			return $this->hasOne('App\Model\Phone_join', 'table_parent_id', 'id')->where('phone_join_table','=','users');
		}
		public function getAvata(){
			return $this->hasOne('App\Model\Users_attribute', 'parent_id', 'id')->where('attribute_type','=','avata');
		}
		public function getRegion(){
			return $this->hasOne('App\Model\Users_attribute', 'parent_id', 'id')->where('attribute_type','=','region');
		}
		public function getSubRegion(){
			return $this->hasOne('App\Model\Users_attribute', 'parent_id', 'id')->where('attribute_type','=','subRegion');
		}
		public function getBank(){
			return $this->hasOne('App\Model\Users_attribute', 'parent_id', 'id')->where('attribute_type','=','bank');
		}
		public function reseller(){
			return $this->hasMany('App\Model\Channel_attribute', 'channel_attribute_value', 'id')->where('channel_attribute_type','=','reseller');
		}
		public function note(){
			return $this->hasMany('App\Model\Users_attribute', 'parent_id', 'id')->where('attribute_type','=','note');
		}
		public function userVoucher(){
			return $this->hasOne('App\Model\Voucher_attribute', 'attribute_value', 'id')->where('attribute_type','=','author');
		}
		public function channelRole(){
			return $this->hasMany('App\Model\Channel_role', 'user_id', 'id')->orderBy('updated_at','desc')->take(5);
		}
	}
