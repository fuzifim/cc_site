<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use WebService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class SchedulingController extends Controller
{
    public $_domain;
    public $_domain_link;
    public function insertPhoto(){
        $getPhoto=DB::connection('mongodb_old')->collection('note')
            ->where('type','image')
            ->where('index','<',3)
            ->limit(500)->get();
        foreach ($getPhoto as $item){
            if(!empty($item['index_replay'])){

                DB::connection('mongodb_old')->collection('note')->where('type','image')
                    ->where('_id',(string)$item['_id'])
                    ->update(['index_replay' => $item['index_replay']+1]);
            }else{
                DB::connection('mongodb_old')->collection('note')->where('type','image')
                    ->where('_id',(string)$item['_id'])
                    ->update(['index_replay' => 1]);
            }
            if(!empty($item['index_replay']) && $item['index_replay']==3){
                DB::connection('mongodb_old')->collection('note')->where('type','image')
                    ->where('_id',(string)$item['_id'])
                    ->update(['index' => 3]);
                echo $item['title'].' update success <br>';
            }
            $checkSite=DB::connection('mongodb')->collection('mongo_image')
                ->where('base_64',base64_encode($item['link']))->first();
            if(empty($checkSite['title'])){
                $getParent=array(
                    'title'=>''
                );
                if(!empty($item['parent'])) {
                    $getParent = DB::connection('mongodb_old')->collection('note')
                        //->where('type', 'category')
                        ->where('_id', $item['parent'])
                        ->first();
                }
                DB::connection('mongodb')->collection('mongo_image')
                    ->insertGetId(
                        [
                            'parent'=>$getParent['title'],
                            'title' => $item['title'],
                            'link' => $item['link'],
                            'base_64' => base64_encode($item['link']),
                            'attribute'=>$item['attribute'],
                            'status'=>$item['status'],
                            'created_at'=>$item['created_at'],
                            'updated_at'=>$item['updated_at']
                        ]
                    );
                DB::connection('mongodb_old')->collection('note')->where('type','image')
                    ->where('_id',(string)$item['_id'])
                    ->update(['index' => 3]);
                echo $item['title'].' insert success <br>';
            }else{
                DB::connection('mongodb_old')->collection('note')->where('type','image')
                    ->where('_id',(string)$item['_id'])
                    ->update(['index' => 3]);
                echo $item['title'].' insert success <br>';
            }
        }
    }
    public function insertSite(){
        $getSite=DB::connection('mongodb_old')->collection('note')
            ->where('type','site')
            ->where('index','<',3)
            ->limit(1000)->get();
        foreach ($getSite as $item){
            if(!empty($item['index_replay'])){

                DB::connection('mongodb_old')->collection('note')->where('type','site')
                    ->where('_id',(string)$item['_id'])
                    ->update(['index_replay' => $item['index_replay']+1]);
            }else{
                DB::connection('mongodb_old')->collection('note')->where('type','site')
                    ->where('_id',(string)$item['_id'])
                    ->update(['index_replay' => 1]);
            }
            if(!empty($item['index_replay']) && $item['index_replay']==3){
                DB::connection('mongodb_old')->collection('note')->where('type','site')
                    ->where('_id',(string)$item['_id'])
                    ->update(['index' => 3]);
                echo $item['title'].' update success <br>';
            }
            $checkSite=DB::connection('mongodb')->collection('mongo_site')
                ->where('base_64',base64_encode($item['link']))->first();
            if(empty($checkSite['title'])){
                $getParent=array(
                    'domain'=>''
                );
                if(!empty($item['view'])){
                    $view=$item['view'];
                }else{
                    $view='';
                }
                if(!empty($item['parent'])) {
                    $getParent = DB::connection('mongodb_old')->collection('note')
                        ->where('type', 'domain')
                        ->where('_id', $item['parent'])
                        ->first();
                }
                DB::connection('mongodb')->collection('mongo_site')
                    ->insertGetId(
                        [
                            'parent'=>$getParent['domain'],
                            'title' => $item['title'],
                            'link' => $item['link'],
                            'base_64' => base64_encode($item['link']),
                            'description'=>$item['description'],
                            'attribute'=>$item['attribute'],
                            'view'=>$view,
                            'status'=>$item['status'],
                            'created_at'=>$item['created_at'],
                            'updated_at'=>$item['updated_at']
                        ]
                    );
                DB::connection('mongodb_old')->collection('note')->where('type','site')
                    ->where('_id',(string)$item['_id'])
                    ->update(['index' => 3]);
                echo $item['title'].' insert success <br>';
            }else{
                DB::connection('mongodb_old')->collection('note')->where('type','site')
                    ->where('_id',(string)$item['_id'])
                    ->update(['index' => 3]);
                echo $item['title'].' update success <br>';
            }
        }
    }
    public function insertVideo(){
        $getVideo=DB::connection('mongodb_old')->collection('note')
            ->where('type','video')
            ->where('index','<',3)
            ->limit(500)->get();
        foreach ($getVideo as $item){
            $checkVideo=DB::connection('mongodb')->collection('mongo_video')
                ->where('base_64',base64_encode($item['title']))->first();
            if(empty($checkVideo['title'])){
                $getParent=array(
                    'title'=>''
                );
                if(!empty($item['parent'])){
                    $getParent=DB::connection('mongodb_old')->collection('note')
                        ->where('type','category')
                        ->where('_id',$item['parent'])
                        ->first();
                    DB::connection('mongodb')->collection('mongo_video')
                        ->insertGetId(
                            [
                                'parent'=>$getParent['title'],
                                'title' => $item['title'],
                                'base_64' => base64_encode($item['title']),
                                'description'=>$item['description'],
                                'yid'=>$item['yid'],
                                'image'=>$item['image'],
                                'thumb'=>$item['thumb'],
                                'view'=>$item['view'],
                                'status'=>$item['status'],
                                'created_at'=>$item['created_at'],
                                'updated_at'=>$item['updated_at']
                            ]
                        );
                    DB::connection('mongodb_old')->collection('note')->where('type','video')
                        ->where('title_encode',base64_encode($item['title']))
                        ->update(['index' => 3]);
                    echo $item['title'].' insert success <br>';
                }
            }else{
                DB::connection('mongodb_old')->collection('note')->where('type','video')
                    ->where('title_encode',base64_encode($item['title']))
                    ->update(['index' => 3]);
                echo $item['title'].' update success <br>';
            }
        }
    }
    public function insertCategory(){
        $getCategory=DB::connection('mongodb_old')->collection('note')
            ->where('type','category')
            ->where('index','<',4)
            ->limit(500)->get();
        foreach($getCategory as $item){
            $checkKeyword=DB::connection('mongodb')->collection('mongo_keyword')
                ->where('base_64',base64_encode($item['title']))->first();
            if(empty($checkKeyword['keyword'])){
                $getParent=array(
                    'title'=>''
                );
                if(!empty($item['parent'])){
                    $getParent=DB::connection('mongodb_old')->collection('note')
                        ->where('type','category')
                        ->where('_id',$item['parent'])
                        ->first();
                }
                if(!empty($item['description'])){
                    $description=$item['description'];
                }else{
                    $description='';
                }
                if(!empty($item['image'])){
                    $image=$item['image'];
                }else{
                    $image='';
                }
                DB::connection('mongodb')->collection('mongo_keyword')
                    ->insertGetId(
                        [
                            'parent'=>$getParent['title'],
                            'keyword' => $item['title'],
                            'base_64' => base64_encode($item['title']),
                            'description'=>$description,
                            'image'=>$image,
                            'status'=>$item['status'],
                            'created_at'=>$item['created_at'],
                            'updated_at'=>$item['updated_at']
                        ]
                    );
                DB::connection('mongodb_old')->collection('note')->where('type','category')
                    ->where('title_encode',base64_encode($item['title']))
                    ->update(['index' => 4]);
                echo $item['title'].' insert success <br>';
            }else{
                DB::connection('mongodb_old')->collection('note')->where('type','category')
                    ->where('title_encode',base64_encode($item['title']))
                    ->update(['index' => 4]);
                echo $item['title'].' update success <br>';
            }
        }
    }
    public function insertIp(){
        $getIp=DB::connection('mongodb_old')->collection('note')->where('type','ip')
            ->where('update_time','<',3)
            ->limit(1000)->get();
        foreach ($getIp as $item){
            $checkIp=DB::connection('mongodb')->collection('mongo_ip')
                ->where('base_64',base64_encode($item['title']))->first();
            if(empty($checkIp['ip'])){
                DB::connection('mongodb')->collection('mongo_ip')
                    ->insertGetId(
                        [
                            'ip' => $item['title'],
                            'base_64' => base64_encode($item['title']),
                            'description'=>$item['description'],
                            'created_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                        ]
                    );
                DB::connection('mongodb_old')->collection('note')->where('type','ip')
                    ->where('title_encode',base64_encode($item['title']))
                    ->update(['update_time' => 3]);
                echo $item['title'].' insert success <br>';
            }else{
                DB::connection('mongodb_old')->collection('note')->where('type','ip')
                    ->where('title_encode',base64_encode($item['title']))
                    ->update(['update_time' => 3]);
                echo $item['title'].' update success <br>';
            }
        }
    }
    public function insertDomain(){
        $getDomain=DB::connection('mongodb_old')->collection('note')->where('type','domain')
            ->where('index','<',4)->limit(1000)->get();
        foreach ($getDomain as $item){
            $checkDomain=DB::connection('mongodb')->collection('mongo_domain')
                ->where('base_64',base64_encode($item['domain']))->first();
            if(empty($checkDomain['domain'])){
                DB::connection('mongodb')->collection('mongo_domain')
                    ->insertGetId(
                        [
                            'domain' => $item['domain'],
                            'base_64' => base64_encode($item['domain']),
                            'title'=>$item['title'],
                            'description'=>$item['description'],
                            'keywords'=>$item['keywords'],
                            'image'=>$item['image'],
                            'attribute'=>$item['attribute'],
                            'view'=>$item['view'],
                            'status'=>$item['status'],
                            'created_at'=>$item['created_at'],
                            'updated_at'=>$item['updated_at']
                        ]
                    );
                DB::connection('mongodb_old')->collection('note')->where('type','domain')
                    ->where('domain_encode',base64_encode($item['domain']))
                    ->update(['index' => 4]);
                echo 'insert success <br>';
            }else{
                DB::connection('mongodb_old')->collection('note')->where('type','domain')
                    ->where('domain_encode',base64_encode($item['domain']))
                    ->update(['index' => 4]);
                echo 'Domain exist <br>';
            }
        }
    }
    public function updateCountry(){
        $getDomain=DB::connection('mongodb')->collection('mongo_domain')
            ->where('craw_next','update_country')
            ->limit(3)->get();
        foreach ($getDomain as $domain){
            $this->_domain=$domain['domain'];
            if(!empty($domain['attribute']['country_code'])){
                DB::connection('mongodb')->collection('mongo_domain')
                    ->where('base_64',base64_encode($domain['domain']))
                    ->update([
                        'status_domain_country'=>'active',
                        'domain_country_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_next'=>'step_6',
                        'country'=>$domain['attribute']['country_code'],
                        'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                    ]);
            }else{
                DB::connection('mongodb')->collection('mongo_domain')
                    ->where('base_64',base64_encode($domain['domain']))
                    ->update([
                        'status_domain_country'=>'error',
                        'domain_country_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_next'=>'step_6'
                    ]);
            }
        }
    }
    public function getIpRecord(){
        $getDomain=DB::connection('mongodb')->collection('mongo_domain')
            ->where('craw_next','ip')
            ->limit(3)->get();
        foreach ($getDomain as $domain){
            $this->_domain=$domain['domain'];
            if(empty($domain['attribute']['dns_record'])){
                $result=$this->getIpDomain();
                if($result['result']=='error'){
                    DB::connection('mongodb')->collection('mongo_domain')
                        ->where('base_64',base64_encode($domain['domain']))
                        ->update([
                            'status_domain_ip'=>'error',
                            'domain_ip_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_next'=>'update_country'
                        ]);
                    echo $domain['domain']. ' insert ip error';
                }else if($result['result']=='success'){
                    $domainAttribute=$domain['attribute'];
                    $noteMer=array('dns_record'=>$result['dns_record']);
                    $domainAttribute= array_merge($domainAttribute, $noteMer);
                    DB::connection('mongodb')->collection('mongo_domain')
                        ->where('base_64',base64_encode($domain['domain']))
                        ->update([
                            'status_domain_ip'=>'active',
                            'domain_ip_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_next'=>'update_country',
                            'attribute'=>$domainAttribute,
                            'ip'=>$result['ip'],
                            'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                        ]);
                    echo $domain['domain']. ' insert ip success';
                }
            }else if(empty($domain['ip'])){
                $ipDomain=gethostbyname($domain['domain']);
                $ip='';
                if($ipDomain!=$domain['domain']){
                    $ip=$ipDomain;
                }
                DB::connection('mongodb')->collection('mongo_domain')
                    ->where('base_64',base64_encode($domain['domain']))
                    ->update([
                        'status_domain_ip'=>'active',
                        'domain_ip_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_next'=>'update_country',
                        'ip'=>$ip,
                        'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                    ]);
                echo $domain['domain']. ' update only ip<p>';
            }else{
                DB::connection('mongodb')->collection('mongo_domain')
                    ->where('base_64',base64_encode($domain['domain']))
                    ->update([
                        'status_domain_ip'=>'error',
                        'domain_ip_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_next'=>'update_country'
                    ]);
                echo $domain['domain']. ' insert or update ip error';
            }
        }
    }
    public function getRankDomain(){
        $getDomain=DB::connection('mongodb')->collection('mongo_domain')
            ->where('craw_next','rank')
            ->limit(3)->get();
        foreach ($getDomain as $domain){
            $this->_domain=$domain['domain'];
            $result=$this->getRank();
            if($result['result']=='error'){
                DB::connection('mongodb')->collection('mongo_domain')
                    ->where('base_64',base64_encode($domain['domain']))
                    ->update([
                        'status_domain_rank'=>'error',
                        'domain_rank_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_next'=>'ip'
                    ]);
                echo $domain['domain'].' error';
            }else if($result['result']=='success'){
                $domainAttribute=$domain['attribute'];
                $noteMer=array('rank'=>$result['rank'],'rank_country'=>$result['rank_country'],'country_code'=>$result['country_code']);
                $domainAttribute= array_merge($domainAttribute, $noteMer);
                DB::connection('mongodb')->collection('mongo_domain')
                    ->where('base_64',base64_encode($domain['domain']))
                    ->update([
                        'status_domain_rank'=>'active',
                        'domain_rank_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_next'=>'ip',
                        'attribute'=>$domainAttribute,
                        'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                    ]);
                echo $domain['domain'].' success';
            }
        }
    }
    public function getWhoisDomain(){
        $getDomain=DB::connection('mongodb')->collection('mongo_domain')
            ->where('craw_next','whois')
            ->limit(3)->get();
        foreach ($getDomain as $domain){
            if(empty($domain['attribute']['whois'])){
                $this->_domain=$domain['domain'];
                $result=$this->getWhois();
                if($result['result']=='success'){
                    $domainAttribute=$domain['attribute'];
                    $noteMer=array('whois'=>$result['data']);
                    $domainAttribute= array_merge($domainAttribute, $noteMer);
                    DB::connection('mongodb')->collection('mongo_domain')
                        ->where('base_64',base64_encode($domain['domain']))
                        ->update([
                            'status_domain_whois'=>'active',
                            'domain_whois_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_next'=>'rank',
                            'attribute'=>$domainAttribute,
                            'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                        ]);
                }else if($result['result']=='error'){
                    DB::connection('mongodb')->collection('mongo_domain')
                        ->where('base_64',base64_encode($domain['domain']))
                        ->update([
                            'status_domain_whois'=>'error',
                            'domain_whois_status_message'=>$result['message'],
                            'domain_whois_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_next'=>'rank'
                        ]);
                }
            }else{
                DB::connection('mongodb')->collection('mongo_domain')
                    ->where('base_64',base64_encode($domain['domain']))
                    ->update([
                        'status_domain_whois'=>'active',
                        'domain_whois_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_next'=>'rank'
                    ]);
            }
        }
    }
    public function crawInfoDomain(){
        $getDomain=DB::connection('mongodb')->collection('mongo_domain')
            ->where(function($query)
            {
                $query->where('status', '!=','active')
                    ->where('craw_next','exists',false);
            })
            ->orWhere(function($query)
            {
                $query->where('title', '')
                    ->where('craw_next','exists',false);
            })
            ->orWhere(function($query)
            {
                $query->where('title', null)
                    ->where('craw_next','exists',false);
            })
            ->limit(3)->get();
        foreach($getDomain as $domain){
            $this->_domain=$domain['domain'];
            $result=$this->getInfoSite();
            if($result['result']=='error'){
                DB::connection('mongodb')->collection('mongo_domain')
                    ->where('base_64',base64_encode($domain['domain']))
                    ->update([
                        'scheme' => $result['scheme'],
                        'status_domain_info'=>$result['result'],
                        'domain_info_status_message'=>$result['message'],
                        'domain_info_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_next'=>'whois'
                    ]);
            }else if($result['result']=='active'){
                $domainAttribute=$domain['attribute'];
                if(empty($domain['attribute']['ads'])){
                    if($result['data']['status']=='blacklist'){
                        $noteMer=array('ads'=>'disable');
                        $domainAttribute=array_merge($domain['attribute'], $noteMer);
                    }
                }else if($domain['attribute']['ads']!='disable' || $domain['status']=='blacklist' || $domain['status']=='disable' || $domain['status']=='delete'){
                    if($result['data']['status']=='blacklist'){
                        $noteMer=array('ads'=>'disable');
                        $domainAttribute=array_merge($domain['attribute'], $noteMer);
                    }
                }
                DB::connection('mongodb')->collection('mongo_domain')
                    ->where('base_64',base64_encode($domain['domain']))
                    ->update([
                        'scheme' => $result['scheme'],
                        'status_domain_info'=>$result['result'],
                        'domain_info_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_next'=>'whois',
                        'title'=>$result['data']['title'],
                        'description'=>$result['data']['description'],
                        'keywords'=>$result['data']['keywords'],
                        'image'=>$result['data']['image'],
                        'get_header'=>$result['data']['get_header'],
                        'contents'=>$result['data']['contents'],
                        'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'attribute'=>$domainAttribute
                    ]);
            }
        }
    }
    public function getIpDomain(){
        $replaceDomain=str_replace('www.', '',$this->_domain);
        $getRecord=@dns_get_record($replaceDomain,DNS_ALL);
        if($getRecord){
            $ipDomain=gethostbyname($this->_domain);
            $ip='';
            if($ipDomain!=$this->_domain){
                $ip=$ipDomain;
            }
            return array(
                'result'=>'success',
                'domain'=>$this->_domain,
                'ip'=>$ip,
                'dns_record'=>$this->utf8_converter($getRecord)
            );
        }else{
            return array(
                'result'=>'error',
                'domain'=>$this->_domain
            );
        }
    }
    public function getRank(){
        $rank='';
        $country_code='';
        $rank_country='';
        $urlRank='http://data.alexa.com/data?cli=10&url='.$this->_domain;
        $getXml=simplexml_load_file($urlRank);
        if(!empty($getXml->SD->POPULARITY['TEXT'])){
            $rank=$getXml->SD->POPULARITY['TEXT'];
        }
        if(!empty($getXml->SD->COUNTRY['CODE'])){
            $country_code=$getXml->SD->COUNTRY['CODE'];
        }
        if(!empty($getXml->SD->COUNTRY['RANK'])){
            $rank_country=$getXml->SD->COUNTRY['RANK'];
        }
        if(empty($rank) && empty($country_code) && empty($rank_country)){
            return array(
                'result'=>'error'
            );
        }else{
            return array(
                'result'=>'success',
                'domain'=>$this->_domain,
                'rank'=>(string)$rank,
                'country_code'=>(string)$country_code,
                'rank_country'=>(string)$rank_country
            );
        }
    }
    public function getWhois(){
        try {
            $client = new Client([
                'headers' => ['Content-Type' => 'application/json']
            ]);
            $checkDomain = str_replace('www.', '', $this->_domain);
            $postData = '{
								"domainName": "' . $checkDomain . '"
						}';
            $response = $client->request('POST', 'https://dms.inet.vn/api/public/whois/v1/whois/directly',
                ['body' => $postData]
            );
            $getResponse = $response->getBody()->getContents();
            $resultDecode = json_decode($getResponse);
            if($resultDecode->code==1){
                return array(
                    'result'=>'error',
                    'message'=>$resultDecode->message
                );
            }else if($resultDecode->code==0){
                return array(
                    'result'=>'success',
                    'data'=>$getResponse
                );
            }
        }catch (\GuzzleHttp\Exception\ServerException $e){
            return array(
                'result'=>'error',
                'message'=>'connect_500'
            );
        }catch (\GuzzleHttp\Exception\BadResponseException $e){
            return array(
                'result'=>'error',
                'message'=>'connect_bad'
            );
        }catch (\GuzzleHttp\Exception\ClientException $e){
            return array(
                'result'=>'error',
                'message'=>'connect_400'
            );
        }catch (\GuzzleHttp\Exception\ConnectException $e){
            return array(
                'result'=>'error',
                'message'=>'connect_failed'
            );
        }catch (\GuzzleHttp\Exception\RequestException $e){
            return array(
                'result'=>'error',
                'message'=>'connect_request'
            );
        }
    }
    public function getInfoSite(){
        $title='';
        $scheme='';
        $description='';
        $keywords='';
        $image='';
        $status='true';
        try {
            $client = new Client([
                'headers' => [
                    'Content-Type' => 'text/html',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36'
                ],
                'connect_timeout' => '5',
                'timeout' => '5'
            ]);
            if(WebService::has_ssl($this->_domain)){
                $this->_domain_link='https://'.$this->_domain;
                $scheme='https';
            }else{
                $this->_domain_link='http://'.$this->_domain;
                $scheme='http';
            }
            $response = $client->request('GET', $this->_domain_link);
            $getResponse=$response->getBody()->getContents();
            $dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse;
            $doc = new \DOMDocument;
            @$doc->loadHTML($dataConvertUtf8);
            $nodes = $doc->getElementsByTagName('title');
            if($nodes->length>0){
                $title = $nodes->item(0)->nodeValue;
            }
            $metas = $doc->getElementsByTagName('meta');
            for ($i = 0; $i < $metas->length; $i++)
            {
                $meta = $metas->item($i);
                if($meta->getAttribute('name') == 'description')
                    $description = $meta->getAttribute('content');
                if($meta->getAttribute('name') == 'keywords')
                    $keywords = $meta->getAttribute('content');
                if($meta->getAttribute('property') == 'og:image')
                    $image = $meta->getAttribute('content');
            }
            if(!empty($title))
            {
                if(!WebService::checkBlacklistWord($title)){
                    $status='blacklist';
                }
            }
            if(!empty($description))
            {
                if(!WebService::checkBlacklistWord($description)){
                    $status='blacklist';
                }
            }
            if(!empty($keywords))
            {
                if(!WebService::checkBlacklistWord($keywords)){
                    $status='blacklist';
                }
            }
            $enc = mb_detect_encoding($dataConvertUtf8, mb_list_encodings(), true);
            if ($enc===false){
                //$dataConvertUtf8=WebService::str_to_utf8($dataConvertUtf8);
            }
            else if ($enc!=="UTF-8"){
                //$dataConvertUtf8 = mb_convert_encoding($dataConvertUtf8, $enc, "UTF-8");
                //$dataConvertUtf8 = iconv('UTF-8', 'UTF-8//TRANSLIT', $dataConvertUtf8);
                $dataConvertUtf8=WebService::ConvertToUTF8($dataConvertUtf8);
            }
            else {
                $dataConvertUtf8=mb_strtolower($dataConvertUtf8, 'UTF-8');
            }
            return array(
                'result'=>'active',
                'scheme'=>$scheme,
                'data'=>array(
                    'domain'=>$this->_domain,
                    'title'=>iconv('UTF-8', 'UTF-8//IGNORE', str_replace("\n", "", str_replace("\r", "", $title))),
                    'description'=>iconv('UTF-8', 'UTF-8//IGNORE', str_replace("\n", "", str_replace("\r", "", $description))),
                    'keywords'=>$keywords,
                    'image'=>$image,
                    'status'=>$status,
                    'get_header'=>WebService::ConvertToUTF8Array(@get_headers($this->_domain_link)),
                    'contents'=>$dataConvertUtf8
                )
            );
        }catch (\GuzzleHttp\Exception\ServerException $e){
            return array(
                'result'=>'error',
                'scheme'=>$scheme,
                'message'=>'connect_500'
            );
        }catch (\GuzzleHttp\Exception\BadResponseException $e){
            return array(
                'result'=>'error',
                'scheme'=>$scheme,
                'message'=>'connect_bad'
            );
        }catch (\GuzzleHttp\Exception\ClientException $e){
            return array(
                'result'=>'error',
                'scheme'=>$scheme,
                'message'=>'connect_400'
            );
        }catch (\GuzzleHttp\Exception\ConnectException $e){
            return array(
                'result'=>'error',
                'scheme'=>$scheme,
                'message'=>'connect_failed'
            );
        }catch (\GuzzleHttp\Exception\RequestException $e){
            return array(
                'result'=>'error',
                'scheme'=>$scheme,
                'message'=>'connect_request'
            );
        }
    }
    function utf8_converter($array)
    {
        array_walk_recursive($array, function(&$item, $key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
            }
        });

        return $array;
    }
}
