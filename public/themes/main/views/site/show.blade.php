<?php
Theme::setTitle($site['title']);
Theme::setDescription($site['description']);
Theme::setCanonical(route('site.show.id',array($channel['domainPrimary'],$site['_id'],str_slug(mb_substr($site['title'], 0, \App\Model\Mongo_site::MAX_LENGTH_SLUG),'-'))));

?>
<section>
    <div class="mainpanel">
        {!!Theme::partial('headerbar', array('title' => 'Header'))!!}
        <div class="pageheader form-group">
            @if(!empty($site['title_full']))
                <h1><strong>{!! $site['title_full'] !!}</strong></h1>
            @else
                <h1><strong>{!! $site['title'] !!}</strong></h1>
            @endif
            <?php
            if ($site['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                $updated_at= $site['updated_at']->toDateTime()->setTimezone(new \DateTimeZone(config('app.timezone')))->format('Y-m-d H:i:s');
            }else{
                $updated_at= $site['updated_at'];
            }
            ?>
            <small>Updated at {!! $updated_at !!}</small> @if(!empty($site['view']))<small><strong>Views: {!! $site['view'] !!}</strong></small>@endif
        </div>
        <div class="container">
            <div class="panel panel-default">
                <div class="panel-heading">
                    infomation for {!! $site['domain'] !!}: {!! $site['title'] !!}
                </div>
                <div class="panel-body">
                    <span>{!! $site['description'] !!}</span><br>
                    <span>{!! $site['link'] !!}</span><br>
                    <a class="btn btn-primary btn-block" id="" href="{!! route('go.to.url',array(config('app.url'),urlencode($site['link']))) !!}" rel="nofollow" target="_blank">Visit to {!! $site['title'] !!}
                        <p><strong>Click here</strong></p>
                    </a>
                </div>
            </div>
            @if(count($siteRelate))
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Site relate for {!! $site['domain'] !!}
                    </div>
                    <ul class="list-group">
                        {!!Theme::partial('site.listSite', array('sites' => $siteRelate,'showDomain'=>true))!!}
                    </ul>
                </div>
            @endif
        </div>
    </div>
</section>
