<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;
use Auth;
use App\User; 
use App\Model\Users_join; 
use App\Model\Users_join_email; 
use App\Model\Users_join_phone; 
use App\Model\Users_attribute;  
use App\Permission;
use App\Role;
use App\Model\Channel_role; 
use Carbon\Carbon;
use App\Model\History; 
use App\Model\Fields; 
use App\Model\Category; 
use App\Model\Category_join;
use App\Model\Category_join_channel;
use App\Model\Keywords;
use App\Model\Posts;
use App\Model\Posts_join; 
use App\Model\Posts_join_channel;
use App\Model\Posts_join_category;
use App\Model\Posts_join_field;
use App\Model\Posts_attribute; 
use App\Model\Posts_join_keywords;
use App\Model\Post_join_user;
use App\Model\Post_join_region;
use App\Model\Post_join_subregion;
use App\Model\Post_join_district;
use App\Model\Post_join_ward; 
use App\Model\Posts_share;
use App\Model\Slug; 
use App\Model\Media; 
use App\Model\Media_join;
use App\Model\Regions;
use App\Model\Subregions;
use App\Model\Region_district;
use App\Model\Region_ward;
use App\Model\Domain; 
use App\Model\Domain_join; 
use App\Model\Company; 
use App\Model\Company_join; 
use App\Model\Company_join_channel; 
use App\Model\Company_join_field; 
use App\Model\Company_join_address; 
use App\Model\Channel; 
use App\Model\Channel_join; 
use App\Model\Channel_join_region;
use App\Model\Channel_join_subregion;
use App\Model\Channel_join_district;
use App\Model\Channel_join_ward;
use App\Model\Channel_join_field; 
use App\Model\Channel_join_address; 
use App\Model\Channel_join_email; 
use App\Model\Channel_join_phone; 
use App\Model\Channel_attribute; 
use App\Model\Media_join_post; 
use App\Model\Media_join_channel; 
use App\Model\Address;
use App\Model\Address_join_region;
use App\Model\Address_join_subregion;
use App\Model\Address_join_district;
use App\Model\Address_join_ward;
use App\Model\Email;
use App\Model\Email_join;
use App\Model\Phone;
use App\Model\Phone_join;
use File;
use Youtube; 
use Theme;
use WebService; 
use Route; 
use Redirect; 
use Response; 
use Input; 
use Validator;
use Site; 
use DB; 
use Storage;
use Pdp\PublicSuffixListManager; 
use Pdp\Parser; 
use App\Http\Controllers\MediaController; 
use App\Http\Controllers\CategoryController;
use Cache;
class PostsController extends ConstructController
{
	public function __construct(){
		parent::__construct(); 
	}
	/*-- new --*/
	public function postList(Request $request){
        $page = $request->has('page') ? $request->query('page') : 1;
        $posts=Cache::store('memcached')->remember('post_list_'.$page, 1, function()
        {
            $posts=Posts::where('posts.posts_status','=','active')
                //->where('posts.posts_type','=',0)
                ->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
                //->where('posts_join_channel.channel_id','=',$this->_channel->id)
                ->join('channel','channel.id','=','posts_join_channel.channel_id')
                ->where('channel.channel_status','!=','delete')
                //->where('channel.channel_date_end','>=',Carbon::now()->format('Y-m-d H:i:s'))
                //->where('channel.service_attribute_id','!=',1)
                //->groupBy('posts.id')
                ->orderBy('posts.posts_updated_at','desc')
                ->select('posts.*')
                ->simplepaginate(18);
            $posts->setPath(route('post.list',$this->_domainPrimary));
            return $posts;
        });

		$return=array(
			'posts'=>$posts
		); 
		return $this->_theme->scope('posts.list', $return)->render();
	}
	public function postMe(){
		if(Auth::check()){
			$posts=Posts::where('user_id',Auth::user()->id)->where('posts.posts_status','=','active')->orderBy('posts.posts_updated_at','desc')->paginate(18); 
			$return=array(
				'posts'=>$posts
			); 
			return $this->_theme->scope('posts.me', $return)->render();
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.login', $view)->render(); 
		}
	}
	public function postBilling(){
		if(Auth::check()){
			$posts=Posts::where('user_id',Auth::user()->id)->where('posts.posts_status','=','billing')->orderBy('posts.posts_updated_at','desc')->paginate(18); 
			$return=array(
				'posts'=>$posts
			); 
			return $this->_theme->scope('posts.billing', $return)->render();
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.login', $view)->render(); 
		}
	}
	public function postAdd(){
		if(Auth::check()){
			if($this->_channel->channel_parent_id==0){
				$return=array(); 
				return $this->_theme->scope('posts.add', $return)->render();
			}else if($this->_security==true){
				$return=array(); 
				return $this->_theme->scope('posts.add', $return)->render();
			}
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.login', $view)->render(); 
		}
	}
	public function postEdit(){
		if(Auth::check()){
			$post=Posts::find($this->_parame['id']); 
			if($this->_security==true || (Auth::check() && Auth::user()->id==$post->author->user->id)){
				if(!empty($post->id)){
					$return=array(
						'post'=>$post
					); 
					return $this->_theme->scope('posts.add', $return)->render();
				}
			}
		}else{
			$view = array();
			return $this->_theme->scope('admin.user.login', $view)->render(); 
		}
	}
	public function postAddRequest(){
//		return response()->json(['success'=>false,
//			'message'=>'Tính năng này đang bảo trì, vui lòng quay lại sau! ',
//		]);
			if(Auth::check()){
				/*$posts=Posts::where('user_id',Auth::user()->id)
					->where('posts.posts_status','=','billing')
					->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
					->where('posts_join_channel.channel_id','=',2)
					->groupBy('posts.id')
					//->orderBy('posts.posts_updated_at','desc')
					->select('posts.*')
					->get(); 
				if(count($posts)>=3){
					return response()->json(['success'=>false,
						'message'=>'Bạn đang có các tin đăng chưa thanh toán. Vui lòng thanh toán hoặc xóa để có thể đăng tin mới!', 
					]);
				}*/
				$postId=Input::get('postId'); 
				$postTitle=Input::get('postTitle'); 
				$postContent=Input::get('postContent'); 
				$categoryId=Input::get('categoryId'); 
				$medias=Input::get('medias'); 	
				$keywords=Input::get('keywords'); 	
				(int)$idRegion=Input::get('idRegion');
				(int)$idSubRegion=Input::get('idSubRegion');
				(int)$idDistrict=Input::get('idDistrict');
				(int)$idWard=Input::get('idWard'); 
				$dataContact=Input::get('dataContact'); 
				(int)$price=Input::get('price'); 
				(int)$priceSale=Input::get('priceSale'); 
				(int)$quanlityProduct=Input::get('quanlityProduct'); 
				$idPostPageFacebook=Input::get('selectPostToFacebook'); 
				$mediaJsonDecode=json_decode($medias); 
				$categoryArray=explode(',',$categoryId); 
				$keywordsArray=explode(',',$keywords); 
				$post=Posts::find($postId); 
				if(empty($post->id)){
					if($this->_channel->channel_parent_id!=0){
						if(Webservice::formatBytesToMb($this->_totalSize)>=$this->_limitSize || $this->_totalPosts>=$this->_limitPosts){
							return response()->json(['success'=>false,
								'message'=>'Tài khoản của bạn đã hết dung lượng sử dụng! ', 
							]);
						}
					}
					$post= new Posts(); 
					$post->user_id=Auth::user()->id; 
					$post->posts_created_at=Carbon::now()->format('Y-m-d H:i:s'); 
					$post->posts_updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
					$post->save(); 
				}
				if(empty($post->getSlug->slug_value)){
					$slug=new Slug(); 
					$slug->slug_channel=$this->_channel->id; 
					$slug->slug_table='posts'; 
					$slug->slug_table_id=$post->id; 
					$slug->created_at=Carbon::now()->format('Y-m-d H:i:s'); 
					$slug->save(); 
					if(!empty($slug->id)){
						$checkSlug=Slug::where('slug_channel','=',$this->_channel->id)
							->where('slug_value','=',Str::slug($postTitle))->get(); 
						if(count($checkSlug)>0){
							$slug->slug_value=Str::slug($postTitle.'-'.$slug->id); 
							$slug->save(); 
						}else{
							$slug->slug_value=Str::slug($postTitle); 
							$slug->save(); 
						}
					}
					$post=Posts::find($post->id); 
				}
				$post->user_id=Auth::user()->id; 
				$post->posts_title = htmlspecialchars(strip_tags($postTitle, '')); 
				$post->posts_title_convert = WebService::vn_str_filter(htmlspecialchars(strip_tags($postTitle, ''))); 
				$post->posts_updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
				//$post->posts_status='active'; 
				$post->save(); 
				if(!empty($post->id)){
					if($this->_channel->channel_parent_id==0){
						if(Auth::user()->hasRole(['manage', 'admin'])){
							$post->posts_type=0; 
							$post->save(); 
						}else{
							$post->posts_type=1; 
							$post->save(); 
						}
						Post_join_user::where('post_id')->delete(); 
						Post_join_user::insertGetId(array(
							'post_id'=>$post->id, 
							'user_id'=>Auth::user()->id
						)); 
						if(!empty($idRegion)){
							$getRegion=Regions::find($idRegion); 
							if(!empty($getRegion->id)){
								Post_join_region::where('post_id','=',$post->id)->delete(); 
								Post_join_region::insertGetId(array(
									'post_id'=>$post->id, 
									'region_id'=>$getRegion->id
								)); 
							}
						}
						if(!empty($idSubRegion)){
							$getSubRegion=Subregions::find($idSubRegion); 
							if(!empty($getSubRegion->id)){
								Post_join_subregion::where('post_id','=',$post->id)->delete(); 
								Post_join_subregion::insertGetId(array(
									'post_id'=>$post->id, 
									'subregion_id'=>$getSubRegion->id
								)); 
							}
						}
						if(!empty($idDistrict)){
							$getDistrict=Region_district::find($idDistrict); 
							if(!empty($getDistrict->id)){
								Post_join_district::where('post_id','=',$post->id)->delete(); 
								Post_join_district::insertGetId(array(
									'post_id'=>$post->id, 
									'district_id'=>$getDistrict->id
								)); 
							}
						}
						if(!empty($idWard)){
							$getWard=Region_ward::find($idWard); 
							if(!empty($getWard->id)){
								Post_join_ward::where('post_id','=',$post->id)->delete(); 
								Post_join_ward::insertGetId(array(
									'post_id'=>$post->id, 
									'ward_id'=>$getWard->id
								)); 
							}
						}
					}
					//preg_match_all('/<img(.+?)src=\"(.+?)\"(.*?)>/', $postContent, $imgUrl); 
					/*$urls = $imgUrl[2];
					if(count($urls)){
						$pslManager = new PublicSuffixListManager(); 
						$parser = new Parser($pslManager->getList()); 
						foreach ($urls as $image) {
							if($parser->isSuffixValid($image)){
								$domainName = $parser->parseUrl($image); 
								if(!empty($domainName->host->registerableDomain) && $domainName->host->registerableDomain!=config('app.url')){
									$handle = fopen($image, 'rb'); 
									$mediaControl= new MediaController(); 
									$getMediaUpload= $mediaControl->uploadFileFromUrl($handle); 
									if(!empty($getMediaUpload->media_url)){
										Media_join_channel::insertGetId(array(
											'media_id'=>$getMediaUpload->id, 
											'channel_id'=>$this->_channel->id
										)); 
										Media_join_post::insertGetId(array(
											'post_id'=>$post->id, 
											'media_id'=>$getMediaUpload->id
										)); 
										$postContent = str_replace($image, $getMediaUpload->media_url, $postContent); 
									}
								}
							}
						}
					}*/
					//$postContent=preg_replace('/(\<img[^>]+)(src\=\"[^\"]+\")([^>]+)(>)/', '<img ${2} >', $postContent); 
					$post->posts_description=htmlspecialchars($postContent, ENT_COMPAT,'UTF-8', true); 
					$post->save(); 
					if(count($keywordsArray)>0){
						Posts_join_keywords::where('post_id','=',$post->id)->delete(); 
						foreach($keywordsArray as $keyword){ 
							if(!empty($keyword)){
								$getKeyword=Keywords::where('keyword','=',$keyword)->orWhere('keyword_encode','=',base64_encode($keyword))->first(); 
								if(!empty($getKeyword->id)){
									$getKeyword->increment('insert',1);
									Posts_join_keywords::insertGetId(array(
										'keyword_id'=>$getKeyword->id, 
										'post_id'=>$post->id
									)); 
								}else{
									$idKeyword=Keywords::insertGetId(array(
										'keyword'=>$keyword, 
										'keyword_encode'=>base64_encode($keyword),
										'slug'=>Str::slug($keyword), 
										'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
									)); 
									if($idKeyword){
										Posts_join_keywords::insertGetId(array(
											'keyword_id'=>$idKeyword, 
											'post_id'=>$post->id
										)); 
									}
								}
							}
						}
					}
					Posts_join_channel::where('posts_id','=',$post->id)->delete(); 
					Posts_join_channel::insertGetId(array(
						'channel_id'=>$this->_channel->id, 
						'posts_id'=>$post->id, 
					)); 
					Posts_attribute::where('posts_parent_id','=',$post->id)->where('posts_attribute_type','=','author')->delete(); 
					Posts_attribute::insertGetId(array(
						'posts_parent_id'=>$post->id, 
						'posts_attribute_type'=>'author', 
						'posts_attribute_value'=>Auth::user()->id, 
						'posts_attribute_status'=>'active',
						'posts_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
					));
					if(!empty($quanlityProduct)){
						Posts_attribute::where('posts_parent_id','=',$post->id)->where('posts_attribute_type','=','quanlity')->delete(); 
						Posts_attribute::insertGetId(array(
							'posts_parent_id'=>$post->id,
							'posts_attribute_type'=>'quanlity', 
							'posts_attribute_value'=>$quanlityProduct, 
							'posts_attribute_status'=>'active', 
							'posts_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
						)); 
					}
					if(!empty($price)){
						Posts_attribute::where('posts_parent_id','=',$post->id)->where('posts_attribute_type','=','price')->delete(); 
						Posts_attribute::insertGetId(array(
							'posts_parent_id'=>$post->id,
							'posts_attribute_type'=>'price', 
							'posts_attribute_value'=>$price, 
							'posts_attribute_status'=>'active', 
							'posts_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
						)); 
					}
					if(!empty($dataContact)){
						Posts_attribute::where('posts_parent_id','=',$post->id)->where('posts_attribute_type','=','contact')->delete(); 
						Posts_attribute::insertGetId(array(
							'posts_parent_id'=>$post->id, 
							'posts_attribute_type'=>'contact', 
							'posts_attribute_value'=>$dataContact, 
							'posts_attribute_status'=>'active', 
							'posts_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
						));
					} 
					if(count($mediaJsonDecode)>0){
					    try{
                            foreach($mediaJsonDecode as $media){
                                if($media->type=="image"){
                                    $mediaControl= new MediaController();
                                    $getMediaUpload= $mediaControl->uploadFileFromTmp($media->type,$media->fileTmp,$media->mediaIdRandom,$media->destinationPath,$postTitle,$content="",$post->id,$this->_channel->id);
                                    if($getMediaUpload!==false){
                                        Posts_attribute::insertGetId(array(
                                            'posts_parent_id'=>$post->id,
                                            'posts_attribute_type'=>'gallery',
                                            'posts_attribute_value'=>$getMediaUpload->id,
                                            'posts_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
                                        ));
                                    }
                                }
                                else if($media->type=="video"){
                                    $mediaControl= new MediaController();
                                    $getMediaUpload= $mediaControl->uploadFileFromTmp($media->type,$media->fileTmp,$media->mediaIdRandom,$media->destinationPath,$postTitle,$content="",$post->id,$this->_channel->id);
                                    if($getMediaUpload!==false){
                                        Posts_attribute::insertGetId(array(
                                            'posts_parent_id'=>$post->id,
                                            'posts_attribute_type'=>'gallery',
                                            'posts_attribute_value'=>$getMediaUpload->id,
                                            'posts_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
                                        ));
                                    }
                                }else if($media->type=="files"){

                                }
                            }
                        }catch (\Exception $e) {
                            return response()->json(['success'=>false,
                                'message'=>'Không thể đăng media! ',
                            ]);
                        }
					}
					if($this->_channel->channel_parent_id==0){
						if(count($categoryArray)>0){
							Posts_join_field::where('post_id','=',$post->id)->delete(); 
							foreach($categoryArray as $fieldId){ 
								$getField=Fields::find($fieldId); 
								if(!empty($getField->id)){
									Posts_join_field::insertGetId(array(
										'post_id'=>$post->id, 
										'field_id'=>$getField->id, 
									));
								}
							} 
						}
					}else{
						if(count($categoryArray)>0){
							Posts_join_category::where('posts_id','=',$post->id)->delete(); 
							foreach($categoryArray as $category){ 
								$getCategory=Category::find($category); 
								if(!empty($getCategory->id)){
									Posts_join_category::insertGetId(array(
										'posts_id'=>$post->id, 
										'category_id'=>$category, 
									));
								}elseif(count($this->_channel->joinCategory)>0){
									$getCategory=Category::find($this->_channel->joinCategory[0]->category_id); 
									if(!empty($getCategory->id)){
										Posts_join_category::insertGetId(array(
											'posts_id'=>$post->id, 
											'category_id'=>$getCategory->id, 
										));
									}
								}else{
									$newCategory= new CategoryController(); 
									$newCategory->_idCategory=0; 
									$newCategory->_categoryName='Mục chính'; 
									$newCategory->_categoryDescription=''; 
									$newCategory->_categoryOrderBy=0; 
									$newCategory->_parentId=0; 
									$newCategory->_channelId=$this->_channel->id; 
									$resultAddCategory=$newCategory->addSuccess(); 
									if($resultAddCategory!=false){
										Posts_join_category::insertGetId(array(
											'posts_id'=>$post->id, 
											'category_id'=>$resultAddCategory, 
										));
									}else{
										return response()->json(['success'=>false,
											'message'=>'Không thể tạo danh mục! ', 
										]);
									}
								}
							} 
						}
					}
					if(count($post->gallery)<=0){
						$post->posts_status='draft'; 
						$post->save(); 
						return response()->json(['success'=>false,
							'message'=>'Bài đăng phải thêm ít nhất 1 hình ảnh! ', 
						]);
					}else{
						if(!empty($idPostPageFacebook)){
							if(Auth::check() && !empty(Auth::user()->joinOauthFacebook->access_token)){ 
								$this->fb = new \Facebook\Facebook([
								  'app_id' => env('FACEBOOK_APP_ID'),
								  'app_secret' => env('FACEBOOK_APP_SECRET'),
								  'default_graph_version' => 'v3.0',
								]); 
								if(empty($post->postsShareFacebook->id)){
									/*$response = $this->fb->post(
										'/'.$idPostPageFacebook.'/feed',
										array(
											"message" => "Message",
											"link" => $this->_domain->domain,$post->getSlug->slug_value,
											"picture" => config('app.link_media').$post->gallery[0]->media->media_path.'thumb/'.$post->gallery[0]->media->media_name,
											"name" => $post->posts_title,
											"caption" => $this->_domain->domain,
											"description" => $post->posts_description
										),
										Auth::user()->joinOauthFacebook->access_token
									);
									// Success
									$postId = $response->getGraphNode(); 
									Posts_share::insertGetId(array(
										'post_id'=>$post->id, 
										'social'=>'facebook', 
										'post_id_social'=>$postId, 
										'user_id'=>Auth::user()->id
									)); */
								}
							}
						}
					}
				}
				/*$posts=Posts::where('user_id',Auth::user()->id)
					->where('posts.posts_status','=','active')
					->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
					->where('posts_join_channel.channel_id','=',2)
					->groupBy('posts.id')
					//->orderBy('posts.posts_updated_at','desc')
					->select('posts.*')
					->get(); 
				if(count($posts)>=4){
					$post->posts_status='billing'; 
					$post->save(); 
					return response()->json(['success'=>true,
						'message'=>'Đăng bài thành công!', 
						'postId'=>$post->id, 
						'link'=>route('post.billing',$this->_domain->domain), 
					]);
				}*/
				if($this->_channel->channel_parent_id==0 && $post->posts_status!='active'){
					$post->posts_status='billing'; 
					$post->save(); 
					return response()->json(['success'=>true,
						'message'=>'Đăng bài thành công!', 
						'postId'=>$post->id, 
						'link'=>route('post.billing',$this->_domain->domain), 
					]);
				}else{ 
					$post->posts_status='active'; 
					$post->save();
					$checkIndex=DB::table('index_post_elasticsearch')
                        ->where('posts_id',$post->id)
                        ->first();
					if(empty($checkIndex->id)){
                        if(config('app.env')!='local'){
                            $post->addToIndex();
                            DB::table('index_post_elasticsearch')->insertGetId(
                                [
                                    'posts_id'=>$post->id,
                                    'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
                                    'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
                                ]
                            );
                        }
                    }else{
                        if(config('app.env')!='local'){
                            $post->reindex();
                            DB::table('index_post_elasticsearch')->where('posts_id',$post->id)
                                ->update(
                                    [
                                        'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
                                    ]
                                );
                        }
                    }
					return response()->json(['success'=>true,
						'message'=>'Đăng bài thành công!', 
						'postId'=>$post->id, 
						'link'=>route('channel.slug',array($this->_domainPrimary,$post->getSlug->slug_value)),
					]);
				}
			}else{
				return response()->json(['success'=>false,
					'message'=>'Bạn phải đăng nhập mới có thể đăng bài! ', 
				]);
			}
	}
	/*-- end new --*/
	public function moveAttribute(){
		$getCategory=Category::where('category_status','=','active')->get(); 
		foreach($getCategory as $category){
			$getCategoryJoinChannel=Category_join_channel::where('category_id','=',$category->id)->first(); 
			if(!empty($getCategoryJoinChannel->category_id)){
				$getCategoryJoinChannel->order_by=$category->category_order_by; 
				$getCategoryJoinChannel->save(); 
			}
		}
	}
	public function moveAttribute3(){
		$getCategoryJoin=Category_join::where('category_join_table','=','channel')->get(); 
		foreach($getCategoryJoin as $categoryJoin){
			$getCategory=Category::where('id','=',$categoryJoin->category_parent_id)->first(); 
			$getChannel=Channel::where('id','=',$categoryJoin->table_parent_id)->first(); 
			if(!empty($getCategory->id) && !empty($getChannel->id)){ 
				Category_join_channel::insertGetId(array(
					'channel_id'=>$getChannel->id, 
					'category_id'=>$getCategory->id
				)); 
			}
		}
	}
	public function moveAttributeOld(){
		$getCompany=Company::where('move','=',0)->take(500)->get(); 
		foreach($getCompany as $company){
			$channelJoin=Company_join_channel::where('company_id','=',$company->id)->first(); 
			if(!empty($channelJoin->channel_id)){ 
				$idAddress=Address::insertGetId(array(
					'address'=>$company->company_address, 
					'status'=>'pending', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
				if($idAddress){
					if(!empty(Regions::find($company->company_region)->id)){
						Address_join_region::insertGetId(array(
							'address_id'=>$idAddress, 
							'region_id'=>$company->company_region
						));
					} 
					if(!empty(Subregions::find($company->company_subregionn)->id)){
						Address_join_subregion::insertGetId(array(
							'address_id'=>$idAddress, 
							'subregion_id'=>$company->company_subregion
						)); 
					}
					if(!empty(Region_district::find($company->company_district)->id)){
						Address_join_district::insertGetId(array(
							'address_id'=>$idAddress, 
							'district_id'=>$company->company_district
						));
					}
					if(!empty(Region_ward::find($company->company_ward)->id)){
						Address_join_ward::insertGetId(array(
							'address_id'=>$idAddress, 
							'ward_id'=>$company->company_ward
						));
					}
					Company_join_address::insertGetId(array(
						'company_id'=>$company->id, 
						'address_id'=>$idAddress
					)); 
					Channel_join_address::insertGetId(array(
						'address_id'=>$idAddress, 
						'channel_id'=>$channelJoin->channel_id
					)); 
					$company->move=1; 
					$company->save(); 
				}
			}
		}
	}
	public function postsRemove()
	{
		if($this->_security==true){
			$postId=Input::get('postId'); 
			$post=Posts::find($postId); 
			if(!empty($post->id)){
				if(count($post->gallery)>0){
					foreach($post->gallery as $gallery){
						$getMedia=Media::find($gallery->media->id); 
						if(!empty($getMedia->id)){
							if($getMedia->media_storage=='youtube'){
								//Youtube::delete($getMedia->media_name);
							}elseif($getMedia->media_storage=='files'){
								File::delete($getMedia->media_path.$getMedia->media_name);
							}elseif($getMedia->media_storage=='video'){
								File::delete($getMedia->media_path.$getMedia->media_name); 
								File::delete($getMedia->media_path.'thumb/'.$getMedia->media_id_random.'.png'); 
								File::delete($getMedia->media_path.'small/'.$getMedia->media_id_random.'.png'); 
								File::delete($getMedia->media_path.'xs/'.$getMedia->media_id_random.'.png'); 
							}else{
								if(Storage::disk('s3')->exists($getMedia->media_path.$getMedia->media_name)) {
									Storage::disk('s3')->delete($getMedia->media_path.$getMedia->media_name);
								}
								if(Storage::disk('s3')->exists($getMedia->media_path.'thumb/'.$getMedia->media_name)) {
									Storage::disk('s3')->delete($getMedia->media_path.'thumb/'.$getMedia->media_name);
								}
								if(Storage::disk('s3')->exists($getMedia->media_path.'small/'.$getMedia->media_name)) {
									Storage::disk('s3')->delete($getMedia->media_path.'small/'.$getMedia->media_name);
								}
								if(Storage::disk('s3')->exists($getMedia->media_path.'xs/'.$getMedia->media_name)) {
									Storage::disk('s3')->delete($getMedia->media_path.'xs/'.$getMedia->media_name);
								}
								File::delete($getMedia->media_path.$getMedia->media_name); 
								File::delete($getMedia->media_path.'thumb/'.$getMedia->media_name);
								File::delete($getMedia->media_path.'small/'.$getMedia->media_name);
								File::delete($getMedia->media_path.'xs/'.$getMedia->media_name);
							}
							$getMedia->delete(); 
						}
					}
				}
				Slug::where('slug_channel','=',$this->_channel->id)->where('slug_table','=','posts')->where('slug_table_id','=',$post->id)->delete(); 
				$post->delete();
				if(config('app.env')!='local'){
                    $post->deleteIndex();
                }
				return response()->json(['success'=>true,
					'message'=>'Đã xóa bài viết! ',
				]); 
			}else{
				return response()->json(['success'=>false,
					'message'=>'Không tìm thấy bài viết! ',
				]); 
			}
		}else{
			$view = array(); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function postsDelete()
	{
		if($this->_security==true){
			$postId=Input::get('postId'); 
			$getPost=Posts::find($postId); 
			$getPost->posts_status='delete'; 
			//$getPost->posts_updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
			$getPost->save(); 
			//Slug::where('slug_channel','=',$this->_channel->id)->where('slug_table','=','posts')->where('slug_table_id','=',$getPost->id)->delete();
            if(config('app.env')!='local'){
                $getPost->deleteIndex();
            }
            return response()->json(['success'=>true,
				'message'=>'Đã xóa bài viết vào thùng rác! ',
			]); 
		}else{
			$view = array(); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function postsAdd()
    {
		if($this->_security==true){
			$postId = Posts::insertGetId(array(
				'posts_created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
				'posts_updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				'posts_status'=>'draft'
			));
			Posts_join_channel::insertGetId(array(
				'channel_id'=>$this->_channel->id, 
				'posts_id'=>$postId, 
			));
			return Redirect::route('channel.post.edit',array($this->_parame['domain'],$postId));
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function postsEdit()
    {	if($this->_security==true){
			$post=Posts::find($this->_parame['id']); 
			if(!empty($post->id)){
				$view = array(
					'theme'=>$this->_theme, 
					'post'=>$post
				);
				return $this->_theme->scope('admin.posts.add', $view)->render(); 
			}
			else{
				$view = array(
					'channel'=>$this->_channel, 
					'domain'=>$this->_domain, 
				); 
				return $this->_theme->scope('404', $view)->render(); 
			}
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function postsSave(Request $request)
    {
		if($this->_security==true){
			$channelId=Input::get('channelId'); 
			$postId=Input::get('postId'); 
			$postContent=Input::get('postContent'); 
			$postTitle=Input::get('postTitle'); 
			$getPost=Posts::where('posts.id','=',$postId)
				->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
				->where('posts_join_channel.channel_id','=',$this->_channel->id)
				->select('posts.*')
				->first(); 
			$error=''; 
			$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
			$keyRandom=substr(str_shuffle(str_repeat($pool, 5)), 0, 5); 
			if(empty($postTitle)){
				$error='Vui lòng nhập tiêu đề bài viết';
			}elseif(count($getPost->postsJoinCategory)<=0){
				$error='Vui lòng thêm danh mục cho bài viết';
			}elseif(count($getPost->gallery)<=0){
				$error='Vui lòng thêm ít nhất 1 hình ảnh cho bài viết';
			}
			$checkPostSlug=Slug::where('slug_channel','=',$this->_channel->id)
				->where('slug_table','=','posts')
				->where('slug_table_id','=',$getPost->id)
				->delete(); 
			$checkSlug=Slug::where('slug_channel','=',$this->_channel->id)
				->where('slug_value','=',Str::slug($postTitle))->get(); 
			if(count($checkSlug)>0){
				$slugValue=Str::slug($postTitle).'-'.$getPost->id.'-'.$keyRandom;
			}else if(!empty($postTitle)){
				$slugValue=Str::slug($postTitle);
			}else{
				$slugValue=Str::slug(time().$keyRandom);
			}
			$slugId=Slug::insertGetId(array(
				'slug_channel'=>$this->_channel->id, 
				'slug_value'=>$slugValue, 
				'slug_table'=>'posts', 
				'slug_table_id'=>$postId, 
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
			));
			if(empty($error)){
				Posts_join_channel::where('channel_id','=',$this->_channel->id)->where('posts_id','=',$postId)->delete(); 
				Posts_join_channel::insertGetId(array(
					'channel_id'=>$this->_channel->id, 
					'posts_id'=>$postId, 
				));
				Posts_attribute::where('posts_parent_id','=',$postId)
					->where('posts_attribute_type','=','author')
					->delete(); 
				Posts_attribute::insertGetId(array(
					'posts_parent_id'=>$postId, 
					'posts_attribute_type'=>'author', 
					'posts_attribute_value'=>Auth::user()->id, 
					'posts_attribute_status'=>'active',
					'posts_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
				));
				preg_match_all('/<img(.+?)src=\"(.+?)\"(.*?)>/', $postContent, $images); 
				//preg_replace('<img(.*)src="([^ "]*)"([^>]*)>', '<img src="$2">', $postContent);
				$urls = $images[2];
				if(count($urls)){
					$pslManager = new PublicSuffixListManager(); 
					$parser = new Parser($pslManager->getList()); 
					foreach ($urls as $image) {
						$domainName = $parser->parseUrl($image); 
						if($domainName->host->registerableDomain!=config('app.url')){
							$handle = fopen($image, 'rb'); 
							$mediaControl= new MediaController(); 
							$getMediaUpload= $mediaControl->uploadFileFromUrl($handle); 
							if(!empty($getMediaUpload->media_url)){
								Media_join_channel::insertGetId(array(
									'media_id'=>$getMediaUpload->id, 
									'channel_id'=>$this->_channel->id
								)); 
								Media_join_post::insertGetId(array(
									'post_id'=>$postId, 
									'media_id'=>$getMediaUpload->id
								)); 
							}
							$postContent = str_replace($image, $getMediaUpload->media_url, $postContent); 
						}
						
					}
				} 
				$update_post = [
					'posts_title' => strip_tags($postTitle, ''), 
					'posts_title_convert' => WebService::vn_str_filter(htmlspecialchars(strip_tags($postTitle, ''))),
					'posts_description'=>htmlspecialchars($postContent, ENT_COMPAT,'UTF-8', true), 
					'posts_status'=>(count($getPost->gallery)>0) ? 'active' : 'pending',
					'posts_updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				];
				Posts::where('id','=',$postId)
					->update($update_post); 
				$this->_channel->channel_updated_at=Carbon::now()->format('Y-m-d H:i:s'); 
				$this->_channel->save(); 
				return response()->json(['success'=>true,
					'message'=>'Cập nhật thành công! ',
					'post_name'=>$postTitle
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>$error,
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
	public function postGetOptions(){
		if($this->_security==true){
			$postId=Input::get('postId'); 
			$post=Posts::find($postId); 
			if(!empty($post->id)){
				$options=Posts_attribute::where('posts_parent_id','=',$post->id)->where('posts_attribute_type','=','options')->first(); 
				if(!empty($options->posts_attribute_value)){
					return response()->json(['success'=>true,
						'message'=>'Danh sách tùy chọn', 
						'options'=>json_decode($options->posts_attribute_value)
					]);
				}else{
					return response()->json(['success'=>false,
						'message'=>'Danh sách tùy chọn',
					]);
				}
			}else{
				return response()->json(['success'=>false,
					'message'=>'Không tìm thấy bài',
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
	public function postAddOptions(){
		if($this->_security==true){
			$postId=Input::get('postId'); 
			$options=Input::get('options'); 
			$post=Posts::find($postId); 
			if(!empty($post->id)){
				Posts_attribute::where('posts_parent_id','=',$post->id)->where('posts_attribute_type','=','options')->delete(); 
				Posts_attribute::insertGetId(array(
					'posts_parent_id'=>$post->id, 
					'posts_attribute_type'=>'options', 
					'posts_attribute_value'=>$options
				)); 
				return response()->json(['success'=>true,
					'message'=>'Đã cập nhật tùy chọn. ',
				]); 
			}else{
				return response()->json(['success'=>false,
					'message'=>'Không tìm thấy bài',
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
	public function postAttributeAdd(){
		if($this->_security==true){
			$attributePrice=Input::get('attributePrice'); 
			$postId=Input::get('postId'); 
			$post=Posts::where('id','=',$postId)->first(); 
			if(!empty($attributePrice)){ 
				$messages = array(
					'numeric'=>'Giá phải là dạng số'
				);
				$rules = array(
					'attributePrice' => 'numeric',
				);
				$validator = Validator::make(Input::all(), $rules, $messages);
				if ($validator->fails())
				{
					$error='Giá phải là dạng số'; 
				}
				if(empty($error)){
					if(empty($post->price->posts_attribute_value)){
						Posts_attribute::insertGetId(array(
							'posts_parent_id'=>$postId, 
							'posts_attribute_type'=>'price', 
							'posts_attribute_value'=>$attributePrice, 
							'posts_attribute_status'=>'active', 
							'posts_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
						)); 
						return response()->json(['success'=>true,
							'message'=>'Đã thêm giá vào bài viết! ', 
							'price'=>Site::price($attributePrice).$this->_channel->channelJoinRegion->region->currency_code
						]);
					}else{
						return response()->json(['success'=>false,
							'message'=>'Bài viết đã có giá! '
						]);
					}
				}
				else{
					return response()->json(['success'=>false,
						'message'=>$error,
					]); 
				}
			}
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function postAttributeDeletePrice(){
		if($this->_security==true){
			$postId=Input::get('postId'); 
			$post=Posts::find($postId); 
			if(!empty($post->id)){
				Posts_attribute::where('posts_attribute_type','=','price')->where('posts_parent_id','=',$post->id)->delete(); 
				return response()->json(['success'=>true,
					'message'=>'Đã xóa giá!  '
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
	public function postJoinCategoryList()
    {
		$categoryList=array(); 
		foreach($this->_channel->getCategory as $category){
			$categoryList[]=$category->category; 
		}
		return response()->json(['success'=>true,
			'message'=>'Danh sách thể loại', 
			'category'=>$categoryList
		]);
	}
	public function postJoinCategoryAdd()
    {
		if($this->_security==true){
			$postId=Input::get('postId');
			$categoryId=Input::get('categoryId');
			$checkPostJoin=Posts_join_category::where('posts_id','=',$postId)
				->where('category_id','=',$categoryId)
				->get(); 
			if(count($checkPostJoin)<=0){
				Posts_join_category::insertGetId(array(
					'category_id'=>$categoryId, 
					'posts_id'=>$postId, 
				));
				$post=Posts::where('id','=',$postId)->first(); 
				$categoryList=array(); 
				foreach($post->postsJoinCategory as $category){
					$categoryList[]=$category->getCategory; 
				}
				return response()->json(['success'=>true,
					'message'=>'Lưu thành công!', 
					'category'=>$categoryList
				]); 
			}else{
				return response()->json(['success'=>false,
					'message'=>'Thể loại này đã được sử dụng! ',
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
	public function postJoinCategoryDelete()
    {
		if($this->_security==true){
			$categoryId=Input::get('categoryId'); 
			$postId=Input::get('postId'); 
			Posts_join_category::where('category_id','=',$categoryId)->where('posts_id','=',$postId)->delete(); 
			$post=Posts::where('id','=',$postId)->first(); 
			$categoryList=array(); 
			foreach($post->postsJoinCategory as $category){
				$categoryList[]=$category->getCategory; 
			}
			return response()->json(['success'=>true,
				'message'=>'Xóa thành công! ', 
				'category'=>$categoryList
			]); 
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function postAttributeGalleryInsert()
    {
		if($this->_security==true){
			$postId=Input::get('postId'); 
			$postAttributeType=Input::get('postAttributeType'); 
			$postAttributeValue=Input::get('postAttributeValue'); 
			$idPostsAttribute=Posts_attribute::insertGetId(array(
				'posts_parent_id'=>$postId, 
				'posts_attribute_type'=>$postAttributeType, 
				'posts_attribute_value'=>$postAttributeValue, 
				'posts_attribute_created_at'=>Carbon::now()->format('Y-m-d H:i:s')
			)); 
			$gallery=Posts_attribute::where('posts_parent_id','=',$postId)
				->where('posts_attribute_type','=',$postAttributeType)->get(); 
			$media=array(); 
			foreach($gallery as $medias){
				$media[]=$medias->media; 
			}
			return response()->json(['success'=>true,
				'message'=>'Đã thêm hình ảnh', 
				'media'=>$media
			]);
		}else{
			$view = array(
				'channel'=>$this->_channel, 
				'domain'=>$this->_domain, 
			); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function postAttributeGallery()
	{
		$getPost=Posts::find($this->_parame['id']); 
		$media=array(); 
		foreach($getPost->gallery as $medias){
			$media[]=$medias->media; 
		}
		return response()->json(['success'=>true,
			'gallery'=>$media
		]);	
	}
	public function postAttributeMediaDelete()
    {
		if($this->_security==true){
			$postId=Input::get('postId'); 
			$mediaId=Input::get('mediaId'); 
			$postAttributeType=Input::get('postAttributeType');  
			$getMedia=Media::find($mediaId); 
			if(!empty($getMedia->id)){
				if($getMedia->media_storage=='youtube'){
					//Youtube::delete($getMedia->media_name);
				}elseif($getMedia->media_storage=='files'){
					File::delete($getMedia->media_path.$getMedia->media_name);
				}elseif($getMedia->media_storage=='video'){
					File::delete($getMedia->media_path.$getMedia->media_name); 
					File::delete($getMedia->media_path.'thumb/'.$getMedia->media_id_random.'.png'); 
					File::delete($getMedia->media_path.'small/'.$getMedia->media_id_random.'.png'); 
					File::delete($getMedia->media_path.'xs/'.$getMedia->media_id_random.'.png'); 
				}else{
					File::delete($getMedia->media_path.$getMedia->media_name); 
					File::delete($getMedia->media_path.'thumb/'.$getMedia->media_name);
					File::delete($getMedia->media_path.'small/'.$getMedia->media_name);
					File::delete($getMedia->media_path.'xs/'.$getMedia->media_name);
				}
				$getMedia->delete(); 
				Posts_attribute::where('posts_parent_id','=',$postId)
					->where('posts_attribute_type','=',$postAttributeType)
					->where('posts_attribute_value','=',$getMedia->id)->delete(); 
				$getGallery=Posts_attribute::where('posts_parent_id','=',$postId)
					->where('posts_attribute_type','=',$postAttributeType)->get();
				if(count($getGallery)<=0){
					$getPost=Posts::find($postId); 
					$getPost->posts_status='draft'; 
					$getPost->save(); 
				}
				$media=array(); 
				foreach($getGallery as $medias){
					$media[]=$medias->media; 
				}
				return response()->json(['success'=>true,
					'message'=>'Đã xóa! '.$getMedia->media_name, 
					'media'=>$media
				]);
			}else{
				return response()->json(['success'=>false,
					'message'=>'Không tìm thấy media! ', 
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
	
}