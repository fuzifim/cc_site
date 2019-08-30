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
use Theme;
use App\Model\Mongo_keyword;
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
	public function show(Request $request){
        $this->_keyword=str_replace('+', ' ', $this->_parame['slug']);
        $this->_keyword=WebService::keywordDecodeBase64($this->_keyword);
		if(WebService::is_valid_url($this->_keyword)!=true) {
            $getKeyword = DB::connection('mongodb')->collection('mongo_keyword')
                ->where('base_64', base64_encode($this->_keyword))->first();
            if(!empty($getKeyword['keyword'])){
                DB::connection('mongodb')->collection('mongo_keyword')
                    ->where('base_64',base64_encode($this->_keyword))
                    ->increment('view', 1);
                $postList=[];
                $paginate=6;
                $page = $request->has('page') ? $request->query('page') : 1;
                $offSet = ($page * $paginate) - $paginate;
                try{
                    $postSearch=Posts::searchByQuery([
                        'bool'=>[
                            'must'=>[
                                'multi_match' => [
                                    'query' => $getKeyword['keyword'],
                                    'fields' => ['posts_title','posts_title_convert']
                                ]
                            ]
                        ]
                    ], null, null, $paginate, $offSet);
                }catch (\Exception $e) {
                    $postSearch=array();
                }
                if(count($postSearch)){
                    $listId=[];
                    foreach($postSearch as $post){
                        array_push($listId,$post->id);
                    }
                    $postList=Posts::whereIn('id',$listId)->get();
                }
                $return=array(
                    'keyword'=>$getKeyword,
                    'postSearch'=>$postList
                );
                $amp=addslashes($request->query('amp'));
                if($amp=='true'){
                    $this->_theme=Theme::uses('main_amp')->layout('default');
                    return Cache::store('memcached')->remember('show_keyword_amp_'.$getKeyword['_id'], 5, function() use($return)
                    {
                        return Theme::view('keyword.show', $return);
                    });
                }else{
                    return Cache::store('memcached')->remember('show_keyword_'.$getKeyword['_id'], 5, function() use($return)
                    {
                        return Theme::view('keyword.show', $return);
                    });
                }
            }else{
                $return=array(
                    'keyword'=>$this->_keyword
                );
                return $this->_theme->scope('404notfoundKeyword', $return)->render();
            }
        }else{
            $parsedUrl=parse_url($this->_keyword);
            if(!empty($parsedUrl['host'])){
                $domain=$this->_rulesDomain->resolve($parsedUrl['host']);
            }else{
                $domain=$this->_rulesDomain->resolve($this->_keyword);
            }
            if(!empty($domain->getRegistrableDomain())){
                return redirect()->to('http://'.$domain->getRegistrableDomain().'.d.'.config('app.url'));
            }else{
                $return=array(
                    'keyword'=>$this->_keyword
                );
                return $this->_theme->scope('404notfoundKeyword', $return)->render();
            }
        }
	}
	public function showById(Request $request){
	    if(!empty($this->_parame['id'])){
            $getKeyword = DB::connection('mongodb')->collection('mongo_keyword')
                ->where('_id', $this->_parame['id'])->first();
            if(!empty($getKeyword['keyword'])){
                DB::connection('mongodb')->collection('mongo_keyword')
                    ->where('_id',$this->_parame['id'])
                    ->increment('view', 1);
                $postList=[];
                $paginate=6;
                $page = $request->has('page') ? $request->query('page') : 1;
                $offSet = ($page * $paginate) - $paginate;
                try{
                    $postSearch=Posts::searchByQuery([
                        'bool'=>[
                            'must'=>[
                                'multi_match' => [
                                    'query' => $getKeyword['keyword'],
                                    'fields' => ['posts_title','posts_title_convert']
                                ]
                            ]
                        ]
                    ], null, null, $paginate, $offSet);
                }catch (\Exception $e) {
                    $postSearch=array();
                }
                if(count($postSearch)){
                    $listId=[];
                    foreach($postSearch as $post){
                        array_push($listId,$post->id);
                    }
                    $postList=Posts::whereIn('id',$listId)->get();
                }
                $return=array(
                    'keyword'=>$getKeyword,
                    'postSearch'=>$postList
                );
                $amp=addslashes($request->query('amp'));
                if($amp=='true'){
                    $this->_theme=Theme::uses('main_amp')->layout('default');
                    return Cache::store('memcached')->remember('show_keyword_amp_'.$getKeyword['_id'], 5, function() use($return)
                    {
                        return Theme::view('keyword.show', $return);
                    });
                }else{
                    return Cache::store('memcached')->remember('show_keyword_'.$getKeyword['_id'], 5, function() use($return)
                    {
                        return Theme::view('keyword.show', $return);
                    });
                }
            }else{
                $return=array(
                    'keyword'=>'Keyword notfound'
                );
                return $this->_theme->scope('404notfoundKeyword', $return)->render();
            }
        }
    }
	public function redirectIndex()
    {
		return Redirect::route('keyword.list',$this->_domainPrimary,301);
	}
	public function redirectShow()
    {
		return Redirect::route('keyword.show',array($this->_domainPrimary,$this->_parame['slug']),301);
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