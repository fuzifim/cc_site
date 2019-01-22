<?
	Theme::setTitle(html_entity_decode($feed->title));
	//Theme::setKeywords($key);
	Theme::setDescription(WebService::limit_string(strip_tags(html_entity_decode($feed->description),""), $limit = 200)); 
	Theme::setCanonical('https://feed-'.$feed->id.'.'.config('app.url'));
	if(!empty($feed->image)){
		Theme::setImage($feed->image);
	}
?>
{!!Theme::asset()->add('photoswipe', 'assets/library/PhotoSwipe/dist/photoswipe.css', array('core-style'))!!}
{!!Theme::asset()->add('photoswipeSkin', 'assets/library/PhotoSwipe/dist/default-skin/default-skin.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->add('photoswipeJs', 'assets/library/PhotoSwipe/dist/photoswipe.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->add('photoswipeJsdefault', 'assets/library/PhotoSwipe/dist/photoswipe-ui-default.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('swiper.min', 'library/swiper/js/swiper.min.js', array('core-script'))!!}

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
{!!Theme::partial('formSearch', array('region' => $channel['region']))!!}
	<div class="contentpanel">
		<div class="row-pad-5 section-content postView">
			<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" itemscope itemtype="http://schema.org/Article">
				@if(!empty($feed->image))
					<div class="swiper-container postGallery mb5 " id="postGallery" style="max-height:520px; overflow:hidden;padding:5px;background:#fff; ">
						<!-- Wrapper for slides -->
						  <div class="swiper-wrapper my-gallery" itemscope itemtype="http://schema.org/ImageGallery">
							<?php
								$width='';
								$height='';
								list($width, $height, $type, $attr) = getimagesize('http:'.$feed->image); 
							?>
							<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" class="swiper-slide">
									<a href="{{$feed->image}}" itemprop="contentUrl" data-size="{{$width}}x{{$height}}">
									<img itemprop="thumbnail" class="img-responsive lazy" alt="{{$feed->title}}" src="{{$feed->image}}" url-lg="{{$feed->image}}"/>
									</a>
								</figure>
						  </div>
					</div>
				@endif
				@if(!empty($feed->category))
				<ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
					<li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemscope itemtype="http://schema.org/Thing"  itemprop="item"><span itemprop="name">{!!$feed->category!!}</span></span></li> 
				</ol> 
				@endif
				<div class="panel panel-default">
					<div class="panel-body content-show">
						<div class="mb5">
							<h1 class="panel-title"><strong itemprop="name"><a href="https://feed-{{$feed->id}}.{{config('app.url')}}" class="siteLink" target="_blank" data-url='{{json_encode(route("go.to.url",array(config("app.url"),urlencode($feed->link))))}}' >{!!$feed->title!!}</a></strong></h1>
						</div>
						<div class="attribute-2">
							<small><span class="post-view text-danger">{{$feed->view}} lượt xem</span></small> 
							<span class="time-update"><i class="glyphicon glyphicon-time"></i> <small datetime="{!!Site::Date($feed->updated_at)!!}" itemprop="datePublished">{!!WebService::time_request($feed->updated_at)!!}</small></span> 
						</div>
						<div class="timeline-btns attribute-2">
							<a href="" class="tooltips btnShare text-muted"  data-title="{!!$feed->title!!}" data-image="" data-url="https://feed-{{$feed->id}}.{{config('app.url')}}" data-toggle="tooltip" title="" data-original-title="Share"><i class="glyphicon glyphicon-share"></i> Chia sẻ</a>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="btn-group btn-group-justified">
					<a class="btn btn-primary siteLink" href="javascript:void(0);" data-url='{{json_encode("https://www.facebook.com/sharer/sharer.php?u=https://feed-".$feed->id.".".config("app.url"))}}'><span class="fa fa-facebook"></span> Share on Facebook</a> 
					<a class="btn btn-info siteLink" href="javascript:void(0);" data-url='{{json_encode("https://twitter.com/share?url=https://feed-".$feed->id.".".config("app.url"))}}'><span class="fa fa-twitter"></span> Share on Twitter</a>
					</div>
				</div>
				<div class="form-group">
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
				<div class="form-group" itemprop="description">
					@if(!empty($feed->description))
					<div class="panel panel-default">
						<div id="postDescription" class="panel-body postDescription postGallery">
							@if(!empty($feed->description))
								<div class="form-group">
								<strong>{!!WebService::addNofollow(html_entity_decode($feed->description),$channel['domainPrimary'],true)!!}</strong>
								</div>
							@endif
							@if(!empty($feed->host) && !empty($feed->link))
								<?
									$domainFeed=$channel['_parser']->parseUrl($feed->host); 
								?>
								Xem tiếp tại: <a href="http://{{$domainFeed->host->registerableDomain}}.{{config('app.url')}}" class="btn btn-xs btn-primary siteLink" target="_blank" data-url='{{json_encode(route("go.to.url",array(config("app.url"),urlencode($feed->link))))}}' ><i class="glyphicon glyphicon-hand-right"></i> {{$domainFeed->host->registerableDomain}}</a> 
							@endif
						</div>
					</div>
					@endif
					@if(count($feedSearch)>0)
					<div class="panel panel-default form-group">
						<div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> Feed liên quan</div>
						<div class="list-group">
							@foreach($feedSearch as $postRelate)
								<?
									$domainFeed=$channel['_parser']->parseUrl($postRelate->host); 
								?>
								@if($postRelate->id!=$feed->id)
									@if(!empty($postRelate->image))
										<div class="list-group-item form-group">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
												<a class="image" href="https://feed-{{$postRelate->id}}.{{config('app.url')}}">
													<img src="{{$postRelate->image}}" class="img-responsive img-thumbnail lazy" alt="{!!$postRelate->title!!}" title="" >
												</a>
											</div>
											<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
												<h5 class="postTitle nomargin"><a class="blog-title" href="https://feed-{{$postRelate->id}}.{{config('app.url')}}">{!!$postRelate->title!!}</a></h5>
												<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($postRelate->updated_at)!!}</span></small> 
												<p class="hidden-xs">{!!str_limit($postRelate->description, $limit = 150, $end = '...')!!}</p> 
												<a href="http://{{$domainFeed->host->registerableDomain}}.{{config('app.url')}}" class="siteLink" target="_blank" data-url='{{json_encode(route("go.to.url",array(config("app.url"),urlencode($postRelate->link))))}}' ><i class="glyphicon glyphicon-hand-right"></i> {{$domainFeed->host->registerableDomain}}</a> 
											</div>
										</div>
									@else 
										<div class="list-group-item form-group">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<h5 class="postTitle"><a class="blog-title" href="https://feed-{{$postRelate->id}}.{{config('app.url')}}">{!!$postRelate->title!!}</a></h5>
												<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($postRelate->updated_at)!!}</span></small>
												<p class="hidden-xs">{!!str_limit($postRelate->description, $limit = 150, $end = '...')!!}</p>
												<a href="http://{{$domainFeed->host->registerableDomain}}.{{config('app.url')}}" class="siteLink" target="_blank" data-url='{{json_encode(route("go.to.url",array(config("app.url"),urlencode($postRelate->link))))}}' ><i class="glyphicon glyphicon-hand-right"></i> {{$domainFeed->host->registerableDomain}}</a> 
											</div>
										</div>
									@endif
								@endif
							@endforeach
						</div>
						<div class="panel-footer text-center">
							<a href="#" class="view-more-ads"><i class="glyphicon glyphicon-chevron-down"></i> Hiển thị thêm</a>
						</div>
					</div>
					@endif
					@if(count($postSearch)>0)
						<div class="mt5">
							<?
								$idPost=array(); 
								$i=0; 
							?>
							@foreach($postSearch as $item)
								<?
									array_push($idPost,$item->id); 
								?>
							@endforeach 
							<?
								$getPost = Cache::store('file')->remember('feed_getPost_search_'.$feed->id, 5, function() use($idPost)
								{
									return \App\Model\Posts::where('posts_status','=','active')->whereIn('id',$idPost)->limit(6)->get(); 
								});
							?> 
							@if(count($getPost)>0) 
								@foreach($getPost->chunk(3) as $chunk)
								{!!Theme::partial('listPostChannel', array('chunk' => $chunk))!!}
								@endforeach
							@endif
						</div>
					@endif
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				@if(count($newsSearch)>0)
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
				@endif
				<div class="form-group">
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
				@if(count($feedRelate)>0)
				<div class="panel panel-default form-group">
					<div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> Feed mới</div>
					<div class="list-group">
						@foreach($feedRelate as $postRelate)
							@if(!empty($postRelate->image))
								<div class="list-group-item form-group">
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
										<a class="image" href="https://feed-{{$postRelate->id}}.{{config('app.url')}}">
											<img src="{{$postRelate->image}}" class="img-responsive img-thumbnail lazy" alt="{!!$postRelate->title!!}" title="" >
										</a>
									</div>
									<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
										<h5 class="postTitle nomargin"><a class="title" href="https://feed-{{$postRelate->id}}.{{config('app.url')}}">{!!$postRelate->title!!}</a></h5>
										<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($postRelate->updated_at)!!}</span></small> <small><span class="text-danger">{{$postRelate->view}} lượt xem</span></small>
									</div>
								</div>
							@else 
								<div class="list-group-item form-group">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<h5 class="postTitle"><a class="title" href="https://feed-{{$postRelate->id}}.{{config('app.url')}}">{!!$postRelate->title!!}</a></h5>
										<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($postRelate->updated_at)!!}</span></small> <small><span class="text-danger">{{$postRelate->view}} lượt xem</span></small> 
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
	Theme::asset()->writeScript('custom', '
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