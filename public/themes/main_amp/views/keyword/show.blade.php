<?
$setKeyword=[];
if(!empty($keyword['keyword'])){
    Theme::setCanonical(route('keyword.show.id',array($channel['domainPrimary'],$keyword['_id'],str_slug(mb_substr($keyword['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))));
    Theme::setTitle($keyword['keyword']);
}
if(!empty($keyword['description'])){
    Theme::setDescription(htmlentities($keyword['description']));
}
if(!empty($keyword['image'])){
    Theme::setImage($keyword['image']);
}
$showListImage=0;
if(!empty($keyword['site_relate']) && count($keyword['site_relate'])>0 && !empty($keyword['image_relate']) && count($keyword['image_relate'])>0){
    $showListImage=1;
}else if(!empty($keyword['image_relate']) && count($keyword['image_relate'])>0){
    $showListImage=2;
}
$showListVideo=0;
if(!empty($keyword['site_relate']) && count($keyword['site_relate'])>0 && !empty($keyword['video_relate']) && count($keyword['video_relate'])>0){
    $showListVideo=1;
}else if(!empty($keyword['video_relate']) && count($keyword['video_relate'])>0){
    $showListVideo=2;
}
$showEmpty=false;
if(empty($keyword['site_relate']) && empty($keyword['image_relate']) && empty($keyword['video_relate'])){
    $showEmpty=true;
}
$ads='true';
?>
<article class="amp-wp-article">
    <header class="amp-wp-article-header">
        <h1 class="amp-wp-title">{!! $keyword['keyword'] !!}</h1>
        <div class="amp-wp-meta amp-wp-byline">
            <amp-img src="{{asset('assets/img/no-avata.png')}}" width="24" height="24" layout="fixed"></amp-img>
            <span class="amp-wp-author author vcard">Fuzifim</span>
        </div>
        <div class="amp-wp-meta amp-wp-posted-on">
            <?php
            if ($keyword['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                $updated_at= $keyword['updated_at']->toDateTime()->setTimezone(new \DateTimeZone(config('app.timezone')))->format('Y-m-d H:i:s');
            }else{
                $updated_at= $keyword['updated_at'];
            }
            ?>
            <time datetime="{!!Site::Date($updated_at)!!}">{!!WebService::time_request($updated_at)!!}</time>
        </div>
    </header>
    @if($showListImage==1)
    <figure class="amp-wp-article-featured-image wp-caption">
        <?php
        $image=DB::connection('mongodb')->collection('mongo_image')
            ->where('_id', (string)$keyword['image_relate'][0])->first();
        ?>
        <amp-anim width="720" height="480" src="https:{!! $image['attribute']['image'] !!}" class="" alt="" layout="intrinsic"></amp-anim>
        <p class="text-center">{!! $image['title'] !!}</p>
    </figure>
    <amp-ad width="100vw" height=320
            type="adsense"
            data-ad-client="ca-pub-6739685874678212"
            data-ad-slot="7536384219"
            data-auto-format="rspv"
            data-full-width>
        <div overflow></div>
    </amp-ad>
    @endif
    <div class="amp-wp-article-content">
        @if($showEmpty==true)
            Từ khóa {!! $keyword['keyword'] !!} chưa có bất kỳ thông tin trang web, hình ảnh, video nào!
        @endif
        @if($showListVideo==1)
            <p>Video relate for {!! $keyword['keyword'] !!}</p>
            <div class="amp-list-video">
                @foreach(array_slice($keyword['video_relate'], 0, 6) as $videoRelate)
                    <?php
                    $video=DB::connection('mongodb')->collection('mongo_video')
                        ->where('_id', (string)$videoRelate)->first();
                    ?>
                    <div class="amp-video-item">
                        @if(!empty($video['title']))
                        <a href="{!! route('video.youtube.view.id.slug',array($channel['domainPrimary'],$video['yid'],str_slug(mb_substr($video['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))) !!}"><amp-anim width="210" height="118" src="https:{!! $video['thumb'] !!}" class="" alt="" layout="intrinsic"></amp-anim></a>
                        <span class="amp-group-title"><a href="{!! route('video.youtube.view.id.slug',array($channel['domainPrimary'],$video['yid'],str_slug(mb_substr($video['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))) !!}" class="amp-title">{!! $video['title'] !!}</a></span>
                        @endif
                    </div>
                @endforeach
            </div>
            <amp-ad width="100vw" height=320
                    type="adsense"
                    data-ad-client="ca-pub-6739685874678212"
                    data-ad-slot="7536384219"
                    data-auto-format="rspv"
                    data-full-width>
                <div overflow></div>
            </amp-ad>
        @endif
        @if(!empty($keyword['site_relate']) && count($keyword['site_relate'])>0)
            <ul>
                <?php
                $i=0;
                $description='';
                ?>
                @foreach($keyword['site_relate'] as $siteRelate)
                    <?php
                    $i++;
                    $site=DB::connection('mongodb')->collection('mongo_site')
                        ->where('_id', (string)$siteRelate)->first();
                    ?>
                    @if(!empty($site['title']))
                        @if(empty($keyword['description']) && $i<=3)
                            <?php
                            $description=$description.' '.$site['title'];
                            ?>
                        @endif
                        @if($i==3 || $i==8)
                            <amp-ad width="100vw" height=320
                                    type="adsense"
                                    data-ad-client="ca-pub-6739685874678212"
                                    data-ad-slot="7536384219"
                                    data-auto-format="rspv"
                                    data-full-width>
                                <div overflow></div>
                            </amp-ad>
                        @endif
                        <li class="list-group-item">
                            <h4><a class="siteLink" id="linkContinue" href="{!! route('site.show.id',array($channel['domainPrimary'],$site['_id'],str_slug(mb_substr($site['title'], 0, \App\Model\Mongo_site::MAX_LENGTH_SLUG),'-'))) !!}">{!! $site['title'] !!}</a></h4>
                            <?php
                            if ($site['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                                $updated_at= $site['updated_at']->toDateTime()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
                            }else{
                                $updated_at= $site['updated_at'];
                            }
                            ?>
                            <span class="text-muted"><small>{!! $updated_at !!}</small></span><br>
                            <span>{!! $site['description'] !!}</span><br>
                            <span>{!! $site['link'] !!}</span><br>
                            <i class="glyphicon glyphicon-globe"></i> <a href="http://{!! $site['domain'] !!}.d.{!! config('app.url') !!}">{!! WebService::renameBlacklistWord($site['domain']) !!}</a>
                        </li>
                    @endif
                @endforeach
                @if(empty($keyword['description']) && !empty($description))
                    <?php
                    Theme::setDescription(htmlentities($description));
                    ?>
                @endif
            </ul>
        @endif
    </div>

    <footer class="amp-wp-article-footer">
        <div class="amp-wp-meta amp-wp-tax-category">
            @if(!empty($keyword['parent']))
                @if(empty($keyword['parent_id']))
                    <?php
                    $parentKey = DB::connection('mongodb')->collection('mongo_keyword')
                        ->where('base_64', base64_encode($keyword['parent']))->first();
                    DB::connection('mongodb')->collection('mongo_keyword')
                        ->where('_id',(string)$keyword['_id'])
                        ->update(
                            [
                                'parent_id'=>(string)$parentKey['_id']
                            ]
                        );

                    ?>
                    <ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
                        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a></li>
                        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{!! route('keyword.show.id',array($channel['domainPrimary'],$parentKey['_id'],str_slug(mb_substr($parentKey['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}"><span itemprop="name">{!! $keyword['parent'] !!}</span></a></li>
                    </ol>
                @else
                    <ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
                        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a></li>
                        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{!! route('keyword.show.id',array($channel['domainPrimary'],$keyword['parent_id'],str_slug(mb_substr($keyword['parent'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}"><span itemprop="name">{!! $keyword['parent'] !!}</span></a></li>
                    </ol>
                @endif
            @endif
        </div>
        <div class="amp-wp-meta amp-wp-comments-link">
            <a href="{!! route('keyword.show.id',array($channel['domainPrimary'],$keyword['_id'],str_slug(mb_substr($keyword['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}">Xem bản đầy đủ</a>
        </div>
    </footer>
</article>