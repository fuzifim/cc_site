<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model {

	//
		//
	protected $table = 'message_sms';
	protected $guarded = array(); 
	public static $rule = [
		'to_member' => 'required',
		'message_title' => 'required',
		'message_body' => 'required'
	];

}
