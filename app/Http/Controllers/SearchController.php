<?php 
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator; 
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request; 
use Illuminate\Support\Str;
use Auth;
use App\User; 
use App\Model\History; 
use App\Model\Category; 
use App\Model\Category_join;
use App\Model\Category_join_channel;
use App\Model\Keywords; 
use App\Model\News;
use App\Model\Posts;
use App\Model\Posts_join; 
use App\Model\Posts_join_channel;
use App\Model\Posts_join_category;
use App\Model\Posts_attribute; 
use App\Model\Posts_join_keywords; 
use App\Model\Affiliate_feed;
use App\Model\Slug; 
use App\Model\Media; 
use App\Model\Media_join;
use App\Model\Regions;
use App\Model\Subregions;
use App\Model\Region_district;
use App\Model\Region_ward;
use App\Model\Domain; 
use App\Model\Domain_join; 
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
use App\Model\Company; 
use App\Model\Company_join; 
use App\Model\Company_join_channel; 
use App\Model\Company_join_field; 
use App\Model\Company_join_address; 
use Carbon\Carbon; 
use WebService;
use DB;
use Response;
use Session;
use Redirect;
use File;  
class SearchController extends ConstructController
{
	public $_listArray=array();
	public $_listResult=array(); 
	public $_searchResult=array(); 
	public $_ads='false'; 
	public $_wordBlacklist; 
	public function __construct(){
		parent::__construct(); 
	}
	
	public function getSearchQuery(Request $request)
    {
		$this->_wordBlacklist=preg_split("/(\r\n|\n|\r)/",File::get('words_blacklist.txt')); 
		$type=addslashes($request->query('t')); 
		$id=addslashes($request->query('i')); 
		$string_search=addslashes($request->query('v')); 
		if(strlen($string_search)>2){
			if($this->_channel->channel_parent_id==0){
				if(WebService::is_valid_url($string_search)){
					$domainParser=$this->_parser->parseUrl($string_search); 
					if(!is_null($domainParser->host->registerableDomain) && $domainParser->host->registerableDomain!=config('app.url')){
						return Redirect::to('http://'.$domainParser->host->registerableDomain.'.'.config('app.url'));
					}
				}
				$postSearch=Posts::searchByQuery([
					'multi_match' => [
						'query' => $string_search,
						'fields' => ['posts_title','posts_title_convert'], 
						//'type'=>'phrase', 
						//'slop'=>3
					]
				], $aggregations = null, $sourceFields = null, $limit = 1000, $offset = null, $sort = null);  
				$results_post = collect($postSearch)->map(function($x,$y){return $x;})->toArray(); 
				foreach($results_post as $item){
					$this->_listResult['type']='post'; 
					$this->_listResult['id']=$item['id']; 
					$this->_listResult['title']=$item['posts_title']; 
					$this->_listResult['description']=$item['posts_description']; 
					array_push($this->_listArray,$this->_listResult);
				}
				$affiliateSearch=Affiliate_feed::searchByQuery([
					'multi_match' => [
						'query' => $string_search,
						'fields' => ['title','category'], 
						//'type'=>'phrase', 
						//'slop'=>3
					]
				], $aggregations = null, $sourceFields = null, $limit = 5, $offset = null, $sort = null);  
				$domainSearch=Domain::searchByQuery([
					'multi_match' => [
						'query' => $string_search,
						'fields' => ['domain_title','domain_description'], 
						//'type'=>'phrase', 
						//'slop'=>3
					]
				], $aggregations = null, $sourceFields = null, $limit = 10, $offset = null, $sort = null);   
				$newsSearch=News::searchByQuery([
					'multi_match' => [
						'query' => $string_search,
						'fields' => ['title','title_convert'], 
						//'type'=>'phrase', 
						//'slop'=>3
					]
				], $aggregations = null, $sourceFields = null, $limit = 10, $offset = null, $sort = null); 
				/*$results_domain = collect($domainSearch)->map(function($x,$y){return $x;})->toArray(); 
				foreach($results_domain as $item){
					$this->_listResult['type']='domain'; 
					$this->_listResult['id']=$item['id']; 
					$this->_listResult['title']=$item['domain_title']; 
					$this->_listResult['description']=$item['domain_description']; 
					array_push($this->_listArray,$this->_listResult);
				}*/
				$results=$this->_listArray; 
				
				$currentPage = LengthAwarePaginator::resolveCurrentPage(); 
				$itemCollection = collect($results); 
				//dd($domainSearch); 
				$perPage = 10; 
				$currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all(); 
				$paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage); 
				$paginatedItems->setPath(\Request::url()); 
				$paginatedItems->appends(\Request::query())->render(); 
				if(WebService::checkBlacklistWord($string_search, $this->_wordBlacklist))
				{
					$this->_ads='true'; 
				}
				$return=array(
					'keyword'=>$string_search, 
					'getItems'=>$paginatedItems, 
					'affiliateSearch'=>$affiliateSearch, 
					'domainSearch'=>$domainSearch, 
					'newsSearch'=>$newsSearch, 
					'getPosts'=>array(), 
					'ads'=>$this->_ads
				); 
				return $this->_theme->scope('search', $return)->render();
			}else{
				$getPost=DB::table('posts')
					->join('posts_join_channel', function($join)
					{
						$join->on('posts_join_channel.posts_id', '=', 'posts.id'); 
						$join->where('posts_join_channel.channel_id', '=', $this->_channel->id);
					})
					->where(function($query) use ($string_search,$id)
					{
						$query->where('posts.posts_title', 'like', '%'.$string_search.'%')
								->where('posts.id','!=',$id)
								->where('posts.posts_status','=','active');
					})
					->orWhere(function($query) use ($string_search,$id)
					{
						$query->where('posts.posts_title_convert', 'like', '%'.$string_search.'%')
								->where('posts.id','!=',$id)
								->where('posts.posts_status','=','active');
					})
					->groupBy('posts.id')
					->orderBy('posts.posts_updated_at','desc')
					->selectRaw("DISTINCT 'post' as type,`id` as `id`,`posts_title` as `value`"); 
				if($type=='post'){
					$post=DB::table('posts')
					->where('posts.id','=',$id)
					->where('posts.posts_status','=','active')
					->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
					->where('posts_join_channel.channel_id','=',$this->_channel->id)
					->groupBy('posts.id')
					->selectRaw("DISTINCT 'post' as type,`id` as `id`,`posts_title` as `value`")
					->take(1);  
					$results = $post->union($getPost)->get(); 
				}else{
					$results = $getPost->get(); 
				}
			}
			$currentPage = LengthAwarePaginator::resolveCurrentPage(); 
			$itemCollection = collect($results); 
			//dd($itemCollection); 
			$perPage = 10; 
			$currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all(); 
			$paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage); 
			$paginatedItems->setPath(\Request::url()); 
			$paginatedItems->appends(\Request::query())->render();
		}else{
			$paginatedItems=array(); 
		}
		$return=array(
			'keyword'=>$string_search, 
			'getItems'=>$paginatedItems, 
			'getPosts'=>array(), 
			'affiliateSearch'=>array(), 
			'domainSearch'=>array(), 
			'ads'=>$this->_ads
		); 
		return $this->_theme->scope('search', $return)->render();
	}
	public function searchAll(Request $request)
    {
		$string_search=addslashes($request->query('txt')); 
		if($this->_channel->channel_parent_id==0){
			$results=Posts::searchByQuery([
				'multi_match' => [
					'query' => $string_search,
					'fields' => ['posts_title','posts_title_convert'], 
					//'type'=>'phrase', 
					//'slop'=>3
				]
			], $aggregations = null, $sourceFields = null, $limit = 10, $offset = null, $sort = null);  
			$results_select = collect($results)->map(function($x,$y){return $x;})->toArray(); 
			foreach($results_select as $item){
				$this->_listResult['type']='post'; 
				$this->_listResult['id']=$item['id']; 
				$this->_listResult['value']=$item['posts_title']; 
				//$this->_listResult['description']=$item['posts_description']; 
				array_push($this->_listArray,$this->_listResult);
			}
			$newsSearch=News::searchByQuery([
					'multi_match' => [
						'query' => $string_search,
						'fields' => ['title','title_convert'], 
						//'type'=>'phrase', 
						//'slop'=>3
					]
				], $aggregations = null, $sourceFields = null, $limit = 10, $offset = null, $sort = null); 
			$results_select = collect($newsSearch)->map(function($x,$y){return $x;})->toArray(); 
			foreach($results_select as $item){
				$this->_listResult['type']='news'; 
				$this->_listResult['id']=$item['id']; 
				$this->_listResult['value']=$item['title']; 
				//$this->_listResult['description']=$item['posts_description']; 
				array_push($this->_listArray,$this->_listResult);
			}
			/*$domain=DB::table('domains')
				->orWhere('domain', 'like', '%'.$string_search.'%')
				//->orWhere('posts_title_convert', 'like', '%'.$string_search.'%')
				->where('status','!=','delete')
				->where('status','!=','blacklist')
				->selectRaw("DISTINCT 'domain' as type,`id` as `id`,`domain` as `value`")
				->orderBy('domain','ASC')
				->take(10); 
			$keyword=DB::table('keywords')
				->orWhere('keyword', 'like', '%'.$string_search.'%')
				//->orWhere('posts_title_convert', 'like', '%'.$string_search.'%')
				->where('status','=','active')
				//->where('status','!=','blacklist')
				->selectRaw("DISTINCT 'keyword' as type,`id` as `id`,`keyword` as `value`")
				->orderBy('keyword','ASC')
				->take(10); 
			$company=DB::table('company')
				->orWhere('company_name', 'like', '%'.$string_search.'%')
				->orWhere('company_tax_code', 'like', '%'.$string_search.'%')
				->where('company_status','=','active')
				->selectRaw("DISTINCT 'company' as type,`id` as `id`,`company_name` as `value`")
				->orderBy('company_name','ASC')
				->take(10);
			*/
			//$results = $domain->union($keyword)->union($company)->get();
			$results_select = collect($this->_listArray)->map(function($x,$y){return $x;})->toArray(); 
		}else{
			$channelId=$this->_channel->id; 
			$post=DB::table('posts as posts')
				->join('posts_join_channel', function($join)
				{
					$join->on('posts_join_channel.posts_id', '=', 'posts.id'); 
					$join->where('posts_join_channel.channel_id', '=', $this->_channel->id);
				})
				->where(function($query) use ($string_search)
				{
					$query->where('posts.posts_title', 'like', '%'.$string_search.'%')
							->where('posts.posts_status','=','active');
				})
				->orWhere(function($query) use ($string_search)
				{
					$query->where('posts.posts_title_convert', 'like', '%'.$string_search.'%')
							->where('posts.posts_status','=','active');
				})
				->groupBy('posts.id')
				->orderBy('posts.posts_updated_at','desc')
				->selectRaw("DISTINCT 'post' as type,`id` as `id`,`posts_title` as `value`")
				->take(10); 
			$results_select = collect($post->get())->map(function($x,$y){return $x;})->toArray(); 
		}
		$array_search=array("suggestions" => $results_select);	
		return Response::json($array_search);
	}
	public function searchCompany(Request $request)
    {
		$string_search=addslashes($request->query('txt'));
		$company=DB::table('company')
			->orWhere('company_name', 'like', '%'.$string_search.'%')
			->orWhere('company_tax_code', 'like', '%'.$string_search.'%')
			->select('company_name as value','id as data','company_tax_code as tax_code')
			//->orderByRaw("MATCH(name,email) AGAINST (?) DESC",array($string_search))
			->orderBy('company_name','ASC')
			->take(10)
			->get();
		$company = collect($company)->map(function($x){ return (array) $x; })->toArray(); 
		$array_tags=array("suggestions" => $company);	
		return Response::json($array_tags);
	}
	public function searchSlug()
    {
		$getKeyword=Keywords::where('slug','=',$this->_parame['slug'])->first(); 
		if(!empty($getKeyword->id)){
			//return Redirect::route('keyword.show',array(config('app.url'),urlencode(str_replace(' ', '+', $getKeyword->keyword))));
			$getPosts=Posts::where('posts.posts_status','=','active')
				->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
				->where('posts_join_channel.channel_id','=',$this->_channel->id)
				->join('posts_join_keywords','posts_join_keywords.post_id','=','posts.id')
				->where('posts_join_keywords.keyword_id','=',$getKeyword->id)
				->groupBy('posts.id')
				->select('posts.*')
				->orderBy('posts.posts_updated_at','desc')
				->paginate(20); 
			$return=array(
				'keyword'=>$getKeyword->keyword, 
				'getPosts'=>$getPosts, 
				'getItems'=>array(), 
			); 
			return $this->_theme->scope('search', $return)->render();
		}else{
			$view = array(); 
			return $this->_theme->scope('404', $view)->render(); 
		}
	}
	public function checkSearch($pram,$id)
    {
		if($pram=='channel'){
			$template=Template_setting::where('id',$id)
			->where('date_end','>',Carbon::now()->format('Y-m-d H:i:s'))
			->first(); 
			if(isset($template->domain)){
				$getDomain=Domain::where('site_id','=',$template->id)->where('status','=','active')->where('domain_primary','=','default')->first(); 
				if(isset($getDomain->domain)){
					$domainPrimary=$getDomain->domain; 
				}else{
					$domainPrimary=$template->domain; 
				}
				return redirect('//'.$domainPrimary);
			}else{
				return view('404'); 
			}
		}else if($pram=='company'){
			 $customer=Cp_customers::where('id_auto','=',$id)->first(); 
			 if(isset($customer->customers_company)){
				 return redirect()->route('front.customer.details',array($customer->id_auto,Str::slug($customer->customers_company))); 
			 }else{
				 return view('404'); 
			 }
		}else if($pram=='post'){
			$ads = DB::table('ads')->where('ads.id','=',$id)->first(); 
			if(isset($ads->id)){
				$template=Template_setting::where('id',$ads->template_setting_id)
				->where('date_end','>',Carbon::now()->format('Y-m-d H:i:s'))
				->first(); 
				if(isset($template->domain)){
					return redirect()->route('front.ads.detail',array(Regions::find($template->regionsID)->iso,Subregions::find($template->subRegionsID)->subregions_name_slug,$ads->ads_slug)); 
				}else{
					return view('404')-> with('fail_msg','Bài viết '.$ads->ads_title.' có thể bị xóa hoặc hết hạn! ');
				}
			}else{
				return view('404')-> with('fail_msg','Bài viết có thể bị xóa!  '); 
			}
		}
	}
	public function searchContent($txt){
		$string_search=addslashes($txt);
		$search = $string_search;
		$customer_cp=DB::table('cp_customers')->whereRaw('MATCH (tax_code, customers_company,customers_name) AGAINST (?)' , array($search))
			->selectRaw("DISTINCT 'company' as type,`id_auto` as `id`,`tax_code` as `value`")
			->orderByRaw("MATCH(tax_code, customers_company,customers_name) AGAINST (?) DESC",array($search))
			->groupBy('id_auto')
			->take(50);
		$ads_cp=DB::table('ads')->whereRaw('MATCH (ads_title,ads_slug) AGAINST (?)' , array($search))	
			->selectRaw("DISTINCT  'post' as `type`,`id`, `ads_title` as `value`")
			->orderByRaw("MATCH(ads_title,ads_slug) AGAINST (?) DESC",array($search))
			->groupBy('id')
			->take(50);
		$template_cp=DB::table('template_setting')->whereRaw('MATCH (title,title_shop_slug) AGAINST (?)' , array($search))
			->selectRaw("DISTINCT 'channel' as `type`,`id`, `title` as `value`")
			->orderByRaw("MATCH(title,title_shop_slug) AGAINST (?) DESC",array($search))
			->groupBy('id')
			->take(50);
		$results = $template_cp->union($ads_cp)->union($customer_cp)->get();
		if(count($results)>0){
			foreach($results as $value){
				if($value->type=='channel'){
					$template=Template_setting::where('id',$value->id)
					->where('date_end','>',Carbon::now()->format('Y-m-d H:i:s'))
					->first(); 
					if(isset($template->id)){
						$getDomain=Domain::where('site_id','=',$template->id)->where('status','=','active')->where('domain_primary','=','default')->first(); 
						if(isset($getDomain->domain)){
							$domainPrimary=$getDomain->domain; 
						}else{
							$domainPrimary=$template->domain; 
						}
						$arrayResult[]=array(
							'title'=>$template->title, 
							'image'=>$template->logo, 
							'updated_at'=>$template->updated_at, 
							'description'=>$template->description,
							'url'=>'//'.$domainPrimary, 
						);
					} 
				}else if($value->type=='post'){
					$ads = DB::table('ads')->where('ads.id','=',$value->id)->first(); 
					if(isset($ads->id)){
						$template=Template_setting::where('id',$ads->template_setting_id)
						->where('date_end','>',Carbon::now()->format('Y-m-d H:i:s'))
						->first();
						if(isset($template->domain)){
							$arrayResult[]=array(
								'title'=>$ads->ads_title, 
								'image'=>$ads->ads_thumbnail, 
								'updated_at'=>$ads->updated_at, 
								'description'=>$ads->ads_description,
								'url'=>route('front.ads.detail',array(Regions::find($template->regionsID)->iso,Subregions::find($template->subRegionsID)->subregions_name_slug,$ads->ads_slug)), 
							); 
						}
					}
				}else if($value->type=='company'){
					$customer=Cp_customers::where('id_auto','=',$value->id)->first(); 
					if(isset($customer->id_auto)){
						$arrayResult[]=array(
							'title'=>$customer->customers_company, 
							'image'=>'', 
							'updated_at'=>$customer->customers_op_at, 
							'description'=>$customer->address, 
							'url'=>route('front.customer.details',array($customer->id_auto,Str::slug($customer->customers_company)))
						); 
					}
				}
			}
			$currentPage = LengthAwarePaginator::resolveCurrentPage(); 
			$itemCollection = collect($arrayResult);
			$perPage = 10; 
			$currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all(); 
			$paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage); 
			$paginatedItems->setPath(\Request::url());
			return view('front.search.search_content', ['items' => $paginatedItems,'txt'=>$txt]); 
		}else{
			return view('front.search.search_content', ['items' => null,'txt'=>$txt]); 
		}
    }
	public function search_all(Request $request){
		$string_search=$request->query('txt');
		$string_search=addslashes($string_search);
		$search = $string_search;
		$customer_cp=DB::table('cp_customers')->whereRaw('MATCH (tax_code, customers_company, customers_name) AGAINST (?)' , array($search))
			->where('tax_code','!=',"")
			->selectRaw("DISTINCT concat('company','-',`id_auto`) as `data`,`customers_company` as `value`")
			->orderByRaw("MATCH(tax_code, customers_company, customers_name) AGAINST (?) DESC",array($search))
			->groupBy('id_auto')
			->take(8);
		$ads_cp=DB::table('ads')
			->whereRaw('MATCH (ads_title,ads_slug) AGAINST (?)' , array($search)) 
			->selectRaw("DISTINCT  concat('post','-',`id`) as `data`, `ads_title` as `value`")
			->orderByRaw("MATCH(ads_title,ads_slug) AGAINST (?) DESC",array($search))
			->groupBy('id')
			->take(8);
		$template_cp=DB::table('template_setting')
			->where('date_end','>',Carbon::now()->format('Y-m-d H:i:s'))
			->whereRaw('MATCH (title,title_shop_slug) AGAINST (?)' , array($search))
			->selectRaw("DISTINCT concat('channel','-',`id`) as `data`, `title` as `value`")
			->orderByRaw("MATCH(title,title_shop_slug) AGAINST (?) DESC",array($search))
			->groupBy('id')
			->take(8);
		$results = $template_cp->union($ads_cp)->union($customer_cp)->get();
		$results_select = collect($results)->map(function($x,$y){return $x;})->toArray(); 
		//dd($results_select);
		$array_search=array("suggestions" => $results_select);	
		
		
		return Response::json($array_search);
		
	}
	public function search_member(Request $request){
		if(Auth::check()){
			$string_search=$request->query('txt');
			$string_search=addslashes($string_search);
			$users=DB::table('users')
				->orWhere('name', 'like', '%'.$string_search.'%')
				->orWhere('email', 'like', '%'.$string_search.'%')
				->orWhere('user_phone', 'like', '%'.$string_search.'%')
				->select('name as value','id as data','avata as avata')
				//->orderByRaw("MATCH(name,email) AGAINST (?) DESC",array($string_search))
				->orderBy('name','ASC')
				->get();
			$users = collect($users)->map(function($x){ return (array) $x; })->toArray(); 
			$array_tags=array("suggestions" => $users);	
			return Response::json($array_tags);
			//$result=json_encode($tags);
			//return $result;
		}
	}
}