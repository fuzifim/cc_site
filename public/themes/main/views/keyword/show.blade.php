<?
	$setKeyword=[];
	if(!empty($keyword['keyword'])){
		Theme::setCanonical(route('keyword.show.id',array('k.cungcap.net',$keyword['_id'],str_slug(mb_substr($keyword['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))));
		Theme::setTitle($keyword['keyword']);
	}
	if(!empty($keyword['description'])){
		Theme::setDescription($keyword['description']);
	}
	if(!empty($keyword['image'])){
		Theme::setImage($keyword['image']);
	}
	Theme::setAmp(route('keyword.show.id',array($channel['domainPrimary'],$keyword['_id'],str_slug(mb_substr($keyword['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))).'?amp=true');
	Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	$imageShow=false;
	$siteShow=false;
	$videoShow=false;
	$showEmpty=false;
	if(!empty($keyword['site_relate']) && count($keyword['site_relate'])>0){
		$siteShow=true;
		$count=count($keyword['site_relate']);
		if($count>=5){
			$skipImage=3;
			$skipVideo=5;
		}else if($count==4){
			$skipImage=2;
			$skipVideo=4;
		}else if($count==3){
			$skipImage=1;
			$skipVideo=2;
		}else if($count==2){
			$skipImage=1;
			$skipVideo=2;
		}else if($count==1){
			$skipImage=1;
			$skipVideo=2;
		}
	}
	if(!empty($keyword['image_relate']) && count($keyword['image_relate'])>0){
		$imageShow=true;
	}
	if(!empty($keyword['video_relate']) && count($keyword['video_relate'])>0){
		$videoShow=true;
	}
	if(empty($keyword['site_relate']) && empty($keyword['image_relate']) && empty($keyword['video_relate'])){
		$showEmpty=true;
	}
	$ads='true';
    if($ads=='true' && config('app.env')!='local'){

        Theme::setAds('true');
    }
?>
<section>
	<div class="mainpanel">
		{!!Theme::partial('headerbar_domain', array('title' => 'Header'))!!}
		@if(!empty($keyword['parent']))
			@if(empty($keyword['parent_id']))
				<?php
				$parentKey = DB::connection('mongodb')->collection('mongo_keyword')
						->where('base_64', base64_encode($keyword['parent']))->first();
				DB::connection('mongodb')->collection('mongo_keyword')
						->where('_id',(string)$keyword['_id'])
						->update(
								[
										'parent_id'=>(string)$parentKey['_id']
								]
						);

				?>
				<ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
					<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.home',config('app.url'))}}"><i class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a></li>
					<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{!! route('keyword.show.id',array('k.cungcap.net',$parentKey['_id'],str_slug(mb_substr($parentKey['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}"><span itemprop="name">{!! $keyword['parent'] !!}</span></a></li>
				</ol>
			@else
				<ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
					<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.home',config('app.url'))}}"><i class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a></li>
					<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{!! route('keyword.show.id',array('k.cungcap.net',$keyword['parent_id'],str_slug(mb_substr($keyword['parent'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}"><span itemprop="name">{!! $keyword['parent'] !!}</span></a></li>
				</ol>
			@endif
		@endif
		<div class="pageheader form-group">
			<h1><strong>{!! $keyword['keyword'] !!}</strong></h1>
			<?php
			if ($keyword['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
				$updated_at= $keyword['updated_at']->toDateTime()->setTimezone(new \DateTimeZone(config('app.timezone')))->format('Y-m-d H:i:s');
			}else{
				$updated_at= $keyword['updated_at'];
			}
			?>
			<small>Updated at {!! $updated_at !!}</small> @if(!empty($keyword['view']))<small><strong>Views: {!! $keyword['view'] !!}</strong></small>@endif
		</div>
		<div class="container">
			<div class="row row-pad-5">
				<div class="col-md-12">
					@if($showEmpty==true)
						Từ khóa {!! $keyword['keyword'] !!} chưa có bất kỳ thông tin trang web, hình ảnh, video nào!
					@endif
					@if(count($postSearch))
						<div class="PostlistItem">
							{!!Theme::partial('listPostChannelSlider', array('postSearch' => $postSearch,'keyword'=>$keyword))!!}
						</div>
						@if($ads=='true' && config('app.env')!='local')
							<ins class="adsbygoogle"
								 style="display:block"
								 data-ad-client="ca-pub-6739685874678212"
								 data-ad-slot="7536384219"
								 data-ad-format="auto"></ins>
							<script>
								(adsbygoogle = window.adsbygoogle || []).push({});
							</script>
						@endif
					@endif
					<div class="form-group mt-2">
						<div class="row row-pad-5">
							<div class="col-md-6">
								<div class="alert alert-info p-2">
									<strong>Cung Cấp đến mọi người ⭐ ⭐ ⭐ ⭐ ⭐</strong>
									<p>Đăng tin lên Cung Cấp để cung cấp sản phẩm, dịch vụ kinh doanh đến mọi người hoàn toàn miễn phí! </p>
								</div>
								<a class="btn btn-success btn-block" href="https://soc.cungcap.net" target="_blank"><h4>Đăng tin miễn phí</h4></a>
							</div>
							<div class="col-md-6">
								<div class="embed-responsive embed-responsive-16by9">
									<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/kGaGrI8dkLI?&autoplay=1&mute=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
								</div>
							</div>
						</div>
					</div>
					@if($siteShow==true)
						{!!Theme::partial('keyword.listSite', array('keyword' => $keyword,'ads'=>$ads,'skipImage'=>$skipImage,'skipVideo'=>$skipVideo,'imageShow'=>$imageShow,'videoShow'=>$videoShow))!!}
					@else
						@if($imageShow==true)
							{!!Theme::partial('keyword.listImage', array('keyword' => $keyword))!!}
						@endif
						@if($videoShow==true)
							{!!Theme::partial('keyword.listVideo_slider', array('keyword' => $keyword,'from'=>0,'to'=>8))!!}
						@endif
					@endif
					@if(!empty($keyword['keyword_relate']) && count($keyword['keyword_relate'])>0)
						<div class="form-group">
							<p><strong>Keyword relate for {!! $keyword['keyword'] !!}</strong></p>
							@foreach($keyword['keyword_relate'] as $keywordRelate)
								<?php
								$keywordRe=DB::connection('mongodb')->collection('mongo_keyword')
										->where('_id', (string)$keywordRelate)->first();
								?>
							@if(empty($keywordRe['craw_next']))
								<span class="badge">{!! $keywordRe['keyword'] !!}</span>
							@else
								<span><a class="badge progress-bar-success" href="{!! route('keyword.show.id',array('k.cungcap.net',$keywordRe['_id'],str_slug(mb_substr($keywordRe['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}">{!! $keywordRe['keyword'] !!}</a></span>
							@endif

							@endforeach
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</section>

<div class="modal fade" id="ModalFacebook">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true" id="timeLeft">&times;</span>
				</button>
				<h4>{{ trans('base.like_and_share_to_see_content') }}</h4>
			</div>
			<div class="modal-body text-center">
				<p>{!! trans('base.or_click_sub_youtube',['content'=>$keyword['keyword']]) !!}</p>
				@if($ads=='true' && config('app.env')!='local')
					<div class="modal-footer text-center">
						<div class="container form-group">
							<ins class="adsbygoogle"
								 style="display:block"
								 data-ad-client="ca-pub-6739685874678212"
								 data-ad-slot="7536384219"
								 data-ad-format="auto"></ins>
							<script>
								setTimeout(function(){(adsbygoogle = window.adsbygoogle || []).push({})}, 1000);
							</script>
						</div>
					</div>
				@endif
				<div id="showVideo"></div>
			</div>
		</div>
	</div>
</div>
<?php
$channel['theme']->asset()->writeScript('customScript','
    jQuery(document).ready(function(){
        "use strict";
        $(window).on("load",function(){
            $("#ModalFacebook").modal("show");
        });
        var count = 100;
        setInterval(function(){
            document.getElementById("timeLeft").innerHTML = count;
            if (count == 0) {
                $("#ModalFacebook").modal("hide");
                document.getElementById("timeLeft").innerHTML = "&times;";
            }
            count--;
        },1000);
        $("#ModalFacebook").modal({backdrop: "static", keyboard: false});
    });
', []);
?>
<?php
$dependencies = array();
Theme::asset()->writeScript('loadLazy','
		$(".showImageLink").click(function(){
			$("#showImageLarge").attr("src",$(this).attr("data-image"));
			$("#showImageLarge").attr("title",$(this).attr("data-title"));
			$("#showImageLarge").attr("alt",$(this).attr("data-title"));
			$("#showImageLargeLink").attr("href",$(this).attr("data-url"));
			$("#showImageLargeLink").text($(this).attr("data-title"));
			return false;
		});
	', $dependencies);
?>
<?php
$dependencies = array();
Theme::asset()->writeScript('ScriptKeywordShow','
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
          allowPageScroll:"vertical",
          excludedElements: "label, button, input, select, textarea, .noSwipe"

        });
        var initPhotoSwipeFromDOM = function(gallerySelector) {

		var parseThumbnailElements = function(el) {
			var thumbElements = el.childNodes,
				numNodes = thumbElements.length,
				items = [],
				figureEl,
				linkEl,
				size,
				item;

			for(var i = 0; i < numNodes; i++) {

				figureEl = thumbElements[i];
				if(figureEl.nodeType !== 1) {
					continue;
				}

				linkEl = figureEl.children[0];

				size = linkEl.getAttribute("data-size").split("x");
				item = {
					src: linkEl.getAttribute("href"),
					w: parseInt(size[0], 10),
					h: parseInt(size[1], 10)
				};
				if(figureEl.children.length > 1) {
					item.title = figureEl.children[1].innerHTML;
				}

				if(linkEl.children.length > 0) {
					item.msrc = linkEl.children[0].getAttribute("src");
				}

				item.el = figureEl;
				items.push(item);
			}

			return items;
		};
		var closest = function closest(el, fn) {
			return el && ( fn(el) ? el : closest(el.parentNode, fn) );
		};
		var onThumbnailsClick = function(e) {
			e = e || window.event;
			e.preventDefault ? e.preventDefault() : e.returnValue = false;

			var eTarget = e.target || e.srcElement;
			var clickedListItem = closest(eTarget, function(el) {
				return (el.tagName && el.tagName.toUpperCase() === "FIGURE");
			});

			if(!clickedListItem) {
				return;
			}

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
				openPhotoSwipe( index, clickedGallery );
			}
			return false;
		};

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
			options = {
				galleryUID: galleryElement.getAttribute("data-pswp-uid"),

				getThumbBoundsFn: function(index) {
					var thumbnail = items[index].el.getElementsByTagName("img")[0],
						pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
						rect = thumbnail.getBoundingClientRect();

					return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
				}

			};
			if(fromURL) {
				if(options.galleryPIDs) {
					for(var j = 0; j < items.length; j++) {
						if(items[j].pid == index) {
							options.index = j;
							break;
						}
					}
				} else {
					options.index = parseInt(index, 10) - 1;
				}
			} else {
				options.index = parseInt(index, 10);
			}
			if( isNaN(options.index) ) {
				return;
			}

			if(disableAnimation) {
				options.showAnimationDuration = 0;
			}

			gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
			gallery.init();
		};
		var galleryElements = document.querySelectorAll( gallerySelector );

		for(var i = 0, l = galleryElements.length; i < l; i++) {
			galleryElements[i].setAttribute("data-pswp-uid", i+1);
			galleryElements[i].onclick = onThumbnailsClick;
		}
		var hashData = photoswipeParseHash();
		if(hashData.pid && hashData.gid) {
			openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
		}
	};

	initPhotoSwipeFromDOM(".my-gallery");
	', $dependencies);
?>
