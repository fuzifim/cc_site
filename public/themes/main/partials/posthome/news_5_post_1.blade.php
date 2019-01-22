<div class="panel panel-default">
	<div class="panel-heading"><h3 class="panel-title">Cung Cấp {!!$field->name!!}</h3></div>
	<div class="list-group">
		<div class="row row-pad-5">
			<div class="col-md-6">
				{!!Theme::partial('onePost', array('post' => $getPost[0]))!!}
			</div>
			<div class="col-md-6">
				<? 
					$i=0; 
					$firstNews=WebService::filter_by_value($getNews, 'image',!null); 
				?>
				<div class="form-group padding5">
					<div class="form-group">
						<a class="image" href="https://news-{{$firstNews[0]['id']}}.{{config('app.url')}}">
							<img src="{{$firstNews[0]['image']}}" class="img-responsive img-thumbnail lazy" alt="{!!$firstNews[0]['title']!!}" title="" >
						</a>
					</div>
					<h5 class="postTitle nomargin"><a class="blog-title" href="https://news-{{$firstNews[0]['id']}}.{{config('app.url')}}">{!!$firstNews[0]['title']!!}</a></h5>
					<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($firstNews[0]['updated_at'])!!}</span></small>
				</div>
				@foreach($getNews as $postRelate)
					<? $i++; ?>
					@if($postRelate['id']!=$firstNews[0]['id'])
						@if(!empty($postRelate['image']))
							<div class="list-group-item form-group padding5">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
									<a class="image" href="https://news-{{$postRelate['id']}}.{{config('app.url')}}">
										<img src="{{$postRelate['image']}}" class="img-responsive img-thumbnail lazy" alt="{!!$postRelate['title']!!}" title="" >
									</a>
								</div>
								<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
									<h5 class="postTitle nomargin"><a class="title" href="https://news-{{$postRelate['id']}}.{{config('app.url')}}">{!!$postRelate['title']!!}</a></h5>
									<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($postRelate['updated_at'])!!}</span></small>
								</div>
							</div>
						@else 
							<div class="list-group-item form-group padding5">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<h5 class="postTitle"><a class="title" href="https://news-{{$postRelate['id']}}.{{config('app.url')}}">{!!$postRelate['title']!!}</a></h5>
									<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($postRelate['updated_at'])!!}</span></small>
								</div>
							</div>
						@endif
					@endif
				@endforeach
			</div>
		</div>
	</div>
</div>