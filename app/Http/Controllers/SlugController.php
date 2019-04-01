<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request; 
use Illuminate\Support\Str;
use Auth;
use App\Model\History; 
use App\Permission;
use App\Role; 
use App\Model\Channel_role;
use App\User;
use App\Model\Keywords;
use App\Model\Posts;
use App\Model\Posts_join;
use App\Model\Posts_join_channel;
use App\Model\Posts_join_category;
use App\Model\Posts_attribute; 
use App\Model\Posts_join_keywords;
use App\Model\Category;
use App\Model\Category_join;
use App\Model\Slug;
use App\Model\Channel; 
use App\Model\Channel_join;
use App\Model\Channel_join_region;
use App\Model\Channel_join_subregion;
use App\Model\Channel_join_district;
use App\Model\Channel_join_ward;
use App\Model\Channel_join_field;
use App\Model\Address;
use App\Model\Address_join_region;
use App\Model\Address_join_subregion;
use App\Model\Address_join_district;
use App\Model\Address_join_ward; 
use App\Model\Company; 
use App\Model\Company_join; 
use App\Model\Company_join_channel; 
use App\Model\Company_join_field; 
use App\Model\Company_join_address; 
use App\Model\Regions;
use App\Model\Subregions;
use App\Model\Region_district;
use App\Model\Region_ward;
use App\Model\Fields;
use Carbon\Carbon;
use File;
use Theme;
use WebService; 
use Route; 
use Redirect; 
use Response; 
use Input; 
use Session; 
use Validator; 
use Lang; 
use Cache; 
use Pdp\PublicSuffixListManager; 
use Pdp\Parser; 
use Lullabot\AMP\AMP;
use Lullabot\AMP\Validate\Scope; 
use DonatelloZa\RakePlus\RakePlus; 
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
class SlugController extends ConstructController
{
	public function __construct(){
		parent::__construct(); 
	}
	public function getCategoryIdAll($categories, $parent_id = 0, $categoryId)
	{
		foreach ($categories as $key => $item)
		{
			if ($item->category->parent_id == $parent_id)
			{
				$categoryId[]=$item->category->id; 
				unset($categories[$key]);
				$this->getCategoryIdAll($categories, $item->category->id,$categoryId);
			}
		}
		return $categoryId; 
	}
	public function slug(Request $request)
    {
		if(!empty($this->_parame['slug'])){
			if(Session::has('view_channel')){
				$sessionHistory=Session::get('view_channel'); 
				if(!empty($sessionHistory['created_at'])){
					if(Carbon::parse($sessionHistory['created_at'])->addMinutes(5) > Carbon::now()->format('Y-m-d H:i:s')){
						$error='Mỗi lượt xem cách nhau 5 phút. Lần xem gần đây nhất của bạn cách đây '.WebService::time_request($sessionHistory['created_at']); 
					}
				}
			}
			if(empty($error)){
				$this->_channel->increment('channel_view',1); 
				if(Session::has('view_channel')){
					Session::forget('view_channel');
				}
				Session::put('view_channel', [ 
					'ip' => $request->ip(), 
					'created_at' => Carbon::now()->format('Y-m-d H:i:s')
				]); 
			}
			$slug=Slug::where('slug.slug_value','=',$this->_parame['slug'])
				->where('slug_channel','=',$this->_channel->id)
				->first(); 
			if(!empty($slug->slug_table)){
				if($slug->slug_table=='posts'){
					$post=Posts::where('posts.id','=',$slug->slug_table_id)
						->where('posts.posts_status','=','active')
						//->join('posts_attribute','posts_attribute.posts_parent_id','=','posts.id')
						//->where('posts_attribute.posts_attribute_type','=','gallery')
						//->whereNotNull('posts_attribute.posts_attribute_value')
						//->groupBy('posts.id')
						//->select('posts.*')
						->first(); 
					if(!empty($post->id)){
						/*if($this->_channel->channel_parent_id==0){
							return Redirect::to('https://post-'.$post->id.'.'.config('app.url'), 301);
						}*/
						/*if(count($post->keywords)<=0){
							$checkHistory=History::where('history_type','=','insert_keyword')->first();
							if(!empty($checkHistory->created_at)){
								if(Carbon::parse($checkHistory->created_at)->addSecond(10)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
									History::where('history_type','=','insert_keyword')->delete(); 
									$listHistory=array(
										'history_type'=>'insert_keyword', 
										'parent_id'=>0, 
										'author'=>0,
										'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
									); 
									History::insertGetId($listHistory); 
									$getKey=RakePlus::create($post->posts_title)->sort('asc')->get(); 
									$url=''; 
									if(count($getKey)>0){
										foreach($getKey as $keyGet){
											$rake = RakePlus::create($keyGet)->keywords(); 
											if(count($rake)>2){
												$url='http://suggestqueries.google.com/complete/search?client=chrome&q='.$rake[0].'+'.$rake[1].'+'.$rake[2]; 
											}else if(count($rake)==2){
												$url='http://suggestqueries.google.com/complete/search?client=chrome&q='.$rake[0].'+'.$rake[1]; 
											}
											if(!empty($url)){
												$client = new Client([
													'headers' => [ 
														'Content-Type' => 'text/html',
														'User-Agent' => 'Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n'
													], 
													'connect_timeout' => '2',
													'timeout' => '2'
												]); 
												$response = $client->request('GET', $url); 
												$content=json_decode($response->getBody()->getContents()); 
												if(count($content[1])>0){
													foreach($content[1] as $value){
														if(!empty($value)){
															if(WebService::is_valid_url($value)!=true){
																$getKeyword=Keywords::where('keyword_encode','=',base64_encode($value))->first(); 
																if(!empty($getKeyword->id)){
																	$getKeyword->increment('insert',1);
																	Posts_join_keywords::insertGetId(array(
																		'keyword_id'=>$getKeyword->id, 
																		'post_id'=>$post->id
																	)); 
																}else{
																	$idKeyword=Keywords::insertGetId(array(
																		'keyword'=>$value, 
																		'keyword_encode'=>base64_encode($value),
																		'slug'=>Str::slug($value), 
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
												}
											}
										}
									}
									$post=Posts::find($post->id); 
									
								}
							}else{
								$listHistory=array(
									'history_type'=>'insert_keyword', 
									'parent_id'=>0, 
									'author'=>0,
									'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
								); 
								History::insertGetId($listHistory); 
							}
						}*/
						$error=''; 
						if(Session::has('view_post')){
							$sessionHistory=Session::get('view_post'); 
							if(!empty($sessionHistory['created_at']) && !empty($sessionHistory['post_id']) && $sessionHistory['post_id']==$post->id){
								if(Carbon::parse($sessionHistory['created_at'])->addMinutes(5) > Carbon::now()->format('Y-m-d H:i:s')){
									$error='Mỗi lượt xem cách nhau 5 phút. Lần xem gần đây nhất của bạn cách đây '.WebService::time_request($sessionHistory['created_at']); 
								}
							}
						}
						if(empty($error)){
							$post->increment('posts_view',1); 
							if(Session::has('view_post')){
								Session::forget('view_post');
							}
							Session::put('view_post', [ 
								'ip' => $request->ip(), 
								'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 
								'post_id'=>$post->id, 
							]); 
						}
						$amp=addslashes($request->query('amp')); 
						if($amp=='true'){
							$this->_theme=Theme::uses('amp')->layout('default'); 
							$postDescription=html_entity_decode($post->posts_description); 
							$amp = new AMP(); 
							preg_match_all('/<img(.+?)src=\"(.+?)\"(.*?)>/', $postDescription, $imgUrl); 
							$urls = $imgUrl[2];
							$pslManager = new PublicSuffixListManager(); 
							$parser = new Parser($pslManager->getList());
							if(count($urls)){
								foreach ($urls as $image) {
									$domainName = $parser->parseUrl($image); 
									//print($domainName); 
									$postDescription = str_replace($image, $domainName, $postDescription); 
								}
							}
							$amp->loadHtml($postDescription);
							$return = array(
								'post'=>$post, 
								'postTitle'=>$post->posts_title, 
								'postDescription'=>$amp->convertToAmpHtml(), 
							);
							return $this->_theme->scope('post.show', $return)->render();
						}else{
							$postId=$post->id; 
							$channelId=$this->_channel->id; 
							$postsRelate=array(); 
							$postsNew=array(); 
							if($this->_channel->channel_parent_id!=0){
								$categoryId = []; 
								array_push($categoryId, $post->postsJoinCategory[0]->getCategory->id); 
								if(count($post->postsJoinCategory[0]->getCategory->parent)>0){
									foreach($post->postsJoinCategory[0]->getCategory->parent as $parent){
										if(count($parent->parent)>0){
											foreach($parent->parent as $subParent){
												array_push($categoryId, $subParent->id); 
											}
										}
										array_push($categoryId, $parent->id); 
									}
								}
								$postsRelate = Cache::store('memcached')->remember('postsRelate_sub_site'.$post->id, 5, function() use($postId,$categoryId,$channelId)
								{
									return Posts::where('posts.id','!=',$postId)
										->where('posts.posts_status','=','active')
										->join('posts_join_category','posts_join_category.posts_id','=','posts.id')
										->whereIn('posts_join_category.category_id',$categoryId)
										->join('category_join_channel','category_join_channel.category_id','=','posts_join_category.category_id')
										->where('category_join_channel.channel_id','=',$channelId)
										->orderBy('posts.posts_updated_at','DESC')
										->groupBy('posts.id')
										->select('posts.*')
										->take(10)->get(); 
								}); 
								$postsNew = Cache::store('memcached')->remember('postsNew_sub_site'.$post->id, 5, function() use($postId,$channelId)
								{
									return Posts::where('posts.id','!=',$postId)
										->where('posts.posts_status','=','active')
										->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
										->where('posts_join_channel.channel_id','=',$channelId)
										->orderBy('posts.posts_updated_at','DESC')
										->groupBy('posts.id')
										->select('posts.*')
										->take(10)->get();
								});
							}
							$return = array(
								'post'=>$post, 
								'postsRelate'=>$postsRelate, 
								'postsNew'=>$postsNew
							);
							return $this->_theme->scope('posts.show', $return)->render();
						}
					}else{
						$view = array(); 
						return $this->_theme->scope('404', $view)->render(); 
					}
				}else if($slug->slug_table=='category'){
					$category=Category::where('id','=',$slug->slug_table_id)
						->where('category_status','=','active')
						->first(); 
					$categoryId = []; 
					array_push($categoryId, $category->id); 
					if(count($category->children)>0){
						foreach($category->children as $catChild){
							array_push($categoryId, $catChild->id); 
							if(count($catChild->children)>0){
								foreach($catChild->children as $subChild){
									array_push($categoryId, $subChild->id); 
								}
							}
						}
					}
					$getPost = Cache::store('memcached')->remember('post_category'.$category->id, 5, function() use($categoryId)
						{
							return Posts::where('posts.posts_status','=','active')
								->join('posts_join_category','posts_join_category.posts_id','=','posts.id')
								->whereIn('posts_join_category.category_id',$categoryId)
								->join('posts_attribute','posts_attribute.posts_parent_id','=','posts.id')
								->where('posts_attribute.posts_attribute_type','=','gallery')
								->groupBy('posts.id')
								->orderBy('posts.posts_updated_at','desc')
								->select('posts.*')
								->paginate(9); 
						});
					if(count($getPost)>0){
						if(count($getPost)==1){
							if(!empty($getPost[0]->getSlug->slug_value)){
								return Redirect::route('channel.slug',array($this->_domain->domain,$getPost[0]->getSlug->slug_value));
							}else{
								$return=array(
									'getPost'=>$getPost, 
									'data'=>array(
										'category'=>$category, 
									), 
								); 
								return $this->_theme->scope('category.showMany', $return)->render();
							}
						}else{ 
							$return=array(
								'getPost'=>$getPost, 
								'data'=>array(
									'category'=>$category, 
								), 
							); 
							return $this->_theme->scope('category.showMany', $return)->render();
						}
					}else{
						$return=array(
							'getPost'=>$getPost, 
							'data'=>array(
								'category'=>$category, 
							), 
						); 
						return $this->_theme->scope('category.showMany', $return)->render();
					}
				}
			}
			else{
				if($this->_channel->channel_parent_id==0){
					$getSlug=explode('/',$this->_parame['slug']); 
					$getRegion=$this->_region; 
					$channelId=$this->_channel->id; 
					$getSubRegion = Cache::store('memcached')->remember('getSubRegion_'.$getRegion->id.$getSlug[0], 500, function() use($getRegion,$getSlug)
					{
						return Subregions::where('SolrID','=','/'.$getRegion->iso.'/'.$getSlug[0])->first(); 
					});
					if(!empty($getSubRegion->id)){
						if(!empty($getSlug[1])){
							$slug1=$getSlug[1]; 
						}else{
							$slug1=''; 
						}
						$getField=Fields::where('SolrID','=','/'.$slug1)->first(); 
						if(!empty($getField->id)){
							$channelList=array(); 
							$postListNew = Cache::store('memcached')->remember('postListNew_slug_field_subregion'.$getField->id.'subregion'.$getSubRegion->id, 5, function() use($channelId,$getField,$getSubRegion)
							{
								return Posts::where('posts.posts_status','=','active')
									->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
									->join('channel','channel.id','=','posts_join_channel.channel_id')
									->where('channel.channel_status','=','active')
									->where('channel.channel_parent_id','=',$channelId)
									->join('channel_join_field','channel_join_field.channel_id','=','channel.id')
									->where('channel_join_field.field_id','=',$getField->id)
									->join('domain_join_channel','domain_join_channel.channel_id','=','channel.id')
									->join('channel_join_address','channel_join_address.channel_id','=','channel.id')
									->join('address_join_subregion','address_join_subregion.address_id','=','channel_join_address.address_id')
									->where('address_join_subregion.subregion_id','=',$getSubRegion->id)
									//->groupBy('posts.id')
									->orderBy('posts.posts_updated_at','desc')
									->select('posts.*')
									->paginate(18); 
							});
							$return=array(
								'getField'=>$getField,
								'region'=>$getRegion, 
								'subregion'=>$getSubRegion, 
								'channelList'=>$channelList, 
								'postListNew'=>$postListNew, 
								'companyRelate'=>''
							); 
							return $this->_theme->scope('regions.subregion', $return)->render(); 
						}else{
							$getRegionDistrict = Cache::store('memcached')->remember('getRegionDistrict_'.$getRegion->id.$getSlug[0].$slug1, 500, function() use($getRegion,$getSlug,$slug1)
							{
								return Region_district::where('SolrID','=','/'.$getRegion->iso.'/'.$getSlug[0].'/'.$slug1)->first(); 
							});
							if(!empty($getRegionDistrict->id)){
								if(!empty($getSlug[2])){
									$slug2=$getSlug[2]; 
								}else{
									$slug2=''; 
								}
								$getField=Fields::where('SolrID','=','/'.$slug2)->first(); 
								if(!empty($getField->id)){
									$channelList=array(); 
									$postListNew = Cache::store('memcached')->remember('postListNew_slug_field_district'.$getField->id.'district'.$getRegionDistrict->id, 5, function() use($channelId,$getField,$getRegionDistrict)
									{
										return Posts::where('posts.posts_status','=','active')
											->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
											->join('channel','channel.id','=','posts_join_channel.channel_id')
											->where('channel.channel_status','=','active')
											->where('channel.channel_parent_id','=',$channelId)
											->join('channel_join_field','channel_join_field.channel_id','=','channel.id')
											->where('channel_join_field.field_id','=',$getField->id)
											->join('domain_join_channel','domain_join_channel.channel_id','=','channel.id')
											->join('channel_join_address','channel_join_address.channel_id','=','channel.id')
											->join('address_join_district','address_join_district.address_id','=','channel_join_address.address_id')
											->where('address_join_district.district_id','=',$getRegionDistrict->id)
											//->groupBy('posts.id')
											->orderBy('posts.posts_updated_at','desc')
											->select('posts.*')
											->paginate(18); 
									});
									$return=array(
										'getField'=>$getField,
										'region'=>$getRegion, 
										'subregion'=>$getSubRegion, 
										'getDistrict'=>$getRegionDistrict, 
										'channelList'=>$channelList, 
										'postListNew'=>$postListNew, 
										'companyRelate'=>''
									); 
									return $this->_theme->scope('regions.district', $return)->render();
								}else{
									$getRegionWard = Cache::store('memcached')->remember('getRegionWard_'.$getRegion->id.$getSlug[0].$slug1.$slug2, 500, function() use($getRegion,$getSlug,$slug1,$slug2)
									{
										return Region_ward::where('SolrID','=','/'.$getRegion->iso.'/'.$getSlug[0].'/'.$slug1.'/'.$slug2)->first();
									});
									if(!empty($getRegionWard->id)){
										if(!empty($getSlug[3])){
											$slug3=$getSlug[3]; 
										}else{
											$slug3=''; 
										}
										$getField=Fields::where('SolrID','=','/'.$slug3)->first(); 
										if(!empty($getField->id)){
											//$addressJoinWard=Address_join_ward::where('ward_id','=',$getRegionWard->id)->first(); 
											$channelList=array(); 
											$postListNew = Cache::store('memcached')->remember('postListNew_slug_field_ward'.$getField->id.'ward'.$getRegionWard->id, 5, function() use($channelId,$getField,$getRegionWard)
											{
												return Posts::where('posts.posts_status','=','active')
													->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
													->join('channel','channel.id','=','posts_join_channel.channel_id')
													->where('channel.channel_status','=','active')
													->where('channel.channel_parent_id','=',$channelId)
													->join('channel_join_field','channel_join_field.channel_id','=','channel.id')
													->where('channel_join_field.field_id','=',$getField->id)
													->join('domain_join_channel','domain_join_channel.channel_id','=','channel.id')
													->join('channel_join_address','channel_join_address.channel_id','=','channel.id')
													//->where('channel_join_address.address_id','=',$addressJoinWard->address->id)
													->join('address_join_ward','address_join_ward.address_id','=','channel_join_address.address_id')
													->where('address_join_ward.ward_id','=',$getRegionWard->id)
													//->groupBy('posts.id')
													->orderBy('posts.posts_updated_at','desc')
													->select('posts.*')
													->paginate(18); 
											}); 
											$companyRelate = Cache::store('memcached')->remember('companyRelate_slug_field_ward'.$getField->id.'ward'.$getRegionWard->id, 5, function() use($getRegionWard)
											{
												return Company::where('company.company_status','!=','delete')
													->where('company_ward',$getRegionWard->id)
													->orderBy('company.company_updated_at','desc')
													->select('company.*')
													->paginate(10);
											});
											$return=array(
												'getField'=>$getField,
												'region'=>$getRegion, 
												'subregion'=>$getSubRegion, 
												'getDistrict'=>$getRegionDistrict, 
												'getWard'=>$getRegionWard, 
												'channelList'=>$channelList, 
												'postListNew'=>$postListNew, 
												'companyRelate'=>$companyRelate
											); 
											return $this->_theme->scope('regions.ward', $return)->render();
										}else{
											$channelList=array(); 
											$postListNew = Cache::store('memcached')->remember('postListNew_slug_ward'.$getRegionWard->id, 5, function() use($channelId,$getRegionWard)
											{
												return Posts::where('posts.posts_status','=','active')
													->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
													->join('channel','channel.id','=','posts_join_channel.channel_id')
													->where('channel.channel_status','=','active')
													->where('channel.channel_parent_id','=',$channelId)
													->join('domain_join_channel','domain_join_channel.channel_id','=','channel.id')
													->join('channel_join_address','channel_join_address.channel_id','=','channel.id')
													->join('address_join_ward','address_join_ward.address_id','=','channel_join_address.address_id')
													->where('address_join_ward.ward_id','=',$getRegionWard->id)
													//->groupBy('posts.id')
													->orderBy('posts.posts_updated_at','desc')
													->select('posts.*')
													->paginate(18); 
											}); 
											$companyRelate = Cache::store('memcached')->remember('companyRelate_slug_ward'.$getRegionWard->id, 5, function() use($getRegionWard)
											{
												return Company::where('company.company_status','!=','delete')
													->where('company_ward',$getRegionWard->id)
													->orderBy('company.company_updated_at','desc')
													->select('company.*')
													->paginate(10);
											});
											$return=array(
												'getField'=>$getField,
												'region'=>$getRegion, 
												'subregion'=>$getSubRegion, 
												'getDistrict'=>$getRegionDistrict, 
												'getWard'=>$getRegionWard, 
												'channelList'=>$channelList, 
												'postListNew'=>$postListNew, 
												'companyRelate'=>$companyRelate
											); 
											return $this->_theme->scope('regions.ward', $return)->render();
										}
									}else{
										$channelList=array(); 
										$postListNew = Cache::store('memcached')->remember('postListNew_slug_district'.$getRegionDistrict->id, 5, function() use($channelId,$getRegionDistrict)
											{
												return Posts::where('posts.posts_status','=','active')
													->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
													->join('channel','channel.id','=','posts_join_channel.channel_id')
													->where('channel.channel_status','=','active')
													->where('channel.channel_parent_id','=',$channelId)
													->join('domain_join_channel','domain_join_channel.channel_id','=','channel.id')
													->join('channel_join_address','channel_join_address.channel_id','=','channel.id')
													->join('address_join_district','address_join_district.address_id','=','channel_join_address.address_id')
													->where('address_join_district.district_id','=',$getRegionDistrict->id)
													//->groupBy('posts.id')
													->orderBy('posts.posts_updated_at','desc')
													->select('posts.*')
													->paginate(18); 
											}); 
										$companyRelate = Cache::store('memcached')->remember('companyRelate_slug_district'.$getRegionDistrict->id, 5, function() use($getRegionDistrict)
											{
												return Company::where('company.company_status','!=','delete')
													->where('company_district',$getRegionDistrict->id)
													->orderBy('company.company_updated_at','desc')
													->select('company.*')
													->paginate(10); 
											});
										$return=array(
											'region'=>$getRegion, 
											'subregion'=>$getSubRegion, 
											'getDistrict'=>$getRegionDistrict, 
											'channelList'=>$channelList, 
											'postListNew'=>$postListNew, 
											'companyRelate'=>$companyRelate
										); 
										return $this->_theme->scope('regions.district', $return)->render();
									}
								}
							}else{
								$channelList=array(); 
								$postListNew = Cache::store('memcached')->remember('postListNew_slug_subregion'.$getSubRegion->id, 5, function() use($channelId,$getSubRegion)
									{
										return Posts::where('posts.posts_status','=','active')
										->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
										->join('channel','channel.id','=','posts_join_channel.channel_id')
										->where('channel.channel_status','=','active')
										//->where('channel.channel_parent_id','=',$this->_channel->id)
										->join('channel_join_field','channel_join_field.channel_id','=','channel.id')
										->join('domain_join_channel','domain_join_channel.channel_id','=','channel.id')
										->join('channel_join_address','channel_join_address.channel_id','=','channel.id')
										->join('address_join_subregion','address_join_subregion.address_id','=','channel_join_address.address_id')
										->where('address_join_subregion.subregion_id','=',$getSubRegion->id)
										//->groupBy('posts.id')
										->orderBy('posts.posts_updated_at','desc')
										->select('posts.*')
										->paginate(18); 
									}); 
								$companyRelate = Cache::store('memcached')->remember('companyRelate_slug_subregion'.$getSubRegion->id, 5, function() use($getSubRegion)
									{
										return Company::where('company.company_status','!=','delete')
											->where('company_subregion',$getSubRegion->id)
											->orderBy('company.company_updated_at','desc')
											->select('company.*')
											->paginate(10); 
									});
								$return=array(
									'region'=>$getRegion, 
									'subregion'=>$getSubRegion, 
									'channelList'=>$channelList, 
									'postListNew'=>$postListNew, 
									'companyRelate'=>$companyRelate
								); 
								return $this->_theme->scope('regions.subregion', $return)->render(); 
							}
						}
					}else{
						$getField=Fields::where('SolrID','=','/'.$getSlug[0])->first(); 
						if(!empty($getField->id)){
							$channelList=array(); 
							$postListNew = Cache::store('memcached')->remember('postListNew_slug_region'.$getRegion->id, 5, function() use($channelId,$getField,$getRegion)
							{
								return Posts::where('posts.posts_status','=','active')
									->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
									->join('channel','channel.id','=','posts_join_channel.channel_id')
									->where('channel.channel_status','=','active')
									->where('channel.channel_parent_id','=',$channelId)
									->join('channel_join_field','channel_join_field.channel_id','=','channel.id')
									->where('channel_join_field.field_id','=',$getField->id)
									->join('domain_join_channel','domain_join_channel.channel_id','=','channel.id')
									->join('channel_join_address','channel_join_address.channel_id','=','channel.id')
									->join('address_join_region','address_join_region.address_id','=','channel_join_address.address_id')
									->where('address_join_region.region_id','=',$getRegion->id)
									//->groupBy('posts.id')
									->orderBy('posts.posts_updated_at','desc')
									->select('posts.*')
									->paginate(4); 
							}); 
							$return=array(
								'getField'=>$getField,
								'region'=>$getRegion, 
								'channelList'=>$channelList, 
								'postListNew'=>$postListNew, 
								'companyRelate'=>''
							); 
							return $this->_theme->scope('regions.region', $return)->render();
						}else{
							//return Redirect::to('//'.$this->_domain->domain);
                            return Redirect::route('keyword.show',array($this->_domainPrimary,WebService::characterReplaceUrl($this->_parame['slug'])));
						}
					}
				}else{
					return Redirect::route('keyword.show',array($this->_domainPrimary,WebService::characterReplaceUrl($this->_parame['slug'])));
				}
			}
		}
	}
}