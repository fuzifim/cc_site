@if(!empty($post->gallery[0]->media->media_url) && !empty($post->getSlug->slug_value))
<div class="col-xs-12 col-sm-4 col-md-4">
	<div class="form-group content-list-post">
		<a class="image img-thumbnail" href="{{route('channel.slug',array($channel['domain']->domain,$post->getSlug->slug_value))}}">
		@if($post->gallery[0]->media->media_storage=='youtube')
			<img src="//img.youtube.com/vi/{{$post->gallery[0]->media->media_name}}/hqdefault.jpg" class="img-responsive imgThumb lazy" alt="{!!$post->posts_title!!}" title="" >
		@elseif($post->gallery[0]->media->media_storage=='video')
		<div class="groupThumb" style="position:relative;">
			<span class="btnPlayVideoClick"><i class="glyphicon glyphicon-play"></i></span>
			<img itemprop="image" class="img-responsive imgThumb lazy" alt="{{$post->posts_title}}" src="{{config('app.link_media').$post->gallery[0]->media->media_path.'xs/'.$post->gallery[0]->media->media_id_random.'.png'}}"/>
		</div>
		@elseif($post->gallery[0]->media->media_storage=='files')
		<img src="{!!asset('assets/img/file.jpg')!!}" class="img-responsive imgThumb lazy" alt="{!!$post->posts_title!!}" title="" >
		@else
			<img src="{{config('app.link_media').$post->gallery[0]->media->media_path.'xs/'.$post->gallery[0]->media->media_name}}" class="img-responsive imgThumb lazy" alt="{!!$post->posts_title!!}" title="" >
		@endif
		</a>
		<h2 class="blog-title mb10"><a class="title" href="{{route('channel.slug',array($channel['domain']->domain,$post->getSlug->slug_value))}}">{!!$post->posts_title!!}</a></h2>
		<div class="attribute-2 mb5">
			<small><span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($post->posts_updated_at)!!}</span> 
			<span class="author"><i class="glyphicon glyphicon-user"></i> {!!$post->author->user->name!!}</span></small> 
		</div>
		<div class="attribute-1">
			@if(!empty($post->quanlity->posts_attribute_value) && $post->quanlity->posts_attribute_value>0)
				<small><span class="text-success">{{$post->quanlity->posts_attribute_value}} Còn hàng</span></small>
			@endif
			  @if(!empty($post->price->posts_attribute_value))<span class="group-attribute pull-right"><span class="price"><strong>{!!Site::price($post->price->posts_attribute_value)!!} @if(!empty($channel['info']->channel_currency))<sup>{{$channel['info']->channelCurrency->currency_name}}</sup>@elseif(!empty($channel['info']->joinAddress[0]->address->joinRegion->region->currency_name))<sup>{{$channel['info']->joinAddress[0]->address->joinRegion->region->currency_name}}</sup>@else<sup>VNĐ</sup>@endif</strong></span> <button type="button" class="btn btn-xs btn-danger itemBuyNow" data-id="{{$post->id}}"><i class="glyphicon glyphicon-check"></i> Mua ngay</button></span>@endif
		</div>
		<div class="timeline-btns">
			<div class="pull-left">
				<a href="" class="tooltips likeUp text-muted likeUp_{{$post->id}} @if(count($post->like)>0) @if(Auth::check())@foreach($post->like as $like) @if($like->user_id==Auth::user()->id) text-success @endif @endforeach @else @foreach($post->like as $like) @if($like->user_id==Request::ip()) text-success @endif @endforeach @endif @endif" data-id="{{$post->id}}" data-toggle="tooltip" title="" data-original-title="Thích"><i class="glyphicon glyphicon-thumbs-up"></i> <span class="countLike_{{$post->id}}">{{count($post->like)}}</span></a>
				<a href="" class="tooltips likeDown text-muted likeDown_{{$post->id}} @if(count($post->unLike)>0) @if(Auth::check())@foreach($post->unLike as $like) @if($like->user_id==Auth::user()->id) text-danger @endif @endforeach @else @foreach($post->unLike as $like) @if($like->user_id==Request::ip()) text-danger @endif @endforeach @endif @endif" data-id="{{$post->id}}" data-toggle="tooltip" title="" data-original-title="Không thích"><i class="glyphicon glyphicon-thumbs-down"></i> <span class="countLikeDown_{{$post->id}}">{{count($post->unlike)}}</span></a>
				<a href="" class="tooltips text-muted" data-toggle="tooltip" title="" data-original-title="Add Comment"><i class="glyphicon glyphicon-comment"></i> {{count($post->commentJoinPost)}}</a>
				<a href="" class="tooltips btnShare text-muted"  data-title="{!!$post->posts_title!!}" data-image="" data-url="{{route('channel.slug',array($channel['domain']->domain,$post->getSlug->slug_value))}}" data-toggle="tooltip" title="" data-original-title="Share"><i class="glyphicon glyphicon-share"></i></a>
			</div>
			<div class="pull-right">
				<small class="text-muted text-danger"><strong>{{$post->posts_view}} lượt xem</strong></small>
			</div>
		</div>
	</div>
</div>
@endif
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('loadLazy','
		$(function() {
			$(".lazy").lazy();
		});
	', $dependencies);
?>