<?php
Theme::setTitle('Video '.$video['title']);
Theme::setDescription($video['description']);
Theme::setImage('https:'.$video['image']);
Theme::setCanonical(route('video.youtube.view.id.slug',array('v.cungcap.net',$video['yid'],str_slug(mb_substr($video['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))));
Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
$ads='true';
if($ads=='true' && config('app.env')!='local'){
    Theme::setAds('true');
}
if(!empty($video['parent'])){
    $parentKey = DB::connection('mongodb')->collection('mongo_keyword')
        ->where('base_64', base64_encode($video['parent']))->first();
    DB::connection('mongodb')->collection('mongo_video')
        ->where('_id',(string)$video['_id'])
        ->update(
            [
                'parent_id'=>(string)$parentKey['_id']
            ]
        );

    if($parentKey['_id'] ==='5cb3c98f45f4c906266afebc'){
        Theme::setAds('false');
        $ads='false';
    }
}
?>
<section>
    <div class="mainpanel">
        {!!Theme::partial('headerbar_domain', array('title' => 'Header'))!!}
        @if(!empty($video['parent']))
            @if(empty($video['parent_id']))
                <ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i>
                            <span class="hidden-xs" itemprop="name">Cung Cấp Website</span>
                        </a>
                        <meta itemprop="position" content="1" />
                    </li>
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{!! route('keyword.show.id',array('k.cungcap.net',$parentKey['_id'],str_slug(mb_substr($parentKey['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}">
                            <span itemprop="name">{!! $video['parent'] !!}</span>
                        </a>
                        <meta itemprop="position" content="2" />
                    </li>
                </ol>
            @else
                <ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i>
                            <span class="hidden-xs" itemprop="name">Cung Cấp Website</span>
                        </a>
                        <meta itemprop="position" content="1" />
                    </li>
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{!! route('keyword.show.id',array('k.cungcap.net',$video['parent_id'],str_slug(mb_substr($video['parent'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}">
                            <span itemprop="name">{!! $video['parent'] !!}</span>
                        </a>
                        <meta itemprop="position" content="2" />
                    </li>
                </ol>
            @endif
        @endif
        <div class="pageheader form-group">
            <h1><strong>Video {!! $video['title'] !!}</strong></h1>
            <?php
            if ($video['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                $updated_at= $video['updated_at']->toDateTime()->setTimezone(new \DateTimeZone(config('app.timezone')))->format('Y-m-d H:i:s');
            }else{
                $updated_at= $video['updated_at'];
            }
            ?>
            <small>Updated at {!! $updated_at !!}</small> @if(!empty($video['view']))<small><strong>Views: {!! $video['view'] !!}</strong></small>@endif
        </div>
        <div class="container">
            <div class="row row-pad-5">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe width="640" height="320" class="embed-responsive-item" src="https://www.youtube.com/embed/{!! $video['yid'] !!}" allowfullscreen></iframe>
                            </div>
                            <p>{!! $video['description'] !!}</p>
                        </div>
                    </div>
                    @if($ads=='true' && config('app.env')!='local')
                        <div class="form-group">
                            <ins class="adsbygoogle"
                                 style="display:block"
                                 data-ad-client="ca-pub-6739685874678212"
                                 data-ad-slot="7536384219"
                                 data-ad-format="auto"></ins>
                            <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                            </script>
                        </div>
                    @else
                        <div class="form-group">
                            <!-- Composite Start -->
                            <div id="M572724ScriptRootC883235">
                            </div>
                            <script src="https://jsc.mgid.com/c/u/cungcap.net.883235.js" async></script>
                            <!-- Composite End -->
                        </div>
                    @endif
                    <div class="form-group mt-2">
                        <div class="alert alert-info text-center">
                            <div class="alert alert-info text-center">
                                <i class="glyphicon glyphicon-globe"></i> Tạo website bán hàng, website giới thiệu công ty, website kinh doanh dịch vụ cực nhanh và tiện lợi!<br>
                                <div class="text-center"><h3><strong>MIỄN PHÍ</strong></h3></div>
                                <a class="btn btn-success btn-block" href="https://cungcap.net" target="_blank"><h4><strong><i class="glyphicon glyphicon-hand-right"></i> Vào tạo website</strong></h4></a>
                            </div>
                        </div>
                    </div>
                    @if(count($videoParent))
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                Video liên quan
                            </div>
                            <div class="panel-body">
                                @foreach(array_chunk(array_slice($videoParent, 0, 12),4) as $chunk)
                                    <div class="row row-pad-5">
                                        @foreach($chunk as $item)
                                            <div class="col-md-3">
                                                <p class="text-center"><a href="{!! route('video.youtube.view.id.slug',array('v.cungcap.net',$item['yid'],str_slug(mb_substr($item['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))) !!}"><img class="img-responsive" src="{!! $item['thumb'] !!}" title="{!! $item['title'] !!}" alt="{!! $item['title'] !!}"></a></p>
                                                <strong><a href="{!! route('video.youtube.view.id.slug',array('v.cungcap.net',$item['yid'],str_slug(mb_substr($item['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))) !!}">Video {!! $item['title'] !!}</a> </strong>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    @if(count($videoParent))
                        @if($ads=='true' && config('app.env')!='local')
                            <div class="form-group">
                                <ins class="adsbygoogle"
                                     style="display:block"
                                     data-ad-client="ca-pub-6739685874678212"
                                     data-ad-slot="7536384219"
                                     data-ad-format="auto"></ins>
                                <script>
                                    (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>
                            </div>
                        @else
                            <div class="form-group">
                                <!-- Composite Start -->
                                <div id="M572724ScriptRootC883235">
                                </div>
                                <script src="https://jsc.mgid.com/c/u/cungcap.net.883235.js" async></script>
                                <!-- Composite End -->
                            </div>
                        @endif
                        <div class="panel panel-primary">
                            @foreach(array_slice($videoParent, 12, 8) as $item)
                                <li class="list-group-item">
                                    <p class="text-center"><a href="{!! route('video.youtube.view.id.slug',array('v.cungcap.net',$item['yid'],str_slug(mb_substr($item['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))) !!}"><img src="{!! $item['thumb'] !!}" title="{!! $item['title'] !!}" alt="{!! $item['title'] !!}"></a></p>
                                    <strong><a href="{!! route('video.youtube.view.id.slug',array('v.cungcap.net',$item['yid'],str_slug(mb_substr($item['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))) !!}">Video {!! $item['title'] !!}</a> </strong>
                                </li>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$channel['theme']->asset()->writeScript('customScript','
', []);
?>