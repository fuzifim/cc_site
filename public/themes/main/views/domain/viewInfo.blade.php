<?php
if(!empty($domain['attribute']['whois'])){
    $decodeWhois=json_decode($domain['attribute']['whois']);
}
$description='';
$description.=$domain['domain'];
if(!empty($decodeWhois->creationDate)){
    $description.=' created at '.$decodeWhois->creationDate;
}
if(!empty($decodeWhois->expirationDate)){
    $description.=' and expiration date '.$decodeWhois->expirationDate;
}
if(!empty($decodeWhois->registrar)){
    $description.=' Registrar by '.$decodeWhois->registrar;
}
if(!empty($decodeWhois->nameServer) && !empty($decodeWhois->nameServer[0])){
    $description.=' Name server: '.$decodeWhois->nameServer[0];
}
if(!empty($decodeWhois->nameServer[1])){
    $description.=' and '.$decodeWhois->nameServer[1];
}
if(!empty($domain['attribute']['rank'])){
    $description.=' It has a global traffic rank of '.$domain['attribute']['rank'].' in the world';
}
if(!empty($domain['attribute']['country_code']) && !empty($domain['attribute']['rank_country'])){
    $description.=' and rank at '.$domain['attribute']['country_code'].' is '.$domain['attribute']['rank_country'];
}
if(!empty($domain['attribute']['content'])){
    $domainContent=json_decode($domain['attribute']['content']);
}else{
    $domainContent=array();
}
$channel['theme']->setTitle($domain['domain'].' '.mb_substr(WebService::renameBlacklistWord($domain['title']),0,150));
$channel['theme']->setKeywords(mb_substr(WebService::renameBlacklistWord($domain['keywords']),0,320));
$channel['theme']->setDescription(mb_substr(WebService::renameBlacklistWord($domain['description']),0,320).' - '.$description);
Theme::setCanonical(route('domain.info',array('d.cungcap.net',$domain['domain'])));
$ads='true';
if(!empty($domain['attribute']['ads']) && $domain['attribute']['ads']=='disable'){
    $ads='false';
}else if($domain['status']=='blacklist' && $domain['status']=='disable' && $domain['status']=='delete'){
    $ads='false';
}
Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
if($ads=='true' && config('app.env')!='local'){
    Theme::setAds('true');
}
?>
<section>
    <div class="mainpanel">
    {!!Theme::partial('headerbar_domain', array('title' => 'Header'))!!}
        <ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a></li>
            <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <span itemprop="name">{!! $domain['domain'] !!}</span>
                <meta itemprop="url" content="{!! route('domain.info',array(config('app.url'),$domain['domain'])) !!}" />
            </li>
        </ol>
        <div class="pageheader">
            <h1><strong>{!! $domain['domain'] !!}</strong></h1>
            <?php
            if ($domain['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                $updated_at= $domain['updated_at']->toDateTime()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
            }else{
                $updated_at= $domain['updated_at'];
            }
            ?>
            <small>Updated at {!! $updated_at !!}</small> @if(!empty($domain['view']))<small><strong>Views: {!! $domain['view'] !!}</strong></small>@endif<br>
        </div>
        <div class="container">
            <div class="row row-pad-5">
                <div class="col-md-12">
                    @if(!empty($domain['title']))<strong>{!! mb_substr(WebService::renameBlacklistWord($domain['title']),0,150) !!}</strong>@endif
                    @if(!empty($domain['description']))<p>{!! mb_substr(WebService::renameBlacklistWord($domain['description']),0,320); !!}</p>@endif
                    @if(empty($domain['title']) && empty($domain['description']))
                    <div class="alert alert-info">
                        This domain {!! $domain['domain'] !!} not any information, please access  next time
                    </div>
                    @endif
                    <p>
                    @if(!empty($domain['attribute']['rank']))
                        <span class="label label-primary">Global rank: {!! Site::price($domain['attribute']['rank']) !!}</span>
                        @if(!empty($domain['attribute']['country_code']))
                            <?php
                                $regionCode=Cache::store('memcached')->remember('region_code_'.$domain['attribute']['country_code'], 50, function() use($domain)
                                {
                                    return DB::table('regions')
                                        ->where('iso',$domain['attribute']['country_code'])
                                        ->first();
                                });
                            ?>
                            <span class="">Rank in <i class="flag flag-16 flag-{!! mb_strtolower($regionCode->iso) !!}"></i> <a href="{!! route('domain.country.iso',array(config('app.url'),$regionCode->iso)) !!}">{!! $regionCode->country !!}</a>@if(!empty($domain['attribute']['rank_country'])): {!! Site::price($domain['attribute']['rank_country']) !!}@endif
                            </span>
                        @endif
                    @endif
                    </p>
                    @if($channel['security']==true)
                        <p>
                            <span style="cursor: pointer;" class="label label-success" id="update_info">update info</span>
                            @if(!empty($domain['attribute']['ads']) && $domain['attribute']['ads']=='disable')
                                <span style="cursor: pointer;" class="label label-success" id="active_ads">Active ads</span>
                            @else
                                <span style="cursor: pointer;" class="label label-danger" id="disable_ads">Disable ads</span>
                            @endif
                        </p>
                    @endif
                    <p>
                        @if(!empty($domain['ip']))Ip address: <a href="{!! route('domain.by.ip',array(config('app.url'),$domain['ip'])) !!}">{!! $domain['ip'] !!}</a>@endif
                        <a href="{!! route('domain.top.view',$channel['domainPrimary']) !!}" target="_blank"> <i class="glyphicon glyphicon-star"></i> Top domains</a>
                    </p>

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
                    <div class="form-group">
                        <?php
                            if(!empty($domain['scheme'])){
                                $scheme=$domain['scheme'];
                            }else{
                                $scheme='http';
                            }
                        ?>
                        @if($ads=='true')
                            <a class="btn btn-primary btn-block siteLink" id="linkContinue" href="{!! route('go.to.url',array('go.cungcap.net',$scheme.'://'.$domain['domain'])) !!}" rel="nofollow" target="_blank">Visit to site click here
                                <p><strong>{!! $domain['domain'] !!}</strong></p>
                            </a>
                        @else
                            <a class="btn btn-primary btn-block" id="linkContinue" href="https://www.youtube.com/channel/UCTR65Hn65TWPupGBWUMkzuA?sub_confirmation=1" rel="nofollow" target="_blank">Visit to site click here
                                <p><strong>{!! $domain['domain'] !!}</strong></p>
                            </a>
                        @endif
                    </div>
                    <div class="form-group mt-2">
                        <div class="alert alert-info p-2">
                            <strong>Cung Cấp đến mọi người ⭐ ⭐ ⭐ ⭐ ⭐</strong>
                            <p>Đăng tin lên Cung Cấp để cung cấp sản phẩm, dịch vụ kinh doanh đến mọi người hoàn toàn miễn phí! </p>
                        </div>
                        <a class="btn btn-success btn-block" href="https://soc.cungcap.net" target="_blank"><h4>Đăng tin miễn phí</h4></a>
                    </div>
                    @if(!empty($domain['attribute']['whois']))
                        <div class="form-group mt-2">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Domain infomation {!! $domain['domain'] !!}</div>
                                <div class="panel-body">
                                    <strong>{{$domain['domain']}}</strong>@if(!empty($decodeWhois->creationDate)) created at {{$decodeWhois->creationDate}}  @endif @if(!empty($decodeWhois->expirationDate)) and expiration date {{$decodeWhois->expirationDate}}. @endif @if(!empty($decodeWhois->registrar)) Registrar by <strong>{!!$decodeWhois->registrar!!}</strong>.@endif @if(!empty($decodeWhois->nameServer)) Name server: @if(!empty($decodeWhois->nameServer[0])){{$decodeWhois->nameServer[0]}}@endif @if(!empty($decodeWhois->nameServer[1]))and {{$decodeWhois->nameServer[1]}}@endif @endif. @if(!empty($note->attribute['rank'])) It has a global traffic rank of {{$note->attribute['rank']}} in the world @if(!empty($note->attribute['country_code']) && !empty($note->attribute['rank_country'])) and rank at <strong>{{$note->attribute['country_code']}}</strong> is {{$note->attribute['rank_country']}}@endif @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($domainContent->basic_info))
                        <div class="form-group mt-2">
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a class="nav-link active" href="#basicInfo" data-toggle="tab"><strong>Basic</strong></a></li>
                                <li class="nav-item"><a class="nav-link" href="#info" data-toggle="tab"><strong>Website</strong></a></li>
                                <li class="nav-item"><a class="nav-link" href="#SemRush" data-toggle="tab"><strong>SemRush Metrics</strong></a></li>
                                <li class="nav-item"><a class="nav-link" href="#dns" data-toggle="tab"><strong>DNS Report</strong></a></li>
                                <li class="nav-item"><a class="nav-link" href="#ipAddress" data-toggle="tab"><strong>IP</strong></a></li>
                                <li class="nav-item"><a class="nav-link" href="#whois" data-toggle="tab"><strong>Whois</strong></a></li>
                            </ul>
                            <div class="card tab-content mb10">
                                @if(!empty($domainContent->basic_info))
                                    <div class="tab-pane active" id="basicInfo">
                                        @if($ads=='true' && config('app.env')!='local')
                                            <div class="row">
                                                <div class="col-md-4">
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
                                                </div>
                                                <div class="col-md-8">
                                                    {!!$domainContent->basic_info!!}
                                                </div>
                                            </div>
                                        @else
                                            {!!$domainContent->basic_info!!}
                                        @endif
                                    </div>
                                @endif
                                @if(!empty($domainContent->website_info))
                                    <div class="card-body tab-pane" id="info">
                                        {!!$domainContent->website_info!!}
                                    </div>
                                @endif
                                @if(!empty($domainContent->semrush_metrics))
                                    <div class="card-body tab-pane" id="SemRush">
                                        {!!$domainContent->semrush_metrics!!}
                                    </div>
                                @endif
                                @if(!empty($domainContent->dns_report))
                                    <div class="card-body tab-pane" id="dns">
                                        <div class="table-responsive">
                                            {!!$domainContent->dns_report!!}
                                        </div>
                                    </div>
                                @endif
                                @if(!empty($domainContent->ip_address_info))
                                    <div class="card-body tab-pane" id="ipAddress">
                                        {!!$domainContent->ip_address_info!!}
                                    </div>
                                @endif
                                @if(!empty($domainContent->whois_record))
                                    <div class="card-body tab-pane" id="whois">
                                        {!!$domainContent->whois_record!!}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                    @if(!empty($domain['get_header']))
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Header infomation for {!! $domain['domain'] !!}
                        </div>
                        <div class="panel-body">
                            @foreach($domain['get_header'] as $header)
                                <span>{!! $header !!}</span><br>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if(!empty($domain['attribute']['dns_record']) && count($domain['attribute']['dns_record']))
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead><tr>
                                    <th>Host</th>
                                    <th>Type</th>
                                    <th>Class</th>
                                    <th>TTL</th>
                                    <th>Extra</th>
                                </tr></thead>
                                <tbody>
                                @foreach($domain['attribute']['dns_record'] as $record)
                                <tr>
                                    <td>@if(!empty($record['host'])){!! $record['host'] !!}@endif</td>
                                    <td>@if(!empty($record['type'])){!! $record['type'] !!}@endif</td>
                                    <td>@if(!empty($record['class'])){!! $record['class'] !!}@endif</td>
                                    <td>@if(!empty($record['ttl'])){!! $record['ttl'] !!}@endif</td>
                                    <td>
                                        @if(!empty($record['ip']))
                                        <b>ip:</b> {!! $record['ip'] !!}<br>
                                        @endif
                                        @if(!empty($record['ipv6']))
                                            <b>Ipv6:</b> {!! $record['ipv6'] !!}<br>
                                        @endif
                                        @if(!empty($record['txt']))
                                            <b>Txt:</b> {!! $record['txt'] !!}<br>
                                        @endif
                                        @if(!empty($record['target']))
                                            <b>Target:</b> {!! $record['target'] !!}<br>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    @endif
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
                    @if(!empty($domain['contents']))
                        <?php
                            $html = $domain['contents'];
                            $html=WebService::renameBlacklistWord($html);
                            $dom = new \DOMDocument;
                            @$dom->loadHTML($html);
                            $getH1 = $dom->getElementsByTagName('h1');
                            $getH2 = $dom->getElementsByTagName('h2');
                            $getH3 = $dom->getElementsByTagName('h3');
                            $getH4 = $dom->getElementsByTagName('h4');
                            $getH5 = $dom->getElementsByTagName('h5');
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">Tag infomations for {!! $domain['domain'] !!}</div>
                            <div class="panel-body">
                                @if($getH1->length>0)
                                    <p><strong>H1 Tag</strong> <span class="label label-default">{!! $getH1->length !!}</span></p>
                                    @foreach($getH1 as $h1)
                                        <span>{!! WebService::renameBlacklistWord($h1->nodeValue) !!}</span><br>
                                    @endforeach
                                    <hr>
                                @endif
                                @if($getH2->length>0)
                                    <p><strong>H2 Tag</strong> <span class="label label-default">{!! $getH2->length !!}</span></p>
                                    @foreach($getH2 as $h2)
                                        <span>{!! WebService::renameBlacklistWord($h2->nodeValue) !!}</span><br>
                                    @endforeach
                                     <hr>
                                @endif
                                @if($getH3->length>0)
                                    <p><strong>H3 Tag</strong> <span class="label label-default">{!! $getH3->length !!}</span></p>
                                    @foreach($getH3 as $h3)
                                        <span>{!! WebService::renameBlacklistWord($h3->nodeValue) !!}</span><br>
                                    @endforeach
                                    <hr>
                                @endif
                                @if($getH4->length>0)
                                    <p><strong>H4 Tag</strong> <span class="label label-default">{!! $getH4->length !!}</span></p>
                                    @foreach($getH4 as $h4)
                                        <span>{!! WebService::renameBlacklistWord($h4->nodeValue) !!}</span><br>
                                    @endforeach
                                    <hr>
                                @endif
                                @if($getH5->length>0)
                                    <p><strong>H5 Tag</strong> <span class="label label-default">{!! $getH5->length !!}</span></p>
                                    @foreach($getH5 as $h5)
                                        <span>{!! WebService::renameBlacklistWord($h5->nodeValue) !!}</span><br>
                                    @endforeach
                                    <hr>
                                @endif
                            </div>
                        </div>
                    @endif
                    @if(count($siteRelate))
                        <div class="panel panel-default panel-responsive">
                            <div class="panel-heading heading-responsive">
                                Site relate for {!! $domain['domain'] !!}
                            </div>
                            <ul class="list-group">
                                {!!Theme::partial('site.listSite', array('sites' => $siteRelate,'showDomain'=>false,'ads'=>'false'))!!}
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
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
                <h4>Like trang và chia sẻ để thấy nội dung</h4>
            </div>
            <div class="modal-body text-center">
                <p>Nhấn vào nút <strong>thích trang</strong> để thấy nội dung <strong>{!! $domain['domain'] !!}</strong></p>
                <div class="fb-page" data-href="https://www.facebook.com/cungcap.net/" data-tabs="" data-width="" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/cungcap.net/" class="fb-xfbml-parse-ignore"></blockquote></div>
                <p>Hoặc nhấn <a href="https://www.youtube.com/channel/UCTR65Hn65TWPupGBWUMkzuA?sub_confirmation=1" target="_blank" rel="nofollow" class="btn btn-sm btn-success">vào đây</a> và sau đó xác nhận đăng ký kênh bấm vào <strong>Đăng ký</strong> để xem nội dung {!! $domain['domain'] !!}</p>
            </div>
        </div>
    </div>
</div>
<?php
$dependencies = array();
$channel['theme']->asset()->writeScript('customScript','
    jQuery(document).ready(function(){
        "use strict";
        $(window).on("load",function(){
            $("#ModalFacebook").modal("show");
        });
        var count = 50;
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
@if($channel['security']==true)
<?php
$dependencies = array();
$channel['theme']->asset()->writeScript('customDomain','
    jQuery(document).ready(function(){
        "use strict";
        $("#update_info").click(function(){
            $(this).addClass("disabled");
            var formData = new FormData();
            formData.append("domain", "'.$domain['domain'].'");
            $.ajax({
                url: "'.route('domain.update.info',array(config('app.url'),$domain['domain'])).'",
                headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                dataType:"json",
                success:function(result){
                    location.reload();
                },
                error: function(result) {
                console.log("error");
                }
            });
            return false;
        });
        $("#disable_ads").click(function(){
            if(confirm("Bạn có chắc muốn tắt quảng cáo? ")){
                $(this).addClass("disabled");
                var formData = new FormData();
                formData.append("domain", "'.$domain['domain'].'");
                $.ajax({
                    url: "'.route('domain.disable.ads',array(config('app.url'),$domain['domain'])).'",
                    headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    dataType:"json",
                    success:function(result){
                        location.reload();
                    },
                    error: function(result) {
                    console.log("error");
                    }
                });
            }

            return false;
        });
        $("#active_ads").click(function(){
            if(confirm("Bạn có chắc muốn kích hoạt quảng cáo? ")){
                $(this).addClass("disabled");
                var formData = new FormData();
                formData.append("domain", "'.$domain['domain'].'");
                $.ajax({
                    url: "'.route('domain.active.ads',array(config('app.url'),$domain['domain'])).'",
                    headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    dataType:"json",
                    success:function(result){
                        location.reload();
                    },
                    error: function(result) {
                    console.log("error");
                    }
                });
            }
            return false;
        });
    });
', $dependencies);
?>
@endif
