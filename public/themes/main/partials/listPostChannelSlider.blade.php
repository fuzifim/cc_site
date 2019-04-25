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
		border: 1px #dadada solid; padding: 5px; 
	}
</style>
<div class="panel panel-primary panel-responsive">
	<div class="panel-heading heading-responsive">
		<h2 class="panel-title">Post relate for {!! $keyword['keyword'] !!}</h2>
	</div>
	<div class="">
		<div id="carousel" class="carousel slide" data-ride="carousel" data-type="multi" data-interval="5000">
			<div class="carousel-inner">
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
							@if(!empty($post->gallery[0]->media->media_url))
								<div class="item {!! $itemActive !!}">
									<div class="carousel-col">
										<div class="blockPost img-responsive">
											<div class="thmb">
												<a class="thmb-prev" href="{{$postLink}}" {{$target}}>
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
												</a>
												<h5 class="fm-title"><a class="title" href="{{$postLink}}"  {{$target}}>{!!$post->posts_title!!}</a></h5>
												@if(!empty($postJoinChannel->channel_name))
													<div class="attribute-3">
														<small>
															<a href="{{route('channel.home',$domainPrimary)}}" class="text-muted" {{$target}}>
																<img src="@if(!empty($postJoinChannel->channelAttributeLogo->media->media_name)){{config('app.link_media').$postJoinChannel->channelAttributeLogo->media->media_path.'xs/'.$postJoinChannel->channelAttributeLogo->media->media_name}}@endif" alt="" style="max-height:18px;"> <small>{!!$postJoinChannel->channel_name!!}</small>
															</a>
														</small>
													</div>
												@endif
												<div class="attribute-2">
													<small>
														<span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($post->posts_updated_at)!!}</span>
														<span class="post-view">{{$post->posts_view}} lượt xem</span>
														@if(!empty($post->price->posts_attribute_value))
															<br><strong class="text-danger">{!!WebService::price($post->price->posts_attribute_value)!!} @if(!empty($post->postsJoinChannel->channel->channel_currency))<sup>{{$postJoinChannel->channelCurrency->currency_name}}</sup>@elseif(!empty($postJoinChannel->joinAddress[0]->address->joinRegion->region->currency_name))<sup>{{$postJoinChannel->joinAddress[0]->address->joinRegion->region->currency_name}}</sup>@else<sup>VNĐ</sup>@endif</strong>
														@endif
													</small>
												</div>
												<div class="form-group attribute-1">
													<small class="text-muted"><span class="author"><i class="glyphicon glyphicon-user"></i> {{$post->author->user->name}}</span></small>
												</div>
											</div>
										</div>
									</div>
								</div>
							@endif
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