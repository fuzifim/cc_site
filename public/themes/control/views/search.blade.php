<?
	$channel['theme']->setTitle('Tìm kiếm '.$keyword);
	//$channel['theme']->setKeywords('Tìm kiếm '.$keyword);
	$channel['theme']->setDescription('Kết quả tìm kiếm ('.$keyword.' - '.WebService::vn_str_filter($keyword).') trên '.$channel['info']->channel_name);
	Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.sparkline', 'js/jquery.sparkline.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('retina', 'js/retina.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'));
?>
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>Tìm kiếm "{!!$keyword!!}"</h1>
		<span><small>@if(count($getPosts)>0)Khoảng {{$getPosts->total()}}@elseif(count($getItems)>0)Khoảng {{$getItems->total()}} @endif kết quả cho từ khóa {!!$keyword!!}</small></span>
	</div>
	<div class="contentpanel" style="position:relative; ">
		<div id="preloaderInBox" style="display:none;">
			<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
		</div>
		@if(!empty($keyword))
			<ol class="breadcrumb mb5">
				<li class="breadcrumb-item"><a href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> Trang chủ</a></li> 
				<li class="breadcrumb-item"><a href="{{route('search.query',$channel['domainPrimary'])}}">Tìm kiếm</a></li> 
				<li class="breadcrumb-item active">{!!$keyword!!}</li> 
				<script type="application/ld+json">
					{
					"@context": "http://schema.org",
					"@type": "BreadcrumbList",
					"itemListElement": [
							{
							"@type": "ListItem",
							"position": 1,
							"item": {
							"@id": "{{route('channel.home',$channel['domainPrimary'])}}",
							"name": "Trang chủ"
							}
							},{
								"@type": "ListItem",
								"position": 2,
								"item": {
								"@id": "{{route('search.query',$channel['domainPrimary'])}}",
								"name": "Tìm kiếm"
								}
							}
						]
					}
				</script>
			</ol> 
		@endif
		<div class="panel panel-default">
			<div class="panel-body">
				<h2 class="subtitle mb5"><a href="{{route('channel.home',$channel['domainPrimary'])}}"><img src="@if(!empty($channel['info']->channelAttributeLogo->media->media_name)){{config('app.link_media').$channel['info']->channelAttributeLogo->media->media_path.'xs/'.$channel['info']->channelAttributeLogo->media->media_name}}@endif" alt="" style="max-height:18px;"> <strong>{!!$channel['info']->channel_name!!}</strong></a></h2> 
				{!!str_limit(strip_tags(html_entity_decode($channel['info']->channel_description),""), $limit = 250, $end='...')!!}
			</div>
		</div>
		@if($channel['info']->service_attribute_id==1 && $channel['totalPosts']>5)
			<div class="form-group">
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
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
		@if(count($getPosts)>0)
			<div class="panel panel-default">
                <div class="panel-body results-list">
					@foreach($getPosts as $post)
						<div class="row media">
							<div class="col-md-3 col-xs-12">
								<a href="{{route('channel.slug',array($channel['domain']->domain,$post->getSlug->slug_value))}}" class="">
									@if($post->gallery[0]->media->media_storage=='youtube')
										<img src="//img.youtube.com/vi/{{$post->gallery[0]->media->media_name}}/hqdefault.jpg" class="media-object" alt="" title="" >
									@elseif($post->gallery[0]->media->media_storage=='video')
										<div class="groupThumb" style="position:relative;">
											<span class="btnPlayVideoClick"><i class="glyphicon glyphicon-play"></i></span>
											<img itemprop="image" class="img-responsive imgThumb" alt="{{$post->posts_title}}" src="{{config('app.link_media').$post->gallery[0]->media->media_path.'xs/'.$post->gallery[0]->media->media_id_random.'.png'}}"/>
										</div>
									@elseif($post->gallery[0]->media->media_storage=='files')
										<img src="{!!asset('assets/img/file.jpg')!!}" class="media-object" alt="" title="" >
									@else
										<img src="{{config('app.link_media').$post->gallery[0]->media->media_path.'xs/'.$post->gallery[0]->media->media_name}}" class="img-responsive" alt="" title="" >
									@endif
								</a>
							</div>
							<div class="col-md-9 col-xs-12">
							  <h2 class="blog-title text-primary"><a href="{{route('channel.slug',array($channel['domain']->domain,$post->getSlug->slug_value))}}">{!!$post->posts_title!!}</a></h2>
							  @if(strlen($post->posts_description)>5)<p>{{WebService::limit_string(strip_tags(html_entity_decode($post->posts_description),""), $limit = 200)}}</p>@endif
							  <small class="text-muted"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($post->posts_updated_at)!!}</small> 
							  <small class="text-muted"><i class="glyphicon glyphicon-user"></i> {!!$post->author->user->name!!}</small> 
							  <small class="text-muted text-danger"><strong>{{$post->posts_view}} lượt xem</strong></small>
							</div>
						</div><!-- results-list -->
					@endforeach
                </div><!-- panel-body -->
				<div class="panel-footer">
					{!!$getPosts->render()!!}
				</div>
            </div>
		@elseif(count($getItems)>0)
			<div class="panel panel-default">
                <div class="panel-body results-list">
					@foreach($getItems as $item)
						@if($item->type=='post')
							<?
								$post=\App\Model\Posts::find($item->id); 
								$postJoinChannel=\App\Model\Posts_join_channel::where('posts_id','=',$post->id)->first(); 
								$getChannel=\App\Model\Channel::find($postJoinChannel->channel_id); 
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
							?>
							<div class="row media">
								<div class="col-md-3 col-xs-12">
									<a href="{{route('channel.slug',array($domainPrimary,$post->getSlug->slug_value))}}" class="">
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
								  <h2 class="blog-title text-primary"><a href="{{route('channel.slug',array($domainPrimary,$post->getSlug->slug_value))}}">{!!$post->posts_title!!}</a></h2>
								  @if(strlen($post->posts_description)>5)<p>{{WebService::limit_string(strip_tags(html_entity_decode($post->posts_description),""), $limit = 200)}}</p>@endif
								  <small class="text-muted"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($post->posts_updated_at)!!}</small><br>
								  <small class="text-muted"><i class="glyphicon glyphicon-user"></i> {!!$post->author->user->name!!}</small><br>
								  <small class="text-muted text-danger"><strong>{{$post->posts_view}} lượt xem</strong></small>
								</div>
							</div><!-- results-list -->
						@elseif($item->type=='channel')
							<?
								$getChannel=\App\Model\Channel::find($item->id); 
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
							?>
							<div class="row media">
								<div class="col-md-12">
									<h2 class="blog-title"><a href="{{route('channel.home',$domainPrimary)}}">{!!$getChannel->channel_name!!}</a></h2>
								</div>
							</div>
						@elseif($item->type=='company')
							<?
								$getCompany=\App\Model\Company::find($item->id); 
							?>
							<div class="row media">
								<div class="col-md-12">
									<h2 class="blog-title"><a href="{{route('company.view.slug',array(config('app.url'),$getCompany->id.'-'.Str::slug($getCompany->company_name)))}}">{!!$getCompany->company_name!!}</a></h2>
								</div>
							</div>
						@endif
					@endforeach
                </div><!-- panel-body -->
				<div class="panel-footer">
					{!!$getItems->render()!!}
				</div>
            </div>
		@else
			<div class="alert alert-warning">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<strong>Thông báo!</strong> Không tìm thấy kết quả tìm kiếm nào.
			</div>
		@endif
	</div>
</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		$(function() {
			$(".lazy").lazy();
		});
	', $dependencies);
?>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('onload',' 
	', $dependencies);
?>