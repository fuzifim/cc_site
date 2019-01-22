<ul class="comments-list reply-list">
	@foreach($comments as $comment)
	<li>
		<div class="comment-box">
			<div class="comment-head">
				<div class="comment-avatar"><img src="@if(!empty($comment->author->user->getAvata->media->media_url)){{$comment->author->user->getAvata->media->media_url}}@else {{asset('assets/img/no-avata.png')}}@endif" alt=""></div>
				<h6 class="comment-name"><a href="#">{{$comment->author->user->name}}</a><span>{!!WebService::time_request($comment->created_at)!!}</span></h6>
				<i class="glyphicon glyphicon-share-alt"></i>
				<i class="glyphicon glyphicon-thumbs-up"></i>
			</div>
			<div class="comment-content">
				{{$comment->content}}
			</div>
		</div>
	</li>
	@endforeach
</ul>