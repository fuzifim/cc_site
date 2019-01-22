<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Request;
use Illuminate\Support\Str;
use Auth;
use App\Permission;
use App\Role; 
use App\Model\Channel_role;
use App\User;
use App\Model\Like; 
use App\Model\Posts;
use App\Model\Posts_join;
use App\Model\Posts_attribute; 
use Carbon\Carbon;
use Theme;
use WebService; 
use Route; 
use Redirect; 
use Response; 
use Input; 
use Validator; 
class LikeController extends ConstructController
{
	public function __construct(){
		parent::__construct(); 
	}
	public function likeAdd()
    {
		$postId=Input::get('postId'); 
		$likeType=Input::get('likeType'); 
		$likeTable=Input::get('likeTable'); 
		$post=Posts::where('id','=',$postId)->first(); 
		$error=''; 
		$action='';
		if(count($post->like)>0){
			foreach($post->like as $like){
				if(Auth::check()){
					if($like->user_id==Auth::user()->id){
						Like::where('table_parent_id','=',$postId)->where('from','=','user')->where('user_id','=',Auth::user()->id)->delete(); 
						$error='Đã hủy like'; 
						$action='delete';
					}
				}else{
					if($like->user_id==Request::ip()){
						Like::where('table_parent_id','=',$postId)->where('from','=','ip')->where('user_id','=',Request::ip())->delete(); 
						$error='Đã hủy like'; 
						$action='delete';
					}
				}
			}
		}
		if(count($post->unlike)>0){
			foreach($post->unlike as $like){
				if(Auth::check()){
					if($like->user_id==Auth::user()->id){
						Like::where('table_parent_id','=',$postId)->where('from','=','user')->where('user_id','=',Auth::user()->id)->delete(); 
						$error='Đã hủy like'; 
						$action='delete';
					}
				}else{
					if($like->user_id==Request::ip()){
						Like::where('table_parent_id','=',$postId)->where('from','=','ip')->where('user_id','=',Request::ip())->delete(); 
						$error='Đã hủy like'; 
						$action='delete';
					}
				}
			}
		}
		if(empty($error)){
			if(Auth::check()){
				Like::insertGetId(array(
					'like_type'=>$likeType, 
					'like_table'=>$likeTable, 
					'table_parent_id'=>$postId, 
					'from'=>'user', 
					'user_id'=>Auth::user()->id, 
					'like_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
				));
			}else{
				Like::insertGetId(array(
					'like_type'=>$likeType, 
					'like_table'=>$likeTable, 
					'table_parent_id'=>$postId, 
					'from'=>'ip', 
					'user_id'=>Request::ip(), 
					'like_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
				));
			} 
			$action='add';
		}
		return response()->json(['success'=>true,
			'message'=>'Đã like', 
			'action'=>$action
		]);
		
	}
}