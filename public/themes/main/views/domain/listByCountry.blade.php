<?php
$channel['theme']->setTitle('Cung cấp tên miền tại '.$country->country);
$channel['theme']->setKeywords('');
$channel['theme']->setDescription('cung cấp danh sách tên miền tại '.$country->country);
?>
<section>
    <div class="mainpanel">
        {!!Theme::partial('headerbar', array('title' => 'Header'))!!}
        <div class="pageheader">
            <h1>Cung cấp tên miền tại {!! $country->country !!}</h1>
            <span></span>
        </div>
        <div class="contentpanel">
            <div class="row">
                <div class="col-md-8">
                    @if(count($getDomain))
                        <ul class="list-group">
                            @foreach($getDomain as $item)
                            <li class="list-group-item">
                                <a href="http://{!! $item['domain'] !!}.d.{!! config('app.url') !!}">{!! $item['domain'] !!}</a><br>
                                @if(!empty($item['attribute']['rank']))
                                    <p>
                                        <span class="label label-primary">Global rank: {!! Site::price($item['attribute']['rank']) !!}</span>
                                        @if(!empty($item['attribute']['country_code']))
                                            <span class="">Rank in <i class="flag flag-16 flag-{!! mb_strtolower($country->iso) !!}"></i> {!! $country->country !!}@if(!empty($item['attribute']['rank_country'])): {!! Site::price($item['attribute']['rank_country']) !!}@endif
                                            </span>
                                        @endif
                                    </p>
                                @endif
                                @if(!empty($item['title']))<span>{!! $item['title'] !!}</span>@endif
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
                                    <a href="http://{!! $item['domain'] !!}.d.{!! config('app.url') !!}">{!! $item['domain'] !!}</a><br>
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