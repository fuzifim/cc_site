<!--content list website-->
<div id="panel_change_{{$channel->channel_id}}" class="panel panel-program">
	@if(count($posts)<1)
		<script>
			$( "#panel_change_{{$channel->channel_id}}" ).hide();
		</script>
	@endif
	<div class="panel-heading heading-program">
		<h4 class="company-name">
			<a href="//{{$channel->domain}}">
				<div class="company-avatar">
					@if($channel->media_join_table_type=='avatar')
						<img src="{{$channel->media_url}}" alt="" height="30" width="30"/> 
					@else 
						<span class="glyphicon glyphicon-picture" style="font-size: 20px;"></span>
					@endif
				</div>
				 <span>{{$channel->channel_name}}</span>
			</a>
			<div class="pull-right dropdown">
				<a class="" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-option-vertical"></i> </a> 
				<ul class="dropdown-menu">
					<li> 
						<a class="" onclick="setLikeWebsite({{$channel->channel_id}},'like')" href="javascript:void(0)"><i class="glyphicon glyphicon-thumbs-up"></i>Thích</a>
					</li>
					<li>
						 @if(Auth::check())
							<a href="javascript:void(0)" onclick="notCareAds({{$channel->channel_id}})" class="click_hiden" title="Không quan tâm"><i class="glyphicon glyphicon-remove"></i></a>
						 @else
							<a href="#!login" data-toggle="modal" data-target="#myModal" data-backdrop="true" title="Không quan tâm"><i class="glyphicon glyphicon-remove"></i></a>
						 @endif
					</li>
				</ul>
			</div>
		</h4>
	</div>
   <div class="panel-body">
	@foreach ($posts->chunk(4) as $chunk)
		<div class="row" role="listbox">
			@foreach($chunk as $post)
				<div class="col-xs-12 col-sm-6 col-md-3">
					<div class="portfolio_utube_item_image">
						<a href="{{route('front.post.show',$post->post_slug)}}" class="coverImagePostThumbnail">
							<img src="{{$post->media_url}}" class="img-fluid lazy-load" alt="{{$post->post_title}}"/>
						</a>
					</div>
					<div class="item_caption">
						<h4><a href="{{route('front.post.show',$post->post_slug)}}">{{$post->post_title}}</a></h4>
						<div class="item_caption_author">
							<span>bởi</span>
							  <a href="//{{$channel->domain}}"> 
								 <span>{{$channel->channel_name}}</span>
							  </a>
						</div>
						<ul>
							<li>{{$post->post_view}} lượt xem</li>
							<li><span>.</span></li>
							<li>{!!WebService::time_request($post->updated_at)!!}</li>
						</ul>
				   </div>
				</div>
			@endforeach
		</div>
	@endforeach
   </div><!--panel-body-->
</div><!--panel-default panel-chanel-->