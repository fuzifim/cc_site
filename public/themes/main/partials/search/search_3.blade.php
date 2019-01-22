<div class="row row-pad-5">
	<div class="col-md-9">
		@if(count($newsSearch)>0)
			<div class="row row-pad-5">
				<div class="col-md-8">
					<div class="results-list PostlistItem">
			<?
				$idPost=array(); 
				$i=0; 
			?>
			@foreach($getItems as $item)
				<? $i++;?>
				@if(count($getItems)>1 && $i==2 && count($affiliateSearch)>0)
					{!!Theme::partial('affiliate.affiliate_2', array('affiliateSearch' => $affiliateSearch))!!}
				@elseif(count($getItems)<=1 && $i==1)
					{!!Theme::partial('affiliate.affiliate_2', array('affiliateSearch' => $affiliateSearch))!!}
				@endif
				@if($ads=='true')
					@if($i==2 || $i==5)
						<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
						<div class="form-group mt5">
							<!-- Ad News -->
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
				@if($item['type']=='post')
					<?
						$post=\App\Model\Posts::find($item['id']); 
					?>
				@if(!empty($post->id) && !empty($post->getSlug->slug_value) && $post->posts_status=='active')
					<?
						$postJoinChannel=\App\Model\Posts_join_channel::where('posts_id','=',$post->id)->first(); 
						$domainPrimary=''; 
						if(!empty($postJoinChannel->channel_id)){
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
						  <h2 class="blog-title text-primary"><a href="{{$postLink}}" {{$target}}>{!!$post->posts_title!!}</a></h2>
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
				</div>
				<div class="col-md-4">
					<div class="panel panel-default form-group">
						<div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> Tin liên quan</div>
						<div class="list-group">
							@foreach($newsSearch as $postRelate)
								@if(!empty($postRelate->image))
									<div class="list-group-item form-group">
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
											<a class="image" href="https://news-{{$postRelate->id}}.{{config('app.url')}}">
												<img src="{{$postRelate->image}}" class="img-responsive img-thumbnail lazy" alt="{!!$postRelate->title!!}" title="" >
											</a>
										</div>
										<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
											<h5 class="postTitle nomargin"><a class="title" href="https://news-{{$postRelate->id}}.{{config('app.url')}}">{!!$postRelate->title!!}</a></h5>
											<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($postRelate->updated_at)!!}</span></small> <small><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],Str::slug($postRelate->joinField->SolrID)))}}"><span itemprop="name">{!!$postRelate->joinField->name!!}</span></a></small>
										</div>
									</div>
								@else 
									<div class="list-group-item form-group">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<h5 class="postTitle"><a class="title" href="https://news-{{$postRelate->id}}.{{config('app.url')}}">{!!$postRelate->title!!}</a></h5>
											<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($postRelate->updated_at)!!}</span></small> <small><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],Str::slug($postRelate->joinField->SolrID)))}}"><span itemprop="name">{!!$postRelate->joinField->name!!}</span></a></small>
										</div>
									</div>
								@endif
							@endforeach
						</div>
						<div class="panel-footer text-center">
							<a href="#" class="view-more-ads"><i class="glyphicon glyphicon-chevron-down"></i> Hiển thị thêm</a>
						</div>
					</div> 
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<div class="form-group mt5">
						<!-- Ad News -->
						<ins class="adsbygoogle"
							 style="display:block"
							 data-ad-client="ca-pub-6739685874678212"
							 data-ad-slot="7536384219"
							 data-ad-format="auto"></ins>
						<script>
						(adsbygoogle = window.adsbygoogle || []).push({});
						</script>
					</div>
				</div>
			</div>
		@else 
			<div class="results-list PostlistItem">
				<?
					$idPost=array(); 
					$i=0; 
				?>
				@foreach($getItems as $item)
					<? $i++;?>
					@if(count($getItems)>1 && $i==2 && count($affiliateSearch)>0)
						{!!Theme::partial('affiliate.affiliate_2', array('affiliateSearch' => $affiliateSearch))!!}
					@elseif(count($getItems)<=1 && $i==1)
						{!!Theme::partial('affiliate.affiliate_2', array('affiliateSearch' => $affiliateSearch))!!}
					@endif
					@if($ads=='true')
						@if($i==2 || $i==5)
							<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
							<div class="form-group mt5">
								<!-- Ad News -->
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
					@if($item['type']=='post')
						<?
							$post=\App\Model\Posts::find($item['id']); 
						?>
					@if(!empty($post->id) && !empty($post->getSlug->slug_value) && $post->posts_status=='active')
						<?
							$postJoinChannel=\App\Model\Posts_join_channel::where('posts_id','=',$post->id)->first(); 
							$domainPrimary=''; 
							if(!empty($postJoinChannel->channel_id)){
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
							  <h2 class="blog-title text-primary"><a href="{{$postLink}}" {{$target}}>{!!$post->posts_title!!}</a></h2>
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
		@endif
	</div>
	<div class="col-md-3">
		<div class="list-group">
			<h5 class="panel-heading subtitle mb5">Website liên quan</h5>
			@foreach($domainSearch as $item)
			<?
				$domain=\App\Model\Domain::find($item->id); 
			?>
			<div class="list-group-item">
				<h4 class="blog-title headTitle"><strong><a class="text-info siteLink" data-url="{{json_encode(route("go.to.url",array(config("app.url"),urlencode('http://'.$domain->domain))))}}" target="_blank" href="@if($domain->domain!=config('app.url'))http://{{$domain->domain}}.{{config('app.url')}}@endif">{{$domain->domain_title}}</a></strong></h4>
				<span class=""><img class="lazy" src="https://www.google.com/s2/favicons?domain={{$domain->domain}}" alt="{{$domain->domain}}" title="{{$domain->domain}}"> <a class="text-danger siteLink" target="_blank" data-url="{{json_encode(route("go.to.url",array(config("app.url"),urlencode('http://'.$domain->domain))))}}" href="@if($domain->domain!=config('app.url'))http://{{$domain->domain}}.{{config('app.url')}}@endif">{{$domain->domain}}</a></span> 
				<p>{{$domain->domain_description}}</p>
			</div>
		@endforeach
		</div>
	</div>
</div>