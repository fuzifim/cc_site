<?php
Theme::setTitle('Cung cấp video '.$video['title']);
Theme::setDescription($video['description']);
Theme::setImage('https:'.$video['image']);
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
                <p>Parent <a href="{{route('keyword.show',array($channel['domainPrimary'],WebService::characterReplaceUrl($video['parent'])))}}">{!! $video['parent'] !!}</a></p>
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
                                                <p class="text-center"><a href="{!! route('video.youtube.view',array($channel['domainPrimary'],$item['yid'])) !!}"><img class="img-responsive" src="{!! $item['thumb'] !!}" title="{!! $item['title'] !!}" alt="{!! $item['title'] !!}"></a></p>
                                                <strong><a href="{!! route('video.youtube.view',array($channel['domainPrimary'],$item['yid'])) !!}">Cung cấp video {!! $item['title'] !!}</a> </strong>
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
                                    <p class="text-center"><a href="{!! route('video.youtube.view',array($channel['domainPrimary'],$item['yid'])) !!}"><img src="{!! $item['thumb'] !!}" title="{!! $item['title'] !!}" alt="{!! $item['title'] !!}"></a></p>
                                    <strong><a href="{!! route('video.youtube.view',array($channel['domainPrimary'],$item['yid'])) !!}">Cung cấp video {!! $item['title'] !!}</a> </strong>
                                </li>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>