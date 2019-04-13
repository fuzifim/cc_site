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
class SiteController extends ConstructController
{
    public $_keyword;
    public function __construct(){
        parent::__construct();
    }
    public function siteShowByIdSlug(){
        if(!empty($this->_parame['id'])){
            $site = Cache::store('memcached')->remember('infoSite_'.$this->_parame['id'], 1, function()
            {
                return DB::connection('mongodb')->collection('mongo_site')
                    ->where('_id',$this->_parame['id'])->first();
            });
            if(!empty($site['title'])){
                DB::connection('mongodb')->collection('mongo_site')
                    ->where('_id',(string)$site['_id'])
                    ->increment('view', 1);
                $siteRelate=Cache::store('memcached')->remember('site_relate_'.base64_encode($site['domain']), 1, function() use($site)
                {
                    return DB::connection('mongodb')->collection('mongo_site')
                        ->where('domain',$site['domain'])
                        ->limit(10)->get();
                });
                $domain = Cache::store('memcached')->remember('domainInfoSite_'.$this->_parame['id'], 1, function() use($site)
                {
                    return DB::connection('mongodb')->collection('mongo_domain')
                        ->where('base_64',base64_encode($site['domain']))->first();
                });
                $return=array(
                    'site'=>$site,
                    'siteRelate'=>$siteRelate,
                    'domain'=>$domain
                );
                return Theme::view('site.show',$return);
            }else if(!empty($this->_parame['slug'])){
                $this->_keyword=str_replace('+', ' ', $this->_parame['slug']);
                $this->_keyword=WebService::keywordDecodeBase64($this->_keyword);
                $return=array(
                    'keyword'=>$this->_keyword
                );
                return $this->_theme->scope('404notfoundKeyword', $return)->render();
            }else{
                return 'Not found';
            }
        }
    }
}