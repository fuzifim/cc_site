<div class="results-list PostlistItem">
	<?
		$idPost=array(); 
		$i=0; 
	?>
	@foreach($getItems as $item)
		<? $i++;?>
		@if($i==2 && count($affiliateSearch)>0)
			{!!Theme::partial('affiliate.affiliate_2', array('affiliateSearch' => $affiliateSearch))!!}
		@endif
		@if($item['type']=='post')
			<?
				$post=\App\Model\Posts::find($item['id']); 
			?>
		@if(!empty($post->id) && !empty($post->getSlug->slug_value))
			<?
				$postJoinChannel=\App\Model\Posts_join_channel::where('posts_id','=',$post->id)->first(); 
				$getChannel=\App\Model\Channel::find($postJoinChannel->channel_id); 
				if(!empty($getChannel->channel_name)){
					if($getChannel->domainJoinPrimary->domain->domain_primary!='default'){
						if(count($getChannel->domainAll)>0){
							foreach($getChannel->domainAll as $domain){
								if($domain->domain->domain_primary=='default'){
									$domainPrimary=$domain->domain->domain; 
								}
							}
						}else{
							$domainPrimary=$getChannel->domainJoinPrimary->domain->domain; 
						}
					}else{
						$domainPrimary=$getChannel->domainJoinPrimary->domain->domain; 
					}
					$postLink=''; 
					$target=''; 
					if($getChannel->channel_parent_id==0){
						$postLink='https://post-'.$post->id.'.'.config('app.url'); 
					}else{
						$target='target="_blank"'; 
						if(!empty($post->getSlug->slug_value)){
							$postLink=route('channel.slug',array($domainPrimary,$post->getSlug->slug_value)); 
						}
					}
				}
			?>
			<div class="list-group-item media">
				<div class="col-md-3 col-xs-12">
					<a href="{{$postLink}}" class="" {{$target}}>
						@if(count($post->gallery)>0)
							@if($post->gallery[0]->media->media_storage=='youtube')
								<img src="//img.youtube.com/vi/{{$post->gallery[0]->media->media_name}}/hqdefault.jpg" class="media-object lazy" alt="" title="" >
							@elseif($post->gallery[0]->media->media_storage=='video')
								<div class="groupThumb" style="position:relative;">
									<span class="btnPlayVideoClick"><i class="glyphicon glyphicon-play"></i></span>
									<img itemprop="image" class="img-responsive lazy" alt="{{$post->posts_title}}" src="{{config('app.link_media').$post->gallery[0]->media->media_path.'xs/'.$post->gallery[0]->media->media_id_random.'.png'}}"/>
								</div>
							@elseif($post->gallery[0]->media->media_storage=='files')
								<img src="{!!asset('assets/img/file.jpg')!!}" class="media-object" alt="" title="" >
							@else
								<img src="{{config('app.link_media').$post->gallery[0]->media->media_path.'xs/'.$post->gallery[0]->media->media_name}}" class="img-responsive lazy" alt="" title="" >
							@endif
						@endif
					</a>
				</div>
				<div class="col-md-9 col-xs-12">
				  <h2 class="blog-title text-primary"><a href="{{$postLink}}" class="" {{$target}}>{!!$post->posts_title!!}</a></h2>
				  @if(strlen($post->posts_description)>5)<p>{{WebService::limit_string(strip_tags(html_entity_decode($post->posts_description),""), $limit = 200)}}</p>@endif
				  <small class="text-muted"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($post->posts_updated_at)!!}</small> 
				  <small class="text-muted"><i class="glyphicon glyphicon-user"></i> {!!$post->author->user->name!!}</small> 
				  <small class="text-muted text-danger"><strong>{{$post->posts_view}} lượt xem</strong></small>
				</div>
			</div>
			@endif
		@elseif($item['type']=='domain')
			<?
				$domain=\App\Model\Domain::find($item['id']); 
			?>
			<div class="list-group-item">
				<h4 class="blog-title headTitle"><strong><a class="text-info siteLink" data-url="" target="_blank" href="@if($domain->domain!=config('app.url'))http://{{$domain->domain}}.{{config('app.url')}}@endif">{{$domain->domain_title}}</a></strong></h4>
				<span class=""><img class="lazy" src="https://www.google.com/s2/favicons?domain={{$domain->domain}}" alt="{{$domain->domain}}" title="{{$domain->domain}}"> <a class="text-danger siteLink" target="_blank" data-url="" href="@if($domain->domain!=config('app.url'))http://{{$domain->domain}}.{{config('app.url')}}@endif">{{$domain->domain}}</a></span> 
				<p>{{$domain->domain_description}}</p>
			</div>
		@endif
	@endforeach
	
</div>
{!!Theme::partial('pagination', array('paginator' => $getItems))!!}