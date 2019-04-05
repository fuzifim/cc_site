<?php
$channel['theme']->setTitle('Cung cấp tên miền có lượt xem nhiều nhất ');
$channel['theme']->setKeywords('');
$channel['theme']->setDescription('cung cấp danh sách tên miền có lượt xem nhiều nhất ');
?>
<section>
    <div class="mainpanel">
        {!!Theme::partial('headerbar', array('title' => 'Header'))!!}
        <div class="pageheader">
            <h1>Cung cấp tên miền có lượt xem nhiều nhất</h1>
            <span></span>
        </div>
        <div class="contentpanel">
            <div class="row">
                <div class="col-md-8">
                    @if(count($getDomain))
                        <ul class="list-group">
                            @foreach($getDomain as $item)
                            <li class="list-group-item">
                                <a href="http://{!! $item['domain'] !!}.d.{!! config('app.url') !!}">{!! WebService::renameBlacklistWord($item['domain']) !!}</a><br>
                                @if(!empty($item['attribute']['rank']))
                                    <p>
                                        <span class="label label-primary">Global rank: {!! Site::price($item['attribute']['rank']) !!}</span>
                                        @if(!empty($item['attribute']['country_code']))
                                            <span class="">Rank in <i class="flag flag-16 flag-{!! mb_strtolower($item['attribute']['country_code']) !!}"></i> <a href="{!! route('domain.country.iso',array(config('app.url'),$item['attribute']['country_code'])) !!}">{!! $item['attribute']['country_code'] !!}</a>@if(!empty($item['attribute']['rank_country'])): {!! Site::price($item['attribute']['rank_country']) !!}@endif
                                            </span>
                                        @endif
                                    </p>
                                @endif
                                @if(!empty($item['title']))<span>{!! mb_substr(WebService::renameBlacklistWord($item['title']),0,150) !!}</span>@endif
                                <p>
                                @if(!empty($item['view']))<small><strong>Views: {!! $item['view'] !!}</strong></small>@endif
                                @if($channel['security']==true)
                                    @if(!empty($item['attribute']['ads']))<span class="label label-default">{!! $item['attribute']['ads'] !!}</span> @endif
                                @endif
                                </p>
                            </li>
                            @endforeach
                        </ul>
                        <div class="form-group">
                            {!! html_entity_decode($getDomain->links()) !!}
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    @if(count($newDomain))
                        <h4>Domain new updated</h4>
                        <ul class="list-group">
                            @foreach($newDomain as $item)
                                <li class="list-group-item">
                                    <a href="http://{!! $item['domain'] !!}.d.{!! config('app.url') !!}">{!! WebService::renameBlacklistWord($item['domain']) !!}</a><br>
                                    <?php
                                    if ($item['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                                        $date= $item['updated_at']->toDateTime()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
                                    }else{
                                        $date= $item['updated_at'];
                                    }
                                    ?>
                                    <small>{!! $date !!}</small> @if(!empty($item['view']))<small><strong>Views: {!! $item['view'] !!}</strong></small>@endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    @if(count($keywordNewUpdate))
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                New keyword
                            </div>
                            @foreach($keywordNewUpdate as $keyword)
                                <li class="list-group-item">
                                    <a href="{!! route('keyword.show',array($channel['domainPrimary'],WebService::characterReplaceUrl($keyword['keyword']))) !!}">Cung cấp {!! $keyword['keyword'] !!}</a><br>
                                    <small class="text-muted">Updated at {!! $keyword['updated_at']->toDateTime()->setTimezone(new \DateTimeZone(config('app.timezone')))->format('Y-m-d H:i:s') !!}</small>
                                </li>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>