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
    public function getIpRecord(){
        return false;
        $getDomain=DB::connection('mongodb')->collection('mongo_domain')
            ->where('craw_next','ip')
            ->limit(1)->get();
        foreach ($getDomain as $domain){
            $this->_domain=$domain['domain'];
            if(empty($domain['attribute']['dns_record'])){
                $result=$this->getIpDomain();
                dd($result);
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
            return array(
                'result'=>'success',
                'domain'=>$this->_domain,
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
                    'get_header'=>@get_headers($this->_domain_link),
                    'contents'=>iconv('UTF-8', 'UTF-8//IGNORE', $getResponse)
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
