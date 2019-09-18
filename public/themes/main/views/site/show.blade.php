<?php
Theme::setTitle($site['title']);
Theme::setDescription($site['description']);
Theme::setCanonical(route('site.show.id',array('s.cungcap.net',$site['_id'],str_slug(mb_substr($site['title'], 0, \App\Model\Mongo_site::MAX_LENGTH_SLUG),'-'))));
Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
$ads='true';
if(!empty($domain['attribute']['ads']) && $domain['attribute']['ads']=='disable'){
    $ads='false';
}else if($domain['status']=='blacklist' && $domain['status']=='disable' && $domain['status']=='delete'){
    $ads='false';
}
if($ads=='true' && config('app.env')!='local'){
    Theme::setAds('true');
}
?>
<section>
    <div class="mainpanel">
        {!!Theme::partial('headerbar_domain', array('title' => 'Header'))!!}
        <ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a></li>
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{!! route('domain.info',array('d.cungcap.net',$site['domain'])) !!}"><span itemprop="name">{!! WebService::renameBlacklistWord($site['domain']) !!}</span></a></li>
        </ol>
        <div class="pageheader form-group">
            @if(!empty($site['title_full']))
                <h1><strong>{!! $site['title_full'] !!}</strong></h1>
            @else
                <h1><strong>{!! $site['title'] !!}</strong></h1>
            @endif
            <?php
            if ($site['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                $updated_at= $site['updated_at']->toDateTime()->setTimezone(new \DateTimeZone(config('app.timezone')))->format('Y-m-d H:i:s');
            }else{
                $updated_at= $site['updated_at'];
            }
            ?>
            <small>Updated at {!! $updated_at !!}</small> @if(!empty($site['view']))<small><strong>Views: {!! $site['view'] !!}</strong></small>@endif
        </div>
        <div class="container">
            <div class="panel panel-default">
                <div class="panel-heading">
                    infomation for {!! $site['domain'] !!}: {!! $site['title'] !!}
                </div>
                <div class="panel-body">
                    <span>{!! $site['description'] !!}</span><br>
                    <span>{!! $site['link'] !!}</span><br>
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
                    @endif
                    @if($ads=='true')
                        <a class="btn btn-primary btn-block" id="" href="{!! route('go.to.url',array('go.cungcap.net',urlencode($site['link']))) !!}" rel="nofollow" target="_blank">Visit to {!! $site['title'] !!}
                            <p><strong>Click here</strong></p>
                        </a>
                    @else
                        <a class="btn btn-primary btn-block" id="" href="https://www.youtube.com/channel/UCTR65Hn65TWPupGBWUMkzuA?sub_confirmation=1" rel="nofollow" target="_blank">Visit to {!! $site['title'] !!}
                            <p><strong>Click here</strong></p>
                        </a>
                    @endif
                </div>
            </div>
            <div class="form-group mt-2">
                <div class="alert alert-info p-2">
                    <strong>Cung Cấp đến mọi người ⭐ ⭐ ⭐ ⭐ ⭐</strong>
                    <p>Đăng tin lên Cung Cấp để cung cấp sản phẩm, dịch vụ kinh doanh đến mọi người hoàn toàn miễn phí! </p>
                </div>
                <a class="btn btn-success btn-block" href="https://soc.cungcap.net" target="_blank"><h4>Đăng tin miễn phí</h4></a>
            </div>
            @if(count($siteRelate))
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Site relate for {!! $site['domain'] !!}
                    </div>
                    <ul class="list-group">
                        {!!Theme::partial('site.listSite', array('sites' => $siteRelate,'showDomain'=>false,'ads'=>$ads))!!}
                    </ul>
                </div>
            @endif
        </div>
    </div>
</section>

<div class="modal fade" id="ModalFacebook">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" id="timeLeft">&times;</span>
                </button>
                <h4>{{ trans('base.like_and_share_to_see_content') }}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{!! trans('base.click_button_to_see_content',['content'=>$site['title_full']]) !!} </p>
                <div class="fb-page" data-href="https://www.facebook.com/cungcap.net/" data-tabs="" data-width="" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/cungcap.net/" class="fb-xfbml-parse-ignore"></blockquote></div>
                <p>{!! trans('base.or_click_sub_youtube',['content'=>$site['title_full']]) !!}</p>
                @if($ads=='true' && config('app.env')!='local')
                    <div class="modal-footer text-center">
                        <div class="container form-group">
                            <ins class="adsbygoogle"
                                 style="display:block"
                                 data-ad-client="ca-pub-6739685874678212"
                                 data-ad-slot="7536384219"
                                 data-ad-format="auto"></ins>
                            <script>
                                setTimeout(function(){(adsbygoogle = window.adsbygoogle || []).push({})}, 1000);
                            </script>
                        </div>
                    </div>
                @endif
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/watch?v=kGaGrI8dkLI?&autoplay=1&mute=1" frameborder=”0″ allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$channel['theme']->asset()->writeScript('customScript','
    jQuery(document).ready(function(){
        "use strict";
        $(window).on("load",function(){
            $("#ModalFacebook").modal("show");
        });
        var count = 100;
        setInterval(function(){
            document.getElementById("timeLeft").innerHTML = count;
            if (count == 0) {
                $("#ModalFacebook").modal("hide");
                document.getElementById("timeLeft").innerHTML = "&times;";
            }
            count--;
        },1000);
        $("#ModalFacebook").modal({backdrop: "static", keyboard: false});
    });
', []);
?>