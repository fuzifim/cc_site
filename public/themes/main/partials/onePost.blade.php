<?
	$postLink='https://post-'.$post->id.'.'.config('app.url');
?>
@if(!empty($post->gallery[0]->media->media_url))
	<div class="list-group-item form-group padding5">
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
			<a class="image" href="{{$postLink}}">
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
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
			<h5 class="postTitle nomargin"><a class="title" href="{{$postLink}}">{!!$post->posts_title!!}</a></h5>
			<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($post->posts_updated_at)!!}</span></small> 
			<small><span class="author"><i class="glyphicon glyphicon-user"></i> {!!$post->author->user->name!!}</span></small> 
		</div>
	</div>
@else 
	<div class="list-group-item form-group padding5">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h5 class="postTitle"><a class="title" href="{{$postLink}}">{!!$post->posts_title!!}</a></h5>
			<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($post->posts_updated_at)!!}</span></small> 
			<small><span class="author"><i class="glyphicon glyphicon-user"></i> {!!$post->author->user->name!!}</span></small> 
		</div>
	</div>
@endif