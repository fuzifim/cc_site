<?php
Theme::asset()->container('footer')->usePath()->add('swiper.min', 'js/jquery.touchSwipe.min.js', array('core-script'));
?>
<style>
    .col-centered {
        float: none;
        margin: 0 auto;
    }

    .carousel-control {
        width: 8%;
        width: 0px;
    }
    .carousel-control.left,
    .carousel-control.right {
        margin-right: 40px;
        margin-left: 32px;
        background-image: none;
        opacity: 1;
    }
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
        .carousel-inner .active.left { left: -50%; }
        .carousel-inner .active.right { left: 50%; }
        .carousel-inner .next        { left:  50%; }
        .carousel-inner .prev		     { left: -50%; }
        .carousel-col                { width: 50%; }
        .active > div:first-child + div { display:block; }
    }

    /*sm*/
    @media (min-width: 768px) and (max-width: 991px) {
        .carousel-inner .active.left { left: -50%; }
        .carousel-inner .active.right { left: 50%; }
        .carousel-inner .next        { left:  50%; }
        .carousel-inner .prev		     { left: -50%; }
        .carousel-col                { width: 50%; }
        .active > div:first-child + div { display:block; }
    }

    /*md*/
    @media (min-width: 992px) and (max-width: 1199px) {
        .carousel-inner .active.left { left: -33%; }
        .carousel-inner .active.right { left: 33%; }
        .carousel-inner .next        { left:  33%; }
        .carousel-inner .prev		     { left: -33%; }
        .carousel-col                { width: 33%; }
        .active > div:first-child + div { display:block; }
        .active > div:first-child + div + div { display:block; }
    }

    /*lg*/
    @media (min-width: 1200px) {
        .carousel-inner .active.left { left: -25%; }
        .carousel-inner .active.right{ left:  25%; }
        .carousel-inner .next        { left:  25%; }
        .carousel-inner .prev		     { left: -25%; }
        .carousel-col                { width: 25%; }
        .active > div:first-child + div { display:block; }
        .active > div:first-child + div + div { display:block; }
        .active > div:first-child + div + div + div { display:block; }
    }

    .block {
        width: 220px;
        max-height:160px;
        overflow: hidden;
    }

    .red {background: red;}

    .blue {background: blue;}

    .green {background: green;}

    .yellow {background: yellow;}
</style>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h2 class="panel-title">Image relate for {!! $keyword['keyword'] !!}</h2>
    </div>
    <div class="panel-body">

        <div id="carousel" class="carousel slide" data-ride="carousel" data-type="multi" data-interval="2500">
            <div class="carousel-inner">
                <?php $a=0; ?>
                @foreach($keyword['image_relate'] as $imageRelate)
                    <?php
                    $a++;
                    $image=DB::connection('mongodb')->collection('mongo_image')
                        ->where('_id', (string)$imageRelate)->first();
                    ?>
                    @if($a==5)
                        <?php break; ?>
                    @endif
                    @if($a==1)
                        <div class="item active">
                            <div class="carousel-col">
                                <div class="block img-responsive">
                                    <img class="img-responsive" id="showImageLarge" src="https:{{$image['attribute']['thumb']}}" alt="{{$image['title']}}" title="{{$image['title']}}">
                                    {{--                            <h3 class="subtitle text-center"><span class="text-light" id="showImageLargeLink"><span class="text-light">{{$image['title']}}</span></span></h3>--}}
                                </div>
                            </div>
                        </div>
                        @if(empty($keyword['image']))
                            <?php
                            Theme::setImage('https:'.$image['attribute']['image']);
                            ?>
                        @endif
                    @else
                        <div class="item">
                            <div class="carousel-col">
                                <div class="block img-responsive">
                                    <img class="img-responsive" id="showImageLarge" src="https:{{$image['attribute']['thumb']}}" alt="{{$image['title']}}" title="{{$image['title']}}">
                                    {{--                            <h3 class="subtitle text-center"><span class="text-light" id="showImageLargeLink"><span class="text-light">{{$image['title']}}</span></span></h3>--}}
                                </div>
                            </div>
                        </div>
                    @endif
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