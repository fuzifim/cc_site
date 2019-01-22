<?
	$theme->setTitle('Thùng rác');
?>
@include('themes.admin.inc.header')
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				@include('themes.admin.partial.menuManageService')
			</div>
			<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
				<div class="form-group">
					<button type="button" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Dọn sạch thùng rác</button>
				</div>
				@if(count($getPosts)>0)
					<ul class="list-group listMember">
						@foreach($getPosts as $post)
						<li class="list-group-item">
							<a href="#" class="close dropdown dropdown-toggle"  data-toggle="dropdown"><i class="glyphicon glyphicon-option-vertical"></i>
								<ul class="dropdown-menu" style="padding:0px;right:0px;left:inherit;">
									<a class="list-group-item" href="{{route('channel.post.edit',array($channel['domain']->domain,$post->id))}}"><i class="glyphicon glyphicon-edit"></i> Sửa</a> 
									<a class="list-group-item"data-dismiss="alert" href="#"><i class="glyphicon glyphicon-remove"></i> Xóa luôn</a>
								</ul>
							</a> 
							<?
								$getPost=\App\Model\Posts::find($post->id); 
							?>
							<i class="glyphicon glyphicon-list-alt"></i> <span class="text-success">{{$post->posts_title}}</span> <small><i class="glyphicon glyphicon-time"></i> {!!Site::Date($post->posts_updated_at)!!}</small> @if(!empty($getPost->author->user->name))<i class="glyphicon glyphicon-user"></i> {!!$getPost->author->user->name!!}@endif
						</li>
						@endforeach
					</ul>
				@endif
			</div>
		</div>
	</div>
</div>
@include('themes.admin.inc.footer')