<?
	$channel['theme']->setTitle($channel['info']->channel_name.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$region->country);
	$channel['theme']->setKeywords($channel['info']->channel_name.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$region->country);
	$channel['theme']->setDescription($channel['info']->channel_name.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$region->country);
	Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'));
?>
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
{!!Theme::partial('formSearch', array('region' => $channel['region']))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel section-content">
		<?
			$getAllRegion = Cache::store('file')->rememberForever('getAllRegion', function()
			{
				return \App\Model\Regions::get(); 
			}); 
			$getAllSubRegion = Cache::store('file')->rememberForever('getAllSubRegion_region_'.$region->id, function() use($region)
			{
				return \App\Model\Subregions::where('region_id','=',$region->id)->get(); 
			}); 
		?>
		<div id="modaRegion" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<div class="row">
							@foreach($getAllRegion as $regionGet)
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
									<div class="form-group">
										<a href="#" class="list-group-item"><i class="flag flag-{{mb_strtolower($regionGet->iso)}}"></i> {!!$regionGet->country!!}</a>
									</div>
								</div>
							@endforeach
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div id="modalSubRegion" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<div class="row">
							@foreach($getAllSubRegion as $subRegion)
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
									<div class="form-group">
										<a href="{{route('channel.slug',array($channel['domain']->domain,str_replace('/VN/','',$subRegion->SolrID)))}}" class="list-group-item"><i class="glyphicon glyphicon-map-marker"></i> {!!$subRegion->subregions_name!!}</a>
									</div>
								</div>
							@endforeach
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<ol class="breadcrumb mb10" itemscope="" itemtype="http://schema.org/BreadcrumbList">
			<li class="breadcrumb-item" itemprop="itemListElement"><i class="flag flag-16 flag-{{mb_strtolower($region->iso)}}"></i> <a href="#" itemprop="item" class="" data-toggle="modal" data-target="#modaRegion"><span itemprop="name">{!!$region->country!!}</span> <span class="glyphicon glyphicon-menu-down"></span></a></li>
			<li class="breadcrumb-item active" itemprop="itemListElement"><a href="#" itemprop="item" class="" data-toggle="modal" data-target="#modalSubRegion"><span itemprop="name">Thành phố</span> <span class="glyphicon glyphicon-menu-down"></span></a></li>
		</ol>
		@if(count($postListNew))
			@foreach($postListNew->chunk(3) as $chunk)
				{!!Theme::partial('listPostChannel', array('chunk' => $chunk))!!}
			@endforeach
		@endif
		@if(!empty($getField->name))
			<?
				$getPost=\App\Model\Posts::where('posts.posts_status','=','active')
					->join('posts_join_channel','posts_join_channel.posts_id','=','posts.id')
					->where('posts_join_channel.channel_id','=',$channel['info']->id)
					->join('posts_join_field','posts_join_field.post_id','=','posts.id')
					->where('posts_join_field.field_id',$getField->id)
					->join('posts_attribute','posts_attribute.posts_parent_id','=','posts.id')
					->where('posts_attribute.posts_attribute_type','=','gallery')
					->groupBy('posts.id')
					->orderBy('posts.posts_updated_at','desc')
					->select('posts.*')
					->paginate(18);
				$getChannel=\App\Model\Channel::where('channel.channel_status','=','active')
					->where('channel.channel_parent_id','=',$channel['info']->id)
					->join('channel_join_field','channel_join_field.channel_id','=','channel.id')
					->where('channel_join_field.field_id',$getField->id)
					->groupBy('channel.id')
					->orderBy('channel.channel_date_end','desc')
					->orderBy('channel.channel_updated_at','desc')
					->select('channel.*')
					->get(); 
			?>
			@if(count($getPost)>0 || count($getChannel)>0)
				<div class="panel panel-primary panel-program">
					<div class="panel-heading heading-program dropdown">
						<h3 class="panel-title categoryParentTitle"><a href="{{route('channel.slug',array($channel['domain']->domain,Str::slug($getField->SolrID)))}}"><i class="glyphicon glyphicon-book"></i> {!!$getField->name!!}</a></h3> 
						@if(count($getField->children)>0)
							<small>
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Xem thêm <span class="fa fa-angle-down"></span></a>
								<ul class="dropdown-menu nopadding">
									@foreach($getField->children as $catChild) 
										@if(count($catChild->postsJoinParent)>0) 
											<a class="list-group-item" href="{{route('channel.slug',array($channel['domain']->domain,Str::slug($catChild->SolrID)))}}"><i class="glyphicon glyphicon-folder-open"></i> {!!$catChild->name!!}</a>
										@endif 
										@if(count($catChild->children)>0) 
											@foreach($catChild->children as $subChild) 
												@if(count($subChild->postsJoinParent)>0) 
													<a class="list-group-item" href="{{route('channel.slug',array($channel['domain']->domain,Str::slug($subChild->SolrID)))}}"><i class="glyphicon glyphicon-folder-open"></i> {!!$subChild->name!!}</a>
												@endif
											@endforeach
										@endif
									@endforeach
								</ul>
							</small>
						@endif
					</div>
					<div class="panel-body">
						@if(count($getPost)>0)
							<div class="row">
								@if(count($getPost)==1 && !empty($getPost[0]->getSlug->slug_value))
									<?
										if(!empty($getPost[0]->options->posts_attribute_value)){
											$options=json_decode($getPost[0]->options->posts_attribute_value); 
										}else{
											$options=null; 
										}
									?>
									@if($options!=null && !empty($options->viewFullScreen) && $options->viewFullScreen=='checked')
									@else
									<div class="listItem">
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 postOne">
											<a class="image img-thumbnail" href="{{route('channel.slug',array($channel['domain']->domain,$getPost[0]->getSlug->slug_value))}}">
											@if($getPost[0]->gallery[0]->media->media_storage=='youtube')
												<img src="//img.youtube.com/vi/{{$getPost[0]->gallery[0]->media->media_name}}/hqdefault.jpg" class="img-responsive imgThumb" alt="{!!$getPost[0]->posts_title!!}" title="" >
											@elseif($getPost[0]->gallery[0]->media->media_storage=='video')
												<div class="groupThumb">
													<span class="btnPlayVideoClick"><i class="glyphicon glyphicon-play"></i></span>
													<img itemprop="image" class="img-responsive imgThumb" alt="{{$getPost[0]->posts_title}}" src="{{config('app.link_media').$getPost[0]->gallery[0]->media->media_path.'thumb/'.$getPost[0]->gallery[0]->media->media_id_random.'.png'}}"/>
												</div>
											@elseif($getPost[0]->gallery[0]->media->media_storage=='files')
												<img src="{!!asset('assets/img/file.jpg')!!}" class="img-responsive imgThumb" alt="{!!$getPost[0]->posts_title!!}" title="" >
											@else
												<img src="{{config('app.link_media').$getPost[0]->gallery[0]->media->media_path.'thumb/'.$getPost[0]->gallery[0]->media->media_name}}" class="img-responsive imgThumb" alt="{!!$getPost[0]->posts_title!!}" title="" >
											@endif
											</a>
											<div class="attribute-2 mb5">
												<small><span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($getPost[0]->posts_updated_at)!!}</span> 
												<span class="author"><i class="glyphicon glyphicon-user"></i> {!!$getPost[0]->author->user->name!!}</span></small> 
											</div>
											<div class="timeline-btns">
												<div class="pull-left">
													<a href="" class="tooltips likeUp text-muted likeUp_{{$getPost[0]->id}} @if(Auth::check())@foreach($getPost[0]->like as $like) @if($like->user_id==Auth::user()->id) text-success @endif @endforeach @endif" data-id="{{$getPost[0]->id}}" data-toggle="tooltip" title="" data-original-title="Thích"><i class="glyphicon glyphicon-thumbs-up"></i> <span class="countLike_{{$getPost[0]->id}}">{{count($getPost[0]->like)}}</span></a>
													<a href="" class="tooltips likeDown text-muted likeDown_{{$getPost[0]->id}} @if(Auth::check())@foreach($getPost[0]->unLike as $like) @if($like->user_id==Auth::user()->id) text-danger @endif @endforeach @endif" data-id="{{$getPost[0]->id}}" data-toggle="tooltip" title="" data-original-title="Không thích"><i class="glyphicon glyphicon-thumbs-down"></i> <span class="countLikeDown_{{$getPost[0]->id}}">{{count($getPost[0]->unlike)}}</span></a>
													<a href="" class="tooltips text-muted" data-toggle="tooltip" title="" data-original-title="Add Comment"><i class="glyphicon glyphicon-comment"></i></a>
													<a href="" class="tooltips btnShare text-muted"  data-title="{!!$getPost[0]->posts_title!!}" data-image="{{$getPost[0]->gallery[0]->media->media_url_thumb}}" data-url="{{route('channel.slug',array($channel['domain']->domain,$getPost[0]->getSlug->slug_value))}}" data-toggle="tooltip" title="" data-original-title="Share"><i class="glyphicon glyphicon-share"></i></a>
												</div>
												<div class="pull-right">
													<small class="text-muted text-danger"><strong>{{$getPost[0]->posts_view}} lượt xem</strong></small>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
											<h2 class="blog-title-large"><a class="title" href="{{route('channel.slug',array($channel['domain']->domain,$getPost[0]->getSlug->slug_value))}}">{!!$getPost[0]->posts_title!!}</a></h2>
											@if(!empty($getPost[0]->posts_description))
												<div class="form-group">
													<?
														$postContent=html_entity_decode($getPost[0]->posts_description); 
														$postContent=preg_replace('/(<p[^>]*>)(.*?)(<\/p>)/i', '$2<p>', $postContent);
														$postContent=WebService::limit_string(strip_tags($postContent,"<p><br>"), $limit = 500); 
														
													?>
													{!!$postContent!!} <a href="{{route('channel.slug',array($channel['domain']->domain,$getPost[0]->getSlug->slug_value))}}"><i class="glyphicon glyphicon-hand-right"></i> Xem thêm</a>
												</div>
											@endif
										</div>
									</div>
									@endif
								@else
									<div class="listItem">
									@foreach($getPost->chunk(3) as $chunk)
										<div class="row">
											@foreach($chunk as $post)
												{!!Theme::partial('listPost', array('post' => $post))!!}
											@endforeach
										</div>
									@endforeach
									</div>
									<div id="load_item_page" class="text-center">
										<input id="curentPage" class="curentPage" type="hidden" value="{{$getPost->currentPage()}}" autocomplete="off"/>
										<input id="totalPage" class="totalPage" type="hidden" value="{{$getPost->total()}}" autocomplete="off"/>
										<input id="urlPage" class="urlPage" type="hidden" value="{{Request::url()}}" autocomplete="off"/>
										<input id="nextPage" class="nextPage" type="hidden" value="{{$getPost->nextPageUrl()}}" autocomplete="off"/>
										<input id="perPage" class="perPage" type="hidden" value="{{$getPost->perPage()}}" autocomplete="off"/>
										<input id="lastPage" class="lastPage" type="hidden" value="{{$getPost->lastPage()}}" autocomplete="off"/>
										@if(strlen($getPost->nextPageUrl())>0)
											<div class="text-center">
												<div class="click-more">
													<button class="btn btn-success btn-xs" id="loading-page"><i class="fa fa-spinner fa-spin"></i> Loading</button> 
													<a href="{{$getPost->nextPageUrl()}}"><i class="glyphicon glyphicon-hand-right viewMore"></i> Xem thêm...</a>
												</div>
											</div>
										@endif
									</div>
								@endif
							</div>
						@endif
						@if(count($getChannel)>0)
							@foreach($getChannel->take(6)->chunk(3) as $chunk)
								<div class="row activity-list">
									@foreach($chunk as $subChannel)
										<?
											if($subChannel->domainJoinPrimary->domain->domain_primary!='default'){
												if(count($subChannel->domainAll)>0){
													foreach($subChannel->domainAll as $domain){
														if($domain->domain->domain_primary=='default'){
															$domainPrimary=$domain->domain->domain; 
														}
													}
												}else{
													$domainPrimary=$subChannel->domainJoinPrimary->domain->domain; 
												}
											}else{
												$domainPrimary=$subChannel->domainJoinPrimary->domain->domain; 
											}
										?>
										<div class="media act-media">
											<a class="pull-left" href="{{route('channel.home',$domainPrimary)}}">
											  <img class="media-object act-thumb" src="@if(!empty($subChannel->channelAttributeLogo->media->media_name)){{config('app.link_media').$subChannel->channelAttributeLogo->media->media_path.'xs/'.$subChannel->channelAttributeLogo->media->media_name}}@else{{asset('assets/img/logo-default.jpg')}}@endif" alt="">
											</a>
											<div class="media-body act-media-body">
											  <a href="{{route('channel.home',$domainPrimary)}}">{{$subChannel->channel_name}}</a>
											</div>
											<small>{{strip_tags(html_entity_decode($subChannel->channel_description),' ')}}</small> <br>
											<small><a href="{{route('channel.home',$domainPrimary)}}">{{$domainPrimary}}</a></small> <br>
											<small class="text-muted">Cập nhật: {{Site::date($subChannel->channel_updated_at)}} - <span class="text-danger">{{$subChannel->channel_view}} lượt xem</span></small>
										</div>
									@endforeach
								</div>
							@endforeach
						@endif
					</div>
				</div>
			@else 
				<div class="alert alert-info">
					<strong>{!!$channel['info']->channel_name.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$region->country!!}</strong> chưa có thông tin nào! 
				</div>
			@endif
		@elseif(count($postListNew)>0)
			<div class="listItem">
				<?
					$i=0; 
				?>
				@foreach($postListNew->chunk(3) as $chunk)
					{!!Theme::partial('listPostChannel', array('chunk' => $chunk))!!} 
				<?
					$i++; 
				?>
				@endforeach
			</div>
			<div id="load_item_page" class="text-center">
				<input id="curentPage" class="curentPage" type="hidden" value="{{$postListNew->currentPage()}}" autocomplete="off"/>
				<input id="totalPage" class="totalPage" type="hidden" value="{{$postListNew->total()}}" autocomplete="off"/>
				<input id="urlPage" class="urlPage" type="hidden" value="{{Request::url()}}" autocomplete="off"/>
				<input id="nextPage" class="nextPage" type="hidden" value="{{$postListNew->nextPageUrl()}}" autocomplete="off"/>
				<input id="perPage" class="perPage" type="hidden" value="{{$postListNew->perPage()}}" autocomplete="off"/>
				<input id="lastPage" class="lastPage" type="hidden" value="{{$postListNew->lastPage()}}" autocomplete="off"/>
				@if(strlen($postListNew->nextPageUrl())>0)
					<div class="text-center">
						<div class="click-more">
							<button class="btn btn-success btn-xs" id="loading-page"><i class="fa fa-spinner fa-spin"></i> Loading</button> 
							<a href="{{$postListNew->nextPageUrl()}}"><i class="glyphicon glyphicon-hand-right viewMore"></i> Xem thêm...</a>
						</div>
					</div>
				@endif
			</div>
		@else 
			<div class="alert alert-info">
				<strong>{!!$channel['info']->channel_name.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$region->country!!}</strong> chưa có thông tin nào! 
			</div>
		@endif
	</div>

</div><!-- mainpanel -->
</section>
@if($channel['security']==true)
<?

	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('customAdmin','
	', $dependencies);
?>
@endif