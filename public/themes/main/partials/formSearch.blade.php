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
								<a href="{{route('channel.slug',array(config('app.url'),str_replace('/VN/','',$subRegion->SolrID)))}}" class="list-group-item"><i class="glyphicon glyphicon-map-marker"></i> {!!$subRegion->subregions_name!!}</a>
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
<form itemprop="potentialAction" itemscope itemtype="http://schema.org/SearchAction" id="searchform" class="navbar-collapse collapse mb10 formSearchTop" action="{{route('search.query',config('app.url'))}}" method="get">
	<meta itemprop="target" content="{{route('search.query',array(config('app.url')))}}?v={v}"/>
	<input type="hidden" name="t" id="searchType" value="">
	<input type="hidden" name="i" id="searchId" value="">
	<div class="panel-heading">
		<div class="row">
			<div class="col-md-6 mb5">
				<input itemprop="query-input"  type="text" class="form-control" name="v" id="searchAll" placeholder="Search..." value="@if(Theme::get('search')){!!Theme::get('search')!!}@endif" />
			</div>
			<div class="col-md-2 mb5">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="modal" data-target="#modalSubRegion"><i class="flag flag-16 flag-{{mb_strtolower($region->iso)}}"></i> {!!$region->country!!} <span class="caret"></span></button>
			</div>
			<div class="col-md-3 mb5">
				<button data-toggle="dropdown" class="btn btn-default btn-block dropdown-toggle" type="button">Tất cả danh mục <span class="caret"></span></button>
			</div>
			<div class="col-md-1 box-item text-center">
				<button type="submit" class="btn btn-primary btn-block" style=""><i class="glyphicon glyphicon-search"></i> Tìm</button>
			</div>
		</div>
	</div>
</form>