<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Imagick;
use WebService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Cache;
use Storage;
use File;
use App\Model\Posts;
use App\Model\Channel;

class SchedulingController extends Controller
{
    public $_domain;
    public $_domain_link;
    public $_keyword;
    public $_rulesDomain;
    public $_max_length_title=78;
    public function __construct(){
        $this->_rulesDomain = Cache::store('file')->rememberForever('rulesDomain', function()
        {
            $pdp_url = public_path('data/public_suffix_list.dat.txt');
            $rules = \Pdp\Rules::createFromPath($pdp_url);
            return $rules;
        });
    }
    public function crawNews(){
        $client = new Client([
            'headers' => [
                'Content-Type' => 'text/html',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36'
            ],
            'connect_timeout' => '5',
            'timeout' => '5'
        ]);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $getCateNews=DB::table('list_cate_news')
            ->leftJoin('cron_type_multi', 'cron_type_multi.value', '=', 'list_cate_news.id')
            ->where('cron_type_multi.type','news')
            ->where('cron_type_multi.from',config('app.url'))
            ->where('cron_type_multi.craw_at','<=',$now)
            ->orderBy('cron_type_multi.craw_at','asc')
            ->select('list_cate_news.*','cron_type_multi.craw_at','cron_type_multi.id as cron_id')
            ->limit(1)
            ->get();
        $i=0;
        $listArticle=[];
        $itemArticle=[];
        foreach ($getCateNews as $item){
            DB::table('cron_type_multi')
                ->where('id',$item->cron_id)
                ->update(
                    [
                        'craw_at'=>Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s')
                    ]
                );
            if($item->add_keyword!='true'){
                $checkKeyword=DB::connection('mongodb')->collection('mongo_keyword')
                    ->where('base_64',base64_encode($item->keyword))
                    ->first();
                if(empty($checkKeyword['keyword'])){
                    $idKeyword=DB::connection('mongodb')->collection('mongo_keyword')
                        ->insertGetId(
                            [
                                'keyword' => $item->keyword,
                                'base_64' => base64_encode($item->keyword),
                                'type'=>'cate_news',
                                'description'=>'',
                                'image'=>'',
                                'status'=>'pending',
                                'created_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                                'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                            ]
                        );
                    DB::table('list_cate_news')
                        ->where('id',$item->id)
                        ->update(
                            [
                                'add_keyword'=>'true',
                                'id_keyword'=>(string)$idKeyword
                            ]
                        );
                }else{
                    if(empty($checkKeyword['type'])){
                        DB::connection('mongodb')->collection('mongo_keyword')
                            ->where('_id',(string)$checkKeyword['_id'])
                            ->update(
                                [
                                    'type'=>'cate_news'
                                ]
                            );
                    }
                    $idKeyword=$checkKeyword['_id'];
                    DB::table('list_cate_news')
                        ->where('id',$item->id)
                        ->update(
                            [
                                'add_keyword'=>'true',
                                'id_keyword'=>(string)$idKeyword
                            ]
                        );
                }
            }else{
                $idKeyword=$item->id_keyword;
            }
            $response = $client->request('GET', $item->url);
            $getResponse=$response->getBody()->getContents();
            $dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse;
            $doc = new \DOMDocument;
            @$doc->loadHTML($dataConvertUtf8);
            $xpath = new \DOMXpath($doc);
            foreach ($xpath->evaluate('//div[@class="timeline loadmore"]') as $node) {
                $doc->saveHtml($node);
                $metas = $node->getElementsByTagName('h4');
                foreach($metas as $meta){
                    if($meta->getAttribute('class')=='story__heading'){
                        if($i<=1){
                            $title=$meta->nodeValue;
                            $itemArticle['title']=$title;
                            $getLink=$meta->getElementsByTagName('a');
                            $link=$getLink->item(0)->getAttribute('href');
                            $responseRoot = $client->request('GET', 'https://baomoi.com'.$getLink->item(0)->getAttribute('href'));
                            $getResponseRoot=$responseRoot->getBody()->getContents();
                            $pattern = '/window.location(\.assign|\.replace)\("(.+)"/i';
                            $matches = array();
                            if (preg_match($pattern, $getResponseRoot, $matches)) {
                                $itemArticle['link_root']= $matches[2];
                            }
                            $link='https://baomoi.com'.str_replace('/r/','/c/',$link);
                            $linkId=str_replace('.epi','',$link);
                            $itemArticle['id'] = substr($linkId, strrpos($linkId, '/') + 1);
                            $checkNews=DB::connection('mongodb')->collection('mongo_news')
                                ->where('news_id',$itemArticle['id'])
                                ->first();
                            if(empty($checkNews['title'])){
                                $itemArticle['link']=$link;
                                $responseDetail = $client->request('GET', $link);
                                $getResponseDetail=$responseDetail->getBody()->getContents();
                                $dataConvertUtf8Detail = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponseDetail;
                                $docDetail = new \DOMDocument;
                                @$docDetail->loadHTML($dataConvertUtf8Detail);
                                $xpathDetail = new \DOMXpath($docDetail);
                                foreach ($xpathDetail->evaluate('//div[@class="article"]') as $nodeDetail) {
                                    $docDetail->saveHtml($nodeDetail);
                                    $metasDetail = $docDetail->getElementsByTagName('div');
                                    foreach ($metasDetail as $metaD){
                                        if($metaD->getAttribute('class')=='article__sapo'){
                                            $description=$metaD->nodeValue;
                                            $itemArticle['description']=$description;
                                        }
                                        if($metaD->getAttribute('class')=='article__body'){
                                            $listImage=[];
                                            $itemImage=[];
                                            $images = $metaD->getElementsByTagName('img');
                                            if($images->length>0){
                                                foreach($images as $i => $image){
                                                    $dir=WebService::makeDir('media/news');
                                                    $name = str_random(5).'-'.time();
                                                    $imageUrl=$image->getAttribute('src');
                                                    $extension = substr($imageUrl, strrpos($imageUrl, '.') + 1);
                                                    $contents = file_get_contents($imageUrl);
                                                    $filename=$name.'.'.$extension;
                                                    Storage::put('tmp/'.$filename, $contents);
                                                    $file_path = public_path().'/'.'tmp/'.$filename;
                                                    $file_path_thumb = public_path().'/'.'tmp/thumb-'.$filename;
                                                    $img=new Imagick($file_path);
                                                    $filename_rename=$name.'.'.mb_strtolower($img->getImageFormat());
                                                    $identifyImage=$img->identifyImage();
                                                    $demention = getimagesize($file_path);
                                                    $imgThumbnail = new Imagick($file_path);
                                                    $widthMd=720; $heightMd=480;
                                                    $widthXs=320; $heightXs=213;
                                                    if($identifyImage['mimetype'] == "image/gif"){
                                                        $imgThumbnail = $imgThumbnail->coalesceImages();
                                                        foreach ($imgThumbnail as $frame) {
                                                            $frame->scaleImage($widthMd,$heightMd,true);
                                                            $frame->setImageBackgroundColor('white');
                                                            $w = $frame->getImageWidth();
                                                            $h = $frame->getImageHeight();
                                                            $frame->extentImage($widthMd,$heightMd,($w-$widthMd)/2,($h-$heightMd)/2);
                                                        }
                                                        $imgThumbnail = $imgThumbnail->deconstructImages();
                                                    }else{
                                                        $imgThumbnail->scaleImage($widthMd,$heightMd,true);
                                                        $imgThumbnail->setImageBackgroundColor('white');
                                                        $w = $imgThumbnail->getImageWidth();
                                                        $h = $imgThumbnail->getImageHeight();
                                                        $imgThumbnail->extentImage($widthMd,$heightMd,($w-$widthMd)/2,($h-$heightMd)/2);
                                                    }
                                                    $imgThumbnail->setImageCompression(Imagick::COMPRESSION_JPEG);
                                                    $imgThumbnail->setImageCompressionQuality(95);
                                                    $imgThumbnail->writeImage();
                                                    $imgThumbnail->writeImages($file_path,true);
                                                    $imgXS=new Imagick($file_path);
                                                    if($identifyImage['mimetype'] == "image/gif"){
                                                        $imgXS = $imgXS->coalesceImages();
                                                        foreach ($imgXS as $frame) {
                                                            $frame->scaleImage($widthXs,$heightXs,true);
                                                            $frame->setImageBackgroundColor('white');
                                                            $w = $frame->getImageWidth();
                                                            $h = $frame->getImageHeight();
                                                            $frame->extentImage($widthXs,$heightXs,($w-$widthXs)/2,($h-$heightXs)/2);
                                                        }
                                                        $imgXS = $imgXS->deconstructImages();
                                                    }else{
                                                        $imgXS->scaleImage($widthXs,$heightXs,true);
                                                        $imgXS->setImageBackgroundColor('white');
                                                        $w = $imgXS->getImageWidth();
                                                        $h = $imgXS->getImageHeight();
                                                        $imgXS->extentImage($widthXs,$heightXs,($w-$widthXs)/2,($h-$heightXs)/2);
                                                    }
                                                    $imgXS->setImageCompression(Imagick::COMPRESSION_JPEG);
                                                    $imgXS->setImageCompressionQuality(95);
                                                    $imgXS->writeImages($file_path_thumb,true);
                                                    Storage::disk('s3')->put($dir.'/thumb-'.$filename, file_get_contents($file_path_thumb));
                                                    Storage::disk('s3')->put($dir.'/'.$filename, file_get_contents($file_path));
                                                    $imageUrl='https://cdn.cungcap.net/'.$dir.'/'.$filename;
                                                    $imageUrl_thumb='https://cdn.cungcap.net/'.$dir.'/thumb-'.$filename;
                                                    File::delete($file_path);
                                                    File::delete($file_path_thumb);
                                                    $image->setAttribute('src', $imageUrl);
                                                    $image->setAttribute('data-width', 720);
                                                    $image->setAttribute('data-height', 480);
                                                    $itemImage['image']=$imageUrl;
                                                    $itemImage['thumb']=$imageUrl_thumb;
                                                    array_push($listImage,$itemImage);
                                                }
                                            }
                                            $pArticle=$metaD->getElementsByTagName('p');
                                            foreach ($pArticle as $p){
                                                if($p->getAttribute('class')=='body-video'){
                                                    $videos=$p->getElementsByTagName('video');
                                                    if($videos->length>0){
                                                        foreach($videos as $video){
                                                            $source=$video->getElementsByTagName('source');
                                                            $dirVideo=WebService::makeDir('media/news/video');
                                                            $linkVideo=$source->item(0)->getAttribute('data-src');

                                                            //$extensionVideo = substr($linkVideo, strrpos($linkVideo, '.') + 1);
                                                            //$ext = pathinfo($linkVideo, PATHINFO_EXTENSION);
                                                            $name = 'v-'.str_random(5).'-'.time();
                                                            $contents = file_get_contents($linkVideo);
                                                            $filename=$name.'.mp4';
                                                            Storage::put('tmp/'.$filename, $contents);
                                                            $file_path = public_path().'/'.'tmp/'.$filename;
                                                            Storage::disk('s3')->put($dirVideo.'/'.$filename, file_get_contents($file_path));
                                                            File::delete($file_path);
                                                            $videoCdn='https://cdn.cungcap.net/'.$dirVideo.'/'.$filename;
                                                            $source->item(0)->setAttribute('data-src', $videoCdn);

                                                            $image_url = $video->getAttribute('poster');
                                                            $dir=WebService::makeDir('media/news');
                                                            $name = str_random(5).'-'.time();
                                                            $extension = substr($image_url, strrpos($image_url, '.') + 1);
                                                            $contents = file_get_contents($image_url);
                                                            $filename=$name.'.'.$extension;
                                                            Storage::put('tmp/'.$filename, $contents);
                                                            $file_path = public_path().'/'.'tmp/'.$filename;
                                                            $file_path_thumb = public_path().'/'.'tmp/thumb-'.$filename;
                                                            $img=new Imagick($file_path);
                                                            $filename_rename=$name.'.'.mb_strtolower($img->getImageFormat());
                                                            $identifyImage=$img->identifyImage();
                                                            $demention = getimagesize($file_path);
                                                            $imgThumbnail = new Imagick($file_path);
                                                            $widthMd=720; $heightMd=480;
                                                            $widthXs=320; $heightXs=213;
                                                            if($identifyImage['mimetype'] == "image/gif"){
                                                                $imgThumbnail = $imgThumbnail->coalesceImages();
                                                                foreach ($imgThumbnail as $frame) {
                                                                    $frame->scaleImage($widthMd,$heightMd,true);
                                                                    $frame->setImageBackgroundColor('white');
                                                                    $w = $frame->getImageWidth();
                                                                    $h = $frame->getImageHeight();
                                                                    $frame->extentImage($widthMd,$heightMd,($w-$widthMd)/2,($h-$heightMd)/2);
                                                                }
                                                                $imgThumbnail = $imgThumbnail->deconstructImages();
                                                            }else{
                                                                $imgThumbnail->scaleImage($widthMd,$heightMd,true);
                                                                $imgThumbnail->setImageBackgroundColor('white');
                                                                $w = $imgThumbnail->getImageWidth();
                                                                $h = $imgThumbnail->getImageHeight();
                                                                $imgThumbnail->extentImage($widthMd,$heightMd,($w-$widthMd)/2,($h-$heightMd)/2);
                                                            }
                                                            $imgThumbnail->setImageCompression(Imagick::COMPRESSION_JPEG);
                                                            $imgThumbnail->setImageCompressionQuality(95);
                                                            $imgThumbnail->writeImage();
                                                            $imgThumbnail->writeImages($file_path,true);
                                                            $imgXS=new Imagick($file_path);
                                                            if($identifyImage['mimetype'] == "image/gif"){
                                                                $imgXS = $imgXS->coalesceImages();
                                                                foreach ($imgXS as $frame) {
                                                                    $frame->scaleImage($widthXs,$heightXs,true);
                                                                    $frame->setImageBackgroundColor('white');
                                                                    $w = $frame->getImageWidth();
                                                                    $h = $frame->getImageHeight();
                                                                    $frame->extentImage($widthXs,$heightXs,($w-$widthXs)/2,($h-$heightXs)/2);
                                                                }
                                                                $imgXS = $imgXS->deconstructImages();
                                                            }else{
                                                                $imgXS->scaleImage($widthXs,$heightXs,true);
                                                                $imgXS->setImageBackgroundColor('white');
                                                                $w = $imgXS->getImageWidth();
                                                                $h = $imgXS->getImageHeight();
                                                                $imgXS->extentImage($widthXs,$heightXs,($w-$widthXs)/2,($h-$heightXs)/2);
                                                            }
                                                            $imgXS->setImageCompression(Imagick::COMPRESSION_JPEG);
                                                            $imgXS->setImageCompressionQuality(95);
                                                            $imgXS->writeImages($file_path_thumb,true);
                                                            Storage::disk('s3')->put($dir.'/thumb-'.$filename, file_get_contents($file_path_thumb));
                                                            Storage::disk('s3')->put($dir.'/'.$filename, file_get_contents($file_path));
                                                            $imageUrlVideo='https://cdn.cungcap.net/'.$dir.'/'.$filename;
                                                            $imageUrlVideo_thumb='https://cdn.cungcap.net/'.$dir.'/thumb-'.$filename;
                                                            File::delete($file_path);
                                                            File::delete($file_path_thumb);
                                                            $video->setAttribute('poster',$imageUrlVideo);
                                                            $itemImage['image']=$imageUrlVideo;
                                                            $itemImage['thumb']=$imageUrlVideo_thumb;
                                                            array_push($listImage,$itemImage);
                                                        }
                                                    }
                                                }
                                            }
                                            $articleBody=$docDetail->savehtml($metaD);
                                            $itemArticle['body']=$articleBody;
                                        }
                                    }
                                }
                                DB::connection('mongodb')->collection('mongo_news')
                                    ->insertGetId(
                                        [
                                            'parent' => $item->keyword,
                                            'parent_id'=>(string)$idKeyword,
                                            'news_id'=>$itemArticle['id'],
                                            'link'=>$itemArticle['link'],
                                            'link_root'=>$itemArticle['link_root'],
                                            'title' => $itemArticle['title'],
                                            'description'=>$itemArticle['description'],
                                            'body'=>$itemArticle['body'],
                                            'image'=>$listImage,
                                            'status'=>'pending',
                                            'created_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                                            'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                                        ]
                                    );
                                array_push($listArticle,$itemArticle);
                            }
                        }
                        $i++;
                    }
                }
            }
        }
        return $listArticle;
    }
    public function indexPostElasticsearch(){
        if(config('app.env')!='local'){
            $getPost=Posts::where('posts_status','active')
                ->where('posts_title','!=',null)
                ->leftJoin('index_post_elasticsearch', 'index_post_elasticsearch.posts_id', '=', 'posts.id')
                ->whereNull('index_post_elasticsearch.posts_id')
                ->select('posts.*')
                ->orderBy('posts.id','asc')
                ->limit(50)->get();
            //dd($getPost);
            foreach($getPost as $post){
                $post->addToIndex();
                DB::table('index_post_elasticsearch')->insertGetId(
                    [
                        'posts_id'=>$post->id,
                        'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
                    ]
                );
                echo 'index post '.$post->posts_title.'<p>';
            }
        }
    }
    public function UpdateImage(){
        $updateImage=\App\Model\Mongo_Image::where('update_index','exists',false)->limit(500)->get();
        foreach ($updateImage as $item){
            $title=WebService::convertToUTF8(mb_substr($item->title, 0, \App\Model\Mongo_Image::MAX_LENGTH_TITLE));
            $parse=parse_url($item->link);
            $check=\App\Model\Mongo_Image::where('base_64',base64_encode($title))
                ->where('domain',$parse['host'])
                ->first();
            if(empty($check->title)){
                $item->title=$title;
                $item->titlefull=WebService::convertToUTF8($item->title);
                $item->base_64=base64_encode($title);
                $item->domain=$parse['host'];
                $item->update_index=1;
                $item->save();
                echo WebService::convertToUTF8($item->title).'<p>';
            }else{
                echo 'Delete '.WebService::convertToUTF8($check->title).'<p>';
                $item->delete();
            }
        }
    }
    public function updateSite(){
        $getSite=DB::connection('mongodb')->collection('mongo_site')
            ->where('update_site','exists',false)
            ->limit(5)->get();
        foreach($getSite as $item){
            $title=mb_substr($item['title'], 0, $this->_max_length_title);
            DB::connection('mongodb')->collection('mongo_site')
                ->where('_id',(string)$item['_id'])
                ->update(
                    [
                        'title'=>$title,
                        'title_full'=>$item['title'],
                        'base_64'=>base64_encode($title),
                        'domain'=>$item['attribute']['domain'],
                        'update_site'=>1
                    ]
                );
        }
    }
    public function crawVideoSearch(){
        $getKeywords = DB::connection('mongodb')->collection('mongo_keyword')
            ->where('craw_next', 'step_4')
            ->limit(1)->get();
        foreach ($getKeywords as $item) {
            $this->_keyword = $item['keyword'];
            $result = $this->getVideoFromYoutube();
            if($result['result']=='success'){
                if(count($result['data'])){
                    $videoIdArray=[];
                    foreach ($result['data'] as $videoItem){
                        $check=\App\Model\Mongo_video::where('yid',$videoItem['idYoutube'])
                            ->first();
                        if(empty($check->title)){
                            try {
                                $dir=WebService::makeDir('media/img');
                                $name = str_random(5).'-'.time();
                                $imageUrl='https://img.youtube.com/vi/'.$videoItem['idYoutube'].'/0.jpg';
                                $contents = file_get_contents($imageUrl);
                                $filename=$name.'.jpg';
                                Storage::put('tmp/'.$filename, $contents);
                                $file_path = public_path().'/'.'tmp/'.$filename;
                                $file_path_thumb = public_path().'/'.'tmp/thumb-'.$filename;
                                $img=new Imagick($file_path);
                                $filename_rename=$name.'.'.mb_strtolower($img->getImageFormat());
                                $identifyImage=$img->identifyImage();
                                $demention = getimagesize($file_path);
                                $imgThumbnail = new Imagick($file_path);
                                $widthMd=720; $heightMd=480;
                                $widthXs=210; $heightXs=118;
                                if($identifyImage['mimetype'] == "image/gif"){
                                    $imgThumbnail = $imgThumbnail->coalesceImages();
                                    foreach ($imgThumbnail as $frame) {
                                        $frame->scaleImage($widthMd,$heightMd,true);
                                        $frame->setImageBackgroundColor('white');
                                        $w = $frame->getImageWidth();
                                        $h = $frame->getImageHeight();
                                        $frame->extentImage($widthMd,$heightMd,($w-$widthMd)/2,($h-$heightMd)/2);
                                    }
                                    $imgThumbnail = $imgThumbnail->deconstructImages();
                                }else{
                                    $imgThumbnail->scaleImage($widthMd,$heightMd,true);
                                    $imgThumbnail->setImageBackgroundColor('white');
                                    $w = $imgThumbnail->getImageWidth();
                                    $h = $imgThumbnail->getImageHeight();
                                    $imgThumbnail->extentImage($widthMd,$heightMd,($w-$widthMd)/2,($h-$heightMd)/2);
                                }
                                $imgThumbnail->setImageCompression(Imagick::COMPRESSION_JPEG);
                                $imgThumbnail->setImageCompressionQuality(95);
                                $imgThumbnail->writeImage();
                                $imgThumbnail->writeImages($file_path,true);
                                $imgXS=new Imagick($file_path);
                                if($identifyImage['mimetype'] == "image/gif"){
                                    $imgXS = $imgXS->coalesceImages();
                                    foreach ($imgXS as $frame) {
                                        $frame->scaleImage($widthXs,$heightXs,true);
                                        $frame->setImageBackgroundColor('white');
                                        $w = $frame->getImageWidth();
                                        $h = $frame->getImageHeight();
                                        $frame->extentImage($widthXs,$heightXs,($w-$widthXs)/2,($h-$heightXs)/2);
                                    }
                                    $imgXS = $imgXS->deconstructImages();
                                }else{
                                    $imgXS->scaleImage($widthXs,$heightXs,true);
                                    $imgXS->setImageBackgroundColor('white');
                                    $w = $imgXS->getImageWidth();
                                    $h = $imgXS->getImageHeight();
                                    $imgXS->extentImage($widthXs,$heightXs,($w-$widthXs)/2,($h-$heightXs)/2);
                                }
                                $imgXS->setImageCompression(Imagick::COMPRESSION_JPEG);
                                $imgXS->setImageCompressionQuality(95);
                                $imgXS->writeImages($file_path_thumb,true);
                                Storage::disk('s3')->put($dir.'/thumb-'.$filename, file_get_contents($file_path_thumb));
                                Storage::disk('s3')->put($dir.'/'.$filename, file_get_contents($file_path));
                                $image='//cdn.cungcap.net/'.$dir.'/'.$filename;
                                $image_thumb='//cdn.cungcap.net/'.$dir.'/thumb-'.$filename;
                                File::delete($file_path);
                                File::delete($file_path_thumb);
                                $videoId=DB::connection('mongodb')->collection('mongo_video')
                                    ->insertGetId(
                                        [
                                            'parent'=>$item['keyword'],
                                            'parent_id'=>(string)$item['_id'],
                                            'title' => $videoItem['title'],
                                            'description'=>$videoItem['description'],
                                            'yid'=>$videoItem['idYoutube'],
                                            'image'=>$image,
                                            'thumb'=>$image_thumb,
                                            'image_size'=>$demention,
                                            'view'=>0,
                                            'status'=>'pending',
                                            'created_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                                            'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                                        ]
                                    );
                                echo 'Insert video have image url '.$image.'<p>';
                                array_push($videoIdArray,(string)$videoId);
                            } catch (\Exception $e) {
                                continue;
                            }
                        }else{
                            echo 'Update id video for keyword '.$check->title.'<p>';
                            array_push($videoIdArray,(string)$check->id);
                        }
                    }
                    DB::connection('mongodb')->collection('mongo_keyword')
                        ->where('_id',(string)$item['_id'])
                        ->update([
                            'video_relate'=>$videoIdArray,
                            'status_craw_video'=>'success',
                            'craw_video_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_next'=>'step_5',
                            'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                        ]);
                    echo 'insert craw video for '.$item['keyword'].'<p>';
                }else{
                    DB::connection('mongodb')->collection('mongo_keyword')
                        ->where('_id',(string)$item['_id'])
                        ->update([
                            'status_craw_video'=>'error',
                            'video_status_message'=>'no_video',
                            'craw_video_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_next'=>'step_5'
                        ]);
                    echo 'craw video for keyword error '.$item['keyword'].'<p>';
                }
            }else if($result['result']=='error'){
                DB::connection('mongodb')->collection('mongo_keyword')
                    ->where('_id',(string)$item['_id'])
                    ->update([
                        'status_craw_video'=>'error',
                        'video_status_message'=>$result['message'],
                        'craw_video_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_next'=>'step_5'
                    ]);
                echo 'craw video for keyword error '.$item['keyword'].'<p>';
            }
        }
    }
    public function crawImageSearch()
    {
        $getKeywords = DB::connection('mongodb')->collection('mongo_keyword')
            ->where('craw_next', 'step_3')
            ->limit(1)->get();
        foreach ($getKeywords as $item) {
            $this->_keyword = $item['keyword'];
            $result = $this->getImageFromSearch();
            if($result['result']=='success'){
                if(count($result['data'])){
                    $imageIdArray=[];
                    foreach ($result['data'] as $imageItem){
                        $title=WebService::convertToUTF8(mb_substr($imageItem['title'], 0, \App\Model\Mongo_Image::MAX_LENGTH_TITLE));
                        $parse=parse_url($imageItem['link']);
                        $check=\App\Model\Mongo_Image::where('base_64',base64_encode($title))
                            ->where('domain',$parse['host'])
                            ->first();
                        if(empty($check->title)){
                            $dir=WebService::makeDir('media/img');
                            $name = str_random(5).'-'.time();
                            $extension = substr($imageItem['link'], strrpos($imageItem['link'], '.') + 1);
                            try {
                                $contents = file_get_contents($imageItem['link']);
                                $filename=$name.'.'.$extension;
                                Storage::put('tmp/'.$filename, $contents);
                                $file_path = public_path().'/'.'tmp/'.$filename;
                                $file_path_thumb = public_path().'/'.'tmp/thumb-'.$filename;
                                $img=new Imagick($file_path);
                                $filename_rename=$name.'.'.mb_strtolower($img->getImageFormat());
                                $file_path_rename = public_path().'/'.'tmp/'.$filename_rename;
                                $file_path_thumb_rename = public_path().'/'.'tmp/thumb-'.$filename_rename;
                                $identifyImage=$img->identifyImage();
                                $demention = getimagesize($file_path);
                                $imgThumbnail = new Imagick($file_path);
                                $widthMd=720; $heightMd=480;
                                $widthXs=210; $heightXs=118;
                                if($identifyImage['mimetype'] == "image/gif"){
                                    $imgThumbnail = $imgThumbnail->coalesceImages();
                                    foreach ($imgThumbnail as $frame) {
                                        $frame->scaleImage($widthMd,$heightMd,true);
                                        $frame->setImageBackgroundColor('white');
                                        $w = $frame->getImageWidth();
                                        $h = $frame->getImageHeight();
                                        $frame->extentImage($widthMd,$heightMd,($w-$widthMd)/2,($h-$heightMd)/2);
                                    }
                                    $imgThumbnail = $imgThumbnail->deconstructImages();
                                }else{
                                    $imgThumbnail->scaleImage($widthMd,$heightMd,true);
                                    $imgThumbnail->setImageBackgroundColor('white');
                                    $w = $imgThumbnail->getImageWidth();
                                    $h = $imgThumbnail->getImageHeight();
                                    $imgThumbnail->extentImage($widthMd,$heightMd,($w-$widthMd)/2,($h-$heightMd)/2);
                                }
                                $imgThumbnail->setImageCompression(Imagick::COMPRESSION_JPEG);
                                $imgThumbnail->setImageCompressionQuality(95);
                                $imgThumbnail->writeImage();
                                $imgThumbnail->writeImages($file_path_rename,true);
                                $imgXS=new Imagick($file_path);
                                if($identifyImage['mimetype'] == "image/gif"){
                                    $imgXS = $imgXS->coalesceImages();
                                    foreach ($imgXS as $frame) {
                                        $frame->scaleImage($widthXs,$heightXs,true);
                                        $frame->setImageBackgroundColor('white');
                                        $w = $frame->getImageWidth();
                                        $h = $frame->getImageHeight();
                                        $frame->extentImage($widthXs,$heightXs,($w-$widthXs)/2,($h-$heightXs)/2);
                                    }
                                    $imgXS = $imgXS->deconstructImages();
                                }else{
                                    $imgXS->scaleImage($widthXs,$heightXs,true);
                                    $imgXS->setImageBackgroundColor('white');
                                    $w = $imgXS->getImageWidth();
                                    $h = $imgXS->getImageHeight();
                                    $imgXS->extentImage($widthXs,$heightXs,($w-$widthXs)/2,($h-$heightXs)/2);
                                }
                                $imgXS->setImageCompression(Imagick::COMPRESSION_JPEG);
                                $imgXS->setImageCompressionQuality(95);
                                $imgXS->writeImages($file_path_thumb_rename,true);
                                Storage::disk('s3')->put($dir.'/thumb-'.$filename_rename, file_get_contents($file_path_thumb_rename));
                                Storage::disk('s3')->put($dir.'/'.$filename_rename, file_get_contents($file_path_rename));
                                $image='//cdn.cungcap.net/'.$dir.'/'.$filename_rename;
                                $image_thumb='//cdn.cungcap.net/'.$dir.'/thumb-'.$filename_rename;
                                File::delete($file_path);
                                File::delete($file_path_thumb);
                                File::delete($file_path_rename);
                                File::delete($file_path_thumb_rename);
                                $imageId=DB::connection('mongodb')->collection('mongo_image')
                                    ->insertGetId(
                                        [
                                            'parent_name'=>$item['keyword'],
                                            'parent_id'=>(string)$item['_id'],
                                            'title' => $title,
                                            'base_64' => base64_encode($title),
                                            'titlefull'=>$imageItem['title'],
                                            'link'=>$imageItem['link'],
                                            'domain'=>$imageItem['domain'],
                                            'url'=>$imageItem['url'],
                                            'attribute'=>array(
                                                'image'=>$image,
                                                'thumb'=>$image_thumb,
                                                'craw_content'=>$imageItem['content'],
                                                'domain'=>$imageItem['domain']
                                            ),
                                            'image_size'=>$demention,
                                            'image_extension'=>$extension,
                                            'status'=>'pending',
                                            'created_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                                            'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                                        ]
                                    );
                                echo 'Insert image '.$image.'<p>';
                                array_push($imageIdArray,(string)$imageId);
                            } catch (\Exception $e) {
                                continue;
                            }
                        }else{
                            echo 'Update id image for keyword '.$check->link.'<p>';
                            array_push($imageIdArray,(string)$check->id);
                        }
                    }
                    DB::connection('mongodb')->collection('mongo_keyword')
                        ->where('_id',(string)$item['_id'])
                        ->update([
                            'image_relate'=>$imageIdArray,
                            'status_craw_image'=>'success',
                            'craw_image_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_next'=>'step_4',
                            'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                        ]);
                    echo 'insert craw image for '.$item['keyword'].'<p>';
                }else{
                    DB::connection('mongodb')->collection('mongo_keyword')
                        ->where('_id',(string)$item['_id'])
                        ->update([
                            'status_craw_image'=>'error',
                            'image_status_message'=>'no_image',
                            'craw_image_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_next'=>'step_4'
                        ]);
                    echo 'craw image for keyword error '.$item['keyword'].'<p>';
                }
            }else{
                DB::connection('mongodb')->collection('mongo_keyword')
                    ->where('_id',(string)$item['_id'])
                    ->update([
                        'status_craw_image'=>'error',
                        'imageg_status_message'=>$result['message'],
                        'craw_image_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_next'=>'step_4'
                    ]);
                echo 'craw image for keyword error '.$item['keyword'].'<p>';
            }
        }
    }
    public function keywordSuggest(){
        $getKeywords=DB::connection('mongodb')->collection('mongo_keyword')
            ->where('app_domain','exists',false)
            ->where('craw_next','step_2')
            ->limit(1)->get();
        foreach($getKeywords as $item){
            if(!empty($item['keyword'])){

                $this->_keyword=$item['keyword'];
                $result=$this->getSuggestqueries();
                if(!empty($result['result']) && $result['result']=='success'){
                    $keywordIdArray=[];
                    foreach($result['data'][1] as $value){
                        if(!empty($value)){
                            $keyword=WebService::convertToUTF8(mb_substr($value, 0, $this->_max_length_title));
                            $checkKeyword=DB::connection('mongodb')->collection('mongo_keyword')
                                ->where('base_64',base64_encode($keyword))
                                ->first();
                            if(empty($checkKeyword['keyword'])){
                                $keywordId=DB::connection('mongodb')->collection('mongo_keyword')
                                    ->insertGetId(
                                        [
                                            'parent'=>$item['keyword'],
                                            'parent_id'=>(string)$item['_id'],
                                            'keyword' => $keyword,
                                            'base_64' => base64_encode($keyword),
                                            'description'=>'',
                                            'image'=>'',
                                            'status'=>'pending',
                                            'created_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                                            'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                                        ]
                                    );
                                array_push($keywordIdArray,(string)$keywordId);
                            }else{
                                array_push($keywordIdArray,(string)$checkKeyword['_id']);
                            }
                        }
                    }
                    DB::connection('mongodb')->collection('mongo_keyword')
                        ->where('_id',(string)$item['_id'])
                        ->update([
                            'keyword_relate'=>$keywordIdArray,
                            'status_craw_suggest'=>'success',
                            'craw_suggest_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_next'=>'step_3',
                            'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                        ]);
                    echo 'craw suggest keyword success '.$item['keyword'].'<p>';
                }else if(!empty($result['result']) && $result['result']=='error'){
                    DB::connection('mongodb')->collection('mongo_keyword')
                        ->where('_id',(string)$item['_id'])
                        ->update([
                            'status_craw_suggest'=>'error',
                            'suggest_status_message'=>$result['message'],
                            'craw_suggest_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                            'craw_next'=>'step_3'
                        ]);
                    echo 'craw suggest keyword error '.$item['keyword'].'<p>';
                }
            }
        }
    }
    // Step 2 keyword
    public function keywordCraw(){
        $getKeywords=DB::connection('mongodb')->collection('mongo_keyword')
            ->where('app_domain','exists',false)
            ->where('craw_next','exists',false)
            ->orderBy('created_at','asc')
            ->limit(1)->get();
        foreach ($getKeywords as $item){
            if(!empty($item['keyword'])){
                $this->_keyword=$item['keyword'];
                $getRoleSite=DB::table('role_change_site_craw')->first();
                $result=array();
                if(empty($getRoleSite->site)){
                    $idRole=DB::table('role_change_site_craw')->insertGetId(
                        [
                            'site'=>'google',
                            'craw_at'=>Carbon::now(),
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now()
                        ]
                    );
                    $getRoleSite=DB::table('role_change_site_craw')->where('id',$idRole)->first();
                }
                if(!empty($getRoleSite->site) && Carbon::parse($getRoleSite->craw_at)->addSecond(60)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s')){
                    if($getRoleSite->site=='google'){
                        DB::table('role_change_site_craw')->update([
                            'site'=>'yahoo',
                            'craw_at'=>Carbon::now()
                        ]);
                        $result=$this->getSearchYahooFrom();
                    }else if($getRoleSite->site=='yahoo'){
                        DB::table('role_change_site_craw')->update([
                            'site'=>'bing',
                            'craw_at'=>Carbon::now()
                        ]);
                        $result=$this->getSearchBingFrom();
                    }else if($getRoleSite->site=='bing'){
                        DB::table('role_change_site_craw')->update([
                            'site'=>'google',
                            'craw_at'=>Carbon::now()
                        ]);
                        $result=$this->getSearchGoogleFrom();
                    }
                    if(is_array($result) && !empty($result['result']) && $result['result']=='success' && count($result['data'])){
                        $siteArray=[];
                        foreach ($result['data'] as $data){
                            if(!empty($data['title']) && !empty($data['domain'])){
                                $title=WebService::convertToUTF8(mb_substr($data['title'], 0, $this->_max_length_title));
                                $checkSite=DB::connection('mongodb')->collection('mongo_site')
                                    ->where('base_64',base64_encode($title))
                                    ->where('domain',$data['domain'])
                                    ->first();
                                if(empty($checkSite['title'])){
                                    $siteId=DB::connection('mongodb')->collection('mongo_site')
                                        ->insertGetId(
                                            [
                                                'parent'=>$item['keyword'],
                                                'parent_id'=>(string)$item['_id'],
                                                'title' => $title,
                                                'base_64' => base64_encode($title),
                                                'title_full'=>WebService::convertToUTF8($data['title']),
                                                'link' => $data['linkFull'],
                                                'domain'=>$data['domain'],
                                                'description'=>WebService::convertToUTF8($data['description']),
                                                'attribute'=>[],
                                                'view'=>0,
                                                'status'=>'pending',
                                                'created_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                                                'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                                            ]
                                        );
                                    array_push($siteArray,(string)$siteId);
                                    echo 'insert site '.$data['linkFull'].' have count '.count($result['data']).'<p>';
                                }else{
                                    array_push($siteArray,(string)$checkSite['_id']);
                                    echo 'update site relate '.$checkSite['link'].' have count '.count($result['data']).'<p>';
                                }
                                $checkDomain=DB::connection('mongodb')->collection('mongo_domain')
                                    ->where('base_64',base64_encode($data['domain']))->first();
                                if(empty($checkDomain['domain'])){
                                    DB::connection('mongodb')->collection('mongo_domain')
                                        ->insertGetId(
                                            [
                                                'domain' => $data['domain'],
                                                'base_64' => base64_encode($data['domain']),
                                                'title'=>'',
                                                'description'=>'',
                                                'keywords'=>'',
                                                'image'=>'',
                                                'attribute'=>[],
                                                'view'=>0,
                                                'status'=>'pending',
                                                'created_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                                                'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                                            ]
                                        );
                                    echo 'insert domain '.$data['domain'].'<p>';
                                }
                            }
                        }
                        DB::connection('mongodb')->collection('mongo_keyword')
                            ->where('_id',(string)$item['_id'])
                            ->update([
                                'site_craw_content'=>WebService::convertToUTF8($result['content']),
                                'site_relate'=>$siteArray,
                                'status_craw_site'=>'success',
                                'craw_site_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                                'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                                'craw_next'=>'step_2',
                                'updated_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now())
                            ]);
                        echo 'craw keyword success '.$item['keyword'].'<p>';
                    }else if($result['result']=='error'){
                        DB::connection('mongodb')->collection('mongo_keyword')
                            ->where('_id',(string)$item['_id'])
                            ->update([
                                'status_craw_site'=>'error',
                                'craw_site_status_message'=>$result['message'],
                                'craw_site_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                                'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                                'craw_next'=>'step_2'
                            ]);
                        echo 'craw keyword error '.$item['keyword'].'<p>';
                    }
                }
            }else{
                DB::connection('mongodb')->collection('mongo_keyword')
                    ->where('_id',(string)$item['_id'])
                    ->update([
                        'status_craw_site'=>'error',
                        'craw_site_update_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_at'=>new \MongoDB\BSON\UTCDateTime(Carbon::now()),
                        'craw_next'=>'step_2'
                    ]);
                echo 'craw keyword error '.$item['keyword'].'<p>';
            }
        }
    }
    //Step 1
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
            ->where('index','<',4)
            ->limit(1000)->get();
        foreach ($getSite as $item){
//            if(!empty($item['index_replay'])){
//
//                DB::connection('mongodb_old')->collection('note')->where('type','site')
//                    ->where('_id',(string)$item['_id'])
//                    ->update(['index_replay' => $item['index_replay']+1]);
//            }else{
//                DB::connection('mongodb_old')->collection('note')->where('type','site')
//                    ->where('_id',(string)$item['_id'])
//                    ->update(['index_replay' => 1]);
//            }
//            if(!empty($item['index_replay']) && $item['index_replay']==3){
//                DB::connection('mongodb_old')->collection('note')->where('type','site')
//                    ->where('_id',(string)$item['_id'])
//                    ->update(['index' => 4]);
//                echo $item['title'].' update success <br>';
//            }
            $title=WebService::convertToUTF8(mb_substr($item['title'], 0, $this->_max_length_title));
            $checkSite=DB::connection('mongodb')->collection('mongo_site')
                ->where('base_64',base64_encode($title))
                ->where('domain',$item['attribute']['domain'])
                ->first();
            if(empty($checkSite['title'])){
                $getParent=array(
                    'domain'=>''
                );
                if(!empty($item['view'])){
                    $view=$item['view'];
                }else{
                    $view='';
                }
//                if(!empty($item['parent'])) {
//                    $getParent = DB::connection('mongodb_old')->collection('note')
//                        ->where('type', 'domain')
//                        ->where('_id', $item['parent'])
//                        ->first();
//                }
                DB::connection('mongodb')->collection('mongo_site')
                    ->insertGetId(
                        [
                            'parent'=>$getParent['domain'],
                            'title' => $title,
                            'base_64' => base64_encode($title),
                            'title_full'=>$item['title'],
                            'link' => $item['link'],
                            'domain'=>$item['attribute']['domain'],
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
                    ->update(['index' => 4]);
                echo $item['title'].' insert success <br>';
            }else{
                DB::connection('mongodb_old')->collection('note')->where('type','site')
                    ->where('_id',(string)$item['_id'])
                    ->update(['index' => 4]);
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
            ->where('craw_next','exists',false)
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
                        'description'=>mb_substr($result['data']['description'], 0, \App\Model\Mongo_Image::MAX_LENGTH_DESCRIPTION),
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
            return array(
                'result'=>'active',
                'scheme'=>$scheme,
                'data'=>array(
                    'domain'=>$this->_domain,
                    'title'=>WebService::convertToUTF8(str_replace("\n", "", str_replace("\r", "", $title))),
                    'description'=>WebService::convertToUTF8(str_replace("\n", "", str_replace("\r", "", $description))),
                    'keywords'=>WebService::convertToUTF8($keywords),
                    'image'=>$image,
                    'status'=>$status,
                    'get_header'=>WebService::ConvertToUTF8Array(@get_headers($this->_domain_link)),
                    'contents'=>WebService::convertToUTF8($dataConvertUtf8)
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
        }catch (\Exception $e){
            return array(
                'result'=>'error',
                'scheme'=>$scheme,
                'message'=>'connect_failed'
            );
        }
    }
    public function getSearchGoogleFrom()
    {
        $listArray=[];
        $itemSearch=[];
        try {
            $client = new Client([
                'headers' => [ 'Content-Type' => 'text/html' ],
                'connect_timeout' => '5',
                'timeout' => '5'
            ]);
            $response = $client->request('GET', 'https://www.google.com.vn/search?q='.urlencode($this->_keyword));
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
                                        $itemSearch['linkFull']=$query['/url?q'];
                                        $parsedUrl=parse_url($query['/url?q']);
                                        $domain=$this->_rulesDomain->resolve($parsedUrl['host']);
                                        if(!empty($domain->getRegistrableDomain())){
                                            $itemSearch['domain']=$domain->getRegistrableDomain();
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
                                    $description = $metagetDescription->nodeValue;
                                    $itemSearch['description']=str_replace("\n", "", str_replace("\r", "", $description));
                                }
                            }
                            $status='true';
                            if(!empty($itemSearch['title'])){
                                if(!WebService::checkBlacklistWord($itemSearch['title'])){
                                    $status='false';
                                }
                            }
                            if(!empty($itemSearch['description']))
                            {
                                if(!WebService::checkBlacklistWord($itemSearch['description'])){
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
            return array(
                'result'=>'success',
                'from'=>'google',
                'data'=>$listArray,
                'content'=>$dataConvertUtf8
            );
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
        }catch (\Exception $e){
            return array(
                'result'=>'error',
                'message'=>'connect_failed'
            );
        }
    }
    public function getSearchYahooFrom()
    {
        $listArray=[];
        $itemSearch=[];
        try {
            $client = new Client([
                'headers' => [ 'Content-Type' => 'text/html' ],
                'connect_timeout' => '5',
                'timeout' => '5'
            ]);
            $response = $client->request('GET', 'https://vn.search.yahoo.com/search?p='.urlencode($this->_keyword));
            $getResponse=$response->getBody()->getContents();
            $dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse;
            $doc = new \DOMDocument;
            @$doc->loadHTML($dataConvertUtf8);
            $xpath = new \DOMXpath($doc);
            foreach ($xpath->evaluate('//div[@id="web"]') as $node) {
                $html=$doc->saveHtml($node);
                $metas = $doc->getElementsByTagName('ol');
                foreach($metas as $meta){
                    if($meta->getAttribute('class')=='mb-15 reg searchCenterMiddle'){
                        $getElement = $meta->getElementsByTagName('li');
                        foreach($getElement as $element){
                            $getTitle=$element->getElementsByTagName('h3');
                            $getLink=$element->getElementsByTagName('a');
                            $getDescription=$element->getElementsByTagName('div');

                            if($getTitle->length>0 && $getLink->length>0 && $getDescription->length>0){
                                $getLinkFull=$getLink->item(0)->getAttribute('href');
                                $itemSearch['title']=$getTitle->item(0)->nodeValue;
                                $getDomainLink=preg_replace('/(.*?RU=)(.*?)(\/RK.*)/', '$2', urldecode($getLinkFull));
                                if(!empty($getDomainLink)){
                                    $parsedUrl=parse_url($getDomainLink);
                                    $domain=$this->_rulesDomain->resolve($parsedUrl['host']);
                                    if(!empty($domain->getRegistrableDomain())){
                                        $itemSearch['linkFull']=$getDomainLink;
                                        $itemSearch['domain']=$domain->getRegistrableDomain();
                                    }
                                }
                                foreach($getDescription as $getDes){
                                    if($getDes->getAttribute('class')=='compText aAbs'){
                                        $description=$getDes->nodeValue;
                                        $itemSearch['description']=str_replace("\n", "", str_replace("\r", "", $description));
                                    }
                                }
                                $status='true';
                                if(!empty($itemSearch['title'])){
                                    if(!WebService::checkBlacklistWord($itemSearch['title'])){
                                        $status='false';
                                    }
                                }
                                if(!empty($itemSearch['description']))
                                {
                                    if(!WebService::checkBlacklistWord($itemSearch['description'])){
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
            }
            return array(
                'result'=>'success',
                'from'=>'yahoo',
                'data'=>$listArray,
                'content'=>$dataConvertUtf8
            );
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
        }catch (\Exception $e){
            return array(
                'result'=>'error',
                'message'=>'connect_failed'
            );
        }
    }
    public function getSearchBingFrom()
    {
        $listArray=[];
        $itemSearch=[];
        try {
            $client = new Client([
                'headers' => [ 'Content-Type' => 'text/html' ],
                'connect_timeout' => '5',
                'timeout' => '5'
            ]);
            $response = $client->request('GET', 'https://www.bing.com/search?q='.urlencode($this->_keyword));
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
                                $itemSearch['description']=str_replace("\n", "", str_replace("\r", "", $meta->getElementsByTagName('p')->item(0)->nodeValue));
                            }
                            $parsedUrl=parse_url($getLinkFull);
                            $domain=$this->_rulesDomain->resolve($parsedUrl['host']);
                            if(!empty($domain->getRegistrableDomain())){
                                $itemSearch['domain']=$domain->getRegistrableDomain();
                            }
                            $status='true';
                            if(!empty($itemSearch['title'])){
                                if(!WebService::checkBlacklistWord($itemSearch['title'])){
                                    $status='false';
                                }
                            }
                            if(!empty($itemSearch['description']))
                            {
                                if(!WebService::checkBlacklistWord($itemSearch['description'])){
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
            return array(
                'result'=>'success',
                'from'=>'bing',
                'data'=>$listArray,
                'content'=>$dataConvertUtf8
            );
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
        }catch (\Exception $e){
            return array(
                'result'=>'error',
                'message'=>'connect_failed'
            );
        }
    }
    public function getSuggestqueries(){

        try {
            $url='http://suggestqueries.google.com/complete/search?client=chrome&q='.urlencode($this->_keyword);
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
            if(!empty($content[1]) && count($content[1])>0){
                return array(
                    'result'=>'success',
                    'data'=>$content,
                    'keyword'=>$this->_keyword
                );
            }else{
                return array(
                    'result'=>'error',
                    'message'=>'empty_keyword'
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
        }catch (\Exception $e){
            return array(
                'result'=>'error',
                'message'=>'connect_failed'
            );
        }
    }
    public function getImageFromSearch(){
        try{
            $url='https://www.google.com.vn/search?q='.urlencode($this->_keyword).'&tbm=isch';
            $client = new Client([
                'headers' => [
                    'Content-Type' => 'text/html',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36'
                ],
                'connect_timeout' => '2',
                'timeout' => '2'
            ]);
            $response = $client->request('GET', $url);
            $getResponse=$response->getBody()->getContents();
            $dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse;
            $doc = new \DOMDocument;
            @$doc->loadHTML($dataConvertUtf8);
            $xpath = new \DOMXpath($doc);
            $nodeList = $xpath->query('//div[@id="rg"]');
            $imageList=[];
            $itemSearch=[];
            $listResult=$nodeList->item(0);
            $metas = $listResult->getElementsByTagName('div');
            for ($i = 0; $i < $metas->length; $i++) {
                $meta = $metas->item($i);
                if ($meta->getAttribute('class') == 'rg_meta notranslate') {
                    $decodeItem = json_decode($meta->nodeValue);
                    if (!empty($decodeItem->ou)) {
                        if(!empty($decodeItem->ru)){
                            $itemSearch['url']=$decodeItem->ru;
                        }else{
                            $itemSearch['url']='';
                        }
                        $itemSearch['title']=$decodeItem->pt;
                        $itemSearch['link']=$decodeItem->ou;
                        $itemSearch['content']=$meta->nodeValue;
                        $itemSearch['domain']=$decodeItem->rh;
                        array_push($imageList,$itemSearch);
                    }
                }
            }
            return array(
                'result'=>'success',
                'data'=>$imageList,
                'keyword'=>$this->_keyword
            );
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
        }catch (\Exception $e){
            return array(
                'result'=>'error',
                'message'=>'connect_failed'
            );
        }
    }
    public function getVideoFromYoutube(){
        try{
            $videoList=[];
            $itemSearch=[];
            $url='https://www.youtube.com/results?search_query='.urlencode($this->_keyword);
            $client = new Client([
                'headers' => [
                    'Content-Type' => 'text/html',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36'
                ],
                'connect_timeout' => '2',
                'timeout' => '2'
            ]);
            $response = $client->request('GET', $url);
            $getResponse=$response->getBody()->getContents();
            $dataConvertUtf8 = '<?xml version="1.0" encoding="UTF-8"?>'.$getResponse;
            $doc = new \DOMDocument;
            @$doc->loadHTML($dataConvertUtf8);
            $xpath = new \DOMXpath($doc);
            $nodeList = $xpath->query('//ol[@class="item-section"]');
            $listResult=$nodeList->item(0);
            $metas = $listResult->getElementsByTagName('li');
            for ($i = 0; $i < $metas->length; $i++)
            {
                $meta = $metas->item($i);
                $getElement=$meta->getElementsByTagName('div');
                for ($t = 0; $t < $getElement->length; $t++) {
                    $element = $getElement->item($t);
                    if ($element->getAttribute('class') == 'yt-lockup yt-lockup-tile yt-lockup-video vve-check clearfix') {
                        $getItem = $element->getElementsByTagName('h3');
                        for ($y = 0; $y < $getItem->length; $y++) {
                            $item=$getItem->item($y);
                            if($item->getAttribute('class') == 'yt-lockup-title '){
                                $getLink=$item->getElementsByTagName('a');
                                parse_str($getLink->item(0)->getAttribute('href'), $query );
                                if(!empty($query['/watch?v'])){
                                    $itemSearch['idYoutube']=$query['/watch?v'];
                                    $itemSearch['title']=$getLink->item(0)->nodeValue;
                                }
                            }
                        }
                        $getDescription=$element->getElementsByTagName('div');
                        for ($k = 0; $k < $getDescription->length; $k++)
                        {
                            $des=$getDescription->item($k);
                            if($des->getAttribute('class') == 'yt-lockup-description yt-ui-ellipsis yt-ui-ellipsis-2'){
                                $itemSearch['description']=$des->nodeValue;
                            }
                        }
                        array_push($videoList,$itemSearch);
                    }
                }
            }
            return array(
                'result'=>'success',
                'data'=>$videoList,
                'keyword'=>$this->_keyword
            );
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
        }catch (\Exception $e){
            return array(
                'result'=>'error',
                'message'=>'connect_failed'
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
