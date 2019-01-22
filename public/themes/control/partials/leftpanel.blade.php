<div class="leftpanel">
	<div class="logopanel">
		@if($channel['security']==true)
		<div id="preloaderInBox" style="display:none;">
			<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
		</div>
		<div class="groupChangeLogo" style="position:relative; display:none; ">
			<button type="button" tabindex="500" class="btn btn-xs btn-primary btn-file" style="position: absolute; overflow: hidden; top:0px; right:5px; "><i class="glyphicon glyphicon-camera"></i>&nbsp;  <span>đổi ảnh</span><input id="changeLogo" name="changeLogo" type="file" class=""></button>
		</div>
		<?
			$dependencies = array(); 
			$channel['theme']->asset()->writeScript('changeLogo', '
				jQuery(document).ready(function(){
				"use strict"; 
				jQuery(".logopanel").hover(function(){
					var t = jQuery(this);
					t.find(".groupChangeLogo").show();
					t.find(".btn-file").show();
				}, function() {
					var t = jQuery(this);
					t.find(".groupChangeLogo").hide();
					t.find(".btn-file").hide();
				});
				$("#changeLogo").bind("change", function(){
					$(".logopanel #preloaderInBox").css("display", "block"); 
					var files = $("#changeLogo").prop("files")[0];  
					var formData = new FormData();
					formData.append("file", files); 
					formData.append("postType", "logo");  
					$.ajax({
						url: "'.route("channel.upload.file",$channel["domain"]->domain).'",
						type: "post", 
						headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
						cache: false,
						contentType: false,
						processData: false,
						data: formData,
						dataType:"json",
						success:function(result){
							//console.log(result); 
							var formDataChannel = new FormData();
							formDataChannel.append("mediaId", result.id); 
							$.ajax({
								url: "'.route("channel.attribute.media.logo",$channel["domain"]->domain).'",
								type: "post", 
								headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
								cache: false,
								contentType: false,
								processData: false,
								data: formDataChannel,
								dataType:"json",
								success:function(resultMedia){
									$(".logopanel #preloaderInBox").css("display", "none"); 
									$("#logoChannel").attr("src", result.url_small); 
									$("#changeLogo").val(""); 
								},
								error: function(resultMedia) {
								}
							});
						},
						error: function(result) {
						}
					});
				});
				$(".btnAddNewCategory").click(function(){
					$(".addNewCategoryElement").show();  
					$(".addNewCategoryElement").empty(); 
					$(".addNewCategoryElement").css("position", "relative"); 
					$(".addNewCategoryElement").append("<div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div>"); 
					$(".addNewCategoryElement").append("<form id=\"addCategoryForm\"></form>"); 
					$.ajax({
						url: "'.route("channel.category.select",$channel["domain"]->domain).'",
						type: "GET",
						dataType: "html",
						success: function (data) {
							$(".addNewCategoryElement #preloaderInBox").css("display", "none"); 
							$(".addNewCategoryElement #addCategoryForm").append("<div class=\"form-group\">"
								+"<input type=\"text\" name=\"category\" value=\"\" class=\"form-control\" placeholder=\"Nhập tên danh mục...\" required />"
								+"<label class=\"error\" for=\"category\"></label></div>");
							$(".addNewCategoryElement #addCategoryForm").append("<div class=\"form-group\">"
								+"<textarea class=\"form-control\" type=\"textarea\" name=\"categoryDescription\" placeholder=\"Mô tả tên danh mục...\" /></textarea>"
								+"<label class=\"error\" for=\"categoryDescription\"></label></div>");
							$(".addNewCategoryElement #addCategoryForm").append("<div class=\"form-group\"><select class=\"form-control\" name=\"categoryParentId\" id=\"categoryParentId\">"+data+"</select></div>"); 
							$(".addNewCategoryElement #addCategoryForm").append("<div class=\"form-group\">"
								+"<input type=\"number\" name=\"categoryOrderBy\" value=\"\" class=\"form-control\" placeholder=\"Số thứ tự sắp xếp\" />"
								+"<label class=\"error\" for=\"categoryOrderBy\"></label></div>");
							$(".addNewCategoryElement #addCategoryForm").append("<div class=\"form-group\"><button type=\"submit\" class=\"btn btn-xs btn-primary btnSaveCategory\" data-id=\"\"><i class=\"fa fa-check\"></i> Lưu</button> <button type=\"button\" class=\"btn btn-xs btn-default btnCancelCategory\">Hủy</button> </div>");
							var $validator = jQuery(".addNewCategoryElement #addCategoryForm").validate({
								highlight: function(element) {
								  jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function(element) {
								  jQuery(element).closest(".form-group").removeClass("has-error");
								}
							});
							$(".addNewCategoryElement .btnSaveCategory").click(function(){
								var $valid = jQuery(".addNewCategoryElement #addCategoryForm").valid();
								if(!$valid) {
									$validator.focusInvalid();
									return false;
								}else{
									$(".addNewCategoryElement").append("<div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div>"); 
									var formData = new FormData();
									formData.append("categoryName", $(".addNewCategoryElement #addCategoryForm input[name=category]").val()); 
									formData.append("categoryDescription", $(".addNewCategoryElement #addCategoryForm textarea[name=categoryDescription]").val()); 
									formData.append("parentId", $(".addNewCategoryElement #addCategoryForm select[name=categoryParentId]").val()); 
									formData.append("categoryOrderBy", $(".addNewCategoryElement #addCategoryForm input[name=categoryOrderBy]").val()); 
									$.ajax({
										url: "'.route("channel.category.save",$channel["domain"]->domain).'",
										type: "post", 
										headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
										cache: false,
										contentType: false,
										processData: false,
										data: formData,
										dataType:"json",
										success:function(result){
											//console.log(result); 
											if(result.success==true){
												location.reload(); 
												$(".addNewCategoryElement #preloaderInBox").css("display", "none"); 
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
										}
									});
									//return false;
								}
								return false;
							});
						}
					});
					return false;
				}); 
				$(".addNewCategoryElement").on("click",".btnCancelCategory",function() { 
					var addNewCategoryElement=$(this).parent().closest(".addNewCategoryElement"); 
					addNewCategoryElement.hide(); 
					addNewCategoryElement.empty(); 
				}); 
				$(".reUpdate").click(function(){
					$.ajax({
						url: "'.route("channel.re.update",$channel["domain"]->domain).'",
						headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
						type: "post",
						cache: false,
						contentType: false,
						processData: false,
						dataType:"json",
						success:function(result){
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-success",
								sticky: false,
								time: ""
							}); 
							window.location.href="'.route('channel.home',$channel['domainPrimary']).'"; 
						},
						error: function(result) {
						}
					});
					return false; 
				}); 
			});
			', $dependencies);
		?>
		@endif
		<a class="logo_header" href="{{url('/')}}"><img class="" id="logoChannel" src="@if(!empty($channel['info']->channelAttributeLogo->media->media_name)){{config('app.link_media').$channel['info']->channelAttributeLogo->media->media_path.'small/'.$channel['info']->channelAttributeLogo->media->media_name}}@else {{asset('assets/img/logo-default.jpg')}} @endif" alt="{!!$channel['info']->channel_name!!}"></a>
	</div><!-- logopanel -->

	<div class="leftpanelinner">
		<!-- This is only visible to small devices -->
		<div class="formInsertMobile mb5"></div>
		<ul class="nav nav-pills nav-stacked nav-bracket mb10">
			@if($channel['info']->channel_parent_id!=0) 
			<li><a href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span>Trang chủ</span></a></li> 
			<li class="{{(\Request::url()==route('channel.contact',$channel['domainPrimary'])) ? 'active' : ''}}"><a href="{{route('channel.contact',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-info-sign"></i> <span>Liên hệ</span></a></li> 
			@else 
				<!--<li class="{{(\Request::url()==route('company.list',$channel['domainPrimary'])) ? 'active' : ''}}"><a href="{{route('company.list',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-info-sign"></i> <span>Công ty mới</span></a></li> -->
			@endif 
			@if($channel['security']==true) 
				<?
					if(\Request::url()==route('post.add',$channel['domainPrimary']) || \Request::url()==route('channel.theme',$channel['domainPrimary']) || \Request::url()==route('channel.domain',$channel['domainPrimary']) || \Request::url()==route('channel.members',$channel['domainPrimary']) || \Request::url()==route('channel.statistics',$channel['domainPrimary']) || \Request::url()==route('channel.upgrade',$channel['domainPrimary']) || \Request::url()==route('channel.trash',$channel['domainPrimary'])){
						$navParent='nav-active'; 
						$ulChildren='display:block;'; 
					}else{
						$navParent=''; 
						$ulChildren=''; 
					}
				?>
			<li class="nav-parent {{$navParent}}"><a href=""><i class="glyphicon glyphicon-cog"></i> <span>Quản lý</span></a> 
				<ul class="children" style="{{$ulChildren}}"> 
					<li><a href="{{route('post.add',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-edit"></i> Đăng bài</a></li> 
					<li><a href="{{route('channel.theme',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-picture"></i> Cài đặt giao diện</a></li> 
					<li><a href="{{route('channel.domain',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-globe"></i> Quản lý tên miền</a></li> 
					<li><a href="{{route('channel.members',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-user"></i> <span>Thành viên</span></a></li> 
					<li><a href="{{route('channel.statistics',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-stats"></i> Thống kê</a></li> 
					<!--<li><a href="{{route('channel.upgrade',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-open"></i> Nâng cấp</a></li> -->
					<li><a href="{{route('channel.trash',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-trash"></i> Thùng rác</a></li> 
					<!--<li><a href="" class="reUpdate"><i class="glyphicon glyphicon-refresh"></i> Trở về phiên bản cũ</a></li>-->
				</ul> 
			</li> 
			@endif 
			@if($channel['info']->channel_parent_id!=0)
				@if(count($channel['info']->joinCategory))
					<h5 class="sidebartitle">Danh mục</h5> 
					@foreach($channel['info']->joinCategory as $joinCategory)
						@if($joinCategory->category->parent_id==0)
							@if($joinCategory->category->children->count())
								<li class="nav-parent {{(\Request::url()==route('channel.slug',array($channel['domainPrimary'],$joinCategory->category->getSlug->slug_value))) ? 'nav-active' : ''}}"> 
									<a href="{{route('channel.slug',array($channel['domainPrimary'],$joinCategory->category->getSlug->slug_value))}}"><i class="glyphicon glyphicon-folder-open"></i> <span>{{ html_entity_decode($joinCategory->category->category_name) }}</span></a>
									{!! Theme::partial('childItems',array('menus'=>$joinCategory->category->children,'menuParent'=>$joinCategory->category)) !!}
								</li> 
							@else
								<li class="{{(\Request::url()==route('channel.slug',array($channel['domainPrimary'],$joinCategory->category->getSlug->slug_value))) ? 'active' : ''}}">
									<a href="{{route('channel.slug',array($channel['domainPrimary'],$joinCategory->category->getSlug->slug_value))}}"><i class="glyphicon glyphicon-folder-open"></i> <span>{!!$joinCategory->category->category_name!!}</span></a>
								</li> 
							@endif
						@endif
					@endforeach
				@endif
			@endif
			@if($channel['info']->channel_parent_id==0)
				<li><a href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span>Trang chủ</span></a></li> 
			@endif
			@if($channel['security']==true)<li><a href="{{route('channel.home',$channel['domainPrimary'])}}" class="btnAddNewCategory"><i class="fa fa-plus"></i> <span>Thêm mới danh mục</span></a></li>@endif
		</ul>
		<div class="addNewCategoryElement"></div>
	</div><!-- leftpanelinner -->
</div><!-- leftpanel -->