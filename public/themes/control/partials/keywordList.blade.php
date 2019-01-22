@foreach($keywords as $keyword)
	<a href="{{route('search.slug',array($channel['domainPrimary'],$keyword->getKeyword->slug))}}" class="btn btn-default btn-xs mb5"><i class="fa fa-tag"></i> {!!$keyword->getKeyword->keyword!!}</a>
@endforeach