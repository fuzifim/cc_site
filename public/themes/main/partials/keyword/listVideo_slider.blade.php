<?php
Theme::asset()->container('footer')->usePath()->add('swiper.min', 'js/jquery.touchSwipe.min.js', array('core-script'));
?>
<style>
    .carousel-control > a > span {
        color: white;
        font-size: 29px !important;
    }
    .carousel-col {
        position: relative;
        min-height: 1px;
        padding: 5px;
        float: left;
    }
    .active > div { display:none; }
    .active > div:first-child { display:block; }
    /*xs*/
    @media (max-width: 767px) {
        .carousel-col                { width: 50%; }
        .active > div:first-child + div { display:block; }
    }
    /*sm*/
    @media (min-width: 768px) and (max-width: 991px) {
        .carousel-col                { width: 50%; }
        .active > div:first-child + div { display:block; }
    }
    /*md*/
    @media (min-width: 992px) and (max-width: 1199px) {
        .carousel-col                { width: 33%; }
        .active > div:first-child + div { display:block; }
        .active > div:first-child + div + div { display:block; }
    }
    /*lg*/
    @media (min-width: 1200px) {
        .carousel-col                { width: 25%; }
        .active > div:first-child + div { display:block; }
        .active > div:first-child + div + div { display:block; }
        .active > div:first-child + div + div + div { display:block; }
    }
    .blockVideo {
        width: 220px;
        height:200px;
        overflow: hidden;
    }
</style>
<div class="panel panel-default panel-responsive">
    <div class="panel-heading heading-responsive">
        <h2 class="panel-title">Video relate for {!! $keyword['keyword'] !!}</h2>
    </div>
    <div class="">
        <div id="carousel" class="carousel slide" data-ride="carousel" data-type="multi" data-interval="2500">
            <div class="carousel-inner">
                <?php $a=0; ?>
                @foreach(array_slice($keyword['video_relate'], $from, $to) as $videoRelate)
                    <?php
                        $a++;
                    $video=DB::connection('mongodb')->collection('mongo_video')
                        ->where('_id', (string)$videoRelate)->first();
                    ?>
                        @if($a==1)
                            <?php
                                $itemActive='active';
                            ?>
                        @else
                            <?php
                                $itemActive='';
                            ?>
                        @endif
                        <div class="item {!! $itemActive !!}">
                            <div class="carousel-col">
                                <div class="blockVideo img-responsive">
                                    <a href="{!! route('video.youtube.view.id.slug',array($channel['domainPrimary'],$video['yid'],str_slug(mb_substr($video['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))) !!}"><img class="img-responsive" src="{!! $video['thumb'] !!}" alt="{!! $video['title'] !!}" title="{!! $video['title'] !!}"></a>
                                    <?php
                                    if ($video['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                                        $updated_at= $video['updated_at']->toDateTime()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
                                    }else{
                                        $updated_at= $video['updated_at'];
                                    }
                                    ?>
                                    <span class="text-muted"><small>{!! $updated_at !!}</small></span><br>
                                    <strong><a class="linkTitle" href="{!! route('video.youtube.view.id.slug',array($channel['domainPrimary'],$video['yid'],str_slug(mb_substr($video['title'], 0, \App\Model\Mongo_video::MAX_LENGTH_SLUG),'-'))) !!}">Video {!! mb_substr($video['title'], 0, \App\Model\Mongo_Image::MAX_LENGTH_TITLE) !!}</a></strong>
                                </div>
                            </div>
                        </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<?php
$dependencies = array();
Theme::asset()->writeScript('ImageSlider','
		$(".carousel[data-type=multi] .item").each(function() {
            var next = $(this).next();
            if (!next.length) {
                next = $(this).siblings(":first");
            }
            next.children(":first-child").clone().appendTo($(this));

            for (var i = 0; i < 2; i++) {
                next = next.next();
                if (!next.length) {
                    next = $(this).siblings(":first");
                }

                next.children(":first-child").clone().appendTo($(this));
            }
        });
        $(".carousel").swipe({

          swipe: function(event, direction, distance, duration, fingerCount, fingerData) {

            if (direction == "left") $(this).carousel("next");
            if (direction == "right") $(this).carousel("prev");

          },
          allowPageScroll:"vertical"

        });
	', $dependencies);
?>