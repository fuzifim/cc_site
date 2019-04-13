<?php
Theme::setTitle('Cung cấp video '.$video['title']);
Theme::setDescription($video['description']);
Theme::setImage('https:'.$video['image']);
Theme::setCanonical(route('video.youtube.view.id.slug',array($channel['domainPrimary'],$video['yid'],str_slug(mb_substr($video['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))));
?>
<section>
    <div class="mainpanel">
        {!!Theme::partial('headerbar', array('title' => 'Header'))!!}
        <div class="pageheader form-group">
            <h1><strong>Cung cấp video {!! $video['title'] !!}</strong></h1>
            <?php
            if ($video['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                $updated_at= $video['updated_at']->toDateTime()->setTimezone(new \DateTimeZone(config('app.timezone')))->format('Y-m-d H:i:s');
            }else{
                $updated_at= $video['updated_at'];
            }
            ?>
            <small>Updated at {!! $updated_at !!}</small> @if(!empty($video['view']))<small><strong>Views: {!! $video['view'] !!}</strong></small>@endif
            @if(!empty($video['parent']))
                @if(empty($video['parent_id']))
                    <?php
                    $parentKey = DB::connection('mongodb')->collection('mongo_keyword')
                        ->where('base_64', base64_encode($video['parent']))->first();
                    DB::connection('mongodb')->collection('mongo_video')
                        ->where('_id',(string)$video['_id'])
                        ->update(
                            [
                                'parent_id'=>(string)$parentKey['_id']
                            ]
                        );

                    ?>
                    <p>Parent <a href="{!! route('keyword.show.id',array($channel['domainPrimary'],$parentKey['_id'],str_slug(mb_substr($parentKey['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}">{!! $video['parent'] !!}</a></p>
                @else
                    <p>Parent <a href="{!! route('keyword.show.id',array($channel['domainPrimary'],$video['parent_id'],str_slug(mb_substr($video['parent'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}">{!! $video['parent'] !!}</a></p>
                @endif
            @endif
        </div>
        <div class="container">
            <div class="row row-pad-5">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe width="640" height="320" class="embed-responsive-item" src="https://www.youtube.com/embed/{!! $video['yid'] !!}" allowfullscreen></iframe>
                            </div>
                            <p>{!! $video['description'] !!}</p>
                        </div>
                    </div>
                    @if(count($videoParent))
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                Video liên quan
                            </div>
                            <div class="panel-body">
                                @foreach(array_chunk(array_slice($videoParent, 0, 12),4) as $chunk)
                                    <div class="row row-pad-5">
                                        @foreach($chunk as $item)
                                            <div class="col-md-3">
                                                <p class="text-center"><a href="{!! route('video.youtube.view.id.slug',array($channel['domainPrimary'],$item['yid'],str_slug(mb_substr($item['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))) !!}"><img class="img-responsive" src="{!! $item['thumb'] !!}" title="{!! $item['title'] !!}" alt="{!! $item['title'] !!}"></a></p>
                                                <strong><a href="{!! route('video.youtube.view.id.slug',array($channel['domainPrimary'],$item['yid'],str_slug(mb_substr($item['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))) !!}">Cung cấp video {!! $item['title'] !!}</a> </strong>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    @if(count($videoParent))
                        <div class="panel panel-primary">
                            @foreach(array_slice($videoParent, 12, 8) as $item)
                                <li class="list-group-item">
                                    <p class="text-center"><a href="{!! route('video.youtube.view.id.slug',array($channel['domainPrimary'],$item['yid'],str_slug(mb_substr($item['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))) !!}"><img src="{!! $item['thumb'] !!}" title="{!! $item['title'] !!}" alt="{!! $item['title'] !!}"></a></p>
                                    <strong><a href="{!! route('video.youtube.view.id.slug',array($channel['domainPrimary'],$item['yid'],str_slug(mb_substr($item['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))) !!}">Cung cấp video {!! $item['title'] !!}</a> </strong>
                                </li>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
