<?
	$channel['theme']->setTitle('Danh sách website');
	$channel['theme']->setKeywords('');
	$channel['theme']->setDescription('Danh sách website đăng ký mới nhất'); 
	//$channel['theme']->setCanonical(route("post.list",$channel["domainPrimary"]));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
?>
<section>
	<div class="mainpanel">
		{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
		<div class="pageheader">
			<h1>{!! Theme::get('title') !!}</h1>
			<span><small>{!! Theme::get('description') !!}</small></span>
		</div>
		
		<div class="contentpanel section-content">
		<ol class="breadcrumb mb10 row-pad-5" itemscope="" itemtype="http://schema.org/BreadcrumbList">
			<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"
   itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a></li> 
			<li class="breadcrumb-item active" itemprop="itemListElement"><a href="{{route('channel.list',$channel['domainPrimary'])}}" itemprop="item"><span itemprop="name">Danh sách</span> <span class="glyphicon glyphicon-menu-down"></span></a></li>
		</ol>
			<div class="row row-pad-5">
				<div class="col-md-8">
					@if(count($getChannel)>0)
						<div class="row row-pad-5 filemanager channelList">
						@foreach($getChannel as $subChannel)
							<?
								if(!empty($subChannel->domainJoinPrimary->domainPrimary->domain)){
									$domainPrimary=$subChannel->domainJoinPrimary->domainPrimary->domain;
								}else if(!empty($subChannel->domainJoinPrimary->domain->domain)){

									$domainPrimary=$subChannel->domainJoinPrimary->domain->domain;
								}else{
									$domainPrimary=config('app.url');
								}
							?>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 content-list-post">
							<div class="thmb">
								<a class="thmb-prev" href="{{route('channel.home',$domainPrimary)}}" target="_blank" style="">
									<img src="@if(!empty($subChannel->channelAttributeLogo->media->media_name)){{config('app.link_media').$subChannel->channelAttributeLogo->media->media_path.'small/'.$subChannel->channelAttributeLogo->media->media_name}}@else {{asset('assets/img/logo-default.jpg')}} @endif" data-src="" class="img-responsive imgThumb lazy" alt="" title="" style="height:100px; max-width:100%;">
								</a>
								<h5 class="fm-title"><a class="title" href="{{route('channel.home',$domainPrimary)}}" target="_blank">{{$subChannel->channel_name}}</a></h5>
								<div class="attribute-3">
									<small>
										<a class="title" href="{{route('channel.home',$domainPrimary)}}" target="_blank">{{route('channel.home',$domainPrimary)}}</a>
									</small>
								</div>
								<div class="attribute-2">
									<small>
									<span class="time-update"><i class="glyphicon glyphicon-time"></i> {{WebService::time_request($subChannel->channel_updated_at)}}</span> 
									<span class="post-view text-danger">{{$subChannel->channel_view}} lượt xem</span>  
									</small> 
								</div>
								<div class="form-group attribute-1">
									<small class="text-muted"><span class="author"><i class="glyphicon glyphicon-user"></i> {{$subChannel->admin->name}}</span></small>
								</div>
							</div>
						</div>
						@endforeach
						</div>
					@endif
				{!!Theme::partial('pagination', array('paginator' => $getChannel))!!}
				</div>
				<div class="col-md-4">
					<div class="panel panel-default panel-alt widget-messaging">
						<div class="panel-heading">
							<div class="panel-title">{{$getChannelFree->total()}} website miễn phí</div>
						</div>
						<div class="panel-body">
							@if(count($getChannelFree)>0)
								<ul class="channelListFree">
								@foreach($getChannelFree as $subChannel)
									<?
									if(!empty($subChannel->domainJoinPrimary->domainPrimary->domain)){

										$domainPrimary=$subChannel->domainJoinPrimary->domainPrimary->domain;
									}else if(!empty($subChannel->domainJoinPrimary->domain->domain)){

										$domainPrimary=$subChannel->domainJoinPrimary->domain->domain;
									}else{
										$domainPrimary=config('app.url');
									}
									?>
									<li>
										@if(!empty($subChannel->channelAttributeLogo->media->media_name))<a class="pull-left" href="{{route('channel.home',$domainPrimary)}}" target="_blank">
										  <img class="media-object channel-thumb lazy" src="{{config('app.link_media').$subChannel->channelAttributeLogo->media->media_path.'xs/'.$subChannel->channelAttributeLogo->media->media_name}}" alt="">
										</a>@endif
										<h4 class="sender">
										  <a href="{{route('channel.home',$domainPrimary)}}" target="_blank">{{$subChannel->channel_name}}</a>
										</h4>
										<p><small><a href="{{route('channel.home',$domainPrimary)}}" target="_blank"><i class="glyphicon glyphicon-link"></i> {{$domainPrimary}}</a></small></p> 
										<p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> {{WebService::time_request($subChannel->channel_updated_at)}} - <span class="text-danger">{{$subChannel->channel_view}} lượt xem</span></small></p>
									</li>
								@endforeach
								</ul>
							@endif
						</div>
						<div id="loadChannel" class="text-center">
							<input id="curentPage-key" class="curentPage-key" type="hidden" value="{{$getChannelFree->currentPage()}}" autocomplete="off"/>
							<input id="totalPage-key" class="totalPage-key" type="hidden" value="{{$getChannelFree->total()}}" autocomplete="off"/>
							<input id="urlPage-key" class="urlPage-key" type="hidden" value="{{route('channel.list',$channel['domainPrimary'])}}" autocomplete="off"/>
							<input id="nextPage-key" class="nextPage-key" type="hidden" value="{{$getChannelFree->nextPageUrl()}}" autocomplete="off"/>
							<input id="perPage-key" class="perPage-key" type="hidden" value="{{$getChannelFree->perPage()}}" autocomplete="off"/>
							<input id="lastPage-key" class="lastPage-key" type="hidden" value="{{$getChannelFree->lastPage()}}" autocomplete="off"/>
							@if(strlen($getChannelFree->nextPageUrl())>0)
								<div class="panel-body text-center">
									<a href="{{$getChannelFree->nextPageUrl()}}" class="viewMore btn btn-xs"><i class="glyphicon glyphicon-hand-right"></i> Xem thêm...</a>
								</div>
							@endif
						</div>
						<?
							$dependencies = array(); 
							$channel['theme']->asset()->writeScript('moreChannel', '
								jQuery(document).ready(function(){
								"use strict"; 
								$("#loadChannel .viewMore").click(function(){
									var curentPage=parseInt($("#loadChannel #curentPage-key").val()); 
									var lastPage=parseInt($("#loadChannel #lastPage-key").val()); 
									var pageUrl=$("#loadChannel #urlPage-key").val(); 
									var page_int=curentPage+1;
									if(page_int<=lastPage){
										$("#loadChannel .viewMore").css("position","relative"); 
										$.ajax({
											type: "GET",
											url: pageUrl+"?page="+page_int,
											dataType: "html",
											contentType: "text/html",
											beforeSend: function() {
												$("#loadChannel .viewMore").append("<div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div>"); 
											},
											success: function(data) {
												$("#loadChannel #curentPage-key").val(page_int); 
												$(data).find(".channelListFree").ready(function() {
													var content_ajax = $(data).find(".channelListFree").html();
													$(".channelListFree").append(content_ajax); 
													$("#loadChannel .viewMore #preloaderInBox").remove(); 
												});
											}
										});
									}else{
										$("#loadChannel .viewMore").addClass("hidden");
									}
									return false; 
								}); 
							});
							', $dependencies);
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('loadLazy','
		$(function() {
			$(".lazy").lazy();
		}); 
	', $dependencies);
?>