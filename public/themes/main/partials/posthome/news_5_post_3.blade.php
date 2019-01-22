<div class="panel panel-primary">
	<div class="panel-heading"><h3 class="panel-title">Cung Cấp {!!$field->name!!}</h3></div>
	<div class="row row-pad-5">
		<div class="col-md-6">
			<? 
				$postArray=array(); 
				$arrayResult=array(); 
				foreach($getPost as $post){
					$arrayResult['id']=$post->id; 
					$arrayResult['title']=$post->posts_title; 
					if(!empty($post->gallery[0]->media->media_url)){
						$arrayResult['image']=config('app.link_media').$post->gallery[0]->media->media_path.'small/'.$post->gallery[0]->media->media_name; 
					}else{
						$arrayResult['image']=''; 
					}
					$arrayResult['updated_at']=$post->posts_updated_at; 
					array_push($postArray,$arrayResult);
				}
				$firstPost=WebService::filter_by_value($postArray, 'image',!null); 
			?>
			<div class="list-group-item padding5">
				<div class="form-group">
					<a class="image" href="https://post-{{$firstPost[0]['id']}}.{{config('app.url')}}">
						<img src="{{$firstPost[0]['image']}}" class="img-responsive lazy" alt="{!!$firstPost[0]['title']!!}" title="" >
					</a>
				</div>
				<h3 class="postTitle nomargin blog-title"><a class="" href="https://post-{{$firstPost[0]['id']}}.{{config('app.url')}}"><span class="text-danger">{!!$firstPost[0]['title']!!}</span></a></h3>
				<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($firstPost[0]['updated_at'])!!}</span></small>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel-primary">
				@foreach($getPost as $post)
					@if($post->id != $firstPost[0]['id'])
						{!!Theme::partial('onePost', array('post' => $post))!!}
					@endif
				@endforeach
			</div>
		</div>
	</div>
</div>