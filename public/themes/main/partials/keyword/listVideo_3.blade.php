<div class="panel panel-default">
    <div class="list-group">
        @foreach(array_slice($keyword['video_relate'], $from, $to) as $videoRelate)
            <?php
            $video=DB::connection('mongodb')->collection('mongo_video')
                ->where('_id', (string)$videoRelate)->first();
            ?>
            @if(!empty($video['title']))
                <li class="list-group-item">
                    <a href="{!! route('video.youtube.view.id.slug',array('v.cungcap.net',$video['yid'],str_slug(mb_substr($video['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))) !!}"><img class="img-responsive" src="{!! $video['thumb'] !!}" alt="{!! $video['title'] !!}" title="{!! $video['title'] !!}"></a>
                    <?php
                    if ($video['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                        $updated_at= $video['updated_at']->toDateTime()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
                    }else{
                        $updated_at= $video['updated_at'];
                    }
                    ?>
                    <span class="text-muted"><small>{!! $updated_at !!}</small></span><br>
                    <strong><a href="{!! route('video.youtube.view.id.slug',array('v.cungcap.net',$video['yid'],str_slug(mb_substr($video['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))) !!}">Video {!! mb_substr($video['title'], 0, \App\Model\Mongo_Image::MAX_LENGTH_TITLE) !!}</a></strong><br>
                </li>
            @endif
        @endforeach
    </div>
</div>