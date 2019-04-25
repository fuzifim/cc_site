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
	.blockPost {
		width: 220px;
		height:305px;
		overflow: hidden;
	}
</style>
<div class="panel panel-primary panel-responsive">
	<div class="panel-heading heading-responsive">
		<h2 class="panel-title">Post relate for {!! $keyword['keyword'] !!}</h2>
	</div>
	<div class="">
		<div id="carousel" class="carousel slide" data-ride="carousel" data-type="multi" data-interval="false">
			<div class="carousel-inner">
				<div class="filemanager">
					<?php
					$i=0;
					?>
					@foreach($postSearch as $post)
						@if(!empty($post->id))
							<?php
							$i++;
							$postLink='';
							if(!empty($post->postsJoinChannel->channel->id)){
								$postJoinChannel=$post->postsJoinChannel->channel;
								$domainPrimary = Cache::store('memcached')->remember('domainPrimary_post'.$post->id,5, function() use($postJoinChannel)
								{
									$domainPri='';
									foreach($postJoinChannel->domainAll as $domainChannel){
										if(!empty($domainChannel->domainPrimary->domain)){
											$domainPri=$domainChannel->domainPrimary->domain;
										}
									}
									if(!empty($domainPri)){
										return $domainPri;
									}else if(!empty($postJoinChannel->domainJoinPrimary->domain->domain)){

										return $postJoinChannel->domainJoinPrimary->domain->domain;
									}else{
										return config('app.url');
									}
								});
								if($postJoinChannel->channel_parent_id==0){
									$target='';
									if(!empty($post->getSlug->slug_value)){
										$postLink=route('channel.slug',array($domainPrimary,$post->getSlug->slug_value));
									}
								}else{
									$target='target="_blank"';
									if(!empty($post->getSlug->slug_value)){
										$postLink=route('channel.slug',array($domainPrimary,$post->getSlug->slug_value));
									}
								}
							}
							?>
							@if($i==1)
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
									<div class="blockPost img-responsive content-list-post">
										@if($post->gallery[0]->media->media_storage=='youtube')
											<img src="//img.youtube.com/vi/{{$post->gallery[0]->media->media_name}}/hqdefault.jpg" data-src="" class="img-responsive imgThumb lazy" alt="{!!$post->posts_title!!}" title="{!!$post->posts_title!!}" >
										@elseif($post->gallery[0]->media->media_storage=='video')
											<div class="groupThumb" style="position:relative;">
												<span class="btnPlayVideoClick"><i class="glyphicon glyphicon-play"></i></span>
												<img itemprop="image" class="img-responsive imgThumb lazy" alt="{{$post->posts_title}}" src="{{config('app.link_media').$post->gallery[0]->media->media_path.'xs/'.$post->gallery[0]->media->media_id_random.'.png'}}"/>
											</div>
										@elseif($post->gallery[0]->media->media_storage=='files')
											<img src="{!!asset('assets/img/file.jpg')!!}" class="img-responsive imgThumb lazy" alt="" title="" >
										@else
											@if(empty(Theme::get('image')) && $i==1)
												<?php
												Theme::setImage('https:'.config('app.link_media').$post->gallery[0]->media->media_path.'thumb/'.$post->gallery[0]->media->media_name);
												?>
											@endif
											<img src="{{config('app.link_media').$post->gallery[0]->media->media_path.'xs/'.$post->gallery[0]->media->media_name}}" data-src="" class="img-responsive imgThumb lazy" alt="{!!$post->posts_title!!}" title="{!!$post->posts_title!!}" >
										@endif
									</div>
								</div>
							</div>
						@endif
					@endforeach
				</div>
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