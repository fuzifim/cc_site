<?
	$key=''; 
	if(count($post->keywords)>0){
		foreach($post->keywords as $keyword){
			$key.=$keyword->getKeyword->keyword.', ';
		}
	}
	$channel['theme']->setTitle(html_entity_decode($post->posts_title));
	$channel['theme']->setKeywords($key);
	$channel['theme']->setDescription(WebService::limit_string(strip_tags(html_entity_decode($post->posts_description),""), $limit = 200)); 
	//Theme::setCanonical('https://post-'.$post->id.'.'.config('app.url'));
	if(count($post->gallery)>0){
		if($post->gallery[0]->media->media_storage=='youtube'){
			$channel['theme']->setImage('//img.youtube.com/vi/'.$post->gallery[0]->media->media_name.'/hqdefault.jpg'); 
			$channel['theme']->setVideo($post->gallery[0]->media->media_url); 
			$channel['theme']->setType('video'); 
			$channel['theme']->setUrl($post->gallery[0]->media->media_url); 
		}else if($post->gallery[0]->media->media_storage=='video'){
			$channel['theme']->setImage(config('app.link_media').$post->gallery[0]->media->media_path.'thumb/'.$post->gallery[0]->media->media_id_random.'.png');
		}else{
			if(!empty($post->gallery[0]->media->media_name)){
				$channel['theme']->setImage(config('app.link_media').$post->gallery[0]->media->media_path.'thumb/'.$post->gallery[0]->media->media_name);
			} 
			if(!empty($post->price->posts_attribute_value)){
				$channel['theme']->setType('product'); 
			}else{
				$channel['theme']->setType('article'); 
			}
			$channel['theme']->setUrl(route('channel.slug',array($channel['domainPrimary'],$post->getSlug->slug_value))); 
		}
	}
	if(!empty($post->options->posts_attribute_value)){
		$options=json_decode($post->options->posts_attribute_value); 
	}else{
		$options=null; 
	}
	Theme::asset()->add('photoswipe', 'assets/library/PhotoSwipe/dist/photoswipe.css', array('core-style'));
	Theme::asset()->add('photoswipeSkin', 'assets/library/PhotoSwipe/dist/default-skin/default-skin.css', array('core-style'));
	Theme::asset()->container('footer')->add('photoswipeJs', 'assets/library/PhotoSwipe/dist/photoswipe.min.js', array('core-script'));
	Theme::asset()->container('footer')->add('photoswipeJsdefault', 'assets/library/PhotoSwipe/dist/photoswipe-ui-default.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('swiper.min', 'library/swiper/js/swiper.min.js', array('core-script'));
	if($channel['security']==true){
		Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'));
	}
?>
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	@if(count($post->postsJoinField)>0)
		<ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
			<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"
   itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a></li> 
			@if(count($post->postsJoinField[0]->getField->parent)>0)
				@foreach($post->postsJoinField[0]->getField->parent as $parent) 
					@if(count($parent->parent)>0)
						@foreach($parent->parent as $subParent) 
							<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],Str::slug($subParent->SolrID)))}}"><span itemprop="name">{!!$subParent->name!!}</span></a></li>
						@endforeach
					@endif
					<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],Str::slug($parent->SolrID)))}}"><span itemprop="name">{!!$parent->name!!}</span></a></li>
				@endforeach
			@endif
			<li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],Str::slug($post->postsJoinField[0]->getField->SolrID)))}}"><span itemprop="name">{!!$post->postsJoinField[0]->getField->name!!}</span></a></li> 
		</ol> 
	@endif
	<div class="pageheader">
		<h1 id="postTitle" class="postTitle">{!!$post->posts_title!!}</h1>
	</div>
	<div class="contentpanel">
		<div class="row-pad-5 section-content postView">
			<div class="@if($options!=null && !empty($options->viewFullScreen) && $options->viewFullScreen=='checked') col-lg-12 col-md-12 col-sm-12 col-xs-12 @else col-lg-8 col-md-8 col-sm-12 col-xs-12 @endif" itemscope @if(!empty($post->price->posts_attribute_value)) itemtype="http://schema.org/Product" @else itemtype="http://schema.org/Article" @endif>
				@if(count($post->gallery)>0)
					<div class="swiper-container postGallery mb5 " id="postGallery" style="max-height:520px; overflow:hidden;padding:5px;background:#fff; ">
						<!-- Wrapper for slides -->
						  <div class="swiper-wrapper my-gallery" itemscope itemtype="http://schema.org/ImageGallery">
							<?php
								$i=0;
								foreach ($post->gallery as $image) {
								list($width, $height, $type, $attr) = getimagesize('http:'.config('app.link_media').$image->media->media_path.$image->media->media_name); 
								
							?>
							@if($image->media->media_type == "image/jpeg" || $image->media->media_type == "image/jpg" || $image->media->media_type == "image/png" || $image->media->media_type == "image/gif") 
								<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" class="swiper-slide @if($i==0) active @endif">
									<a href="{{config('app.link_media').$image->media->media_path.$image->media->media_name}}" itemprop="contentUrl" data-size="{{$width}}x{{$height}}">
									<img itemprop="thumbnail" class="img-responsive lazy" alt="{{$post->posts_title}}" src="{{config('app.link_media').$image->media->media_path.'thumb/'.$image->media->media_name}}" url-lg="{{config('app.link_media').$image->media->media_path.$image->media->media_name}}"/>
									</a>
								</figure>
								<?  $i++; ?>
							@elseif($image->media->media_storage == "youtube")
								<div class="swiper-slide @if($i==0) active @endif">
									<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="{{$image->media->media_url}}" frameborder="0" allowfullscreen></iframe></div>
								</div>
							@elseif($image->media->media_storage == "video")
								<div class="swiper-slide @if($i==0) active @endif">
									<a href="{{config('app.link_media').$image->media->media_path.$image->media->media_name}}" data-url="{{config('app.link_media').$image->media->media_path.$image->media->media_name}}" class="btnPlayVideo btnPlayVideoClick"><i class="glyphicon glyphicon-play"></i></a>
									<img itemprop="image" class="img-responsive btnPlayVideo lazy" alt="{{$post->posts_title}}" src="{{config('app.link_media').$image->media->media_path.'thumb/'.$image->media->media_id_random.'.png'}}" data-url="{{config('app.link_media').$image->media->media_path.$image->media->media_name}}"/>
								</div>
							@endif
							<?  $i++; ?>
							<?php }?>
						  </div>
						  @if(count($post->gallery)>=2)
						  <a class="left carousel-control carousel_control_left" href="#postGallery">
							<span class="fa fa-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						  </a>
						  <a class="right carousel-control carousel_control_right" href="#postGallery">
							<span class="fa fa-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						  </a>
						  @endif
					</div>
				@endif
				<div class="panel panel-default">
					<div class="panel-body content-show">
						<div class="mb5">
							<a href="{{route('channel.slug',array($channel['domainPrimary'],$post->getSlug->slug_value))}}" itemprop="url"><strong itemprop="name">{!!$post->posts_title!!}</strong></a>@if($channel['security']==true || (Auth::check() && Auth::user()->id==$post->author->user->id)) <a class="text-danger" href="{{route('post.edit',array($channel['domainPrimary'],$post->id))}}"><i class="glyphicon glyphicon-edit"></i> Sửa</a> @endif
						</div>
						<div class="attribute-2">
							<small><span class="post-view text-danger">{{$post->posts_view}} lượt xem</span></small> 
							<span class="time-update"><i class="glyphicon glyphicon-time"></i> <small datetime="{!!Site::Date($post->posts_updated_at)!!}" itemprop="datePublished">{!!WebService::time_request($post->posts_updated_at)!!}</small></span> 
							<small><span class="author">bởi <i class="glyphicon glyphicon-user"></i> <span itemprop="author">{{$post->author->user->name}}</span></span></small>
						</div>
						<div class="mb5 attribute-1">
							@if(!empty($post->quanlity->posts_attribute_value) && $post->quanlity->posts_attribute_value>0)
								<small><span class="text-success">{{$post->quanlity->posts_attribute_value}} Còn hàng</span></small>
							@endif
							 @if(!empty($post->price->posts_attribute_value))<span class="group-attribute pull-right"><span class="price" itemprop="priceCurrency" content="VND"><span itemprop="price"><strong>{!!Site::price($post->price->posts_attribute_value)!!}</span> @if(!empty($channel['info']->channel_currency))<sup>{{$channel['info']->channelCurrency->currency_name}}</sup>@elseif(!empty($channel['info']->joinAddress[0]->address->joinRegion->region->currency_name))<sup>{{$channel['info']->joinAddress[0]->address->joinRegion->region->currency_name}}</sup>@else<sup>VNĐ</sup>@endif</strong></span>@if($channel['info']->channel_parent_id!=0) <button type="button" class="btn btn-xs btn-danger itemBuyNow" data-id="{{$post->id}}"><i class="glyphicon glyphicon-check"></i> Mua ngay</button>@endif</span>@endif
						</div>
						<div class="timeline-btns attribute-2">
							<a href="" class="tooltips likeUp text-muted likeUp_{{$post->id}} @if(count($post->like)>0) @if(Auth::check())@foreach($post->like as $like) @if($like->user_id==Auth::user()->id) text-success @endif @endforeach @else @foreach($post->like as $like) @if($like->user_id==Request::ip()) text-success @endif @endforeach @endif @endif" data-id="{{$post->id}}" data-toggle="tooltip" title="" data-original-title="Thích"><i class="glyphicon glyphicon-thumbs-up"></i> <span class="countLike_{{$post->id}}">{{count($post->like)}}</span></a>
							<a href="" class="tooltips likeDown text-muted likeDown_{{$post->id}} @if(count($post->unLike)>0) @if(Auth::check())@foreach($post->unLike as $like) @if($like->user_id==Auth::user()->id) text-danger @endif @endforeach @else @foreach($post->unLike as $like) @if($like->user_id==Request::ip()) text-danger @endif @endforeach @endif @endif" data-id="{{$post->id}}" data-toggle="tooltip" title="" data-original-title="Không thích"><i class="glyphicon glyphicon-thumbs-down"></i> <span class="countLikeDown_{{$post->id}}">{{count($post->unlike)}}</span></a>
							<a href="" class="tooltips text-muted" data-toggle="tooltip" title="" data-original-title="Add Comment"><i class="glyphicon glyphicon-comment"></i> {{count($post->commentJoinPost)}}</a>
							<a href="" class="tooltips btnShare text-muted"  data-title="{!!$post->posts_title!!}" data-image="" data-url="{{route('channel.slug',array($channel['domainPrimary'],$post->getSlug->slug_value))}}" data-toggle="tooltip" title="" data-original-title="Share"><i class="glyphicon glyphicon-share"></i> Chia sẻ</a>
						</div>
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
				@if(!empty($post->posts_description))
				<div class="panel panel-default">
					<div id="postDescription" class="panel-body postDescription postGallery" itemprop="description">
						{!!WebService::addNofollow(html_entity_decode($post->posts_description),$channel['domainPrimary'],true)!!}
					</div>
				</div>
				@endif
				@if(count($post->gallery)>0)
					@foreach ($post->gallery as $file)
						@if($file->media->media_storage == "files")
							<div class="form-group">
								<li class="list-group-item">
									<a href="{{$file->media->media_url}}"><i class="glyphicon glyphicon-download-alt"></i> {{$file->media->media_name}}</a>
								</li>
							</div>
						@endif
					@endforeach
				@endif
				@if($channel['info']->channel_parent_id!=0)
					<div class="panel panel-info">
						<div class="panel-heading"><div class="panel-title"><i class="glyphicon glyphicon-info-sign"></i> <a href="{{route('channel.home',$channel['domainPrimary'])}}">{{$channel['info']->channel_name}}</a></div></div>
						<div class="panel-body">
							@if(isset($channel['info']->companyJoin->company))<h5 class="subtitle mb5"><a href="@if(!empty($company->joinChannelParent->channel->id) && !empty($channel['info']->channelParent->id) && $company->joinChannelParent->channel->id==$channel['info']->id){{route('company.view.slug',array($channel['domainParentPrimary'],$channel['info']->companyJoin->company->id.'-'.Str::slug($channel['info']->companyJoin->company->company_name)))}}@else{{route('company.view.slug',array(config('app.url'),$channel['info']->companyJoin->company->id.'-'.Str::slug($channel['info']->companyJoin->company->company_name)))}}@endif"><strong>{{$channel['info']->companyJoin->company->company_name}}</strong></a></h5>@endif
							@if(count($channel['info']->joinAddress)>0)
								@foreach($channel['info']->joinAddress as $joinAddress)
									<div class="mb5 addressItemGroup">
										<div class="addressItem">
											<i class="glyphicon glyphicon-map-marker"></i> {{$joinAddress->address->address}} 
											@if(!empty($joinAddress->address->joinWard->ward->id)) - {!!$joinAddress->address->joinWard->ward->ward_name!!}@endif
											@if(!empty($joinAddress->address->joinDistrict->district->id)) - {!!$joinAddress->address->joinDistrict->district->district_name!!}@endif
											@if(!empty($joinAddress->address->joinSubRegion->subregion->id)) - {!!$joinAddress->address->joinSubRegion->subregion->subregions_name!!}@endif
											@if(!empty($joinAddress->address->joinRegion->region->id)) - <i class="flag flag-{{mb_strtolower($joinAddress->address->joinRegion->region->iso)}}"></i> {!!$joinAddress->address->joinRegion->region->country!!}@endif
										</div>
									</div>
								@endforeach
							@else
								<div class="mb5 addressItemGroup">
									<div class="addressItem">
										<small><i class="glyphicon glyphicon-map-marker"></i> <span style="font-style:italic; ">Chưa cập nhật địa chỉ...</small>
									</div>
								</div>
							@endif
							@if(count($channel['info']->joinPhone)>0)
								<div class="mb5">
								@foreach($channel['info']->joinPhone as $joinPhone)
									<i class="glyphicon glyphicon-earphone"></i> <a href="tel:{{$joinPhone->phone->phone_number}}">{{$joinPhone->phone->phone_number}}</a> 
								@endforeach
								</div>
							@else
								<div class="mb5 phoneItemGroup">
									<div class="phoneItem">
										<i class="glyphicon glyphicon-earphone"></i> <span style="font-style:italic; ">Chưa cập nhật số điện thoại...</span>
									</div>
								</div>
							@endif
							@if(count($channel['info']->joinEmail)>0)
								<div class="mb5">
								@foreach($channel['info']->joinEmail as $joinEmail)
									<i class="glyphicon glyphicon-envelope"></i> <a href="mailto:{{$joinEmail->email->email_address}}">{{$joinEmail->email->email_address}}</a>
								@endforeach
								</div>
							@else
								<div class="mb5 emailItemGroup">
									<div class="emailItem">
										<i class="glyphicon glyphicon-map-marker"></i> <span style="font-style:italic; ">Chưa cập nhật Email...</span>
									</div>
								</div>
							@endif
							<div class="mb5">
								<i class="glyphicon glyphicon-globe"></i> Website: <a href="{{route('channel.home',$channel['domainPrimary'])}}">http://{!!$channel['domainPrimary']!!}</a>
							</div>
							@if($channel['security']==true)<div class="mb5"><a href="{{route('channel.contact',$channel['domainPrimary'])}}" class="text-danger"><i class="fa fa-pencil"></i> Chỉnh sửa</a></div>@endif
						</div>
					</div>
				@endif
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
				<div class="panel panel-default">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#panelComment"><i class="glyphicon glyphicon-comment"></i> {{count($post->commentJoinPost)}} Bình luận</a></li>
						@if(count($post->keywords)>0)<li><a data-toggle="tab" href="#keyword"><i class="glyphicon glyphicon-tag"></i> Từ khóa</a></li>@endif
					</ul>
					<div class="tab-content">
						<div id="panelComment" class="tab-pane fade active in">
							<div class="messageComment"></div>
							<div class="form-group">
								<textarea id="commentContent" class="form-control" name="commentContent" value="" placeholder="Nội dung bình luận..."></textarea>
							</div>
							<div class="form-group text-right">
								<button type="button" class="btn btn-sm btn-primary addComment"  parent-id="0" data-id="{{$post->id}}"><i class="glyphicon glyphicon-comment"></i> Gửi bình luận</button>
							</div>
							@if(count($post->commentJoinPost)>0)
							<div class="comments-container">
								<ul id="comments-list" class="comments-list">
									@foreach($post->commentJoinPost as $commentJoinPost)
										@if($commentJoinPost->comment->parent_id==0)
											@if($commentJoinPost->comment->children->count())
												<li itemprop="comment" itemscope itemtype="https://schema.org/UserComments">
													<div class="comment-main-level">
														<div class="comment-box">
															<div class="comment-head">
																@if($commentJoinPost->comment->attribute->from=='user')
																	<div class="comment-avatar"><img class="lazy" src="@if(!empty($commentJoinPost->comment->author->user->getAvata->media->media_name)){{config('app.link_media').$commentJoinPost->comment->author->user->getAvata->media->media_path.'xs/'.$commentJoinPost->comment->author->user->getAvata->media->media_name}}@else {{asset('assets/img/no-avata.png')}}@endif" alt=""></div>
																	<h6 class="comment-name" itemprop="creator" itemscope itemtype="https://schema.org/Person"><a href="#" itemprop="name">{{$commentJoinPost->comment->author->user->name}}</a><span itemprop="commentTime" datetime="{!!Site::Date($commentJoinPost->comment->created_at)!!}">{!!WebService::time_request($commentJoinPost->comment->created_at)!!}</span></h6>
																	<i class="glyphicon glyphicon-share-alt"></i>
																	<i class="glyphicon glyphicon-thumbs-up"></i>
																	@if($channel['security']==true || Auth::user()->id==$commentJoinPost->comment->attribute->attribute_value)
																		<i class="glyphicon glyphicon-trash"></i> 
																	@endif 
																@else
																	<div class="comment-avatar"><img class="lazy" src="{{asset('assets/img/no-avata.png')}}" alt=""></div>
																	<h6 class="comment-name" itemprop="creator" itemscope itemtype="https://schema.org/Person"><a href="#" itemprop="name">{{$commentJoinPost->comment->attribute->attribute_value}}</a><span itemprop="commentTime" datetime="{!!Site::Date($commentJoinPost->comment->created_at)!!}">{!!WebService::time_request($commentJoinPost->comment->created_at)!!}</span></h6>
																	<i class="glyphicon glyphicon-share-alt"></i>
																	<i class="glyphicon glyphicon-thumbs-up"></i>
																	@if($channel['security']==true || Request::ip()==$commentJoinPost->comment->attribute->attribute_value)
																		<i class="glyphicon glyphicon-trash"></i>
																	@endif 
																@endif
															</div>
															<div class="comment-content">
															{{$commentJoinPost->comment->content}}
															</div>
														</div>
														{!! Theme::partial('childComment',array('comments'=>$commentJoinPost->comment->children)) !!}
													</div>
												</li>
											@else
												<li itemprop="comment" itemscope itemtype="https://schema.org/UserComments">
													<div class="comment-main-level">
														<div class="comment-box">
															<div class="comment-head">
																@if($commentJoinPost->comment->attribute->from=='user')
																	<div class="comment-avatar"><img class="lazy" src="@if(!empty($commentJoinPost->comment->author->user->getAvata->media->media_name)){{config('app.link_media').$commentJoinPost->comment->author->user->getAvata->media->media_path.'xs/'.$commentJoinPost->comment->author->user->getAvata->media->media_name}}@else {{asset('assets/img/no-avata.png')}}@endif" alt="{{$commentJoinPost->comment->author->user->name}}"></div>
																	<h6 class="comment-name" itemprop="creator" itemscope itemtype="https://schema.org/Person"><a href="#" itemprop="name">{{$commentJoinPost->comment->author->user->name}}</a><span itemprop="commentTime" datetime="{!!Site::Date($commentJoinPost->comment->created_at)!!}">{!!WebService::time_request($commentJoinPost->comment->created_at)!!}</span></h6>
																	<i class="glyphicon glyphicon-share-alt"></i>
																	<i class="glyphicon glyphicon-thumbs-up"></i>
																	@if(Auth::check())
																		@if($channel['security']==true || Auth::user()->id==$commentJoinPost->comment->attribute->attribute_value)
																			<i class="glyphicon glyphicon-trash btnDelComment" data-id="{{$commentJoinPost->comment->id}}"></i> 
																		@endif 
																	@endif
																@else
																	<div class="comment-avatar"><img class="lazy" src="{{asset('assets/img/no-avata.png')}}" alt="{{$commentJoinPost->comment->attribute->attribute_value}}"></div>
																	<h6 class="comment-name" itemprop="creator" itemscope itemtype="https://schema.org/Person"><a href="#" itemprop="name">{{$commentJoinPost->comment->attribute->attribute_value}}</a><span itemprop="commentTime" datetime="{!!Site::Date($commentJoinPost->comment->created_at)!!}">{!!WebService::time_request($commentJoinPost->comment->created_at)!!}</span></h6>
																	<i class="glyphicon glyphicon-share-alt"></i>
																	<i class="glyphicon glyphicon-thumbs-up"></i>
																	@if($channel['security']==true || Request::ip()==$commentJoinPost->comment->attribute->attribute_value)
																		<i class="glyphicon glyphicon-trash btnDelComment" data-id="{{$commentJoinPost->comment->id}}"></i> 
																	@endif 
																@endif
															</div>
															<div class="comment-content">
															{{$commentJoinPost->comment->content}}
															</div>
														</div>
													</div>
												</li>
											@endif
										@endif
									@endforeach
								</ul>
							</div>
							@endif

						</div>
						@if(count($post->joinKeywords)>0)
						<div class="tab-pane fade" id="keyword">
							<h4 class="panel-title">Từ khóa tìm kiếm</h4>
							<p>Các từ khóa tìm kiếm liên quan đến {!!$post->posts_title!!}</p>
							<div class="addKeyword">
							@if(count($post->keywords)>0)
								{!!Theme::partial('keywordList', array('keywords' => $post->keywords))!!}
							@endif
							</div>
						</div>
					@endif
					</div>
				</div>
			</div>
			@if($options!=null && !empty($options->viewFullScreen) && $options->viewFullScreen=='checked')
			@else
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<div class="panel-title">Thông tin liên hệ</div>
					</div>
					<div class="panel-body">
						<?php
							if(!empty($post->contact)){
								$postContact=json_decode($post->contact->posts_attribute_value);
							} 
						?>
						<div itemprop="seller" itemscope="" itemtype="http://schema.org/Person" class="">
							@if($channel['info']->channel_parent_id==0)
								<p><i class="glyphicon glyphicon-user"></i> <strong itemprop="name" class="">
								@if(!empty($postContact->contactName)){{$postContact->contactName}}@else{{$post->author->user->name}}@endif</strong></p>
								<p><i class="glyphicon glyphicon-envelope"></i> @if(!empty($postContact->contactEmail)){{$postContact->contactEmail}}@else{{$post->author->user->email}}@endif</p>
								@if(!empty($postContact->contactAddress))<p><i class="glyphicon glyphicon-map-marker"></i> {{$postContact->contactAddress}}</p>@endif
								@if(!empty($postContact->contactPhone))
								<p><a class="btn btn-success btn-block" href="tel:{{$postContact->contactPhone}}"><i class="glyphicon glyphicon-earphone"></i> {{$postContact->contactPhone}}</a></p>
								@elseif(!empty($post->author->user->phone))
								<p><a class="btn btn-success btn-block" href="tel:{{$post->author->user->phone}}"><i class="glyphicon glyphicon-earphone"></i> {{$post->author->user->phone}}</a></p>
								@endif
							@else
								@if(isset($channel['info']->companyJoin->company))
									<p><i class="glyphicon glyphicon-user"></i> <strong itemprop="name" class="">{{$channel['info']->companyJoin->company->company_name}}</strong></p>
								@else
									<p><i class="glyphicon glyphicon-user"></i> <strong itemprop="name" class="">{{$channel['info']->channel_name}}</strong></p>
								@endif
								@if(count($channel['info']->joinPhone)>0)
									<div class="mb5">
									@foreach($channel['info']->joinPhone as $joinPhone)
										<i class="glyphicon glyphicon-earphone"></i> <a href="tel:{{$joinPhone->phone->phone_number}}">{{$joinPhone->phone->phone_number}}</a> 
									@endforeach
									</div>
								@else
									<div class="mb5 phoneItemGroup">
										<div class="phoneItem">
											<i class="glyphicon glyphicon-earphone"></i> <span style="font-style:italic; ">Chưa cập nhật số điện thoại...</span>
										</div>
									</div>
								@endif
								@if(count($channel['info']->joinEmail)>0)
									<div class="mb5">
									@foreach($channel['info']->joinEmail as $joinEmail)
										<i class="glyphicon glyphicon-envelope"></i> <a href="mailto:{{$joinEmail->email->email_address}}">{{$joinEmail->email->email_address}}</a>
									@endforeach
									</div>
								@else
									<div class="mb5 emailItemGroup">
										<div class="emailItem">
											<i class="glyphicon glyphicon-map-marker"></i> <span style="font-style:italic; ">Chưa cập nhật Email...</span>
										</div>
									</div>
								@endif
							@endif
						</div>
					</div>
				</div>
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
				@if(count($postsRelate)>0)
				<div class="panel panel-default form-group">
					<div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> Bài liên quan</div>
					<div class="panel-body">
						@foreach($postsRelate as $postRelate)
							@if(count($postRelate->gallery)>0 && !empty($postRelate->gallery[0]->media->media_url))
								<div class="row no-gutter">
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
										<a class="image" href="{{route('channel.slug',array($channel['domainPrimary'],$postRelate->getSlug->slug_value))}}">
											@if($postRelate->gallery[0]->media->media_storage=='youtube')
												<img src="//img.youtube.com/vi/{{$postRelate->gallery[0]->media->media_name}}/default.jpg" class="img-responsive img-thumbnail lazy" alt="{!!$postRelate->posts_title!!}" title="" >
											@elseif($postRelate->gallery[0]->media->media_storage=='video')
											<div class="groupThumb" style="position:relative;">
												<span class="btnPlayVideoClickSmall"><i class="glyphicon glyphicon-play"></i></span>
												<img itemprop="image" class="img-responsive imgThumb lazy" alt="{{$postRelate->posts_title}}" src="{{config('app.link_media').$postRelate->gallery[0]->media->media_path.'thumb/'.$postRelate->gallery[0]->media->media_id_random.'.png'}}"/>
											</div>
											@elseif($postRelate->gallery[0]->media->media_storage=='files')
											<img src="{!!asset('assets/img/file.jpg')!!}" class="img-responsive imgThumb lazy" alt="{!!$postRelate->posts_title!!}" title="" >
											@else
												<img src="{{config('app.link_media').$postRelate->gallery[0]->media->media_path.'xs/'.$postRelate->gallery[0]->media->media_name}}" class="img-responsive img-thumbnail" alt="" title="" >
											@endif
										</a>
									</div>
									<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
										<h5 class="postTitle"><a class="title" href="{{route('channel.slug',array($channel['domainPrimary'],$postRelate->getSlug->slug_value))}}">{!!$postRelate->posts_title!!}</a></h5>
										<small><span>{{$postRelate->posts_view}} lượt xem</span></small>
									</div>
								</div>
							@endif
						@endforeach
					</div>
					<div class="panel-footer text-center">
						<a href="#" class="view-more-ads"><i class="glyphicon glyphicon-chevron-down"></i> Hiển thị thêm</a>
					</div>
				</div>
				@endif
			</div>
			@endif
		</div>
	</div>
</div><!-- mainpanel -->
</section>
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
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom', '
		jQuery(document).ready(function(){
		"use strict"; 
		var swiper = new Swiper(".swiper-container", {
			spaceBetween: 30,
			centeredSlides: true,
			autoplay: {
				delay: 2500,
				disableOnInteraction: false,
			},
			navigation: {
				nextEl: ".carousel_control_right",
				prevEl: ".carousel_control_left",
			},
		});
		$(function() {
			$(".lazy").lazy();
		});
		$(".siteLink").click(function(){
			window.open(jQuery.parseJSON($(this).attr("data-url")),"_blank")
			return false; 
		});
		$(".btnPlayVideo").click(function(){
			$(".carousel").carousel("pause"); 
			$("#myModal .modal-header").empty(); 
			$("#myModal .modal-title").empty(); 
			$("#myModal .modal-body").empty(); 
			$("#myModal .modal-footer").empty(); 
			var videoUrl=$(this).attr("data-url"); 
			$("#myModal .modal-body").addClass("nopadding"); 
			$("#myModal .modal-body").append("<div align=\"center\" class=\"\"><video class=\"img-responsive\" controls autoplay><source src=\""+videoUrl+"\" type=\"video/mp4\">Your browser does not support the video tag.</video></div>"); 
			$("#myModal .modal-footer").append("<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Đóng</button>"); 
			$("#myModal").modal("show"); 
			return false; 
		}); 
		$(".btnDelComment").click(function(){
			var commentId=$(this).attr("data-id"); 
			var formData = new FormData();
			formData.append("commentId", commentId); 
			$.ajax({
				url: "'.route("channel.comment.del",$channel["domain"]->domain).'",
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				type: "post",
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				dataType:"json",
				success:function(result){ 
					if(result.success==true){
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						});
						location.reload(); 
					}else{
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-danger",
							sticky: false,
							time: ""
						});
					}
				},
				error: function(result) {
					jQuery.gritter.add({
						title: "Thông báo!",
						text: "Không thể xóa bình luận, vui lòng thử lại! ", 
						class_name: "growl-danger",
						sticky: false,
						time: ""
					}); 
				}
			});
		}); 
		$("#panelComment").on("click",".addComment",function() {
			//$(this).addClass("disabled"); 
			$("#panelComment").css("position","relative"); 
			$("#panelComment").append( "<div class=\"appendPreload\"><div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div></div>" );
			$(this).prop("disabled", true);
			var parentId=$(this).attr("parent-id"); 
			var formData = new FormData();
			formData.append("postId", '.$post->id.'); 
			formData.append("parentId", parentId); 
			formData.append("table", "posts"); 
			formData.append("commentContent", $("#panelComment textarea[name=commentContent]").val()); 
			$.ajax({
				url: "'.route("channel.comment.add",$channel["domain"]->domain).'",
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				type: "post",
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				dataType:"json",
				success:function(result){ 
					//console.log(result); 
					if(result.success==false){
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-danger",
							sticky: false,
							time: ""
						}); 
						$("#panelComment .addComment").prop("disabled", false); 
						$("#panelComment .appendPreload").remove(); 
					}else if(result.success==true){
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						});
						location.reload(); 
					}
				},
				error: function(result) {
					jQuery.gritter.add({
						title: "Thông báo!",
						text: "Không thể đăng bình luận, vui lòng thử lại! ", 
						class_name: "growl-danger",
						sticky: false,
						time: ""
					}); 
					$("#panelComment .addComment").prop("disabled", false); 
					$("#panelComment .appendPreload").remove(); 
				}
			});
		});
	});
	var initPhotoSwipeFromDOM = function(gallerySelector) {

		// parse slide data (url, title, size ...) from DOM elements 
		// (children of gallerySelector)
		var parseThumbnailElements = function(el) {
			var thumbElements = el.childNodes,
				numNodes = thumbElements.length,
				items = [],
				figureEl,
				linkEl,
				size,
				item;

			for(var i = 0; i < numNodes; i++) {

				figureEl = thumbElements[i]; // <figure> element
				// include only element nodes 
				if(figureEl.nodeType !== 1) {
					continue;
				}

				linkEl = figureEl.children[0]; // <a> element
				
				size = linkEl.getAttribute("data-size").split("x");
				// create slide object
				item = {
					src: linkEl.getAttribute("href"),
					w: parseInt(size[0], 10),
					h: parseInt(size[1], 10)
				};
				if(figureEl.children.length > 1) {
					// <figcaption> content
					item.title = figureEl.children[1].innerHTML; 
				}

				if(linkEl.children.length > 0) {
					// <img> thumbnail element, retrieving thumbnail url
					item.msrc = linkEl.children[0].getAttribute("src");
				} 

				item.el = figureEl; // save link to element for getThumbBoundsFn
				items.push(item);
			}

			return items;
		};

		// find nearest parent element
		var closest = function closest(el, fn) {
			return el && ( fn(el) ? el : closest(el.parentNode, fn) );
		};

		// triggers when user clicks on thumbnail
		var onThumbnailsClick = function(e) {
			e = e || window.event;
			e.preventDefault ? e.preventDefault() : e.returnValue = false;

			var eTarget = e.target || e.srcElement;

			// find root element of slide
			var clickedListItem = closest(eTarget, function(el) {
				return (el.tagName && el.tagName.toUpperCase() === "FIGURE");
			});

			if(!clickedListItem) {
				return;
			}

			// find index of clicked item by looping through all child nodes
			// alternatively, you may define index via data- attribute
			var clickedGallery = clickedListItem.parentNode,
				childNodes = clickedListItem.parentNode.childNodes,
				numChildNodes = childNodes.length,
				nodeIndex = 0,
				index;

			for (var i = 0; i < numChildNodes; i++) {
				if(childNodes[i].nodeType !== 1) { 
					continue; 
				}

				if(childNodes[i] === clickedListItem) {
					index = nodeIndex;
					break;
				}
				nodeIndex++;
			}



			if(index >= 0) {
				// open PhotoSwipe if valid index found
				openPhotoSwipe( index, clickedGallery );
			}
			return false;
		};

		// parse picture index and gallery index from URL (#&pid=1&gid=2)
		var photoswipeParseHash = function() {
			var hash = window.location.hash.substring(1),
			params = {};

			if(hash.length < 5) {
				return params;
			}

			var vars = hash.split("&");
			for (var i = 0; i < vars.length; i++) {
				if(!vars[i]) {
					continue;
				}
				var pair = vars[i].split("=");  
				if(pair.length < 2) {
					continue;
				}           
				params[pair[0]] = pair[1];
			}

			if(params.gid) {
				params.gid = parseInt(params.gid, 10);
			}

			return params;
		};

		var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
			var pswpElement = document.querySelectorAll(".pswp")[0],
				gallery,
				options,
				items;

			items = parseThumbnailElements(galleryElement);

			// define options (if needed)
			options = {

				// define gallery index (for URL)
				galleryUID: galleryElement.getAttribute("data-pswp-uid"),

				getThumbBoundsFn: function(index) {
					// See Options -> getThumbBoundsFn section of documentation for more info
					var thumbnail = items[index].el.getElementsByTagName("img")[0], // find thumbnail
						pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
						rect = thumbnail.getBoundingClientRect(); 

					return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
				}

			};

			// PhotoSwipe opened from URL
			if(fromURL) {
				if(options.galleryPIDs) {
					// parse real index when custom PIDs are used 
					// http://photoswipe.com/documentation/faq.html#custom-pid-in-url
					for(var j = 0; j < items.length; j++) {
						if(items[j].pid == index) {
							options.index = j;
							break;
						}
					}
				} else {
					// in URL indexes start from 1
					options.index = parseInt(index, 10) - 1;
				}
			} else {
				options.index = parseInt(index, 10);
			}

			// exit if index not found
			if( isNaN(options.index) ) {
				return;
			}

			if(disableAnimation) {
				options.showAnimationDuration = 0;
			}

			// Pass data to PhotoSwipe and initialize it
			gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
			gallery.init();
		};

		// loop through all gallery elements and bind events
		var galleryElements = document.querySelectorAll( gallerySelector );

		for(var i = 0, l = galleryElements.length; i < l; i++) {
			galleryElements[i].setAttribute("data-pswp-uid", i+1);
			galleryElements[i].onclick = onThumbnailsClick;
		}

		// Parse URL and open gallery if it contains #&pid=3&gid=1
		var hashData = photoswipeParseHash();
		if(hashData.pid && hashData.gid) {
			openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
		}
	};

	// execute above function
	initPhotoSwipeFromDOM(".my-gallery");
	', $dependencies);
?>