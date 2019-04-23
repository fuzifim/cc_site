<?
	$keywordNew=new \App\Http\Controllers\KeywordsController(); 
	$getKeywords=$keywordNew->getKeywordPost($post->id); 
	$key=''; 
	if(count($getKeywords)>0){
		foreach($getKeywords as $keyword){
			$key.=$keyword->keyword.', ';
		}
	}
	$channel['theme']->setTitle(html_entity_decode($post->posts_title));
	$channel['theme']->setKeywords($key);
	$channel['theme']->setDescription(WebService::limit_string(strip_tags(html_entity_decode($post->posts_description),""), $limit = 200)); 
	$channel['theme']->setCanonical(route('channel.slug',array($channel['domainPrimary'],$post->getSlug->slug_value)));
	if(count($post->gallery)>0){
		if($post->gallery[0]->media->media_storage=='youtube'){
			$setImage='//img.youtube.com/vi/'.$post->gallery[0]->media->media_name.'/hqdefault.jpg'; 
			$setUrl=$post->gallery[0]->media->media_url; 
			$channel['theme']->setVideo($post->gallery[0]->media->media_url); 
			$channel['theme']->setType('video'); 
		}else if($post->gallery[0]->media->media_storage=='video'){
			$setImage=config('app.link_media').$post->gallery[0]->media->media_path.'thumb/'.$post->gallery[0]->media->media_id_random.'.png'; 
		}else{
			if(!empty($post->gallery[0]->media->media_name)){
				$setImage=config('app.link_media').$post->gallery[0]->media->media_path.'thumb/'.$post->gallery[0]->media->media_name; 
			} 
			if(!empty($post->price->posts_attribute_value)){
				$channel['theme']->setType('product'); 
			}else{
				$channel['theme']->setType('article'); 
			}
			$setUrl=route('channel.slug',array($channel['domainPrimary'],$post->getSlug->slug_value)); 
		}
		$channel['theme']->setUrl($setUrl); 
		$channel['theme']->setImage($setImage); 
	}
?>
<script type="application/ld+json">{"@context":"http:\/\/schema.org","publisher":{"@type":"Organization","name":"{!!$channel['info']->channel_name!!}","logo":{"@type":"ImageObject","url":"@if(!empty($channel['info']->channelAttributeLogo->media->media_name)){{config('app.link_media').$channel['info']->channelAttributeLogo->media->media_path.'small/'.$channel['info']->channelAttributeLogo->media->media_name}}@else {{asset('assets/img/logo-default.jpg')}} @endif","height":32,"width":32}},"@type":"BlogPosting","mainEntityOfPage":"{{route('channel.slug',array($channel['domainPrimary'],$post->getSlug->slug_value))}}","headline":"{!!$post->posts_title!!}","datePublished":"{!!Site::Date($post->posts_updated_at)!!}","dateModified":"2018-05-27T10:07:38+00:00","author":{"@type":"Person","name":"{{$post->author->user->name}}"},"image":{"@type":"ImageObject","url":"{{$setImage}}","width":720,"height":480}}</script>

<article class="amp-wp-article">
	<header class="amp-wp-article-header">
		<h1 class="amp-wp-title">{!!$post->posts_title!!}</h1>
		<div class="amp-wp-meta amp-wp-byline">
			<amp-img src="@if(!empty($post->author->user->getAvata->media->media_name)){{config('app.link_media').$post->author->user->getAvata->media->media_path.'xs/'.$post->author->user->getAvata->media->media_name}}@else {{asset('assets/img/no-avata.png')}}@endif" width="24" height="24" layout="fixed"></amp-img>
			<span class="amp-wp-author author vcard">{{$post->author->user->name}}</span>
		</div>
		<div class="amp-wp-meta amp-wp-posted-on">
			<time datetime="{!!Site::Date($post->posts_updated_at)!!}">{!!WebService::time_request($post->posts_updated_at)!!}</time>
		</div>
	</header>

	<figure class="amp-wp-article-featured-image wp-caption">
		<amp-anim width="720" height="480" src="{{$setImage}}" class="" alt="" layout="intrinsic"></amp-anim>	
	</figure>
	<div class="amp-wp-article-content">
		{!!WebService::addNofollow($postDescription,$channel['domainPrimary'])!!}
	</div>

	<footer class="amp-wp-article-footer">
		<div class="amp-wp-meta amp-wp-tax-category">
			@if(count($post->postsJoinCategory)>0)
					<a itemscope itemtype="http://schema.org/Thing"
		   itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Trang chủ</span></a> 
					@if(count($post->postsJoinCategory[0]->getCategory->parent)>0)
						@foreach($post->postsJoinCategory[0]->getCategory->parent as $parent) 
							@if(count($parent->parent)>0)
								@foreach($parent->parent as $subParent) 
									<a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],$subParent->getSlug->slug_value))}}"><span itemprop="name">{!!$subParent->category_name!!}</span></a>
								@endforeach
							@endif
							<a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],$parent->getSlug->slug_value))}}"><span itemprop="name">{!!$parent->category_name!!}</span></a>
						@endforeach
					@endif
					<a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],$post->postsJoinCategory[0]->getCategory->getSlug->slug_value))}}"><span itemprop="name">{!!$post->postsJoinCategory[0]->getCategory->category_name!!}</span></a> 
			@endif	
		</div>
		<div class="amp-wp-meta amp-wp-comments-link">
		<a href="{{route('channel.slug',array($channel['domainPrimary'],$post->getSlug->slug_value))}}">Xem bản đầy đủ</a>
	</div>
	</footer>
</article>