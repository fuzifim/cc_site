<?
	Theme::setTitle($channel['info']->channel_name.((!empty($getField->name)) ? ' '.$getField->name : '').' tại '.$subregion->subregions_name);
	Theme::setKeywords($channel['info']->channel_name.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$subregion->subregions_name);
	Theme::setDescription($channel['info']->channel_name.' tại '.$subregion->subregions_name.', '.$region->country);
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
	<ol class="breadcrumb mb10" itemscope="" itemtype="http://schema.org/BreadcrumbList">
		<li class="breadcrumb-item" itemprop="itemListElement"><i class="flag flag-16 flag-{{mb_strtolower($region->iso)}}"></i> <a href="#" itemprop="item" class="" data-toggle="modal" data-target="#modalSubRegion"><span itemprop="name">{!!$subregion->subregions_name!!}</span> <span class="glyphicon glyphicon-menu-down"></span></a></li>
		<li class="breadcrumb-item active" itemprop="itemListElement"><a href="#" itemprop="item" class="" data-toggle="modal" data-target="#modalRegionDistrict"><span itemprop="name">Quận/ huyện</span> <span class="glyphicon glyphicon-menu-down"></span></a></li>
	</ol>
	<div class="pageheader">
		<h1>{!! Theme::get('title') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel section-content">
		<?
			$getAllSubRegion = Cache::store('file')->rememberForever('getAllSubRegion_region_'.$region->id, function() use($region)
			{
				return \App\Model\Subregions::where('region_id','=',$region->id)->get(); 
			}); 
			$getAllRegionDistrict = Cache::store('file')->rememberForever('getAllRegionDistrict_subregion_'.$subregion->id, function() use($subregion)
			{
				return \App\Model\Region_district::where('subregions_id','=',$subregion->id)->get(); 
			}); 
		?>
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
		<div id="modalRegionDistrict" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<div class="row">
							@foreach($getAllRegionDistrict as $district)
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<div class="form-group">
										<a href="{{route('channel.slug',array($channel['domain']->domain,str_replace('/VN/','',$district->SolrID)))}}" class="list-group-item"><i class="glyphicon glyphicon-map-marker"></i> {!!$district->district_name!!}</a>
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
		<div class="row-pad-5">
			<div class="col-md-9">
				@if(count($postListNew)>0)
				<div class="listItem">
					<?
						$i=0; 
					?>
					@foreach($postListNew->chunk(3) as $chunk)
						@if($i % 3 == 0)
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
						{!!Theme::partial('listPostChannel', array('chunk' => $chunk))!!}
					<?
						$i++; 
					?>
					@endforeach
				</div>
				<div id="loadChannel" class="text-center">
					<input id="curentPage-key" class="curentPage-key" type="hidden" value="{{$postListNew->currentPage()}}" autocomplete="off"/>
					<input id="totalPage-key" class="totalPage-key" type="hidden" value="{{$postListNew->total()}}" autocomplete="off"/>
					<input id="urlPage-key" class="urlPage-key" type="hidden" value="{{Request::url()}}" autocomplete="off"/>
					<input id="nextPage-key" class="nextPage-key" type="hidden" value="{{$postListNew->nextPageUrl()}}" autocomplete="off"/>
					<input id="perPage-key" class="perPage-key" type="hidden" value="{{$postListNew->perPage()}}" autocomplete="off"/>
					<input id="lastPage-key" class="lastPage-key" type="hidden" value="{{$postListNew->lastPage()}}" autocomplete="off"/>
					@if(strlen($postListNew->nextPageUrl())>0)
						<div class="panel-body text-center">
							<a href="{{$postListNew->nextPageUrl()}}" class="viewMore btn btn-xs btn-primary"><i class="glyphicon glyphicon-hand-right"></i> Xem thêm...</a>
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
						<strong>{!!$channel['info']->channel_name.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$subregion->subregions_name!!}</strong> chưa có thông tin nào! 
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
				@if(!empty($companyRelate) && count($companyRelate)>0)
				<div class="listItemCompany">
					<div class="results-list">
					@foreach($companyRelate->chunk(1) as $chunk)
						{!!Theme::partial('listCompany', array('chunk' => $chunk))!!}
					@endforeach
					</div>
				</div>
				@endif
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