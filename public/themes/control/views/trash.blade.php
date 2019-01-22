<?
	$channel['theme']->setTitle('Thùng rác');
	$channel['theme']->setKeywords('Thùng rác');
	$channel['theme']->setDescription('Thùng rác '.$channel['info']->channel_name); 
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->add('bootstrap-colorpicker', 'assets/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js', array('core-script'))!!}
@if($channel['security']==true)
{!!Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'))!!}
@endif
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
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
			<li class="filter-type">
				Khoảng <strong>{{$getPosts->total()}}</strong> kết quả
			</li>
		</ul>
		<div class="panel panel-default">
			@if(count($getPosts)>0)
				<ul class="list-group listMember">
					@foreach($getPosts as $post)
					<li class="list-group-item">
						<span class="ckbox ckbox-default">
							<input type="checkbox" id="checkItem-{{$post->id}}" value="{{$post->id}}">
							<label for="checkItem-{{$post->id}}"></label>
						</span>
						<a href="#" class="close dropdown dropdown-toggle"  data-toggle="dropdown"><i class="glyphicon glyphicon-chevron-down"></i>
							<ul class="dropdown-menu" style="padding:0px;right:0px;left:inherit;">
								<a class="list-group-item" href="{{route('post.edit',array($channel['domain']->domain,$post->id))}}"><i class="glyphicon glyphicon-edit"></i> Sửa</a> 
								<a class="list-group-item"data-dismiss="alert" href="#"><i class="glyphicon glyphicon-remove"></i> Xóa luôn</a>
							</ul>
						</a> 
						<?
							$getPost=\App\Model\Posts::find($post->id); 
						?>
						<a href="{{route('post.edit',array($channel['domain']->domain,$post->id))}}"><i class="glyphicon glyphicon-list-alt"></i> <span class="text-success">{{$post->posts_title}}</span></a> <small><i class="glyphicon glyphicon-time"></i> {!!Site::Date($post->posts_updated_at)!!}</small> @if(!empty($getPost->author->user->name))<i class="glyphicon glyphicon-user"></i> {!!$getPost->author->user->name!!}@endif
					</li>
					@endforeach
				</ul>
			@endif
			<div class="panel-footer">
				{!!$getPosts->render()!!}
			</div>
		</div>
	</div>

</div><!-- mainpanel -->
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
				return false; 
			}); 
		}); 
	', $dependencies);
?>
@if($channel['security']==true)
<?

	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('customAdmin','
	', $dependencies);
?>
@endif
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('onload',' 
		var docHeight = jQuery(document).height();
		if(docHeight > jQuery(".mainpanel").height())
		jQuery(".mainpanel").height(docHeight);
	', $dependencies);
?>