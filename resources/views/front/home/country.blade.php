<ul class="list-group">
	@foreach($regions_all as $regions_list)
		<li class="list-group-item"><a href="{{route('front.ads_by_location.contry',$regions_list->iso)}}">
			<i class="fa fa-folder fa-fw"></i> {{$regions_list->country}}</a>
		</li>
	@endforeach
</ul>