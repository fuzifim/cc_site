<?php
Theme::asset()->container('footer')->usePath()->add('swiper.min', 'js/jquery.touchSwipe.min.js', array('core-script'));
Theme::asset()->add('photoswipe', 'assets/library/PhotoSwipe/dist/photoswipe.css', array('core-style'));
Theme::asset()->add('photoswipeSkin', 'assets/library/PhotoSwipe/dist/default-skin/default-skin.css', array('core-style'));
Theme::asset()->container('footer')->add('photoswipeJs', 'assets/library/PhotoSwipe/dist/photoswipe.min.js', array('core-script'));
Theme::asset()->container('footer')->add('photoswipeJsdefault', 'assets/library/PhotoSwipe/dist/photoswipe-ui-default.min.js', array('core-script'));
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
    .block {
        width: 220px;
        max-height:160px;
        overflow: hidden;
        border: 1px solid #dadada;
        padding: 5px;
        background: #fff;
    }
</style>
<div class="panel panel-primary nomargin">
    <div class="panel-heading heading-responsive">
        <h2 class="panel-title">Image relate for {!! $keyword['keyword'] !!}</h2>
    </div>
    <div class="">
        <div id="carousel" class="carousel slide" data-ride="carousel" data-type="multi" data-interval="false">
            <div class="carousel-inner my-gallery" id="" itemscope itemtype="http://schema.org/ImageGallery">
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
                                    <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                                        <a href="https:{{$image['attribute']['image']}}" itemprop="contentUrl" data-width="720" data-height="480" data-size="720x480">
                                            <img class="img-responsive" id="showImageLarge" src="https:{{$image['attribute']['thumb']}}" alt="{{$image['title']}}" title="{{$image['title']}}">
                                        </a>
                                    </figure>
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
                                    <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                                        <a href="https:{{$image['attribute']['image']}}" itemprop="contentUrl" data-width="720" data-height="480" data-size="720x480">
                                            <img class="img-responsive" id="showImageLarge" src="https:{{$image['attribute']['thumb']}}" alt="{{$image['title']}}" title="{{$image['title']}}">
                                        </a>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe.
         It's a separate element, as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
        <!-- don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                        <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div>
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>