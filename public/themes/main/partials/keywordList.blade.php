@foreach($keywords as $keyword)
	<a href="{{route('keyword.show',array(config('app.url'),WebService::characterReplaceUrl($keyword->getKeyword->keyword)))}}" class="btn btn-default btn-xs mb5"><i class="fa fa-tag"></i> {!!$keyword->getKeyword->keyword!!}</a>
@endforeach