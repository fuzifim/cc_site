<aside id="left-panel">
	<nav>
		<ul>
			<?
				$getField=\App\Model\Fields::where('status','=',0)->where('parent_id',0)->orderBy('sort_order','desc')->get(); 
				$parame=Route::current()->parameters(); 
				$location=''; 
				if(!empty($parame['slug'])){
					$getSlug=explode('/',$parame['slug']); 
					if(!empty($getSlug[0])){
						$getRegion=\App\Model\Regions::where('id',704)->first(); 
						$getSubRegion=\App\Model\Subregions::where('SolrID','=','/'.$getRegion->iso.'/'.$getSlug[0])->first(); 
						if(!empty($getSubRegion->id)){
							if(!empty($getSlug[1])){
								$getRegionDistrict=\App\Model\Region_district::where('SolrID','=','/'.$getRegion->iso.'/'.$getSlug[0].'/'.$getSlug[1])->first(); 
								if(!empty($getRegionDistrict->id)){
									if(!empty($getSlug[2])){
										$getRegionWard=\App\Model\Region_ward::where('SolrID','=','/'.$getRegion->iso.'/'.$getSlug[0].'/'.$getSlug[1].'/'.$getSlug[2])->first(); 
										if(!empty($getRegionWard->id)){
											$location=str_replace('/'.$getRegion->iso.'/','',$getRegionWard->SolrID).'/'; 
										}else{
											$location=str_replace('/'.$getRegion->iso.'/','',$getRegionDistrict->SolrID).'/'; 
										}
									}else{
										$location=str_replace('/'.$getRegion->iso.'/','',$getRegionDistrict->SolrID).'/'; 
									}
								}else{
									$location=str_replace('/'.$getRegion->iso.'/','',$getSubRegion->SolrID).'/'; 
								}
							}else{
								$location=str_replace('/'.$getRegion->iso.'/','',$getSubRegion->SolrID).'/'; 
							}
						}else{
							$location=''; 
						}
					}
				}
			?>
			@foreach($getField as $field)
				@if($field->parent_id==0)
					@if($field->children->count())
						<li>
							<a href="javascript:void(0);"><i class="fa fa-lg fa-fw fa-folder"></i> <span class="menu-item-parent">{!!$field->name!!}</span></a>
							{!! Theme::partial('childItems',array('menus'=>$field->children,'menuParent'=>$field,'location'=>$location)) !!}
							
						</li>
					@else 
						<li class="">
							<a href="{{route('channel.slug',array($channel['domainPrimary'],$location.Str::slug($field->SolrID)))}}" title=""><i class="fa fa-lg fa-fw fa-folder"></i> <span class="menu-item-parent">{!!$field->name!!}</span></a>
						</li>
					@endif
				@endif
			@endforeach
		</ul>
	</nav>
	<span class="minifyme" data-action="minifyMenu"> 
		<i class="fa fa-arrow-circle-left hit"></i> 
	</span>

</aside>