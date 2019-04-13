@foreach($sites as $item)
    @if(!empty($item['title']))
        <li class="list-group-item">
            <h4><a class="" id="" href="{!! route('site.show.id',array($channel['domainPrimary'],$item['_id'],str_slug(mb_substr($item['title'], 0, \App\Model\Mongo_site::MAX_LENGTH_SLUG),'-'))) !!}">{!! $item['title'] !!}</a></h4>
            <?php
            if ($item['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                $updated_at= $item['updated_at']->toDateTime()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
            }else{
                $updated_at= $item['updated_at'];
            }
            ?>
            <span class="text-muted"><small>{!! $updated_at !!}</small></span><br>
            <span>{!! $item['description'] !!}</span><br>
            <span>{!! $item['link'] !!}</span><br>
            @if($showDomain==true)
                <i class="glyphicon glyphicon-globe"></i> <a href="http://{!! $item['domain'] !!}.d.{!! config('app.url') !!}">{!! WebService::renameBlacklistWord($item['domain']) !!}</a>
            @endif
        </li>
    @endif
@endforeach