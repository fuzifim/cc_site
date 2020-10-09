<?php
Theme::setTitle($site['title']);
Theme::setDescription($site['description']);
Theme::setCanonical(route('site.show.id',array('s.cungcap.net',$site['_id'],str_slug(mb_substr($site['title'], 0, \App\Model\Mongo_site::MAX_LENGTH_SLUG),'-'))));
Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
$ads='false';
if(!empty($domain['ads_status']) && $domain['ads_status']=='active'){
    $ads='true';
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
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="https://cungcap.net"><i class="fa fa-home"></i>
                    <span class="hidden-xs" itemprop="name">Cung Cấp</span>
                </a>
                <meta itemprop="position" content="1" />
            </li>
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{!! route('domain.info',array('d.cungcap.net',$site['domain'])) !!}">
                    <span itemprop="name">{!! WebService::renameBlacklistWord($site['domain']) !!}</span>
                </a>
                <meta itemprop="position" content="2" />
            </li>
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
            <div class="form-group mt-2">
                <div class="alert alert-info text-center">
                    <i class="glyphicon glyphicon-globe"></i> Nhập từ khóa tìm {!! $site['title'] !!}<br>
                    <script async src="https://cse.google.com/cse.js?cx=010523811584912180996:5rmshldbg3a"></script>
                    <div class="gcse-search"></div>
                    @yield('content')
                    <script>
                        window.onload = function(){
                            document.getElementById('gsc-i-id1').placeholder = 'Search... (Tìm kiếm...)';
                        };
                    </script>
                </div>
            </div>
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
                    @else
                        <div class="form-group">
                            <!-- Composite Start -->
                            <div id="M572724ScriptRootC883235">
                            </div>
                            <script src="https://jsc.mgid.com/c/u/cungcap.net.883235.js" async></script>
                            <!-- Composite End -->
                        </div>
                    @endif
                    <a class="btn btn-primary btn-block" id="" href="{!! route('go.to.url',array('go.cungcap.net',urlencode($site['link']))) !!}" rel="nofollow" target="_blank">Visit to {!! $site['title'] !!}
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
<?php
$channel['theme']->asset()->writeScript('customScript','
', []);
?>