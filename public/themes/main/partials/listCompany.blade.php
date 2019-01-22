<div class="list-group-item">
	@foreach($chunk as $company)
		<h4><a href="https://com-{{$company->id}}.{{config('app.url')}}">{!!$company->title!!}</a></h4>
			@if(!empty($company->address))<p><i class="glyphicon glyphicon-map-marker"></i> {!!$company->address!!}</p>@endif
		<p>
			<small><span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($company->created_at)!!}</span></small>
			<small><i class="glyphicon glyphicon-eye-open"></i> <span class="post-view text-danger">{{$company->view}} lượt xem</span></small>
		</p>
	@endforeach
</div>