
@foreach($chunk as $post)
@if(!empty($post->getSlug->slug_value))
	<?
		if($post->postsJoinChannel->channel->domainJoinPrimary->domain->domain_primary!='default'){
			if(count($post->postsJoinChannel->channel->domainAll)>0){
				foreach($post->postsJoinChannel->channel->domainAll as $domain){
					if($domain->domain->domain_primary=='default'){
						$domainPrimary=$domain->domain->domain; 
					}
				}
			}else{
				$domainPrimary=$post->postsJoinChannel->channel->domainJoinPrimary->domain->domain; 
			}
		}else{
			$domainPrimary=$post->postsJoinChannel->channel->domainJoinPrimary->domain->domain; 
		}
	?>
	@if(!empty($post->gallery[0]->media->media_url))
		<div class="well padding-10">
			<div class="row">
				<div class="col-md-4">
					<a class="thmb-prev" href="{{route('channel.slug',array($domainPrimary,$post->getSlug->slug_value))}}">
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
				</div>
				<div class="col-md-8 padding-left-0">
					<h3 class="margin-top-0 post-title"><a href="{{route('channel.slug',array($domainPrimary,$post->getSlug->slug_value))}}"> {!!$post->posts_title!!}</a></h3>
					<ul class="list-inline font-xs">
						<li>
							<i class="fa fa-calendar"></i> {!!WebService::time_request($post->posts_updated_at)!!}
						</li>
						<li>
							<i class="fa fa-user"></i>
							<a href="javascript:void(0);"> {{$post->author->user->name}} </a>
						</li> 
						<li>
							<i class="fa fa-map-marker"></i>
							<a href="javascript:void(0);"> Hồ Chí Minh </a>
						</li>
					</ul>
					@if(!empty($post->price->posts_attribute_value))
						<p><strong class="text-danger">{!!WebService::price($post->price->posts_attribute_value)!!} @if(!empty($post->postsJoinChannel->channel->channel_currency))<sup>{{$post->postsJoinChannel->channel->channelCurrency->currency_name}}</sup>@elseif(!empty($post->postsJoinChannel->channel->joinAddress[0]->address->joinRegion->region->currency_name))<sup>{{$post->postsJoinChannel->channel->joinAddress[0]->address->joinRegion->region->currency_name}}</sup>@else<sup>VNĐ</sup>@endif</strong></p>
					@endif
					@if(!empty($post->posts_description))
						<p>
							<?
								$postContent=html_entity_decode($post->posts_description); 
								$postContent=preg_replace('/(<p[^>]*>)(.*?)(<\/p>)/i', '$2<p>', $postContent);
								$postContent=WebService::limit_string(strip_tags($postContent," "), $limit = 500); 
								$postContent=str_replace('????', '', $postContent);
								
							?>
							{!!$postContent!!}
						</p>
					@endif
					<ul class="list-inline font-xs">
						<li>
							<a href="javascript:void(0);" class="text-primary"><i class="fa fa-thumbs-up"></i> Like</a>
						</li>
						<li>
							<a href="javascript:void(0);" class="text-danger"><i class="fa fa-thumbs-down"></i> </a>
						</li>
						<li>
							<a href="javascript:void(0);" class="text-muted"><i class="fa fa-eye"></i> {{$post->posts_view}} views</a>
						</li>
						<li>
							<a href="javascript:void(0);" class="text-muted"><i class="fa fa-comment"></i> ({{count($post->commentJoinPost)}})</a>
						</li>
						<li>
							<a href="javascript:void(0);" class="text-primary"><i class="fa fa-share"></i> Share</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	@endif
@endif
@endforeach
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('loadLazy','
		$(function() {
			$(".lazy").lazy();
		});
	', $dependencies);
?>