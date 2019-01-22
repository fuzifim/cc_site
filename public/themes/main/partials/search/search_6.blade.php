<div class="list-group">
	@foreach($domainSearch as $item)
	<?
		$domain=\App\Model\Domain::find($item->id); 
	?>
	<div class="list-group-item">
		<h4 class="blog-title headTitle"><strong><a class="text-info siteLink" data-url="" target="_blank" href="{{route('keyword.show',array(config('app.url'),WebService::characterReplaceUrl($domain->domain_title)))}}">{{$domain->domain_title}}</a></strong></h4>
		<span class=""><img class="lazy" src="https://www.google.com/s2/favicons?domain={{$domain->domain}}" alt="{{$domain->domain}}" title="{{$domain->domain}}"> <a class="text-danger siteLink" target="_blank" data-url="" href="@if($domain->domain!=config('app.url'))http://{{$domain->domain}}.{{config('app.url')}}@endif">{{$domain->domain}}</a></span> 
		<p>{{$domain->domain_description}}</p>
	</div>
@endforeach
</div>