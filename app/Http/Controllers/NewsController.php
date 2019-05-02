<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Theme;
use WebService;
use Route;
use Redirect;
use Response;
use Input;
use Validator;
use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use DB;
use Cache;
class NewsController extends ConstructController
{
    public function __construct(){
        parent::__construct();
    }
    public function listCateNews(){
        $return=array();
        return Theme::view('settings.list_cate_news',$return);
    }
    public function NewsDetail(Request $request){
        if(!empty($this->_parame['id'])){
            $getNews = DB::connection('mongodb')->collection('mongo_news')
                ->where('_id', $this->_parame['id'])->first();
            if(!empty($getNews['title'])){
                $newsRelate=Cache::store('memcached')->remember('news_relate_'.$this->_parame['id'], 1, function() use($getNews)
                {
                    return DB::connection('mongodb')->collection('mongo_news')
                        ->where('parent_id',$getNews['parent_id'])
                        ->where('_id','!=', $this->_parame['id'])
                        ->orderBy('updated_at','desc')
                        ->limit(5)
                        ->get();
                });
                $return=array(
                    'news'=>$getNews,
                    'newsRelate'=>$newsRelate
                );
                return Theme::view('news.show', $return);
            }
        }
    }
}