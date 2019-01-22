<div class="list-group">
	<? $i=0; ?>
	@foreach($domainSearch as $item)
	<?
		$i++; 
		$domain=\App\Model\Domain::find($item->id); 
	?>
	<div class="list-group-item">
		<h4 class="blog-title headTitle"><strong><a class="text-info siteLink" data-url="{{json_encode(route("go.to.url",array(config("app.url"),urlencode('http://'.$domain->domain))))}}" target="_blank" href="@if($domain->domain!=config('app.url'))http://{{$domain->domain}}.{{config('app.url')}}@endif">{{$domain->domain_title}}</a></strong></h4>
		<span class=""><img class="lazy" src="https://www.google.com/s2/favicons?domain={{$domain->domain}}" alt="{{$domain->domain}}" title="{{$domain->domain}}"> <a class="text-danger siteLink" target="_blank" data-url="{{json_encode(route("go.to.url",array(config("app.url"),urlencode('http://'.$domain->domain))))}}" href="@if($domain->domain!=config('app.url'))http://{{$domain->domain}}.{{config('app.url')}}@endif">{{$domain->domain}}</a></span> 
		<p>{{$domain->domain_description}}</p>
	</div>
@endforeach
</div>