<div class="row row-pad-5 filemanager">
@foreach($chunk as $post)
	@if(!empty($post->id))
	<?php
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
	@if(!empty($post->gallery[0]->media->media_url))
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 content-list-post">
		<div class="thmb">
			<a class="thmb-prev" href="{{$postLink}}" {{$target}}>
			@if($post->gallery[0]->media->media_storage=='youtube')
				<img src="//img.youtube.com/vi/{{$post->gallery[0]->media->media_name}}/hqdefault.jpg" data-src="" class="img-responsive imgThumb lazy" alt="" title="" >
			@elseif($post->gallery[0]->media->media_storage=='video')
			<div class="groupThumb" style="position:relative;">
				<span class="btnPlayVideoClick"><i class="glyphicon glyphicon-play"></i></span>
				<img itemprop="image" class="img-responsive imgThumb lazy" alt="{{$post->posts_title}}" src="{{config('app.link_media').$post->gallery[0]->media->media_path.'xs/'.$post->gallery[0]->media->media_id_random.'.png'}}"/>
			</div>
			@elseif($post->gallery[0]->media->media_storage=='files')
			<img src="{!!asset('assets/img/file.jpg')!!}" class="img-responsive imgThumb lazy" alt="" title="" >
			@else
				<img src="{{config('app.link_media').$post->gallery[0]->media->media_path.'xs/'.$post->gallery[0]->media->media_name}}" data-src="" class="img-responsive imgThumb lazy" alt="" title="" >
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
	@endif
	@endif
@endforeach
</div>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('loadLazy','
		$(function() {
			$(".lazy").lazy();
		});
	', $dependencies);
?>