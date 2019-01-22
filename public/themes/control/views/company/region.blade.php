<?
	$channel['theme']->setTitle($channel['info']->channel_name.' Công ty '.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$region->country);
	$channel['theme']->setKeywords($channel['info']->channel_name.' Công ty '.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$region->country);
	$channel['theme']->setDescription($channel['info']->channel_name.' Công ty '.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$region->country); 
	if(!empty($channel['info']->channelAttributeBanner[0]->media->media_name)){$channel['theme']->setImage(config('app.link_media').$channel['info']->channelAttributeBanner[0]->media->media_path.'thumb/'.$channel['info']->channelAttributeBanner[0]->media->media_name);} 
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel section-content">
		<div class="form-group text-center">
			<button type="button" class="btn btn-lg btn-primary" id="btnChannelCreate"><i class="glyphicon glyphicon-plus"></i> Tạo website</button>
		</div>
		<?
			$dependencies = array(); 
			$channel['theme']->asset()->writeScript('customCreate',' 
				$("#btnChannelCreate").click(function(){
					window.location.href ="'.route('channel.add',$channel["domain"]->domain).'";
				});
			', $dependencies);
		?>
		<?
			$getAllRegion=\App\Model\Regions::get(); 
			$getAllSubRegion=\App\Model\Subregions::where('region_id','=',$region->id)->get(); 
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
			<li class="breadcrumb-item" itemprop="itemListElement"><i class="flag flag-16 flag-{{mb_strtolower($region->iso)}}"></i> <a href="#" itemprop="item" class="" data-toggle="modal" data-target="#modaRegion"><span itemprop="name">{!!$region->country!!}</span> <span class="glyphicon glyphicon-menu-down"></span></a></li>
			<li class="breadcrumb-item active" itemprop="itemListElement"><a href="#" itemprop="item" class="" data-toggle="modal" data-target="#modalSubRegion"><span itemprop="name">Thành phố</span> <span class="glyphicon glyphicon-menu-down"></span></a></li>
		</ol>
		@if(count($companyList)>0)
		<div class="listItem">
			<div class="panel panel-default row-pad-5">
				<div class="panel-body results-list">
				@foreach($companyList->chunk(1) as $chunk)
					{!!Theme::partial('listCompany', array('chunk' => $chunk))!!}
				@endforeach
				</div>
			</div>
		</div>
		<div id="load_item_page" class="text-center">
			<input id="curentPage" class="curentPage" type="hidden" value="{{$companyList->currentPage()}}" autocomplete="off"/>
			<input id="totalPage" class="totalPage" type="hidden" value="{{$companyList->total()}}" autocomplete="off"/>
			<input id="urlPage" class="urlPage" type="hidden" value="{{Request::url()}}" autocomplete="off"/>
			<input id="nextPage" class="nextPage" type="hidden" value="{{$companyList->nextPageUrl()}}" autocomplete="off"/>
			<input id="perPage" class="perPage" type="hidden" value="{{$companyList->perPage()}}" autocomplete="off"/>
			<input id="lastPage" class="lastPage" type="hidden" value="{{$companyList->lastPage()}}" autocomplete="off"/>
			@if(strlen($companyList->nextPageUrl())>0)
				<div class="text-center">
					<div class="click-more">
						<button class="btn btn-success btn-xs" id="loading-page"><i class="fa fa-spinner fa-spin"></i> Loading</button> 
						<a href="{{$companyList->nextPageUrl()}}"><i class="glyphicon glyphicon-hand-right viewMore"></i> Xem thêm...</a>
					</div>
				</div>
			@endif
		</div>
	@else 
		<div class="alert alert-info">
			<strong>{!!$channel['info']->channel_name.' Công ty '.((!empty($getField->name)) ? ' '.$getField->name : '').' '.$region->country!!}</strong> chưa có thông tin nào! 
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