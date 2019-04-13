<div class="panel panel-primary">
    <div class="panel-heading">
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
                        $description=$description.' '.$site['title'];
                    ?>
                @endif
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
                    <h4><a class="siteLink" id="linkContinue" href="{!! route('site.show.id',array($channel['domainPrimary'],$site['_id'],str_slug(mb_substr($site['title'], 0, \App\Model\Mongo_site::MAX_LENGTH_SLUG),'-'))) !!}">{!! $site['title'] !!}</a></h4>
                    <?php
                    if ($site['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                        $updated_at= $site['updated_at']->toDateTime()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
                    }else{
                        $updated_at= $site['updated_at'];
                    }
                    ?>
                    <span class="text-muted"><small>{!! $updated_at !!}</small></span><br>
                    <span>{!! $site['description'] !!}</span><br>
                    <span>{!! $site['link'] !!}</span><br>
                    <i class="glyphicon glyphicon-globe"></i> <a href="http://{!! $site['domain'] !!}.d.{!! config('app.url') !!}" target="blank">{!! WebService::renameBlacklistWord($site['domain']) !!}</a>
                </li>
            @endif
        @endforeach
        @if(empty($keyword['description']) && !empty($description))
            <?php
                Theme::setDescription($description);
            ?>
        @endif
    </ul>

</div>