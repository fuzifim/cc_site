<?
	$channel['theme']->setTitle('Tin của tôi');
	$channel['theme']->setKeywords('');
	$channel['theme']->setDescription('Danh sách bài viết đã đăng của tôi'); 
	//$channel['theme']->setCanonical(route("post.list",$channel["domainPrimary"]));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
?>
<section>
	<div class="mainpanel">
		{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
		{!!Theme::partial('formSearch', array('region' => $channel['region']))!!}
		<div class="contentpanel">
			<ul class="filemanager-options mb5">
				<li>
				  <div class="ckbox ckbox-default">
					<input type="checkbox" id="selectall" value="1">
					<label for="selectall">Chọn tất cả</label>
				  </div>
				</li>
				<li>
					<a href="" class="itemopt disabled deleteItemCheck"><i class="fa fa-trash-o"></i> Xóa</a>
				</li>
				<li>
					<a href="{{route('post.me',$channel['domainPrimary'])}}" class="itemopt"><i class="glyphicon glyphicon-list-alt"></i> Đã đăng</a>
				</li>
				<li>
					<a href="{{route('post.billing',$channel['domainPrimary'])}}" class="itemopt"><i class="glyphicon glyphicon-usd"></i> Tin thanh toán</a>
				</li>
				<li>
					<a href="{{route('channel.trash',$channel['domainPrimary'])}}" class="itemopt"><i class="glyphicon glyphicon-trash"></i> Đã xóa</a>
				</li>
				<li class="filter-type">
					Khoảng <strong>{{$posts->total()}}</strong> kết quả
				</li>
			</ul>
			<div class="panel panel-default">
				@if(count($posts)>0)
					<ul class="list-group listMember">
						@foreach($posts as $post)
						<?
							$domainPrimary=config('app.url'); 
							$postLink=''; 
							$target=''; 
							if(!empty($post->postsJoinChannel->channel->id)){
								if($post->postsJoinChannel->channel->domainJoinPrimary->domain->domain_primary!='default'){
									if(count($post->postsJoinChannel->channel->domainAll)>0){
										foreach($post->postsJoinChannel->channel->domainAll as $domain){
											if($domain->domain->domain_primary=='default'){
												$domainPrimary=$domain->domain->domain; 
											}
										}
									}else{
										$domainPrimary=$post->postsJoinChannel->channel->domainJoinPrimary->domain->domain; 
									}
								}else{
									$domainPrimary=$post->postsJoinChannel->channel->domainJoinPrimary->domain->domain; 
								}
								if($post->postsJoinChannel->channel->channel_parent_id==0){
									$postLink='https://post-'.$post->id.'.'.config('app.url');
								}else{
									$target='target="_blank"'; 
									if(!empty($post->getSlug->slug_value)){
										$postLink=route('channel.slug',array($domainPrimary,$post->getSlug->slug_value)); 
									}
								}
							}
						?>
						<li class="list-group-item">
							<span class="ckbox ckbox-default">
								<input type="checkbox" id="checkItem-{{$post->id}}" value="{{$post->id}}">
								<label for="checkItem-{{$post->id}}"></label>
							</span>
							<a href="#" class="close dropdown dropdown-toggle"  data-toggle="dropdown"><i class="glyphicon glyphicon-chevron-down"></i>
								<ul class="dropdown-menu" style="padding:0px;right:0px;left:inherit;">
									<a class="list-group-item" href="{{route('post.edit',array($domainPrimary,$post->id))}}" {{$target}}><i class="glyphicon glyphicon-edit"></i> Sửa</a> 
									<a class="list-group-item"data-dismiss="alert" href="#"><i class="glyphicon glyphicon-remove"></i> Xóa luôn</a>
								</ul>
							</a> 
							<?
								$getPost=\App\Model\Posts::find($post->id); 
							?>
							<a href="{{route('post.edit',array($domainPrimary,$post->id))}}" {{$target}}><i class="glyphicon glyphicon-list-alt"></i> <span class="text-success">{{$post->posts_title}}</span></a>
							<p>
								<small><i class="glyphicon glyphicon-time"></i> {!!Site::Date($post->posts_updated_at)!!}@if(!empty($getPost->author->user->name))<i class="glyphicon glyphicon-user"></i> {!!$getPost->author->user->name!!}@endif</small> 
								@if(!empty($post->postsJoinChannel->channel->channel_name))
								<a href="{{route('channel.home',$domainPrimary)}}" class="text-muted" {{$target}}>
								<img src="@if(!empty($post->postsJoinChannel->channel->channelAttributeLogo->media->media_name)){{config('app.link_media').$post->postsJoinChannel->channel->channelAttributeLogo->media->media_path.'xs/'.$post->postsJoinChannel->channel->channelAttributeLogo->media->media_name}}@endif" alt="" style="max-height:18px;"> <small>{!!$post->postsJoinChannel->channel->channel_name!!}</small>
								</a>
								@endif
							</p>
							<div class="form-group">
								<a href="{{$postLink}}" class="btn btn-xs btn-primary" {{$target}}>Xem</a> <a href="{{route('post.edit',array($domainPrimary,$post->id))}}" {{$target}} class="btn btn-xs btn-default">Sửa</a> <button type="button" class="btn btn-xs btn-danger postDelete" data-id="{{$post->id}}">Xóa</button>
							</div>
						</li>
						@endforeach
					</ul>
				@endif
				<div class="panel-footer">
					{!!Theme::partial('pagination', array('paginator' => $posts))!!}
				</div>
			</div>
		</div>
	</div>
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		jQuery(document).ready(function(){
			"use strict"; 
			jQuery(".ckbox").each(function(){
			  var t = jQuery(this);
			  var parent = t.parent();
			  if(t.find("input").is(":checked")) {
				t.show();
				parent.find(".fm-group").show();
				parent.addClass("disable");
			  }
			});
			jQuery(".ckbox").click(function(){
			  var t = jQuery(this);
			  if(!t.find("input").is(":checked")) {
				t.closest(".list-group-item").removeClass("disabled"); 
				jQuery(".deleteItemCheck").addClass("disabled");
				//enable_itemopt(false);
			  } else {
				t.closest(".list-group-item").addClass("disabled"); 
				jQuery(".deleteItemCheck").removeClass("disabled");
				//enable_itemopt(true);
			  }
			});
			jQuery("#selectall").click(function(){
			  if(jQuery(this).is(":checked")) {
				jQuery(".list-group-item").each(function(){
				  jQuery(this).find("input").attr("checked",true);
				  jQuery(this).addClass("disabled");
				 // jQuery(this).find(".ckbox, .fm-group").show();
				});
				//enable_itemopt(true);
			  } else {
				jQuery(".list-group-item").each(function(){
				  jQuery(this).find("input").attr("checked",false);
				  jQuery(this).removeClass("disabled");
				 // jQuery(this).find(".ckbox, .fm-group").hide();
				});
				//enable_itemopt(false);
			  }
			});
			jQuery(".deleteItemCheck").click(function(){
				if(confirm("Bạn có chắc muốn xóa?")){
					jQuery(".list-group-item").each(function(){
						var t = jQuery(this);
						if(t.find("input").is(":checked")) {
							var postId=t.find("input").val(); 
							var formData = new FormData();
							formData.append("postId", postId); 
							$.ajax({
								url: "'.route("post.remove.request",$channel["domainPrimary"]).'",
								headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
								type: "post",
								cache: false,
								contentType: false,
								processData: false,
								data: formData,
								dataType:"json",
								success:function(result){ 
									if(result.success==true){
										jQuery.gritter.add({
											title: "Thông báo!",
											text: result.message, 
											class_name: "growl-success",
											sticky: false,
											time: ""
										}); 
										location.reload(); 
									}else{
										jQuery.gritter.add({
											title: "Thông báo!",
											text: result.message, 
											class_name: "growl-danger",
											sticky: false,
											time: ""
										});
									}
								},
								error: function(result) {
									jQuery.gritter.add({
										title: "Thông báo!",
										text: "Lỗi không thể xóa bài viết, vui lòng thử lại! ", 
										class_name: "growl-danger",
										sticky: false,
										time: ""
									});
								}
							});
						}
					}); 
				}
				return false; 
			}); 
		}); 
		$(".postDelete").click(function(){
			if(confirm("Bạn có chắc muốn xóa?")){
				$(window).unbind("beforeunload"); 
				var formData = new FormData();
				formData.append("postId", $(this).attr("data-id")); 
				$.ajax({
					url: "'.route("channel.post.delete",$channel["domainPrimary"]).'",
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					type: "post",
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					dataType:"json",
					success:function(result){
						//console.log(result); 
						window.location.href="'.route("channel.trash",$channel["domainPrimary"]).'"; 
					},
					error: function(result) {
					}
				});
			}
			return false; 
		}); 
	', $dependencies);
?>