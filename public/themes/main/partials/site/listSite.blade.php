<?php
$i=0;
?>
@foreach($sites as $item)
    <?php
    $i++;
    ?>
    @if(!empty($item['title']))
        @if($i==3 || $i==8)
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
        @endif
        <li class="list-group-item">
            <h4 class="linkTitleH4"><a class="" id="" href="{!! route('site.show.id',array($channel['domainPrimary'],$item['_id'],str_slug(mb_substr($item['title'], 0, \App\Model\Mongo_site::MAX_LENGTH_SLUG),'-'))) !!}">{!! $item['title'] !!}</a></h4>
            <span class="urlFull text-success">{!! $item['link'] !!}</span>
            <?php
            if ($item['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                $updated_at= $item['updated_at']->toDateTime()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
            }else{
                $updated_at= $item['updated_at'];
            }
            ?>
            <span class="text-muted"><small>{!! $updated_at !!}</small></span><br>
            <span>{!! $item['description'] !!}</span><br>
            @if($showDomain==true)
                <a class="linkTitle text-muted" href="{!! route('domain.info',array(config('app.url'),$item['domain'])) !!}"><i class="glyphicon glyphicon-globe"></i> {!! WebService::renameBlacklistWord($item['domain']) !!}</a>
            @endif
        </li>
    @endif
@endforeach