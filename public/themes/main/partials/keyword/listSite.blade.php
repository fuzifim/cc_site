<div class="panel panel-primary panel-responsive">
    <div class="panel-heading heading-responsive">
        <h2 class="panel-title">Site relate for {!! $keyword['keyword'] !!}</h2>
    </div>
    <ul class="list-group">
        <?php
            $i=0;
            $description='';
        ?>
        @foreach($keyword['site_relate'] as $siteRelate)
            <?php
                $i++;
                $site=DB::connection('mongodb')->collection('mongo_site')
                    ->where('_id', (string)$siteRelate)->first();
            ?>
            @if(!empty($site['title']))
                @if(empty($keyword['description']) && $i<=3)
                    <?php
                        $description=$description.' '.$site['title_full'];
                    ?>
                @endif
                @if($i==$skipImage && $imageShow==true)
                    {!!Theme::partial('keyword.listImage', array('keyword' => $keyword))!!}
                @endif
                @if($i==$skipImage)
                    @if($ads=='true' && config('app.env')!='local')
                        <ins class="adsbygoogle"
                             style="display:block"
                             data-ad-client="ca-pub-6739685874678212"
                             data-ad-slot="7536384219"
                             data-ad-format="auto"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    @endif
                @endif
                <li class="list-group-item">
                    <h4 class="linkTitleH4"><a id="linkContinue" href="{!! route('site.show.id',array('s.cungcap.net',$site['_id'],str_slug(mb_substr($site['title'], 0, \App\Model\Mongo_site::MAX_LENGTH_SLUG),'-'))) !!}">@if(!empty($site['title_full'])){!! $site['title_full'] !!}@else{!! $site['title_'] !!}@endif</a></h4>
                    <small class="urlFull text-success"><i>{!! $site['link'] !!}</i></small><?php
                    if ($site['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                        $updated_at= $site['updated_at']->toDateTime()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
                    }else{
                        $updated_at= $site['updated_at'];
                    }
                    ?>
                    <span class="text-muted"><small>{!! $updated_at !!}</small></span><br>
                    <span>{!! $site['description'] !!}</span><br>
                    <a class="linkTitle text-muted" href="{!! route('domain.info',array('d.cungcap.net',$site['domain'])) !!}"><i class="glyphicon glyphicon-globe"></i> {!! WebService::renameBlacklistWord($site['domain']) !!}</a>
                </li>
                @if($i==$skipVideo && $videoShow==true)
                     {!!Theme::partial('keyword.listVideo_slider', array('keyword' => $keyword,'from'=>0,'to'=>8))!!}
                @endif
                @if($i==$skipVideo)
                    @if($ads=='true' && config('app.env')!='local')
                        <ins class="adsbygoogle"
                             style="display:block"
                             data-ad-client="ca-pub-6739685874678212"
                             data-ad-slot="7536384219"
                             data-ad-format="auto"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    @endif
                @endif
            @endif
        @endforeach
        @if(empty($keyword['description']) && !empty($description))
            <?php
                Theme::setDescription($description);
            ?>
        @endif
    </ul>

</div>