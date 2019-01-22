@foreach($keywords as $keyword)
	<a href="{{route('search.slug',array($channel['domainPrimary'],$keyword->slug))}}" class="btn btn-default btn-xs mb5"><i class="fa fa-tag"></i> {!!$keyword->keyword!!}</a>
@endforeach