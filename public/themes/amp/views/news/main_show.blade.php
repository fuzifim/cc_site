<?
	Theme::setTitle(html_entity_decode($news->title));
	//Theme::setKeywords($key);
	Theme::setDescription(WebService::limit_string(strip_tags(html_entity_decode($news->description),""), $limit = 200)); 
	Theme::setCanonical('https://news-'.$news->id.'.'.config('app.url'));
	if(!empty($news->image)){
		Theme::setImage($news->image);
	}
?>
<article class="amp-wp-article">
	<header class="amp-wp-article-header">
		<h1 class="amp-wp-title">{!!$news->title!!}</h1>
		<div class="amp-wp-meta amp-wp-byline">
			<amp-img src="{{asset('assets/img/no-avata.png')}}" width="24" height="24" layout="fixed"></amp-img>
			<span class="amp-wp-author author vcard">Cung Cấp</span>
		</div>
		<div class="amp-wp-meta amp-wp-posted-on">
			<time datetime="{!!Site::Date($news->updated_at)!!}">{!!WebService::time_request($news->updated_at)!!}</time>
		</div>
	</header>
	@if(!empty($news->image))
	<figure class="amp-wp-article-featured-image wp-caption">
		<amp-anim width="720" height="480" src="{{$news->image}}" class="" alt="" layout="intrinsic"></amp-anim>	
	</figure>
	@endif
	<amp-ad width="100vw" height=320
	  type="adsense"
	  data-ad-client="ca-pub-6739685874678212"
	  data-ad-slot="7536384219"
	  data-auto-format="rspv"
	  data-full-width>
		<div overflow></div>
	</amp-ad>
	<div class="amp-wp-article-content">
		{!!WebService::addNofollow(html_entity_decode($newsDescription),$channel['domainPrimary'],true)!!}
	</div>

	<footer class="amp-wp-article-footer">
		<div class="amp-wp-meta amp-wp-tax-category">
			@if(count($news->joinField->parent)>0)
				@foreach($news->joinField->parent as $parent) 
					@if(count($parent->parent)>0)
						@foreach($parent->parent as $subParent) 
							<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],Str::slug($subParent->SolrID)))}}"><span itemprop="name">{!!$subParent->name!!}</span></a></li>
						@endforeach
					@endif
					<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],Str::slug($parent->SolrID)))}}"><span itemprop="name">{!!$parent->name!!}</span></a></li>
				@endforeach
			@endif
			<li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],Str::slug($news->joinField->SolrID)))}}"><span itemprop="name">{!!$news->joinField->name!!}</span></a></li> 
		</div>
		<div class="amp-wp-meta amp-wp-comments-link">
			<a href="https://news-{{$news->id}}.{{config('app.url')}}">Xem bản đầy đủ</a>
		</div>
	</footer>
</article>