<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Request;
use Illuminate\Support\Str;
use Auth;
use App\User;
use App\Model\Users_attribute;  
use App\Permission;
use App\Role;
use Carbon\Carbon;
use App\Model\History; 
use App\Model\Channel; 
use App\Model\Channel_join;
use App\Model\Channel_attribute; 
use App\Model\Channel_role;
use App\Model\Domain;
use App\Model\Domain_join;
use App\Model\Domain_attribute;
use App\Model\Category;
use App\Model\Category_join; 
use App\Model\Category_join_channel;
use App\Model\Category_attribute;
use App\Model\Slug; 
use Theme;
use WebService; 
use Route; 
use Redirect; 
use Response; 
use Input; 
use Validator;
class CategoryController extends ConstructController
{
	public $_idCategory; 
	public $_categoryName; 
	public $_categoryDescription; 
	public $_parentId; 
	public $_categoryOrderBy; 
	public $_channelId; 
	public function __construct(){
		parent::__construct(); 
	}
	/*-- new --*/
	public function categorySave(){
		if($this->_security==true){
			$this->_idCategory=Input::get('idCategory'); 
			$this->_categoryName=Input::get('categoryName'); 
			$this->_categoryDescription=Input::get('categoryDescription'); 
			$this->_parentId=Input::get('parentId'); 
			$this->_categoryOrderBy=Input::get('categoryOrderBy'); 
			$this->_channelId=$this->_channel->id; 
			$result=$this->addSuccess(); 
			if($result==false){
				return response()->json(['success'=>false,
					'message'=>'Không thể cập nhật! '
				]);
			}else{
				return response()->json(['success'=>true,
					'message'=>'Cập nhật thành công! '
				]);
			}
		}
	}
	public function addSuccess(){
		$getCategory=Category::find($this->_idCategory); 
		if(!empty($getCategory->id)){
			$getCategory->category_name=htmlspecialchars(strip_tags($this->_categoryName,""), ENT_COMPAT,'UTF-8', true); 
			$getCategory->category_description=htmlspecialchars(strip_tags($this->_categoryDescription,""), ENT_COMPAT,'UTF-8', true); 
			$getCategory->parent_id=$this->_parentId; 
			if(!empty($this->_categoryOrderBy)){
				$getCategory->category_order_by=$this->_categoryOrderBy; 
			}
			$getCategory->category_status='active'; 
			$getCategory->updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
			$getCategory->save(); 
			$getCategoryJoinChannel=Category_join_channel::where('category_id','=',$getCategory->id)->first(); 
			$getCategoryJoinChannel->order_by=$this->_categoryOrderBy; 
			$getCategoryJoinChannel->save(); 
		}else{
			if(empty($this->_categoryOrderBy)){
				$this->_categoryOrderBy=0; 
			}
			$this->_idCategory=Category::insertGetId(array(
				'category_name'=>htmlspecialchars(strip_tags($this->_categoryName,""), ENT_COMPAT,'UTF-8', true), 
				'category_description'=>htmlspecialchars(strip_tags($this->_categoryDescription,""), ENT_COMPAT,'UTF-8', true), 
				'parent_id'=>$this->_parentId, 
				'category_order_by'=>$this->_categoryOrderBy, 
				'category_status'=>'active', 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
			)); 
			if(!empty($this->_idCategory)){
				Category_join_channel::insertGetId(array(
					'channel_id'=>$this->_channelId, 
					'category_id'=>$this->_idCategory, 
					'order_by'=>$this->_categoryOrderBy
				)); 
				$slug=new Slug(); 
				$slug->slug_channel=$this->_channelId; 
				$slug->slug_table='category'; 
				$slug->slug_table_id=$this->_idCategory; 
				$slug->created_at=Carbon::now()->format('Y-m-d H:i:s'); 
				$slug->save(); 
				if(!empty($slug->id)){
					$checkSlug=Slug::where('slug_channel','=',$this->_channelId)
						->where('slug_value','=',Str::slug($this->_categoryName))->get(); 
					if(count($checkSlug)>0){
						$slug->slug_value=Str::slug($this->_categoryName.'-'.$slug->id); 
						$slug->save(); 
					}else{
						$slug->slug_value=Str::slug($this->_categoryName); 
						$slug->save(); 
					}
				}
			}
		}
		if(!empty($this->_idCategory)){
			return $this->_idCategory;
		}else{
			return false; 
		}
	}
	/*-- end new --*/
	public function getCategoryById($id)
    {
		$category=Category_join::where('category_join.category_parent_id','=',$id)
			->where('category_join.category_join_table','=','channel')
			->where('category_join.table_parent_id','=',$this->_channel->id)
			->join('category','category.id','category_join.category_parent_id')
			->where('category.category_status','=','active')
			->get(); 
		if(empty($category->category_name)){
			return response()->json(['success'=>false,
				'message'=>'Không tìm thấy danh mục',
			]);
		}else{
			return response()->json(['success'=>true,
				'category'=>$category,
				'message'=>'Thể loại ',
			]);
		}
	}
	public function categoryList()
    {
		$view = array();
		return $this->_theme->scope('admin.category.list', $view)->render(); 
	} 
	public function categorySelect()
    {
		$categoryList=array(); 
		foreach($this->_channel->joinCategory as $joinCategory){
			$categoryList[]=$joinCategory->category; 
		}
		$result='<option value="0">-- Danh mục chính --</option>';
		$result.=$this->tree_option($categoryList,$parent_id=0); 
		echo $result;
	}
	public function categoryAdd()
    {
		if($this->_security==true){
			$categoryId=Input::get('categoryId'); 
			$categoryName=Input::get('categoryName'); 
			$categoryDescription=Input::get('categoryDescription'); 
			$categoryParentId=Input::get('categoryParentId'); 
			$categoryOrderBy=Input::get('categoryOrderBy'); 
			$options=Input::get('options'); 
			$error=''; 
			$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
			$keyRandom=substr(str_shuffle(str_repeat($pool, 5)), 0, 5); 
			$messages = array(
				'required' => 'Vui lòng nhập thông tin tin (*).',
			);
			$rules = array(
				'categoryName' => 'required',
			);
			$validator = Validator::make(Input::all(), $rules, $messages);
			if ($validator->fails())
			{
				return response()->json(['success'=>false,
					'messageType'=>'validation',
					'message'=>$validator->getMessageBag()->toArray(),
					'input'=>Input::all()
				]);
			}else{
				$checkSlug=Slug::where('slug_channel','=',$this->_channel->id)
					->where('slug_value','=',Str::slug($categoryName))->get(); 
				if(count($checkSlug)>0){
					$slugValue=Str::slug($categoryName.'-'.count($checkSlug).'-'.$keyRandom); 
				}else{
					$slugValue=Str::slug($categoryName); 
				}
				if(empty($categoryId)){
					$result='insert';
				}else{
					$checkCategory=Category::where('category.id','=',$categoryId)
						->where('category.category_status','=','active')
						->get(); 
					if(count($checkCategory)>0){
						$result='update';
					}else{
						$error='Không tồn tại danh mục này! ';
					}
				}
				if($result=='insert'){
					$checkExit=Category::where('category.category_name','=',$categoryName)
						->where('category.category_status','=','active')
						->join('category_join','category_join.category_parent_id','=','category.id')
						->where('category_join.category_join_table','=','channel')
						->join('channel','channel.id','=','category_join.table_parent_id')
						->where('channel.id','=',$this->_channel->id)
						->get(); 
					if(count($checkExit)>0){
						$error='Danh mục này đã tồn tại! '; 
					}
					if(empty($error)){
						$categoryId=Category::insertGetId(array(
							'category_name'=>$categoryName, 
							'category_description'=>$categoryDescription, 
							'parent_id'=>$categoryParentId, 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
							'category_status'=>'active',
						)); 
						if($categoryId){
							Category_join::insertGetId(array(
								'category_join_table'=>'channel', 
								'table_parent_id'=>$this->_channel->id, 
								'category_parent_id'=>$categoryId, 
								'order_by'=>$categoryOrderBy, 
								'category_join_status'=>'active', 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
							)); 
							Category_join_channel::insertGetId(array(
								'channel_id'=>$this->_channel->id, 
								'category_id'=>$categoryId, 
								'order_by'=>$categoryOrderBy, 
							)); 
							Slug::insertGetId(array(
								'slug_channel'=>$this->_channel->id, 
								'slug_value'=>$slugValue, 
								'slug_table'=>'category', 
								'slug_table_id'=>$categoryId, 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
							));
							Category_attribute::where('parent_id','=',$categoryId)->where('attribute_type','=','options')->delete(); 
							Category_attribute::insertGetId(array(
								'parent_id'=>$categoryId, 
								'attribute_type'=>'options', 
								'attribute_value'=>$options
							)); 
						}
						$messageSuccess='Thêm mới danh mục thành công! '; 
					}
				}else if($result=='update'){
					$getCategory=Category::where('category.id','=',$categoryId)
						->where('category.category_status','=','active')
						->join('category_join','category_join.category_parent_id','=','category.id')
						->where('category_join.category_join_table','=','channel')
						->where('category_join.table_parent_id','=',$this->_channel->id)
						->first(); 
					if(!empty($getCategory->category_name)){
						if($getCategory->category_name!=$categoryName){
							$checkExit=Category::where('category.category_name','=',$categoryName)
								->where('category.category_status','=','active')
								->join('category_join','category_join.category_parent_id','=','category.id')
								->where('category_join.category_join_table','=','channel')
								->get(); 
							if(count($checkExit)>0){
								$error='Danh mục này đã tồn tại! '; 
							}
						}
					}
					if(empty($error)){
						Category::where('id','=',$getCategory->id)
							->update(array(
								'category_name'=>$categoryName, 
								'category_description'=>$categoryDescription, 
								'parent_id'=>$categoryParentId, 
								'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
							)); 
						Category_join::where('category_parent_id','=',$getCategory->id)
							->where('category_join_table','=','channel')
							->where('table_parent_id','=',$this->_channel->id)
							->update(array(
								'order_by'=>$categoryOrderBy, 
								'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
							)); 
						$getCategoryJoinChannel=Category_join_channel::where('category_id','=',$getCategory->id); 
						$getCategoryJoinChannel->order_by=$categoryOrderBy; 
						$getCategoryJoinChannel->save(); 
						Slug::where('slug_table_id','=',$categoryId)->where('slug_channel','=',$this->_channel->id)->delete(); 
						Slug::insertGetId(array(
							'slug_channel'=>$this->_channel->id, 
							'slug_value'=>$slugValue, 
							'slug_table'=>'category', 
							'slug_table_id'=>$categoryId, 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
						));
						Category_attribute::where('parent_id','=',$categoryId)->where('attribute_type','=','options')->delete(); 
						Category_attribute::insertGetId(array(
							'parent_id'=>$categoryId, 
							'attribute_type'=>'options', 
							'attribute_value'=>$options
						)); 
						$messageSuccess='Cập nhật danh mục thành công! '; 
					}
				}
				if(empty($error)){
					return response()->json(['success'=>true,
						'messageType'=>'success',
						'message'=>$messageSuccess,
					]);
				}else{
					return response()->json(['success'=>false,
						'messageType'=>'false',
						'message'=>$error,
					]);
				}
			}
		}else{
			$view = array(); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function categoryDelete()
    {
		if($this->_security==true){
			$categoryId=Input::get('categoryId'); 
			$getCategoryJoin=Category_join_channel::where('category_id','=',$categoryId)->where('channel_id','=',$this->_channel->id)->first(); 
			if(!empty($getCategoryJoin->category->id)){
				if(count($getCategoryJoin->category->postsJoinParent)>0){
					return response()->json(['success'=>false,
						'message'=>'Danh mục đang có bài viết không thể xóa! ',
					]);
				}else{
					Category_join::where('category_parent_id','=',$categoryId)
					->where('table_parent_id','=',$this->_channel->id)
					->where('category_join_table','=','channel')->delete(); 
					Category::where('id','=',$categoryId)->delete(); 
					Slug::where('slug_table_id','=',$categoryId)
						->where('slug_table','=','category')->delete();  
					$categoryList=array(); 
					foreach($this->_channel->getCategory as $category){
						$categoryList[]=$category->category; 
					}
					return response()->json(['success'=>true,
						'message'=>'Xóa thành công! ', 
						'category'=>$categoryList
					]);
				}
			}else{
				return response()->json(['success'=>false,
					'message'=>'Không thể xóa! ',
				]);
			}
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	function has_children($rows, $id) {
        foreach ($rows as $row) {
            if ($row['parent_id'] == $id)
                return true;
        }
        return false;
    }
    public function tree_option($rows, $parent = 0,$spe = "--",$selected = -1){
        $result = "";
        if(count($rows) > 0) {
            foreach ($rows as $key => $val){
                if($val['parent_id'] == $parent){
                    $att_selected = ($val['id'] == $selected) ? "selected" : "";

                    if($parent == 0)
                        $result .= "<option $att_selected value='$val->id'>$val->category_name</option>";
                    else
                        $result .= "<option $att_selected value='$val->id'>$spe$val->category_name</option>";
                    unset($rows[$key]);
                    if ($this->has_children($rows, $val['id'])){
                        $spe .= "--" ;
                        $result .= $this->tree_option($rows,$val['id'],$spe,$selected);
                    }
                }//end if
            }//end for
        }
        return $result;

    }
}