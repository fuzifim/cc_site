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
$channel['theme']->setTitle($domain['title']);
$channel['theme']->setKeywords($domain['keywords']);
$channel['theme']->setDescription($description.' - '.$domain['description']);
$ads='true';
if(!empty($domain['attribute']['ads']) && $domain['attribute']['ads']=='disable'){
    $ads='false';
}else if($domain['status']=='blacklist' && $domain['status']=='disable' && $domain['status']=='delete'){
    $ads='false';
}
Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
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
        <div class="container">
            <div class="row row-pad-5">
                <div class="col-md-8">
                    <h1><strong>{!! $domain['domain'] !!}</strong></h1>
                    @if(!empty($domain['title']))<strong>Title: {!! $domain['title'] !!}</strong>@endif
                    @if(!empty($domain['description']))<p>{!! $domain['description'] !!}</p>@endif
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
                        <a class="btn btn-primary btn-block siteLink" id="linkContinue" data-url="" rel="nofollow" target="blank">Visit this site click here
                            <p><strong>{!! $domain['domain'] !!}</strong></p>
                        </a>
                    </div>
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                    @if(!empty($domain['attribute']['dns_record']) && count($domain['attribute']['dns_record']))
                        <ul class="list-group form-group mb-2">
                            @foreach($domain['attribute']['dns_record'] as $record)
                                <li class="list-group-item">
                                    @if(!empty($record['host']))
                                        <p><strong>Host: </strong>{{$record['host']}}</p>
                                    @endif
                                    @if(!empty($record['class']))
                                        <p><strong>Class: </strong>{{$record['class']}}</p>
                                    @endif
                                    @if(!empty($record['ttl']))
                                        <p><strong>Ttl: </strong>{{$record['ttl']}}</p>
                                    @endif
                                    @if(!empty($record['type']))
                                        <p><strong>Type: </strong>{{$record['type']}}</p>
                                    @endif
                                    @if(!empty($record['ip']))
                                        <p><strong>Ip: </strong><a href="#" target="_blank">{{$record['ip']}}</a></p>
                                    @endif
                                    @if(!empty($record['ipv6']))
                                        <p><strong>Ipv6: </strong><a href="#" target="_blank">{{$record['ipv6']}}</a></p>
                                    @endif
                                    @if(!empty($record['txt']))
                                        <p><strong>Txt: </strong>{{$record['txt']}}</p>
                                    @endif
                                    @if(!empty($record['target']))
                                        <p><strong>Target: </strong>{{$record['target']}}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="col-md-4">
                    @if(count($newDomain))
                        <ul class="list-group">
                            @foreach($newDomain as $item)
                            <li class="list-group-item">
                                {!! $item['domain'] !!}<br>
                                <?php
                                if ($item['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                                    $date= $item['updated_at']->toDateTime()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
                                }else{
                                    $date= $item['updated_at'];
                                }
                                ?>
                                <small>{!! $date !!}</small>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
