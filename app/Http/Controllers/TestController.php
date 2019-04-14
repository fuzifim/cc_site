<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Regions;
use Request;
use Input;
use App\User;
use App\Model\Media;
use App\Model\Ads;
use App\Model\Template_setting;
use Auth;
use Illuminate\Support\Str;
use Validator;
use DB;
use Image;
use Imagick;
use ImagickDraw;
use File;
use Youtube;
use Response; 
use WebService; 
use Carbon\Carbon; 
use Pdp\PublicSuffixListManager; 
use App\Model\Posts;
use Pdp\Parser; 
use App\Http\Controllers\MediaController;
use Cart; 
use FFMpeg; 
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use LaravelLocalization; 
use App\Model\Category;
use App\Model\Category_join; 
use App\Model\Category_join_channel;
use App\Model\Category_attribute;
use App\Model\Category_join_parent; 
use App\Model\SiteLink;
use App\Model\Site_attribute;
use App\Model\Site_url; 
use ParserXml; 
use App\Model\Fields;
use App\Model\Keywords; 
use App\Model\Company; 
use App\Model\Domain;
use App\Model\Domain_attribute; 
use Lullabot\AMP\AMP;
use Lullabot\AMP\Validate\Scope;
use App\Model\Channel; 
use App\Model\Channel_role; 
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use DonatelloZa\RakePlus\RakePlus;
use Cpanel; 
use CpanelWhm; 
use Session; 
use Cache;
use App\Model\Affiliate_feed; 
use App\Model\Affiliate_data; 
use App\Model\Domain_info; 
use App\Model\Domain_zcom; 
use App\Model\Domain_data; 
use App\Model\Domain_crv; 
use App\Model\Domain_mongo; 
use App\Model\Domain_join_channel; 
use App\Model\News; 
use App\Model\Feed_rss; 
use App\Model\Post_result; 
use App\Model\Channel_result; 
use Adfly; 
use Storage; 
use Tracker;
use Illuminate\Support\Facades\Hash; 
use App\Model\Node; 
use App\Model\Note; 
class TestController extends ConstructController
{
	public $_postSearch;  
	public $_videoSearch; 
	public $_domain_title;
	public $_domain_description;
	public $_domain_keywords;
	public $_domain_image;
	public $_domain_h1; 
	public $_domain_h2; 
	public $_domain_h3; 
	public $_domain_h4; 
	public $_domain_a; 
	public $_domain_ip;
	public $_domainWhois; 
	public function __construct(){
		parent::__construct(); 
	}
	public function postApi(){
		return response()->json();
	}
	public function test(){
//        $getSite=DB::connection('mongodb')->collection('mongo_keyword')
//            ->where('craw_next','step_5')
//            //->where('image_size','exists',true)
//            //->orderBy('updated_at','desc')
//            ->limit(5)->get();
//        dd($getSite);
        $getSite= DB::connection('mongodb')->collection('mongo_keyword')
            ->whereNotExists(function($query)
            {
                $query->select(DB::raw(1))
                    ->from('post_to_social')
                    ->whereRaw('post_to_social.post_id = mongo_keyword._id');
                    //->where('post_id')
                    //->whereRaw("id = '100'");
            })
            ->limit(5)
            ->get();
        dd($getSite);
	}
	public function test2222222222222222222222(){
		//dd(dns_get_record('cungcap.net',DNS_ALL)); 
		$getChannel=Channel::where('insert_by','!=','company')->where('move_result','<',2)->limit(100)->get(); 
		$channelArray=[]; 
		$channelArr=[];
		foreach($getChannel as $channel){
			//dd($channel); 
			$channelArr['id']=$channel->id;
			$channelArr['title']=$channel->channel_name; 
			$channelArr['description']=$channel->channel_description; 
			$channelArr['keywords']=$channel->channel_keywords; 
			$channelArr['user_id']=$channel->user_id; 
			$channelArr['view']=$channel->channel_view; 
			$channelArr['created_at']=$channel->channel_created_at; 
			$channelArr['updated_at']=$channel->channel_updated_at; 
			if(!empty($channel->channelAttributeLogo->media->media_name)){
				$channelMedia=$channel->channelAttributeLogo->media; 
				$channelArr['logo']=array(
					'url'=>'//img.cungcap.net/'.$channelMedia->media_path.$channelMedia->media_name, 
					'url_thumb'=>'//img.cungcap.net/'.$channelMedia->media_path.'thumb/'.$channelMedia->media_name, 
					'url_small'=>'//img.cungcap.net/'.$channelMedia->media_path.'small/'.$channelMedia->media_name, 
					'url_xs'=>'//img.cungcap.net/'.$channelMedia->media_path.'xs/'.$channelMedia->media_name, 
				); 
			}else{
				$channelArr['logo']=[]; 
			}
			if(count($channel->channelAttributeBanner)>0){
				$bannerArray=[]; 
				$bannerArr=[]; 
				foreach($channel->channelAttributeBanner as $banner){
					if(!empty($banner->media->media_name)){
						$bannerArr['url']='//img.cungcap.net/'.$banner->media->media_path.$banner->media->media_name; 
						$bannerArr['url_thumb']='//img.cungcap.net/'.$banner->media->media_path.'thumb/'.$banner->media->media_name; 
						$bannerArr['url_small']='//img.cungcap.net/'.$banner->media->media_path.'small/'.$banner->media->media_name; 
						$bannerArr['url_xs']='//img.cungcap.net/'.$banner->media->media_path.'xs/'.$banner->media->media_name; 
						array_push($bannerArray,$bannerArr);
					}
				}
				$channelArr['banner']=$bannerArray; 
			}else{
				$channelArr['banner']=[]; 
			}
			 
			if(count($channel->joinAddress)>0){
				$addressArray=[]; 
				$addressArr=[]; 
				foreach($channel->joinAddress as $joinAddress){
					if(!empty($joinAddress->address->joinWard->ward->id)){
						$ward=', '.$joinAddress->address->joinWard->ward->ward_name; 
					}else{
						$ward='';
					}
					if(!empty($joinAddress->address->joinDistrict->district->id)){
						$district=', '.$joinAddress->address->joinDistrict->district->district_name; 
					}else{
						$district=''; 
					}
					if(!empty($joinAddress->address->address)){$addressArr['address']=$joinAddress->address->address.$ward.$district; }
					if(!empty($joinAddress->address->joinRegion->region->id)){$addressArr['region']=$joinAddress->address->joinRegion->region->id; }
					if(!empty($joinAddress->address->joinSubRegion->subregion->id)){$addressArr['subregion']=$joinAddress->address->joinSubRegion->subregion->id; }
					array_push($addressArray,$addressArr);
				}
				$channelArr['address']=$addressArray; 
			}else{
				$channelArr['address']=[];
			}
			
			if(count($channel->joinEmail)>0){
				$emailArray=[]; 
				$emailArr=[]; 
				foreach($channel->joinEmail as $joinEmail){
					$emailArr['email']=$joinEmail->email->email_address; 
					array_push($emailArray,$emailArr);
				}
				$channelArr['email']=$emailArray; 
			}else{
				$channelArr['email']=[]; 
			}
			
			if(count($channel->joinPhone)>0){
				$phoneArray=[]; 
				$phoneArr=[]; 
				foreach($channel->joinPhone as $joinPhone){
					$phoneArr['phone']=$joinPhone->phone->phone_number; 
					array_push($phoneArray,$phoneArr);
				}
				$channelArr['phone']=$phoneArray; 
			}else{
				$channelArr['phone']=[]; 
			}
			
			if(count($channel->domainAll)>0){
				$domainArray=[]; 
				$domainArr=[]; 
				foreach($channel->domainAll as $domain){
					$domainArr['domain']=$domain->domain->domain; 
					$domainArr['location']=$domain->domain->domain_location; 
					$domainArr['primary']=$domain->domain->domain_primary; 
					array_push($domainArray,$domainArr);
				}
				$channelArr['domain']=$domainArray; 
			}else{
				$channelArr['domain']=[]; 
			}
			
			if(count($channel->joinCategory)>0){
				$categoryArray=[]; 
				$categoryArr=[]; 
				foreach($channel->joinCategory as $joinCategory){
					$categoryArr['name']=$joinCategory->category->category_name; 
					if(!empty($joinCategory->category->parent_id)){
						$getCategory=Category::find($joinCategory->category->parent_id); 
						if(!empty($getCategory->category_name)){
							$categoryArr['parent']=$getCategory->category_name; 
						}
					}
					array_push($categoryArray,$categoryArr);
				}
				$channelArr['category']=$categoryArray; 
			}else{
				$channelArr['category']=[]; 
			}
			//dd(123); 
			$data=array(
				'data'=>json_encode($channelArr)
			); 
			Channel_result::insertGetId($data); 
			$channel->move_result=2; 
			$channel->save(); 
			//array_push($channelArray,$channelArr);
		} 
		dd($channelArray); 
	}
	public function test23456434567(){
		//dd(Post_result::all()); 
		$getPost=Posts::where('move_result','<',2)->limit(500)->get(); 
		$postArray=[]; 
		$postArr=[]; 
		$mediaArray=[];  
		$categoryArray=[]; 
		foreach($getPost as $post){
			if(!empty($post->posts_title) && !empty($post->getSlug->slug_value)){
				if(count($post->gallery)){
					$mediaArray=[]; 
					$mediaArr=[]; 
					foreach($post->gallery as $media){
						if($media->media->media_storage=='video'){
							$mediaArr['type']='video'; 
							$mediaArr['url']='//img.cungcap.net/'.$media->media->media_path.$media->media->media_name; 
						}else if($media->media->media_storage=='files'){
							$mediaArr['type']='file'; 
							$mediaArr['url']='//img.cungcap.net/'.$media->media->media_path.$media->media->media_name; 
						}else if($media->media->media_storage=='youtube'){
							$mediaArr['type']='youtube'; 
							$mediaArr['url']=$media->media->media_name; 
						}else if($media->media->media_storage=='local'){
							$mediaArr['type']='image'; 
							$mediaArr['url']='//img.cungcap.net/'.$media->media->media_path.$media->media->media_name; 
							$mediaArr['url_thumb']='//img.cungcap.net/'.$media->media->media_path.'thumb/'.$media->media->media_name; 
							$mediaArr['url_small']='//img.cungcap.net/'.$media->media->media_path.'small/'.$media->media->media_name; 
							$mediaArr['url_xs']='//img.cungcap.net/'.$media->media->media_path.'xs/'.$media->media->media_name; 
						}
						array_push($mediaArray,$mediaArr);
					}
				}
				if(count($post->postsJoinCategory)){
					$categoryArray=[]; 
					$categoryArr=[]; 
					foreach($post->postsJoinCategory as $joinCategory){
						$categoryArr['id']=$joinCategory->getCategory->id; 
						$categoryArr['name']=$joinCategory->getCategory->category_name; 
						if(!empty($joinCategory->getCategory->getSlug->slug_value)){
							$categoryArr['slug']=$joinCategory->getCategory->getSlug->slug_value; 
						}
						array_push($categoryArray,$categoryArr);
					}
				}
				if(!empty($post->postsJoinChannel->channel->id)){
					$joinChannelId=$post->postsJoinChannel->channel->id; 
				}else{
					$joinChannelId=0; 
				}
				$data=array(
					'title'=>$post->posts_title, 
					'slug'=>$post->getSlug->slug_value, 
					'user_id'=>$post->user_id, 
					'channel_id'=>$joinChannelId, 
					'category'=>json_encode($categoryArray), 
					'content'=>$post->posts_description, 
					'media'=>json_encode($mediaArray), 
					'view'=>$post->posts_view, 
					'created_at'=>$post->posts_created_at, 
					'updated_at'=>$post->posts_updated_at, 
					'status'=>$post->posts_status
				); 
				Post_result::insertGetId($data); 
			}
			/*$postArr['title']=$post->posts_title; 
			$postArr['slug']=$post->getSlug->slug_value; 
			$postArr['user_id']=$post->user_id; 
			$postArr['channel_id']=$post->postsJoinChannel->channel->id; 
			$postArr['category']=$categoryArray; 
			$postArr['content']=$post->posts_description; 
			$postArr['media']=$mediaArray; 
			$postArr['view']=$post->posts_view; 
			$postArr['created_at']=$post->posts_created_at; 
			$postArr['updated-at']=$post->posts_updated_at; 
			$postArr['status']=$post->posts_status; 
			array_push($postArray,$postArr);*/
			$post->move_result=2; 
			$post->save(); 
			echo $post->posts_title.'<p>';
		}
		//dd($postArray); 
	}
	public function test99999999999(){
		$getDomain=Domain::where('move_mongo','<',7)->where('insert_by','!=','company')->limit(2000)->get(); 
		foreach($getDomain as $domain){ 
			$node=Node::where('type','domain')->where('old_id',$domain->id)->first(); 
			if(!empty($node->domain)){
				$node->title=$domain->domain_title; 
				$node->save(); 
			}
			$domain->move_mongo=7; 
			$domain->save(); 
		}
	}
	public function test214142(){ 
	dd(Node::where('type','news')->orderBy('_id','acs')->limit(10)->get()); 
		$getNews=News::where('move_mongo','<',1)->limit(1000)->get(); 
		foreach($getNews as $news){
			$data=array(
				'old_id'=>$news->id, 
				'type'=>'news', 
				'title'=>$news->title,
				'title_encode'=>base64_encode($news->title), 
				'title_convert'=>$news->title_convert,
				'description'=>$news->description, 
				'content'=>$news->content, 
				'image'=>$news->image, 
				'attribute'=>array(
					'source_url'=>$news->source_url
				), 
				'view'=>$news->view, 
				'field'=>$news->field, 
				'created_at'=>$news->created_at,
				'updated_at'=>$news->updated_at, 
				'status'=>$news->status
			); 
			Node::insertGetId($data); 
			$news->move_mongo=6; 
			$news->save(); 
		}
	}
	public function test678987654(){ 
		//dd(Node::orderBy('_id','acs')->limit(10)->get()); 
		$getDomain=Domain::where('move_mongo','<',7)->where('insert_by','!=','company')->limit(2000)->get(); 
		if(count($getDomain)){
			foreach($getDomain as $domain){
				$data=array(
					'old_id'=>$domain->id, 
					'type'=>'domain', 
					'domain'=>$domain->domain, 
					'domain_encode'=>base64_encode($domain->domain),
					'title'=>$domain->domain_title,
					'title_encode'=>base64_encode($domain->domain_title),
					'description'=>$domain->domain_description, 
					'keywords'=>$domain->domain_keywords, 
					'image'=>$domain->domain_image, 
					'attribute'=>array(
						'content'=>$domain->content, 
						'rank'=>$domain->rank, 
						'rank_country'=>$domain->rank_country, 
						'country_code'=>$domain->country_code, 
						'ads'=>$domain->ads, 
						'ssl_active'=>$domain->ssl_active, 
						'service_attribute_id'=>$domain->service_attribute_id
					), 
					'view'=>$domain->view, 
					'user_id'=>$domain->user_id, 
					'created_at'=>$domain->created_at,
					'updated_at'=>$domain->updated_at, 
					'expiration_date'=>$domain->date_end, 
					'replay'=>0, 
					'status'=>$domain->status
				); 
				Node::insertGetId($data); 
				$domain->move_mongo=6; 
				$domain->save(); 
			}
		}else{
			$getCompany=Company::where('move_mongo','<',1)->limit(2000)->get(); 
			if(count($getCompany)){
				foreach($getCompany as $company){
					$data=array(
						'old_id'=>$company->id, 
						'type'=>'company', 
						'title'=>$company->company_name, 
						'title_encode'=>base64_encode($company->company_name), 
						'address'=>$company->company_address, 
						'view'=>$company->company_views,
						'attribute'=>array(
							'title_en'=>$company->company_name_en, 
							'nganh_chinh'=>$company->nganh_chinh, 
							'SolrID'=>$company->SolrID, 
							'region'=>$company->company_region, 
							'subregion'=>$company->company_subregion, 
							'district'=>$company->company_district, 
							'ward'=>$company->_company_ward, 
							'tax_code'=>$company->company_tax_code, 
							'ngay_cap'=>$company->ngay_cap, 
							'admin_name'=>$company->admin_name, 
							'admin_phone'=>$company->admin_phone, 
							'admin_email'=>$company->admin_email,
						), 
						'created_at'=>$company->company_created_at, 
						'updated_at'=>$company->company_updated_at, 
						'status'=>$company->company_status
					); 
					Node::insertGetId($data); 
					$company->move_mongo=2; 
					$company->save(); 
				}
			}else{
				$getAffiliate=Affiliate_feed::where('campaign','!=','aliexpress.com')->where('move_mongo','<',1)->limit(2000)->get(); 
				foreach($getAffiliate as $aff){
					$data=array(
						'old_id'=>$aff->id, 
						'type'=>'affiliate', 
						'title'=>$aff->title, 
						'title_encode'=>base64_encode($aff->title), 
						'description'=>$aff->description, 
						'image'=>$aff->image, 
						'attribute'=>array(
							'sku'=>$aff->sku, 
							'price'=>$aff->price, 
							'discount'=>$aff->discount, 
							'url'=>$aff->url, 
							'deeplink'=>$aff->deeplink, 
						), 
						'campaign'=>$aff->campaign, 
						'from'=>$aff->by_from, 
						'created_at'=>$aff->created_at, 
						'updated_at'=>$aff->updated_at, 
						'status'=>$aff->status
					); 
					Node::insertGetId($data); 
					$aff->move_mongo=2; 
					$aff->save(); 
				}
			}
		}
	}
	function filter_by_value ($array, $index, $value){ 
        if(is_array($array) && count($array)>0)  
        { 
            foreach(array_keys($array) as $key){ 
                $temp[$key] = $array[$key][$index]; 
                 
                if ($temp[$key] == $value){ 
                    $newarray[$key] = $array[$key]; 
                } 
            } 
          } 
      return $newarray; 
    }
	public function test111111(){
		$getFeed=File::get('affiliate/datafeed_nguyenkim.com_20180716.xml'); 
		echo 123;
		$convertXml=ParserXml::xml($getFeed); 
		dd($convertXml); 
		foreach($convertXml['products']['product'] as $product){
			dd($product); 
			$checkExit=Affiliate_feed::where('title_encode','=',base64_encode($product['name']))->first(); 
			if(empty($checkExit->title)){
				Affiliate_feed::insertGetId(array(
					'category'=>$product['category'], 
					'title'=>$product['name'], 
					'title_encode'=>base64_encode($product['name']), 
					//'description'=>$product['description'], 
					'image'=>$product['image'], 
					'sku'=>$product['sku'], 
					'price'=>$product['price'], 
					'discount'=>$product['discount'], 
					'url'=>$product['url'], 
					'deeplink'=>$product['deeplink'], 
					'campaign'=>'nguyenkim.com', 
					'by_from'=>'accesstrade.vn', 
					'location'=>'vn', 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'status'=>'active', 
					'index_z'=>0
				)); 
				echo $product['name'].'<p>';
			}
		}
	}
	public function test76577(){
		$getDomain=Affiliate_data::all(); 
		dd(count($getDomain)); 
	}
	public function test23444(){ 
		$keyword='Danh sách đồ :10k thức uống, thức ăn'; 
		$getKey=RakePlus::create($keyword)->sort('asc')->get(); 
		dd($getKey); 
		foreach($getKey as $keyGet){
			$rake = RakePlus::create($keyGet)->keywords(); 
			$url=''; 
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
				if(count($content[1])){
					foreach($content[1] as $value){
						echo $value.'<p>';
					}
				}
			}
		}
	}
	public function importData(){
		return false; 
		//$keyIndex=Keywords::search('huong dan tao'); 
		//dd($keyIndex); 
		//$keyIndex->addToIndex();
		//$search=Affiliate_feed::all(); 
		//$search->addToIndex();
		//dd(123);   
		$setIndex=Posts::where('e_index','<',1)->where('posts_status','=','active')->take(100)->get(); 
		foreach($setIndex as $index){
			$index->addToIndex();
			$index->e_index=1; 
			$index->save(); 
			echo $index->posts_title.'<p>';
		}
		$domainIndex=Domain::where('domain_title','<>', '')
			->where('domain_description','<>', '')
			->where('status','=','active')
			->where('e_index','<',1)
			->take(100)->get(); 
		foreach($domainIndex as $index){
			$index->addToIndex();
			$index->e_index=1; 
			$index->save(); 
		}
		$newsIndex=News::where('title','<>', '')
			->where('status','=','active')
			->where('e_index','<',1)
			->take(100)->get(); 
		foreach($newsIndex as $index){
			$index->addToIndex();
			$index->e_index=1; 
			$index->save(); 
		}
		$getCompany=Company::where('index',0)->limit(100)->get(); 
		foreach($getCompany as $company){
			$company->addToIndex();
			$company->index=1; 
			$company->save(); 
		}
		$getFeed=Feed_rss::where('index',0)->limit(100)->get(); 
		foreach($getFeed as $feed){
			$feed->addToIndex();
			$feed->index=1; 
			$feed->save(); 
		}
	}
	public function test42343(){
		$getDomainAttribute=Domain_attribute::take(5000)->get(); 
		$status='false';
		foreach($getDomainAttribute as $domainAttribute){
			if($domainAttribute->attribute_type=='title'){
				$this->_domain_title=strip_tags($domainAttribute->attribute_value,''); 
				$status='true';
			}
			if($domainAttribute->attribute_type=='description'){
				$this->_domain_description=strip_tags($domainAttribute->attribute_value,''); 
				$status='true';
			}
			if($domainAttribute->attribute_type=='keywords'){
				$this->_domain_keywords=strip_tags($domainAttribute->attribute_value,''); 
				$status='true';
			}
			if($domainAttribute->attribute_type=='image'){
				$this->_domain_image=$domainAttribute->attribute_value; 
				$status='true';
			}
			if($domainAttribute->attribute_type=='h1tag'){
				$this->_domain_h1=strip_tags($domainAttribute->attribute_value,''); 
				$status='true';
			}
			if($domainAttribute->attribute_type=='h2tag'){
				$this->_domain_h2=strip_tags($domainAttribute->attribute_value,''); 
				$status='true';
			}
			if($domainAttribute->attribute_type=='h3tag'){
				$this->_domain_h3=strip_tags($domainAttribute->attribute_value,''); 
				$status='true';
			}
			if($domainAttribute->attribute_type=='h4tag'){
				$this->_domain_h4=strip_tags($domainAttribute->attribute_value,''); 
				$status='true';
			}
			if($domainAttribute->attribute_type=='atag'){
				$this->_domain_a=strip_tags($domainAttribute->attribute_value,''); 
				$status='true';
			}
			if($domainAttribute->attribute_type=='ip'){
				$this->_domain_ip=$domainAttribute->attribute_value; 
				$status='true';
			}
			if($domainAttribute->attribute_type=='post_search'){
				$this->_postSearch=strip_tags($domainAttribute->attribute_value,''); 
				$status='true';
			}
			if($domainAttribute->attribute_type=='video_search'){
				$this->_videoSearch=strip_tags($domainAttribute->attribute_value,''); 
				$status='true';
			}
			if($status=='true'){
				$data=array(
					'title'=>$this->_domain_title, 
					'description'=>$this->_domain_description, 
					'keywords'=>$this->_domain_keywords, 
					'image'=>$this->_domain_image, 
					'h1'=>$this->_domain_h1, 
					'h2'=>$this->_domain_h2, 
					'h3'=>$this->_domain_h3, 
					'h4'=>$this->_domain_h4, 
					'a'=>$this->_domain_a, 
					'ip'=>$this->_domain_ip, 
					'post_search'=>$this->_postSearch, 
					'video_search'=>$this->_videoSearch
				); 
				Domain_info::where('parent_id',$domainAttribute->parent_id)->update($data, ['upsert' => true]); 
				echo $domainAttribute->attribute_type.'<p>';
				
			}
			$domainAttribute->delete(); 
		}
	}
	public function test312233(){
		$file = 'datafeed/datafeed_aliexpress.com_20180624-001.csv';
		Excel::load($file, function($reader) {
			$results = $reader->get();
			//dd($results->toArray()); 
			foreach($results->toArray() as $product){
				if(!empty($product['category']) && !empty($product['name']) && !empty($product['image']) && !empty($product['sku']) && !empty($product['price']) && !empty($product['discount']) && !empty($product['url']) && !empty($product['deeplink'])){
					$checkExit=Affiliate_feed::where('title_encode','=',base64_encode($product['name']))->first(); 
					if(empty($checkExit->title)){
						Affiliate_feed::insertGetId(array(
							'category'=>$product['category'], 
							'title'=>$product['name'], 
							'title_encode'=>base64_encode($product['name']), 
							//'description'=>$product['description'], 
							'image'=>$product['image'], 
							'sku'=>$product['sku'], 
							'price'=>$product['price'], 
							'discount'=>$product['discount'], 
							'url'=>$product['url'], 
							'deeplink'=>$product['deeplink'], 
							'campaign'=>'aliexpress.com', 
							'by_from'=>'accesstrade.vn', 
							'location'=>'global', 
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
							'status'=>'active'
						));
					}
				}
			}
		});
	}
	
	public function test7623(){
		echo base64_encode('https://nethost-2311.inet.vn:2083/'); 
	}
	public function test5213(){
		$client = new Client();
		$userData='{
		  "auth": {
			"passwordCredentials": {
			  "username": "zvnu13193796",
			  "password": "Khongnoi@123"
			},
			"tenantId": "d3568db7e90a4e2d9d819fe456ce2301"
		  }
		}';
		$signIn = $client->request('POST', 'https://identity.tyo1.cloud.z.com/v2.0/tokens',
			[
				'headers' => [ 'Content-Type' => 'application/json' ], 
				'body' => $userData
			 ]
		);
		$responseSignIn=json_decode($signIn->getBody()->getContents()); 
		dd($responseSignIn); 
		
	}
	public function test231(){
		$client = new Client();
		$userData='{
			"email": "'.config("app.inet_email").'",
			"password": "'.config("app.inet_pass").'"
		}';
		$signIn = $client->request('POST', 'https://dms.inet.vn/api/sso/v1/user/signin',
			[
				'headers' => [ 'Content-Type' => 'application/json' ], 
				'body' => $userData
			 ]
		);
		$responseSignIn=json_decode($signIn->getBody()->getContents()); 
		//dd($signIn->getStatusCode()); 
		if($signIn->getStatusCode()=='200' && $responseSignIn->status=='SUCCESS'){
			$postData='{
				"id": 1689918
			}';
			$response = $client->request('POST', 'https://dms.inet.vn/api/rms/v1/hosting/get',
				[
					'headers' => [ 'Content-Type' => 'application/json','token' => $responseSignIn->session->token], 
					'body' => $postData
				]
			);
			if($response->getStatusCode()=='200'){
				$getResponse=$response->getBody()->getContents(); 
				$responseDecode=json_decode($getResponse); 
				echo $getResponse; 
			} 
		}
	}
	public function testAccessTrade(){
		$client = new Client([
			'headers' => [ 
			'Authorization'=>'Token krkREE14smW4HEm9_7aGbcYHOCmFSdxs',
			'Content-Type' => 'application/json' 
			]
		]);
		/*$postData='{
			"domainName": "'.$domainName->host->registerableDomain.'"
		}';*/
		$response = $client->request('GET', 'https://api.accesstrade.vn/v1/campaigns'
			
		);
		dd(json_decode($response->getBody()->getContents())); 

	}
	function is_valid_domain_name($domain_name)
	{
		return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
				&& preg_match("/^.{1,253}$/", $domain_name) //overall length check
				&& preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
	}
	public function test111111111(){
		$string='08sujrfluMGQq0IlHRoW5KQ63Z8LFqxTW8ILiWvxvvoNU1UVRMmJi158wHrVMkb9';
		echo base64_decode($string); 
	}
	public function test3132(){
		try{
			$url='http://results.dogpile.com/search/web?q='.urlencode('thiet ke website'); 
			$opts = array(
				  'http'=>array(
					'method'=>"GET",
					'header'=>"Accept-language: en\r\n" .
							  "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
							  "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
				  )
				);
			$context = stream_context_create($opts);  
			$data = @file_get_contents($url, false, $context); 
			echo $data;exit;
			if($data===false){
				return false;
			}else{
				$dataConvertUtf8=mb_convert_encoding($data, 'UTF-8', mb_detect_encoding($data, 'UTF-8, ISO-8859-1', true)); 
				$dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$data;
				$doc = new \DOMDocument;
				@$doc->loadHTML($dataConvertUtf8);    
				$xpath = new \DOMXpath($doc); 
				$listArray=[]; 
				$itemSearch=[]; 
				foreach ($xpath->evaluate('//div[@id="web"]') as $node) {
					$html=$doc->saveHtml($node); 
					$metas = $doc->getElementsByTagName('ol'); 
					foreach($metas as $meta){
						if($meta->getAttribute('class')=='reg searchCenterMiddle'){
							$getElement = $meta->getElementsByTagName('li'); 
							foreach($getElement as $element){
								$getTitle=$element->getElementsByTagName('h3'); 
								$getLink=$element->getElementsByTagName('a'); 
								$getDescription=$element->getElementsByTagName('p'); 
								
								if($getTitle->length>0 && $getLink->length>0 && $getDescription->length>0){
									$getLinkFull=$getLink->item(0)->getAttribute('href');
									$itemSearch['title']=$getTitle->item(0)->nodeValue; 
									$getDomainLink=preg_replace('/(.*?RU=)(.*?)(\/RK.*)/', '$2', urldecode($getLinkFull)); 
									if(!empty($getDomainLink)){
										$domainNameCheck = $this->_parser->parseUrl($getDomainLink); 
										if(!is_null($domainNameCheck->host->registerableDomain)){
											$itemSearch['linkFull']=$getDomainLink; 
											$itemSearch['domainRegister']=$domainNameCheck->host->registerableDomain; 
										}
									}
									foreach($getDescription as $getDes){
										if($getDes->getAttribute('class')=='mw-42em'){
											$description=$getDes->nodeValue;
											$itemSearch['description']=$description; 
										}
									}
									$status='true'; 
									if(!empty($itemSearch['title'])){
										if(!WebService::checkBlacklistWord($itemSearch['title'], $this->_wordBlacklist)){
											$status='false';
										}
									}
									if(!empty($itemSearch['description']))
									{
										if(!WebService::checkBlacklistWord($itemSearch['description'], $this->_wordBlacklist)){
											$status='false';
										}
									}
									if($status=='true'){
										array_push($listArray,$itemSearch);
									}
								}
							}
						}
					}
					return $listArray;
				}
			}
		}catch (Exception $e) {
			return false;
		}
	}
	public function testApi(){
		$client = new Client();
		$userData='{
		  "auth": {
			"passwordCredentials": {
			  "username": "thanh@crviet.com",
			  "password": "Khongnoi@123"
			},
			"tenantId": "487727e3921d44e3bfe7ebb337bf085e"
		  }
		}';
		$signIn = $client->request('POST', 'https://identity.tyo1.cloud.z.com/v2.0/tokens',
			[
				'headers' => [ 'Content-Type' => 'application/json' ], 
				'body' => $userData
			 ]
		);
		$responseSignIn=json_decode($signIn->getBody()->getContents());
		dd($responseSignIn); 
	}
	public function testOld213(){
		$getChannel=Channel::where('update_user','=',1)->get(); 
		foreach($getChannel as $channel){
			if(!empty($channel->author[0])){
				$checkUser=User::find($channel->author[0]->channel_attribute_value);
				if(!empty($checkUser->id)){
					//echo $checkUser->id; 
					$channel->user_id=$checkUser->id; 
				}else{
					$checkRole=Channel_role::where('parent_id','=',$channel->id)->where('role_id','=',1)->first(); 
					if(!empty($checkRole->id)){
						$channel->user_id=$checkRole->user_id; 
					}else{
						$channel->user_id=43; 
					}
				}
			}else{
				$checkRole=Channel_role::where('parent_id','=',$channel->id)->where('role_id','=',1)->first(); 
				if(!empty($checkRole->id)){
					$channel->user_id=$checkRole->user_id; 
				}else{
					$channel->user_id=43; 
				}
				//$channel->user_id=43; 
			}
			$channel->update_user=0; 
			$channel->save(); 
		}
	}
	public function test234(){
		$getDomain=Domain::where('insert_by','=','crawler')->where('status','=','pending')->limit(1000)->get(); 
		foreach($getDomain as $domain){
			$domain->delete(); 
		}
		echo count($getDomain); 
	}
	public function test6546(){
		$getSiteLink=SiteLink::where('inserted','=',1)->where('error','=',0)->get(); 
		$pslManager = new PublicSuffixListManager(); 
		$parser = new Parser($pslManager->getList());
		foreach($getSiteLink as $siteLink){
			$siteLink->inserted=0; 
			$siteLink->save(); 
			if(filter_var(gethostbyname($siteLink->domain), FILTER_VALIDATE_IP))
			{
				$checkDomain=Domain::where('domain_encode','=',base64_encode($siteLink->domain))->first(); 
				if(empty($checkDomain->domain)){
					$idDomain=Domain::insertGetId(array(
						'domain'=>$siteLink->domain, 
						'domain_encode'=>base64_encode($siteLink->domain), 
						'domain_primary'=>'none', 
						'domain_location'=>'outside', 
						'service_attribute_id'=>'', 
						'status'=>$siteLink->status, 
						'created_at'=>$siteLink->created_at, 
						'updated_at'=>$siteLink->updated_at, 
						'rank'=>$siteLink->rank, 
						'rank_country'=>$siteLink->rank_country, 
						'country_code'=>$siteLink->country_code, 
						'view'=>$siteLink->view, 
						'crawler'=>$siteLink->crawler, 
						'insert_by'=>'crawler'
					)); 
					if($idDomain){
						if(!empty($siteLink->title->id)){
							Domain_attribute::insertGetId(array(
								'parent_id'=>$idDomain, 
								'attribute_type'=>'title', 
								'attribute_value'=>$siteLink->title->content
							)); 
						}
						if(!empty($siteLink->description->id)){
							Domain_attribute::insertGetId(array(
								'parent_id'=>$idDomain, 
								'attribute_type'=>'description', 
								'attribute_value'=>$siteLink->description->content
							)); 
						}
						if(!empty($siteLink->keywords->id)){
							Domain_attribute::insertGetId(array(
								'parent_id'=>$idDomain, 
								'attribute_type'=>'keywords', 
								'attribute_value'=>$siteLink->keywords->content
							)); 
						}
						if(!empty($siteLink->image->id)){
							Domain_attribute::insertGetId(array(
								'parent_id'=>$idDomain, 
								'attribute_type'=>'image', 
								'attribute_value'=>$siteLink->image->content
							)); 
						}
					}
				}
			}else{
				$siteLink->error=1; 
				$siteLink->save(); 
			}
		}
	}
	public function test8(){
		$url='https://www.google.com.vn/search?q=con+duong+viet'; 
		$opts = array(  
			'http'=>array(  
				'method'=>"GET",  
				'timeout'=>1, 
				'ignore_errors'=> true

			)  
		);
		$context = stream_context_create($opts);  
		$data = file_get_contents($url, false, $context); 
		$dataConvertUtf8=mb_convert_encoding($data, 'UTF-8', mb_detect_encoding($data, 'UTF-8, ISO-8859-1', true)); 
		$doc = new \DOMDocument;
		@$doc->loadHTML($dataConvertUtf8);    
		$xpath = new \DOMXpath($doc); 
		$pslManager = new PublicSuffixListManager(); 
		$parser = new Parser($pslManager->getList()); 
		foreach ($xpath->evaluate('//div[@id="search"]') as $node) {
			$doc->saveHtml($node); 
			$metas = $doc->getElementsByTagName('div');
			$listArray=[]; 
			$itemSearch=[]; 
			for ($i = 0; $i < $metas->length; $i++)
			{
				$meta = $metas->item($i);
				if($meta->getAttribute('class') == 'g'){
					$getTitle=$meta->getElementsByTagName('h3'); 
					$getImage=$meta->getElementsByTagName('div'); 
					$getDescription=$meta->getElementsByTagName('span'); 
					if(!empty($getTitle->item(0)) && $getTitle->item(0)->getAttribute('class') == 'r'){
						$getLink=$meta->getElementsByTagName('a'); 
						if(!empty($getTitle->item(0)->nodeValue)){
							$title=$getTitle->item(0)->nodeValue; 
							$itemSearch['title']=$title; 
						}
						if(!empty($getLink->item(0)) && !empty($getLink->item(0)->getAttribute('href'))){
							$link=str_replace("/url?q=","",$getLink->item(0)->getAttribute('href')); 
							$domainName = $parser->parseUrl($link); 
							$domainRegister=$domainName->host->registerableDomain; 
							$linkFull='https://www.google.com.vn'.$getLink->item(0)->getAttribute('href'); 
							//echo $domainRegister.'<p>';
							$itemSearch['domainRegister']=$domainRegister; 
							$itemSearch['linkFull']=$linkFull; 
						}
					}
					if(!empty($getImage->item(0)) && $getImage->item(0)->getAttribute('class') == 'th'){
						$getImageLink=$meta->getElementsByTagName('img'); 
						if(!empty($getImageLink->item(0)) && !empty($getImageLink->item(0)->getAttribute('src'))){
							$image=$getImageLink->item(0)->getAttribute('src'); 
							//echo $image.'<p>';
							$itemSearch['image']=$image; 
						}else{
							$itemSearch['image']=''; 
						}
					}else{
						$itemSearch['image']=''; 
					}
					for ($y = 0; $y < $getDescription->length; $y++)
					{
						$metagetDescription = $getDescription->item($y);
						if($metagetDescription->getAttribute('class') == 'st'){
							$description = $metagetDescription->nodeValue; 
							//echo $description.'<p>';
							$itemSearch['description']=$description; 
						} 
					}
					array_push($listArray,$itemSearch);
				}
			}
			print_r($listArray); 
		}
	}
	public function test7(){
		$getSite=SiteLink::where('status','pending')->limit(1)->get(); 
		foreach($getSite as $site){
			$client = new Client([
				'headers' => [ 
					'Content-Type' => 'text/html',
					'User-Agent' => 'Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n'
				], 
				['allow_redirects' => true], 
				'connect_timeout' => '2',
				'timeout' => '2'
			]);
			$response = $client->request('GET', 'http://'.$site->domain); 
			$getResponse=$response->getBody()->getContents(); 
			$data = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse;
			if ($data === false) {
				$site->status='faild'; 
				$site->save(); 
			}else{
				$doc = new \DOMDocument;
				@$doc->loadHTML($data); // load the HTML data
				$pslManager = new PublicSuffixListManager(); 
				$parser = new Parser($pslManager->getList()); 
				$nodes = $doc->getElementsByTagName('title');

				//get and display what you need:
				$title = $nodes->item(0)->nodeValue;

				$metas = $doc->getElementsByTagName('meta');

				for ($i = 0; $i < $metas->length; $i++)
				{
					$meta = $metas->item($i);
					if($meta->getAttribute('name') == 'description')
						$description = $meta->getAttribute('content');
					if($meta->getAttribute('name') == 'keywords')
						$keywords = $meta->getAttribute('content');
				}
				if(!empty($title)){
					if(empty($site->title->id)){
						Site_attribute::insertGetId(array(
							'site_id'=>$site->id, 
							'type'=>'title', 
							'content'=>$title
						)); 
					}else{
						$site->title->delete(); 
						Site_attribute::insertGetId(array(
							'site_id'=>$site->id, 
							'type'=>'title', 
							'content'=>$title
						)); 
					}
				}
				if(!empty($description)){
					if(empty($site->description->id)){
						Site_attribute::insertGetId(array(
							'site_id'=>$site->id, 
							'type'=>'description', 
							'content'=>$description
						)); 
					}else{
						$site->description->delete(); 
						Site_attribute::insertGetId(array(
							'site_id'=>$site->id, 
							'type'=>'description', 
							'content'=>$description
						)); 
					}
				}
				if(!empty($keywords)){
					if(empty($site->keywords->id)){
						Site_attribute::insertGetId(array(
							'site_id'=>$site->id, 
							'type'=>'keywords', 
							'content'=>$keywords
						)); 
					}else{
						$site->keywords->delete(); 
						Site_attribute::insertGetId(array(
							'site_id'=>$site->id, 
							'type'=>'keywords', 
							'content'=>$keywords
						));
					}
				}
				$site->status='active'; 
				$site->save(); 
			}
		}
	}
	public function test2222222222222(){
		$getSiteUrl=Site_url::where('status','pending')->limit(1)->get(); 
		$listLink=array(); 
		$appendLink=array(); 
		$listDomain=array(); 
		$appendDomain=array(); 
		foreach($getSiteUrl as $siteUrl){
			try {
				$client = new Client([
					'headers' => [ 
						'Content-Type' => 'text/html'
					], 
					'connect_timeout' => '2',
					'timeout' => '2',
					'allow_redirects' => [
						'max'             => 5,        // allow at most 10 redirects.
						'strict'          => true,      // use "strict" RFC compliant redirects.
						'referer'         => true,      // add a Referer header
						'protocols'       => ['https'], // only allow https URLs
						'track_redirects' => true
					], 
					'http_errors' => false
				]); 
				//dd($siteUrl->url); 
				$response = $client->request('GET', $siteUrl->url); 
				//dd($response); 
				$getResponse=$response->getBody()->getContents(); 
				$data = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse; 
				//dd($data); 
				//$data = file_get_contents($siteUrl->url, false, $context); 
				if ($data === false) {
					$siteUrl->status='faild'; 
					$siteUrl->save(); 
				}else{
					$regex = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';
					preg_match_all($regex, $data, $matches); 
					$pslManager = new PublicSuffixListManager(); 
					$parser = new Parser($pslManager->getList()); 
					$n=0; 
					foreach ($matches[0] as $link) { 
						$domainName = $parser->parseUrl($link);
						$checkExitUrl=Site_url::where('url_encode','=',base64_encode($link))->first(); 
						if(empty($checkExitUrl->url)){ 
						//$appendLink['link']=$checkExitUrl->url; 
						//array_push($listLink,$appendLink);
						echo 'Link: '.$link.'<p>';
							/*Site_url::insertGetId(array(
								'url'=>$link, 
								'url_encode'=>base64_encode($link), 
								'parent_id'=>0, 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
								'status'=>'pending'
							)); */
						}
						$checkSiteExit=Domain::where('domain_encode','=',base64_encode($domainName->host->registerableDomain))->first(); 
						if(empty($checkSiteExit->domain) && WebService::is_valid_url($domainName->host->registerableDomain)==true){
							//$appendDomain['domain']=$domainName->host->registerableDomain; 
							//array_push($listDomain,$appendDomain);
							echo 'Domain '.$domainName->host->registerableDomain.'<p>';
							/*Domain::insertGetId(array(
								'domain'=>$domainName->host->registerableDomain, 
								'domain_encode'=>base64_encode($domainName->host->registerableDomain), 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
								'updated_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
								'status'=>'pending'
							)); */
						}
						$n++; 
					} 
					//$siteUrl->status='active'; 
					//$siteUrl->save(); 
				}
			}catch (\GuzzleHttp\Exception\ServerException $e){
				echo 'error 1 <p>';
			}catch (\GuzzleHttp\Exception\BadResponseException $e){
				echo 'error 2 <p>';
			}catch (\GuzzleHttp\Exception\ClientException $e){
				echo 'error 3 <p>';
			}catch (\GuzzleHttp\Exception\ConnectException $e){
				echo 'error 4 <p>';
			}catch (\GuzzleHttp\Exception\RequestException $e){
				echo 'error 5 <p>';
			}
		}
		//dd($listLink); 
	}
	public function test5(){
		$this->fb = new \Facebook\Facebook([
          'app_id' => env('FACEBOOK_APP_ID'),
          'app_secret' => env('FACEBOOK_APP_SECRET'),
          'default_graph_version' => 'v3.0',
        ]); 
		/*$me = $this->fb->get('me/permissions',
			'EAAEDFBVQwDQBAAtZAe2JV3UddR53ws5YaKCQdt8TSEbZCgrFWsCIquiZBRLEWwvcLELPpHj9VUcHLrXy2i5dtLYDpUR2ZCwHEAlxUHDBhUENkLyS6JxQBaccZAFHaCzZBE5OeXQKDbyEyUZB0D4GbEgZAKmDl662zKIJz8aRSmNK7wZDZD'); 
		dd($me->getDecodedBody()); 
		*/
		$response = $this->fb->post(
			'/1531540343840372/feed',
			array(
				"message" => "Message",
				"link" => 'cungcap.net',
				"picture" => 'http://img.cungcap.net/media/2018/04/16/thumb/1523877073-wveot1523877073-dien-thoai-nokia-3310-4-sim-4-song-online-gia-re.jpeg',
				"name" => 'Test Controller Facebook',
				"caption" => 'Cung Cap',
				"description" => 'Mo Ta Thong tin'
			),
			'EAAEDFBVQwDQBAAfGUtMdHuFC0FDaywZBKtSTVtziEEgtYZAxHzLheJixO2ZAyr65gJjwvbAIqSPj2ZBUEJsO3rh9Iv9agWZBuRqQ5BoBJyDG3bC7VfTc3Dy0CNrOxZAJ3xfvhI4JmfqLUBn9v5VYeZCZB2Fq6KegLjiLYPqxqZBrRZAzWzMVLoLX7ZAglmHJsjjqcYP3hAGDMnX6QZDZD'
		);
	}
	public function test22(){
		/*$getCategory=Category::all(); 
		foreach($getCategory as $category){
			if(!empty($category->category_description)){
				Category_attribute::insertGetId(array(
					'channel_id'=>$category->joinChannel->channel->id, 
					'parent_id'=>$category->id, 
					'attribute_type'=>'description', 
					'attribute_value'=>$category->category_description, 
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
				)); 
			}
			if($category->parent_id!=0){
				$findCategory=Category::find($category->parent_id); 
				if(!empty($findCategory->id)){
					Category_join_parent::insertGetId(array(
						'channel_id'=>$category->joinChannel->channel->id, 
						'category_id'=>$category->id, 
						'parent_id'=>$findCategory->id
					)); 
				}
			}
		}*/
	}
	public function notfound(){ 
		return false;
		$today=$this->_parame['value']; 
		$image = new Imagick('assets/img/today.gif');

		// Watermark text
		$text = $this->randomText();
		
		// Create a new drawing palette
		$draw = new ImagickDraw();

		// Set font properties
		$draw->setFont('assets/fonts/RobotoBold.ttf'); 
		$draw->setFontSize(40);
		$draw->setGravity(Imagick::GRAVITY_CENTER );
		$draw->setFillColor('white');
		$draw->setTextUnderColor('#ff000088');
		$image->annotateImage($draw, 8,-180, 0,'HÔM NAY CỦA BẠN');
		$draw->setTextUnderColor('none');
		$draw->setFontSize(40);
		// Position text at the bottom-right of the image
		$draw->setGravity(Imagick::GRAVITY_WEST);
		$text = explode(" ", $text);
		$lines = array();
		$current_line = "";
		foreach ($text as $word) {
		  if ($current_line == "") {
			$test = $word;
		  } else {
			$test = "$current_line $word";
		  }
		  $metrics = $image->queryFontMetrics($draw, $test);
		  if ($metrics['textWidth'] > 700) {
			if ($current_line == "") {
			  $lines[] = $test;
			  $current_line = "";
			} else {
			  $lines[] = $current_line;
			  $current_line = $word;
			}
		  } else {
			$current_line = $test;
		  }
		}
		if ($current_line != "") {
		  $lines[] = $current_line;
		}
		$text = implode("\n", $lines);
		$draw->setFillColor('black');
		// Draw text on the image
		$image->annotateImage($draw, 10, 6, 0, $text);
		// Draw text again slightly offset with a different color
		$draw->setFillColor('white');
		$image->annotateImage($draw, 11, 5, 0, $text);

		// Set output image format
		$image->setImageFormat('gif');

		// Output the new image
		header('Content-type: image/gif');
		echo $image;
	}
	public function today(){ 
		return false;
		$today=$this->_parame['value']; 
		$image = new Imagick('assets/img/today.gif');

		// Watermark text
		$text = $this->randomText();
		
		// Create a new drawing palette
		$draw = new ImagickDraw();

		// Set font properties
		$draw->setFont('assets/fonts/RobotoBold.ttf'); 
		$draw->setFontSize(40);
		$draw->setGravity(Imagick::GRAVITY_CENTER );
		$draw->setFillColor('white');
		$draw->setTextUnderColor('#ff000088');
		$image->annotateImage($draw, 8,-180, 0,'HÔM NAY CỦA BẠN');
		$draw->setTextUnderColor('none');
		$draw->setFontSize(40);
		// Position text at the bottom-right of the image
		$draw->setGravity(Imagick::GRAVITY_WEST);
		$text = explode(" ", $text);
		$lines = array();
		$current_line = "";
		foreach ($text as $word) {
		  if ($current_line == "") {
			$test = $word;
		  } else {
			$test = "$current_line $word";
		  }
		  $metrics = $image->queryFontMetrics($draw, $test);
		  if ($metrics['textWidth'] > 700) {
			if ($current_line == "") {
			  $lines[] = $test;
			  $current_line = "";
			} else {
			  $lines[] = $current_line;
			  $current_line = $word;
			}
		  } else {
			$current_line = $test;
		  }
		}
		if ($current_line != "") {
		  $lines[] = $current_line;
		}
		$text = implode("\n", $lines);
		$draw->setFillColor('black');
		// Draw text on the image
		$image->annotateImage($draw, 10, 6, 0, $text);
		// Draw text again slightly offset with a different color
		$draw->setFillColor('white');
		$image->annotateImage($draw, 11, 5, 0, $text);

		// Set output image format
		$image->setImageFormat('gif');

		// Output the new image
		header('Content-type: image/gif');
		echo $image;
	}
	function randomText(){
		$input = [
			'Cuộc sống chỉ mang đến cho bạn 10% cơ hội, 90% còn lại là cách mà bạn phản ứng với nó',
			'Đừng bao giờ từ bỏ ước mơ vì sợ mất quá nhiều thời gian để hoàn thành nó. Dù sao thời gian vẫn luôn trôi đi - Earl Nightingale',
			'Nếu điều gì khiến bạn sợ hãi thì đó cũng có thể là động lực để cố gắng - Seth Godin', 
			'Tôi chưa bao giờ mơ ước thành công. Tôi làm việc để thành công - Estee Lauder', 
			'Hãy tìm kiếm 3 sở thích của bạn: Một cái để kiếm tiền, một cái để bạn phát triển và một cái để sáng tạo', 
			'Để có thể thành công, bạn buộc phải tin rằng bạn có thể', 
			'Không có gì là không thể với một người luôn biết cố gắng', 
			'Nghĩ quá nhiều sẽ hủy hoại bạn. Hủy hoại thực tại, thay đổi mọi thứ xung quanh, khiến bạn lo lắng và làm mọi thứ trở nên tồi tệ hơn bạn nghĩ', 
			'Hãy luyện tập như thể bạn chưa bao giờ chiến thắng. Hãy hành động như thể chưa bao giờ bạn thất bại', 
			'Chỉ cần bạn không dừng lại thì việc bạn tiến chậm cũng không là vấn đề', 
			'Bất kỳ ai cố gắng "dìm" bạn xuống thì họ đều thua kém bạn', 
			'Cách để bắt đầu đó là dừng việc nói lại và hãy bắt đầu làm', 
			'Hãy lắng nghe một cách cẩn thận điều người khác nói với bạn về một ai đó. Đấy chính là cách mà họ sẽ nói với ai đó về bạn', 
			'Giữ đôi mắt của bạn hướng lên bầu trời và đôi chân trên mặt đất', 
			'Có hai điều cần nhớ trong cuộc sống: Hãy chăm sóc suy nghĩ của bạn khi bạn một mình và hãy cẩn trọng với lời nói khi ở chốn đông người', 
			'Hãy không ngừng học hỏi. Nếu bạn là người thông minh nhất trong phòng thì thực sự là bạn đã ở nhầm chỗ', 
			'Đừng đánh mất bản thân mình khi cố gắng níu giữ người mà không hề quan tâm tới việc sắp mất bạn', 
			'Hãy làm những thứ khó khăn khi chúng còn dễ dàng và làm những thứ vĩ đại khi chúng còn nhỏ bé. Hành trình ngàn dặm bắt đầu bằng những bước chân', 
			'Hãy dừng để ý tới những khó khăn của bạn mà hãy biết ơn những gì bạn có', 
			'Không chuẩn bị nghĩa là bạn đã sẵn sàng cho việc thất bại', 
			'Bạn muốn biết bạn là ai? Đừng hỏi nữa. Hãy hành động! Hành động sẽ định nghĩa con người bạn', 
			'Trong khi bạn nhìn họ như những gã "điên" thì chúng tôi nhìn họ như những thiên tài. Bởi vì chỉ có những người điên đến mức nghĩ là có thể thay đổi thế giới thì mới là những người làm được', 
			'Không bao giờ, không bao giờ, không bao giờ từ bỏ', 
			'ĐỪNG LÀM VIỆC CHĂM CHỈ – HÃY LÀM VIỆC THÔNG MINH', 
			'Đừng nhầm lẫn giữa vẻ bề ngoài của giàu có và giàu có thật sự', 
			'Ưu tiên giải trí bằng sách hoặc nghe tin tức.', 
			'Tìm người hướng dẫn để phát triển nghề nghiệp đúng hướng.', 
			'Uống nước lọc để tăng cường sức khoẻ cho não.', 
			'Chấp nhận sự phê bình và chăm đặt câu hỏi để tiến bộ. ', 
			'Đừng đổ lỗi thất lại do kém may mắn, hãy học hỏi từ những lỗi lầm. ', 
			'Hãy để tiền làm việc cho bạn bằng sự đầu tư. '
			
		];
		$randIndex = array_rand($input);
		return $input[$randIndex]; 
	}
}