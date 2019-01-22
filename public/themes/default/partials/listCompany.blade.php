<div class="list-group-item">
	@foreach($chunk as $company)
		@if(!empty($company->joinChannelParent->channel->id) && $company->joinChannelParent->channel->id==$channel['info']->id)
		<h4><a href="{{route('company.view.slug',array($channel['domainPrimary'],$company->id.'-'.Str::slug($company->company_name)))}}">{!!$company->company_name!!}</a></h4>
		@else 
			<h4><a href="{{route('company.view.slug',array(config('app.url'),$company->id.'-'.Str::slug($company->company_name)))}}">{!!$company->company_name!!}</a></h4>
		@endif
		@if(count($company->joinAddress)>0)
			@foreach($company->joinAddress as $joinAddress)
			<p><i class="glyphicon glyphicon-map-marker"></i> 
				{{$joinAddress->address->address}} 
						@if(!empty($joinAddress->address->joinWard->ward->id)) - <a href="{{route('channel.slug',array($channel['domain']->domain,str_replace('/VN/','',$joinAddress->address->joinWard->ward->SolrID)))}}">{!!$joinAddress->address->joinWard->ward->ward_name!!}</a>@endif
						@if(!empty($joinAddress->address->joinDistrict->district->id)) - <a href="{{route('channel.slug',array($channel['domain']->domain,str_replace('/VN/','',$joinAddress->address->joinDistrict->district->SolrID)))}}">{!!$joinAddress->address->joinDistrict->district->district_name!!}</a>@endif
						@if(!empty($joinAddress->address->joinSubRegion->subregion->id)) - <a href="{{route('channel.slug',array($channel['domain']->domain,str_replace('/VN/','',$joinAddress->address->joinSubRegion->subregion->SolrID)))}}">{!!$joinAddress->address->joinSubRegion->subregion->subregions_name!!}</a>@endif
						@if(!empty($joinAddress->address->joinRegion->region->id)) - <i class="flag flag-{{mb_strtolower($joinAddress->address->joinRegion->region->iso)}}"></i> {!!$joinAddress->address->joinRegion->region->country!!}@endif
			</p>
			@endforeach
		@endif
		<p>
			<small><span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($company->company_updated_at)!!}</span></small>
			<small><i class="glyphicon glyphicon-eye-open"></i> <span class="post-view text-danger">{{$company->company_views}} lượt xem</span></small>
		</p>
	@endforeach
</div>