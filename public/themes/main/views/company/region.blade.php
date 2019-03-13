<?
	$channel['theme']->setTitle($channel['info']->channel_name.' Công ty '.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$channel['region']->country);
	$channel['theme']->setKeywords($channel['info']->channel_name.' Công ty '.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$channel['region']->country);
	$channel['theme']->setDescription($channel['info']->channel_name.' thông tin công ty tại '.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$channel['region']->country); 
	if(!empty($channel['info']->channelAttributeBanner[0]->media->media_name)){$channel['theme']->setImage(config('app.link_media').$channel['info']->channelAttributeBanner[0]->media->media_path.'thumb/'.$channel['info']->channelAttributeBanner[0]->media->media_name);}

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
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel section-content">
		<? 
			$region=$channel['region']; 
			$getAllRegion = Cache::rememberForever('getAllRegion', function()
			{
				return \App\Model\Regions::get(); 
			}); 
			$getAllSubRegion = Cache::rememberForever('getAllSubRegion_region_'.$region->id, function() use($region)
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
		<ol class="breadcrumb mb10 row-pad-5" itemscope="" itemtype="http://schema.org/BreadcrumbList">
			<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"
   itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="flag flag-{{mb_strtolower($channel['region']->iso)}}"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a></li> 
			<li class="breadcrumb-item active" itemprop="itemListElement"><a itemprop="item" href="{{route('company.list',$channel['domainPrimary'])}}"><span itemprop="name">Doanh nghiệp</span></a></li>
			<li class="breadcrumb-item active"><a href="#"data-toggle="modal" data-target="#modalSubRegion">Thành phố <span class="glyphicon glyphicon-menu-down"></span></a></li>
		</ol>
		<div class="row-pad-5">
			<div class="col-md-9">
				@if(count($companyList)>0)
					<div class="listItem">
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
						<div class="panel panel-default row-pad-5">
							<div class="list-group results-list">
							@foreach($companyList->chunk(1) as $chunk)
								{!!Theme::partial('listCompany', array('chunk' => $chunk))!!}
							@endforeach
							</div>
						</div>
					</div>
					<div id="loadChannel" class="text-center">
						<input id="curentPage-key" class="curentPage-key" type="hidden" value="{{$companyList->currentPage()}}" autocomplete="off"/>
						<input id="totalPage-key" class="totalPage-key" type="hidden" value="{{$companyList->total()}}" autocomplete="off"/>
						<input id="urlPage-key" class="urlPage-key" type="hidden" value="{{Request::url()}}" autocomplete="off"/>
						<input id="nextPage-key" class="nextPage-key" type="hidden" value="{{$companyList->nextPageUrl()}}" autocomplete="off"/>
						<input id="perPage-key" class="perPage-key" type="hidden" value="{{$companyList->perPage()}}" autocomplete="off"/>
						<input id="lastPage-key" class="lastPage-key" type="hidden" value="{{$companyList->lastPage()}}" autocomplete="off"/>
						@if(strlen($companyList->nextPageUrl())>0)
							<div class="panel-body text-center">
								<a href="{{$companyList->nextPageUrl()}}" class="viewMore btn btn-xs btn-primary"><i class="glyphicon glyphicon-hand-right"></i> Xem thêm...</a>
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
											$(data).find(".listItem").ready(function() {
												var content_ajax = $(data).find(".listItem").html();
												$(".listItem").append(content_ajax); 
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
				@else 
					<div class="alert alert-info">
						<strong>{!!$channel['info']->channel_name.' Công ty '.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$channel['region']->country!!}</strong> chưa có thông tin nào! 
					</div>

				@endif
			</div>
			<div class="col-md-3">
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
				<div class="panel panel-default">
					<div class="panel-heading"><div class="panel-title">Site mới cập nhật</div></div>
					@foreach($getSite as $site)
						<li class="list-group-item"><a href="{{route('sitelink.show',array($channel['domainPrimary'],$site->domain))}}">{{$site->domain}}</a> <small><span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($site->updated_at)!!}</span> - {{$site->view}} xem</small>@if(!empty($site->title->attribute_value))<p>{{str_limit($site->title->attribute_value, 100)}}</p>@if(!empty($site->rank))<p><span class="label label-primary"><i class="glyphicon glyphicon-globe"></i> {{Site::price($site->rank)}}</span> @if(!empty($site->country_code)&&!empty($site->rank_country))
								<?
									$getRegion=\App\Model\Regions::where('iso','=',$site->country_code)->first(); 
								?>
								@if(!empty($getRegion->id))
									<span class="label label-primary"><i class="flag flag-{{mb_strtolower($getRegion->iso)}}"></i> {{$getRegion->country}} {{Site::price($site->rank_country)}}</span>
								@endif
							@endif</p>@endif @endif</li>
					@endforeach
				</div>
			</div>
		</div>
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