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
use App\Model\History; 
use App\Model\Comments; 
use App\Model\Comments_join; 
use App\Model\Comments_attribute;
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
use Session; 
class CommentController extends ConstructController
{
	public function __construct(){
		parent::__construct(); 
	}
	public function commentDel(){
		$commentId=Input::get('commentId'); 
		$getComment=Comments::find($commentId); 
		$error=''; 
		if(!empty($getComment->id)){
			if($this->_security!=true){
				if(Auth::check()){
					if($getComment->attribute->attribute_value!=Auth::user()->id){
						$error='Không đúng chủ sở hữu bình luận'; 
					}
				}else{
					if($getComment->attribute->attribute_value!=Request::ip()){
						$error='Không đúng chủ sở hữu bình luận'; 
					}
				}
			}
		}else{
			$error='Không tìm thấy bình luận! '; 
		}
		if(empty($error)){
			$getComment->delete(); 
			return response()->json(['success'=>true,
				'message'=>'Xóa bình luận thành công! ',
			]); 
		}else{
			return response()->json(['success'=>false,
				'message'=>$error,
			]); 
		}
	}
	public function commentAdd(){
		$postId=Input::get('postId'); 
		$parentId=Input::get('parentId'); 
		$table=Input::get('table'); 
		$error=''; 
		$commentContent=Input::get('commentContent'); 
		$commentName=Input::get('commentName'); 
		$commentPhone=Input::get('commentPhone'); 
		$commentEmail=Input::get('commentEmail'); 
		if(!empty($commentContent)){
			if(Session::has('session_comment')){
				$sessionHistory=Session::get('session_comment'); 
				if(!empty($sessionHistory['created_at'])){
					if(Carbon::parse($sessionHistory['created_at'])->addMinutes(1) > Carbon::now()->format('Y-m-d H:i:s')){
						$error='Mỗi bình luận cách nhau 1 phút. Lần tạo gần đây nhất của bạn cách đây'.WebService::time_request($sessionHistory['created_at']); 
					}
				}
			}
			if(empty($error)){
				if(Session::has('session_comment')){
					Session::forget('session_comment');
				}
				Session::put('session_comment', [ 
					'ip' => Request::ip(), 
					'created_at' => Carbon::now()->format('Y-m-d H:i:s')
				]); 
				$idComment=Comments::insertGetId(array(
					'parent_id'=>$parentId, 
					'content'=>htmlspecialchars(strip_tags($commentContent, '')), 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'status'=>'pending'
				)); 
				if($idComment){
					$idCommentJoin=Comments_join::insertGetId(array(
						'table'=>'posts', 
						'table_parent_id'=>$postId, 
						'comment_parent_id'=>$idComment, 
						'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
					)); 
					if(Auth::check()){
						Comments_attribute::insertGetId(array(
							'parent_id'=>$idComment, 
							'from'=>'user', 
							'attribute_type'=>'author', 
							'attribute_value'=>Auth::user()->id, 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						)); 
						if(!empty($commentName)){
							Comments_attribute::insertGetId(array(
								'parent_id'=>$idComment, 
								'from'=>'user', 
								'attribute_type'=>'comment_name', 
								'attribute_value'=>$commentName, 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							)); 
						}
						if(!empty($commentPhone)){
							Comments_attribute::insertGetId(array(
								'parent_id'=>$idComment, 
								'from'=>'user', 
								'attribute_type'=>'comment_phone', 
								'attribute_value'=>$commentPhone, 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							)); 
						}
						if(!empty($commentEmail)){
							Comments_attribute::insertGetId(array(
								'parent_id'=>$idComment, 
								'from'=>'user', 
								'attribute_type'=>'comment_email', 
								'attribute_value'=>$commentEmail, 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							)); 
						}
					}else{
						Comments_attribute::insertGetId(array(
							'parent_id'=>$idComment, 
							'from'=>'ip', 
							'attribute_type'=>'author', 
							'attribute_value'=>Request::ip(), 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						)); 
						if(!empty($commentName)){
							Comments_attribute::insertGetId(array(
								'parent_id'=>$idComment, 
								'from'=>'ip', 
								'attribute_type'=>'comment_name', 
								'attribute_value'=>$commentName, 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							)); 
						}
						if(!empty($commentPhone)){
							Comments_attribute::insertGetId(array(
								'parent_id'=>$idComment, 
								'from'=>'ip', 
								'attribute_type'=>'comment_phone', 
								'attribute_value'=>$commentPhone, 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							)); 
						}
						if(!empty($commentEmail)){
							Comments_attribute::insertGetId(array(
								'parent_id'=>$idComment, 
								'from'=>'ip', 
								'attribute_type'=>'comment_email', 
								'attribute_value'=>$commentEmail, 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							)); 
						}
					}
				}
				if(Auth::check()){
					History::insertGetId(array(
						'history_type'=>'comment_add', 
						'from'=>'user', 
						'parent_id'=>$this->_channel->id, 
						'author'=>Auth::user()->id,
						'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					)); 
				}else{
					History::insertGetId(array(
						'history_type'=>'comment_add', 
						'from'=>'ip', 
						'parent_id'=>$this->_channel->id, 
						'author'=>Request::ip(),
						'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					)); 
				}
				return response()->json(['success'=>true,
					'message'=>'Đăng bình luận thành công!', 
					'commentId'=>$idComment, 
					'comment'=>$commentContent
				]); 
			}else{
				return response()->json(['success'=>false,
					'message'=>$error,
				]); 
			}
		}else{
			return response()->json(['success'=>false,
				'message'=>'Nhập nội dung bình luận! ',
			]); 
		}
	}
}