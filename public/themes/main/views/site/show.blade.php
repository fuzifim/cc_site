<?php
Theme::setTitle($site['title']);
Theme::setDescription($site['description']);
Theme::setCanonical(route('site.show.id',array($channel['domainPrimary'],$site['_id'],str_slug(mb_substr($site['title'], 0, \App\Model\Mongo_site::MAX_LENGTH_SLUG),'-'))));
$ads='true';
if(!empty($domain['attribute']['ads']) && $domain['attribute']['ads']=='disable'){
    $ads='false';
}else if($domain['status']=='blacklist' && $domain['status']=='disable' && $domain['status']=='delete'){
    $ads='false';
}
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
        <div class="pageheader form-group">
            @if(!empty($site['title_full']))
                <h1><strong>{!! $site['title_full'] !!}</strong></h1>
            @else
                <h1><strong>{!! $site['title'] !!}</strong></h1>
            @endif
            <ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a></li>
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{!! route('domain.info',array(config('app.url'),$site['domain'])) !!}"><span itemprop="name">{!! WebService::renameBlacklistWord($site['domain']) !!}</span></a></li>
            </ol>
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
                    <a class="btn btn-primary btn-block" id="" href="{!! route('go.to.url',array(config('app.url'),urlencode($site['link']))) !!}" rel="nofollow" target="_blank">Visit to {!! $site['title'] !!}
                        <p><strong>Click here</strong></p>
                    </a>
                </div>
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
