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
use App\Model\Keywords; 
use App\Model\Affiliate_feed; 
use App\Model\Domain;
use App\Model\Keywords_attribute;
use App\Model\Keyword_info;
use App\Model\Keyword_data; 
use App\Model\News;
use App\Model\Posts;
use App\Model\Posts_join; 
use App\Model\Posts_join_channel;
use App\Model\Posts_join_category;
use App\Model\Posts_attribute; 
use App\Model\Posts_join_keywords; 
use App\Model\Slug; 
use Carbon\Carbon; 
use WebService;
use DB;
use Response;
use Session;
use Redirect; 
use App\Model\Company; 
use App\Model\SiteLink;
use Pdp\PublicSuffixListManager; 
use Pdp\Parser; 
use DonatelloZa\RakePlus\RakePlus;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Cache; 
class KeywordsController extends ConstructController
{
	public $_keyword; 
	public $_postSearch=array(); 
	public $_videoSearch=array(); 
	public $_searchBy; 
	public $_videoSearchBy; 
	public $_affiliate=array(); 
	public $_listArray=array();
	public $_listResult=array(); 
	public $_searchResult=array(); 
	public $_ads='false'; 
	public function __construct(){
		parent::__construct(); 
	}
	
	public function index()
    {
		$Keywords=Keywords::where('status','=','active')
			->orderBy('created_at','desc')
			->paginate(20); 
		$getSite=Domain::where('status','=','active')
			->orderBy('updated_at','desc')
			->paginate(5); 
		$getCompany=Company::where('company_status','!=','delete')
			->orderBy('company_updated_at','desc')
			->paginate(5); 
		$return=array(
			'Keywords'=>$Keywords, 
			'getSite'=>$getSite, 
			'getCompany'=>$getCompany
		); 
		return $this->_theme->scope('keyword.list', $return)->render();
	}
	public function show(){
		//return false; 
		$this->_keyword=$this->_parame['slug']; 
		$keyword=str_replace('+', ' ', $this->_parame['slug']); 
		$keywordnew=preg_replace('{(.)\1+}','$1',rtrim(str_replace('+', ' ', preg_replace('/[^\w\s]+/u',' ' ,$this->_parame['slug'])), '+')); 
		$return=array(
								'keyword'=>$keyword
							); 
							return $this->_theme->scope('404notfoundKeyword', $return)->render();
		//return false; 
		
		//echo $keyword2; 
		/*if(!empty($keyword)){
			$rake = RakePlus::create($keyword, 'en_US');
			$phrase_scores = $rake->sortByScore('desc')->scores(); 
			if(count($phrase_scores)>0){
				$keyVal=array_slice($phrase_scores, 0);
				$this->_keyword=key($keyVal); 
			}
		}else{
			$this->_keyword=$keyword; 
		}
		$this->_keyword=$keyword; 
		*/
		if(WebService::is_valid_url($keyword)!=true){
			$getKeyword=Keywords::where('keyword_encode','=',base64_encode($keyword))->first(); 
			if(empty($getKeyword->id)){
				$checkHistory=History::where('history_type','=','get_content')->first();
				if(!empty($checkHistory->created_at)){
					if(Carbon::parse($checkHistory->created_at)->addSecond(5)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
						History::where('history_type','=','get_content')->delete(); 
						$listHistory=array(
							'history_type'=>'get_content', 
							'parent_id'=>0, 
							'author'=>0,
							'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
						); 
						History::insertGetId($listHistory); 
						if(WebService::checkBlacklistWord($keyword, $this->_wordBlacklist) && strlen($keyword)>4)
						{
							Keywords::insertGetId(array(
								'keyword'=>$keyword, 
								'keyword_encode'=>base64_encode($keyword), 
								'slug'=>Str::slug($keyword), 
								'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
								'search'=>'pending'
							)); 
						}else{
							$return=array(
								'keyword'=>$keyword
							); 
							return $this->_theme->scope('404notfoundKeyword', $return)->render();
						}
					}
				}else{
					$listHistory=array(
						'history_type'=>'get_content', 
						'parent_id'=>0, 
						'author'=>0,
						'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
					); 
					History::insertGetId($listHistory); 
				}
			}else{
				$getKeyword->increment('view', 1); 
				if($getKeyword->status!='blacklist'){
					$this->_ads='true'; 
				}
				if (Cache::store('file')->has('keyword_'.base64_encode($keyword))) {
					//$return=Cache::store('file')->get('keyword_'.base64_encode($keyword)); 
					//return $this->_theme->scope('search', $return)->render();
				}
			}
		}else{
			return Redirect::to('http://'.$keyword.'.'.config('app.url'), 301);
		}
		$postSearch=Posts::searchByQuery([
			'multi_match' => [
				'query' => $keyword,
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
				'query' => $keyword,
				'fields' => ['title','category'], 
				//'type'=>'phrase', 
				//'slop'=>3
			]
		], $aggregations = null, $sourceFields = null, $limit = 5, $offset = null, $sort = null);  
		$domainSearch=Domain::searchByQuery([
			'multi_match' => [
				'query' => $keyword,
				'fields' => ['domain_title','domain_description'], 
				//'type'=>'phrase', 
				//'slop'=>3
			]
		], $aggregations = null, $sourceFields = null, $limit = 10, $offset = null, $sort = null);  
		$newsSearch=News::searchByQuery([
			'multi_match' => [
				'query' => $keyword,
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
		//dd($affiliateSearch); 
		$perPage = 10; 
		$currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all(); 
		$paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage); 
		$paginatedItems->setPath(\Request::url())->render(); 
		//$paginatedItems->appends(\Request::query())->render(); 
		$return=array(
			'keyword'=>$keyword, 
			'getItems'=>$paginatedItems, 
			'affiliateSearch'=>$affiliateSearch, 
			'domainSearch'=>$domainSearch, 
			'newsSearch'=>$newsSearch, 
			'getPosts'=>array(), 
			'ads'=>$this->_ads
		); 
		//Cache::store('file')->put('keyword_'.base64_encode($keyword),$return, 60);
		return $this->_theme->scope('search', $return)->render();
	}
	public function redirectIndex()
    {
		return Redirect::route('keyword.list',$this->_domainPrimary,301);
	}
	public function redirectShow()
    {
		return Redirect::route('keyword.show',array($this->_domainPrimary,$this->_parame['slug']),301);
	}
	public function getSearchGoogle()
    {
		return 'false';
		$listArray=[]; 
		$itemSearch=[]; 
		$checkHistory=History::where('history_type','=','insert_post_search')->first();
		if(!empty($checkHistory->created_at)){
			if(Carbon::parse($checkHistory->created_at)->addSecond(10)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
				History::where('history_type','=','insert_post_search')->delete(); 
				$listHistory=array(
					'history_type'=>'insert_post_search', 
					'parent_id'=>0, 
					'author'=>0,
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				); 
				History::insertGetId($listHistory); 
				try {
					$client = new Client([
						'headers' => [ 'Content-Type' => 'text/html' ], 
						'connect_timeout' => '2',
						'timeout' => '2'
					]);
					$url='https://www.google.com.vn/search?q='.urlencode($this->_keyword); 
					$response = $client->request('GET', $url); 
					$getResponse=$response->getBody()->getContents(); 
					$dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse;
					$doc = new \DOMDocument;
					@$doc->loadHTML($dataConvertUtf8);    
					$xpath = new \DOMXpath($doc); 
					$domainRegister=''; 
					foreach ($xpath->evaluate('//div[@id="search"]') as $node) {
						$doc->saveHtml($node); 
						$metas = $doc->getElementsByTagName('div');
						for ($i = 0; $i < $metas->length; $i++)
						{
							$meta = $metas->item($i);
							if($meta->getAttribute('class') == 'g'){
								$getTitle=$meta->getElementsByTagName('h3'); 
								$getImage=$meta->getElementsByTagName('div'); 
								$getDescription=$meta->getElementsByTagName('span'); 
								$getLink=$meta->getElementsByTagName('a'); 
								if($getLink->length>0 && $getTitle->length>0 && $getDescription->length>0){
									if(!empty($getTitle->item(0)) && $getTitle->item(0)->getAttribute('class') == 'r'){
										if(!empty($getTitle->item(0)->nodeValue)){
											$title=$getTitle->item(0)->nodeValue; 
											$itemSearch['title']=$title; 
										}
										if(!empty($getLink->item(0)) && !empty($getLink->item(0)->getAttribute('href'))){
											parse_str($getLink->item(0)->getAttribute('href'), $query ); 
											if(!empty($query['/url?q'])){
												$domainNameCheck = $this->_parser->parseUrl($query['/url?q']); 
												if(!is_null($domainNameCheck->host->registerableDomain)){
													$itemSearch['linkFull']=$query['/url?q']; 
													$itemSearch['domainRegister']=$domainNameCheck->host->registerableDomain; 
												}
											}
										}
									}
									if(!empty($getImage->length>0) && $getImage->item(0)->getAttribute('class') == 'th'){
										$getImageLink=$meta->getElementsByTagName('img'); 
										if($getImageLink->length>0 && !empty($getImageLink->item(0)->getAttribute('src'))){
											$image=$getImageLink->item(0)->getAttribute('src'); 
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
											$description = htmlspecialchars(strip_tags($metagetDescription->nodeValue,""), ENT_COMPAT,'UTF-8', true); 
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
									if(!empty($itemSearch['domainRegister']) && $itemSearch['domainRegister']==config('app.url'))
									{
										$status='false'; 
									}
									if($status=='true'){
										array_push($listArray,$itemSearch);
									}
								}
							}
						}
					}
					unset($client);
					return $listArray; 
				}catch (\GuzzleHttp\Exception\ServerException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\BadResponseException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\ClientException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\ConnectException $e){
					return 'false'; 
				}
			}else{
				return 'false'; 
			}
		}else{
			$listHistory=array(
				'history_type'=>'insert_post_search', 
				'parent_id'=>0, 
				'author'=>0,
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
			); 
			History::insertGetId($listHistory); 
			return 'false'; 
		}
	}
	public function getSearchBing()
    {
		return 'false';
		$listArray=[]; 
		$itemSearch=[];
		$checkHistory=History::where('history_type','=','insert_post_search_bing')->first();
		if(!empty($checkHistory->created_at)){
			if(Carbon::parse($checkHistory->created_at)->addSecond(10)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
				History::where('history_type','=','insert_post_search_bing')->delete(); 
				$listHistory=array(
					'history_type'=>'insert_post_search_bing', 
					'parent_id'=>0, 
					'author'=>0,
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				); 
				History::insertGetId($listHistory); 
				try {
					$client = new Client([
						'headers' => [ 'Content-Type' => 'text/html' ], 
						'connect_timeout' => '2',
						'timeout' => '2'
					]);
					$url='https://www.bing.com/search?q='.urlencode($this->_keyword); 
					$response = $client->request('GET', $url); 
					$getResponse=$response->getBody()->getContents(); 
					$dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse;
					$doc = new \DOMDocument;
					@$doc->loadHTML($dataConvertUtf8);    
					$xpath = new \DOMXpath($doc); 
					foreach ($xpath->evaluate('//ol[@id="b_results"]') as $node) {
						$doc->saveHtml($node); 
						$metas = $doc->getElementsByTagName('li'); 
						for ($i = 0; $i < $metas->length; $i++)
						{
							$meta = $metas->item($i);
							if($meta->getAttribute('class') == 'b_algo'){
								$getTitle=$meta->getElementsByTagName('h2'); 
								$getLink=$meta->getElementsByTagName('a'); 
								if($getLink->length>0 && $getTitle->length>0){
									$getLinkFull=$getLink->item(0)->getAttribute('href');
									$itemSearch['title']=$getTitle->item(0)->nodeValue; 
									$itemSearch['linkFull']=$getLinkFull; 
									if($meta->getElementsByTagName('p')->length>0){
										$itemSearch['description']=$meta->getElementsByTagName('p')->item(0)->nodeValue; 
									}
									$domainName = parse_url($getLinkFull); 
									if(!empty($domainName['host'])){
										$domainNameParser = $this->_parser->parseUrl($domainName['host']); 
										$itemSearch['domainRegister']=$domainNameParser->host->registerableDomain; 
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
									if(!empty($itemSearch['domainRegister']) && $itemSearch['domainRegister']==config('app.url'))
									{
										$status='false'; 
									}
									if($status=='true'){
										array_push($listArray,$itemSearch);
									}
								}
							}
						}
					}
					unset($client);
					return $listArray;
				}catch (\GuzzleHttp\Exception\ServerException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\BadResponseException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\ClientException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\ConnectException $e){
					return 'false'; 
				}
			}else{
				return 'false'; 
			}
		}else{
			$listHistory=array(
				'history_type'=>'insert_post_search_bing', 
				'parent_id'=>0, 
				'author'=>0,
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
			); 
			History::insertGetId($listHistory); 
			return 'false'; 
		}
	}
	public function getSearchYahoo()
    {
		return 'false';
		$listArray=[]; 
		$itemSearch=[];
		$checkHistory=History::where('history_type','=','insert_post_search_yahoo')->first();
		if(!empty($checkHistory->created_at)){
			if(Carbon::parse($checkHistory->created_at)->addSecond(10)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
				History::where('history_type','=','insert_post_search_yahoo')->delete(); 
				$listHistory=array(
					'history_type'=>'insert_post_search_yahoo', 
					'parent_id'=>0, 
					'author'=>0,
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				); 
				History::insertGetId($listHistory); 
				try {
					$client = new Client([
						'headers' => [ 
							'Content-Type' => 'text/html',
							'User-Agent' => 'Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n'
						],
						'connect_timeout' => '2',
						'timeout' => '2'
					]);
					$url='https://vn.search.yahoo.com/search?p='.urlencode($this->_keyword); 
					$response = $client->request('GET', $url); 
					$getResponse=$response->getBody()->getContents(); 
					$dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse;
					$doc = new \DOMDocument;
					@$doc->loadHTML($dataConvertUtf8);    
					$xpath = new \DOMXpath($doc);  
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
										if(!empty($itemSearch['domainRegister']) && $itemSearch['domainRegister']==config('app.url'))
										{
											$status='false'; 
										}
										if($status=='true'){
											array_push($listArray,$itemSearch);
										}
									}
								}
							}
						}
					}
					unset($client);
					return $listArray;
				}catch (\GuzzleHttp\Exception\ServerException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\BadResponseException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\ClientException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\ConnectException $e){
					return 'false'; 
				}
			}else{
				return 'false'; 
			}
		}else{
			$listHistory=array(
				'history_type'=>'insert_post_search_yahoo', 
				'parent_id'=>0, 
				'author'=>0,
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
			); 
			History::insertGetId($listHistory); 
			return 'false'; 
		}
	}
	public function getSearchAol()
    {
		return 'false';
		$checkHistory=History::where('history_type','=','insert_post_search_aol')->first();
		if(!empty($checkHistory->created_at)){
			if(Carbon::parse($checkHistory->created_at)->addSecond(10)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
				History::where('history_type','=','insert_post_search_aol')->delete(); 
				$listHistory=array(
					'history_type'=>'insert_post_search_aol', 
					'parent_id'=>0, 
					'author'=>0,
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				); 
				History::insertGetId($listHistory); 
				try {
					$client = new Client([
						'headers' => [ 
							'Content-Type' => 'text/html',
							'User-Agent' => 'Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n'
						],
						'connect_timeout' => '2',
						'timeout' => '2'
					]);
					$url='https://search.aol.com/aol/search?q='.urlencode($this->_keyword);  
					$response = $client->request('GET', $url); 
					$getResponse=$response->getBody()->getContents(); 
					$dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse;
					$doc = new \DOMDocument;
					@$doc->loadHTML($dataConvertUtf8);    
					$xpath = new \DOMXpath($doc); 
					$listArray=[]; 
					$itemSearch=[]; 
					foreach ($xpath->evaluate('//div[@id="web"]') as $node) {
						$doc->saveHtml($node); 
						$metas = $doc->getElementsByTagName('ol');
						if($metas->length>2){
							$getElement=$metas->item(2)->getElementsByTagName('li'); 
							if($getElement->length>0){
								foreach($getElement as $element){
									//dd($element->nodeValue); 
									$getTitle=$element->getElementsByTagName('h3'); 
									$getLink=$element->getElementsByTagName('a'); 
									$getDescription=$element->getElementsByTagName('p'); 
									if($getTitle->length>0 && $getDescription->length>0){
										$getLinkFull=$getLink->item(0)->getAttribute('href');
										$itemSearch['title']=$getTitle->item(0)->nodeValue; 
										$itemSearch['description']=$getDescription->item(0)->nodeValue; 
										$getDomainLink=preg_replace('/(.*?RU=)(.*?)(\/RK.*)/', '$2', urldecode($getLinkFull)); 
										if(!empty($getDomainLink)){
											$domainNameCheck = $this->_parser->parseUrl($getDomainLink); 
											if(!is_null($domainNameCheck->host->registerableDomain)){
												$itemSearch['linkFull']=$getDomainLink; 
												$itemSearch['domainRegister']=$domainNameCheck->host->registerableDomain; 
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
										if(!empty($itemSearch['domainRegister']) && $itemSearch['domainRegister']==config('app.url'))
										{
											$status='false'; 
										}
										if($status=='true'){
											array_push($listArray,$itemSearch);
										}
									}
								}
							}
						}
					}
					unset($client);
					return $listArray;
				}catch (\GuzzleHttp\Exception\ServerException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\BadResponseException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\ClientException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\ConnectException $e){
					return 'false'; 
				}
			}else{
				return 'false'; 
			}
		}else{
			$listHistory=array(
				'history_type'=>'insert_post_search_aol', 
				'parent_id'=>0, 
				'author'=>0,
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
			); 
			History::insertGetId($listHistory); 
			return 'false'; 
		}
	}
	public function getSearchDogpile()
    {
		return 'false';
		$listArray=[]; 
		$itemSearch=[]; 
		$checkHistory=History::where('history_type','=','insert_post_search_dogpile')->first();
		if(!empty($checkHistory->created_at)){
			if(Carbon::parse($checkHistory->created_at)->addSecond(10)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
				History::where('history_type','=','insert_post_search_dogpile')->delete(); 
				$listHistory=array(
					'history_type'=>'insert_post_search_dogpile', 
					'parent_id'=>0, 
					'author'=>0,
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				); 
				History::insertGetId($listHistory); 
				try {
					$client = new Client([
						'headers' => [ 
							'Content-Type' => 'text/html',
							'User-Agent' => 'Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n'
						],
						'connect_timeout' => '2',
						'timeout' => '2'
					]);
					$url='http://results.dogpile.com/search/web?q='.urlencode($this->_keyword); 
					$response = $client->request('GET', $url); 
					$getResponse=$response->getBody()->getContents(); 
					$dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse;
					$doc = new \DOMDocument;
					@$doc->loadHTML($dataConvertUtf8);     
					$xpath = new \DOMXpath($doc); 
					$domainRegister='';
					foreach ($xpath->evaluate('//div[@class="main web"]') as $node) {
						$html=$doc->saveHtml($node); 
						$metas = $doc->getElementsByTagName('div'); 
						foreach($metas as $meta){
							if($meta->getAttribute('class')=='results web'){
									$params1 = $meta->getElementsByTagName('div'); 
									foreach($params1 as $para){
										if($para->getAttribute('class')=='result'){
											$getTitle=$para->getElementsByTagName('a'); 
											$getDescription=$para->getElementsByTagName('span'); 
											if($getTitle->length>0 && $getDescription->length>1){
												$getLinkFull=$getTitle->item(0)->getAttribute('href');
												$itemSearch['title']=$getTitle->item(0)->nodeValue; 
												$itemSearch['linkFull']=$getLinkFull; 
												$description=$getDescription->item(1)->nodeValue;
												$itemSearch['description']=$description; 
												$domainName = parse_url($getLinkFull); 
												if(!empty($domainName['host'])){
													$domainNameParser = $this->_parser->parseUrl($domainName['host']); 
													$itemSearch['domainRegister']=$domainNameParser->host->registerableDomain; 
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
												if(!empty($itemSearch['domainRegister']) && $itemSearch['domainRegister']==config('app.url'))
												{
													$status='false'; 
												}
												if($status=='true'){
													array_push($listArray,$itemSearch);
												}
											}
										}
									}
							}
						}
					}
					unset($client);
					return $listArray;
				}catch (\GuzzleHttp\Exception\ServerException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\BadResponseException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\ClientException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\ConnectException $e){
					return 'false'; 
				}
			}else{
				return 'false'; 
			}
		}else{
			$listHistory=array(
				'history_type'=>'insert_post_search_dogpile', 
				'parent_id'=>0, 
				'author'=>0,
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
			); 
			History::insertGetId($listHistory); 
			return 'false'; 
		}
	}
	public function getSearchMetacrawler()
    {
		return 'false';
		$listArray=[]; 
		$itemSearch=[]; 
		$checkHistory=History::where('history_type','=','search_metacrawler')->first();
		if(!empty($checkHistory->created_at)){
			if(Carbon::parse($checkHistory->created_at)->addSecond(10)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
				History::where('history_type','=','search_metacrawler')->delete(); 
				$listHistory=array(
					'history_type'=>'search_metacrawler', 
					'parent_id'=>0, 
					'author'=>0,
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				); 
				History::insertGetId($listHistory); 
				try {
					$client = new Client([
						'headers' => [ 
							'Content-Type' => 'text/html',
							'User-Agent' => 'Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n'
						],
						'connect_timeout' => '2',
						'timeout' => '2'
					]);
					$url='http://www.metacrawler.com/search/web?q='.urlencode($this->_keyword); 
					$response = $client->request('GET', $url); 
					$getResponse=$response->getBody()->getContents(); 
					$dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse;
					$doc = new \DOMDocument;
					@$doc->loadHTML($dataConvertUtf8);    
					$xpath = new \DOMXpath($doc); 
					foreach ($xpath->evaluate('//div[@id="resultsMain"]') as $node) {
						$doc->saveHtml($node); 
						$metas = $doc->getElementsByTagName('div');
						foreach($metas as $meta){
							if($meta->getAttribute('class')=='searchResult webResult'){
								$getElement=$meta->getElementsByTagName('div'); 
								foreach($getElement as $element){
									if($element->getAttribute('class')=='resultTitlePane'){
										$getLink=$element->getElementsByTagName('a'); 
										$getLinkFull=$getLink->item(0)->getAttribute('href');
										$itemSearch['title']=$getLink->item(0)->nodeValue; 
										$getDomainLink=preg_replace('/(.*?ru=)(.*?)(&du.*)/', '$2', urldecode(urldecode($getLinkFull))); 
										if(!empty($getDomainLink)){
											$domainNameCheck = $this->_parser->parseUrl($getDomainLink); 
											if(!is_null($domainNameCheck->host->registerableDomain)){
												$itemSearch['linkFull']=$getDomainLink; 
												$itemSearch['domainRegister']=$domainNameCheck->host->registerableDomain; 
											}
										}
									}
									if($element->getAttribute('class')=='resultDescription'){
										$itemSearch['description']=$element->nodeValue; 
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
								
								if(!empty($itemSearch['domainRegister']) && $itemSearch['domainRegister']==config('app.url'))
								{
									$status='false'; 
								}
								if($status=='true'){
									array_push($listArray,$itemSearch);
								}
							}
							
						}
					}
					unset($client);
					return $listArray;
				}catch (\GuzzleHttp\Exception\ServerException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\BadResponseException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\ClientException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\ConnectException $e){
					return 'false'; 
				}
			}else{
				return 'false'; 
			}
		}else{
			$listHistory=array(
				'history_type'=>'search_metacrawler', 
				'parent_id'=>0, 
				'author'=>0,
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
			); 
			History::insertGetId($listHistory); 
			return 'false'; 
		}
	}
	public function getSearchYoutube()
    {
		return 'false';
		$listArray=[]; 
		$itemSearch=[]; 
		$checkHistory=History::where('history_type','=','insert_post_search_youtube')->first();
		if(!empty($checkHistory->created_at)){
			if(Carbon::parse($checkHistory->created_at)->addSecond(10)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
				History::where('history_type','=','insert_post_search_youtube')->delete(); 
				$listHistory=array(
					'history_type'=>'insert_post_search_youtube', 
					'parent_id'=>0, 
					'author'=>0,
					'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
				); 
				History::insertGetId($listHistory); 
				try {
					$client = new Client([
						'headers' => [ 
							'Content-Type' => 'text/html'],
						'connect_timeout' => '2',
						'timeout' => '2'
					]);
					$url='https://www.youtube.com/results?search_query='.$this->_keyword; 
					$response = $client->request('GET', $url); 
					$getResponse=$response->getBody()->getContents(); 
					$dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse;
					$doc = new \DOMDocument;
					@$doc->loadHTML($dataConvertUtf8);    
					$xpath = new \DOMXpath($doc); 
					$domainRegister=''; 
					foreach ($xpath->evaluate('//div[@id="results"]') as $node) {
						$doc->saveHtml($node); 
						$metas = $doc->getElementsByTagName('ol');
						for ($i = 0; $i < $metas->length; $i++)
						{
							if($metas->item($i)->getAttribute('class') == 'item-section'){
								$parame = $metas->item($i)->getElementsByTagName('li'); 
								for ($y = 0; $y < $parame->length; $y++)
								{
									$meta = $parame->item($y);
									$getTitle=$meta->getElementsByTagName('h3'); 
									$getLink=$meta->getElementsByTagName('a'); 
									if($getLink->length>0 && $getTitle->length>0){
										if(!empty($getTitle->length>0)){
											if(!empty($getTitle->item(0)->nodeValue) && $getTitle->item(0)->getAttribute('class')=='yt-lockup-title '){
												$getOnLyTitle=$getTitle->item(0)->getElementsByTagName('a'); 
												$title=$getOnLyTitle->item(0)->nodeValue; 
												parse_str($getOnLyTitle->item(0)->getAttribute('href'), $query );
												if(!empty($query['/watch?v'])){
													$itemSearch['title']=$title; 
													$itemSearch['videoId']=$query['/watch?v']; 
													array_push($listArray,$itemSearch);
												}
											}
										}
									}
								}
							}
						} 
					}
					unset($client);
					return $listArray;
				}catch (\GuzzleHttp\Exception\ServerException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\BadResponseException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\ClientException $e){
					return 'false'; 
				}catch (\GuzzleHttp\Exception\ConnectException $e){
					return 'false'; 
				}
			}else{
				return 'false'; 
			}
		}else{
			$listHistory=array(
				'history_type'=>'insert_post_search_youtube', 
				'parent_id'=>0, 
				'author'=>0,
				'created_at'=>Carbon::now()->format('Y-m-d H:i:s'), 
			); 
			History::insertGetId($listHistory); 
			return 'false'; 
		}
	}
	public function getKeywordPost($postId)
    {
		/*$getKeywords=Keywords::join('posts_join_keywords','posts_join_keywords.keyword_id','=','keywords.id')
		->where('posts_join_keywords.post_id','=',$postId)
		->groupBy('keywords.id')
		->orderBy('keywords.created_at','desc')
		->get(); */
		$getKeywords=array(); 
		return $getKeywords;
	}
	public function keywordList()
    {
		/*$currentPage = 2;
		Paginator::currentPageResolver(function() use ($currentPage) {
			return $currentPage;
		});*/
		/*$getKeywords=Keywords::join('posts_join_keywords','posts_join_keywords.keyword_id','=','keywords.id')
		->where('posts_join_keywords.post_id','=',$this->_parame['postId'])
		->join('posts_join_channel','posts_join_channel.posts_id','=','posts_join_keywords.post_id')
		->where('posts_join_channel.channel_id','=',$this->_channel->id)
		->groupBy('keywords.id')
		->orderBy('keywords.created_at','desc')
		->paginate(10); */
		$getKeywords=array(); 
		$post=Posts::find($this->_parame['postId']); 
		$return=array(
			'keywords'=>$getKeywords, 
			'post'=>$post
		); 
		return $this->_theme->scope('keywords', $return)->render();
	}
	
}