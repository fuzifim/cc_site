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
use Cart;
use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use DB;
use Cache;
use Youtube;
class VideoController extends ConstructController
{
    public function __construct(){
        parent::__construct();
    }
    public function viewVideoYoutubeById(Request $request){
        \App::setLocale($this->_detectLang);
        $video = Cache::store('memcached')->remember('infoVideoYoutube_'.$this->_parame['yId'], 1, function()
        {
            return DB::connection('mongodb')->collection('mongo_video')
                ->where('yid',$this->_parame['yId'])->first();
        });
        if(!empty($video['yid'])){
            $parsedUrl=parse_url($request->url());
            if(config('app.env')!='local' && $parsedUrl['scheme']!='https'){
                return redirect()->secure($request->getRequestUri())->send();
            }
            $videoParent=[];
            if(!empty($video['parent'])){
                $videoParent = Cache::store('memcached')->remember('infoVideoYoutube_parent_'.base64_encode($video['parent']).'_'.$video['_id'], 1, function() use($video)
                {
                    return DB::connection('mongodb')->collection('mongo_video')
                        ->where('parent',$video['parent'])
                        ->where('_id','!=',(string)$video['_id'])
                        ->get()->toArray();
                });
            }
            $return=array(
                'video'=>$video,
                'videoParent'=>$videoParent
            );
            return Theme::view('video.youtubeShow',$return);
        }else{
            return Theme::view('404',[]);
        }
    }
    public function videoShowByIdSlug(Request $request){
        \App::setLocale($this->_detectLang);
        if(!empty($this->_parame['yId'])){
            $video = Cache::store('memcached')->remember('infoVideoYoutube_'.$this->_parame['yId'], 1, function()
            {
                return DB::connection('mongodb')->collection('mongo_video')
                    ->where('yid',$this->_parame['yId'])->first();
            });
            if(!empty($video['yid'])){
                $parsedUrl=parse_url($request->url());
                if(config('app.env')!='local' && $parsedUrl['scheme']!='https'){
                    return redirect()->secure($request->getRequestUri())->send();
                }
                $videoParent=[];
                if(!empty($video['parent'])){
                    $videoParent = Cache::store('memcached')->remember('infoVideoYoutube_parent_'.base64_encode($video['parent']).'_'.$video['_id'], 1, function() use($video)
                    {
                        return DB::connection('mongodb')->collection('mongo_video')
                            ->where('parent',$video['parent'])
                            ->where('_id','!=',(string)$video['_id'])
                            ->get()->toArray();
                    });
                }
                $return=array(
                    'video'=>$video,
                    'videoParent'=>$videoParent
                );
                return Theme::view('video.youtubeShow',$return);
            }else{
                return Theme::view('404',[]);
            }
        }
    }
}