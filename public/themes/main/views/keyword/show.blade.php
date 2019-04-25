<?
$setKeyword = [];
if (!empty($keyword['keyword'])) {
    Theme::setCanonical(route('keyword.show.id', array($channel['domainPrimary'], $keyword['_id'], str_slug(mb_substr($keyword['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG), '-'))));
    Theme::setTitle($keyword['keyword']);
}
if (!empty($keyword['description'])) {
    Theme::setDescription($keyword['description']);
}
if (!empty($keyword['image'])) {
    Theme::setImage($keyword['image']);
}
Theme::setAmp(route('keyword.show.id', array($channel['domainPrimary'], $keyword['_id'], str_slug(mb_substr($keyword['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG), '-'))) . '?amp=true');
Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'));
Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
$imageShow = false;
$siteShow = false;
$videoShow = false;
$showEmpty = false;
if (!empty($keyword['site_relate']) && count($keyword['site_relate']) > 0) {
    $siteShow = true;
    $count = count($keyword['site_relate']);
    if ($count >= 5) {
        $skipImage = 3;
        $skipVideo = 5;
    } else if ($count == 4) {
        $skipImage = 2;
        $skipVideo = 4;
    } else if ($count == 3) {
        $skipImage = 1;
        $skipVideo = 2;
    } else if ($count == 2) {
        $skipImage = 1;
        $skipVideo = 2;
    } else if ($count == 1) {
        $skipImage = 1;
        $skipVideo = 2;
    }
}
if (!empty($keyword['image_relate']) && count($keyword['image_relate']) > 0) {
    $imageShow = true;
}
if (!empty($keyword['video_relate']) && count($keyword['video_relate']) > 0) {
    $videoShow = true;
}
if (empty($keyword['site_relate']) && empty($keyword['image_relate']) && empty($keyword['video_relate'])) {
    $showEmpty = true;
}
$ads = 'true';
?>
@if($ads=='true' && config('app.env')!='local')
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-6739685874678212",
            enable_page_level_ads: true
        });
    </script>
@endif
<section>
    <div class="mainpanel">
        {!!Theme::partial('headerbar', array('title' => 'Header'))!!}
        @if(!empty($keyword['parent']))
            @if(empty($keyword['parent_id']))
                <?php
                $parentKey = DB::connection('mongodb')->collection('mongo_keyword')
                    ->where('base_64', base64_encode($keyword['parent']))->first();
                DB::connection('mongodb')->collection('mongo_keyword')
                    ->where('_id', (string)$keyword['_id'])
                    ->update(
                        [
                            'parent_id' => (string)$parentKey['_id']
                        ]
                    );

                ?>
                <ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope
                        itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"
                                                                 itemprop="item"
                                                                 href="{{route('channel.home',$channel['domainPrimary'])}}"><i
                                    class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a>
                    </li>
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope
                        itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"
                                                                 itemprop="item"
                                                                 href="{!! route('keyword.show.id',array($channel['domainPrimary'],$parentKey['_id'],str_slug(mb_substr($parentKey['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}"><span
                                    itemprop="name">{!! $keyword['parent'] !!}</span></a></li>
                </ol>
            @else
                <ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope
                        itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"
                                                                 itemprop="item"
                                                                 href="{{route('channel.home',$channel['domainPrimary'])}}"><i
                                    class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a>
                    </li>
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope
                        itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"
                                                                 itemprop="item"
                                                                 href="{!! route('keyword.show.id',array($channel['domainPrimary'],$keyword['parent_id'],str_slug(mb_substr($keyword['parent'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}"><span
                                    itemprop="name">{!! $keyword['parent'] !!}</span></a></li>
                </ol>
            @endif
        @endif
        <div class="pageheader form-group">
            <h1><strong>{!! $keyword['keyword'] !!}</strong></h1>
            <?php
            if ($keyword['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                $updated_at = $keyword['updated_at']->toDateTime()->setTimezone(new \DateTimeZone(config('app.timezone')))->format('Y-m-d H:i:s');
            } else {
                $updated_at = $keyword['updated_at'];
            }
            ?>
            <small>Updated at {!! $updated_at !!}</small> @if(!empty($keyword['view']))
                <small><strong>Views: {!! $keyword['view'] !!}</strong></small>@endif
        </div>
        <div class="container">
            <div class="row row-pad-5">
                <div class="col-md-12">
                    @if($showEmpty==true)
                        Từ khóa {!! $keyword['keyword'] !!} chưa có bất kỳ thông tin trang web, hình ảnh, video nào!
                    @endif
                    @if(count($postSearch))
                        <div class="PostlistItem">
                            {!!Theme::partial('listPostChannelSlider', array('postSearch' => $postSearch,'keyword'=>$keyword))!!}
                        </div>
                        @if($ads=='true' && config('app.env')!='local')
                            <ins class="adsbygoogle"
                                 style="display:block"
                                 data-ad-client="ca-pub-6739685874678212"
                                 data-ad-slot="7536384219"
                                 data-ad-format="auto"></ins>
                            <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                            </script>
                        @endif
                    @endif
                    @if($siteShow==true)
                        {!!Theme::partial('keyword.listSite', array('keyword' => $keyword,'ads'=>$ads,'skipImage'=>$skipImage,'skipVideo'=>$skipVideo,'imageShow'=>$imageShow,'videoShow'=>$videoShow))!!}
                    @else
                        @if($imageShow==true)
                            {!!Theme::partial('keyword.listImage', array('keyword' => $keyword))!!}
                        @endif
                        @if($videoShow==true)
                            {!!Theme::partial('keyword.listVideo_slider', array('keyword' => $keyword,'from'=>0,'to'=>8))!!}
                        @endif
                    @endif
                    @if(!empty($keyword['keyword_relate']) && count($keyword['keyword_relate'])>0)
                        <div class="form-group">
                            <p><strong>Keyword relate for {!! $keyword['keyword'] !!}</strong></p>
                            @foreach($keyword['keyword_relate'] as $keywordRelate)
                                <?php
                                $keywordRe = DB::connection('mongodb')->collection('mongo_keyword')
                                    ->where('_id', (string)$keywordRelate)->first();
                                ?>
                                @if(empty($keywordRe['craw_next']))
                                    <span class="badge">{!! $keywordRe['keyword'] !!}</span>
                                @else
                                    <span><a class="badge progress-bar-success"
                                             href="{!! route('keyword.show.id',array($channel['domainPrimary'],$keywordRe['_id'],str_slug(mb_substr($keywordRe['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}">{!! $keywordRe['keyword'] !!}</a></span>
                                @endif

                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe.
         It's a separate element, as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
        <!-- don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                        <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div>
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>
<?php
$dependencies = array();
Theme::asset()->writeScript('loadLazy', '
		$(".showImageLink").click(function(){
			$("#showImageLarge").attr("src",$(this).attr("data-image"));
			$("#showImageLarge").attr("title",$(this).attr("data-title"));
			$("#showImageLarge").attr("alt",$(this).attr("data-title"));
			$("#showImageLargeLink").attr("href",$(this).attr("data-url"));
			$("#showImageLargeLink").text($(this).attr("data-title"));
			return false;
		});
	', $dependencies);
?>
<?php
$dependencies = array();
Theme::asset()->writeScript('ScriptKeywordShow', '
		$(".carousel[data-type=multi] .item").each(function() {
            var next = $(this).next();
            if (!next.length) {
                next = $(this).siblings(":first");
            }
            next.children(":first-child").clone().appendTo($(this));
            for (var i = 0; i < 2; i++) {
                next = next.next();
                if (!next.length) {
                    next = $(this).siblings(":first");
                }
                next.children(":first-child").clone().appendTo($(this));
            }
        });
        $(".carousel").swipe({
          swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
            if (direction == "left") $(this).carousel("next");
            if (direction == "right") $(this).carousel("prev");
          },
          allowPageScroll:"vertical",
          excludedElements: "label, button, input, select, textarea, .noSwipe"

        });
        var container = [];
        $(".my-gallery").find("figure").each(function() {
            var $link = $(this).find("a"),
            item = {
                src: $link.attr("href"),
                w: $link.data("width"),
                h: $link.data("height"),
                title: $link.data("caption")
                };
                container.push(item);
            });
            $("a").click(function(event) {
                event.preventDefault();
                var $pswp = $(".pswp")[0],
                options = {
                index: $(this).parent("figure").index(),
                bgOpacity: 0.85,
                showHideOpacity: true
            };
            var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, container, options);
            gallery.init();
        });

	', $dependencies);
?>
