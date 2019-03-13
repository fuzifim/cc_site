<?
	$channel['theme']->setTitle($data['category']->category_name);
	$channel['theme']->setKeywords($data['category']->category_name);
	$channel['theme']->setDescription($data['category']->category_description); 
	if(count($channel['info']->channelAttributeBanner)>0 && !empty($channel['info']->channelAttributeBanner[0]->media->media_name)){$channel['theme']->setImage(config('app.link_media').$channel['info']->channelAttributeBanner[0]->media->media_path.'thumb/'.$channel['info']->channelAttributeBanner[0]->media->media_name);}
	Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'));
?>
@if($channel['security']==true)
	<?
	Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'));
		$dependencies = array(); 
		$channel['theme']->asset()->writeScript('changeChannel','
			jQuery(document).ready(function(){
				"use strict"; 
				$(".groupChannelName").on("click",".btnChannelNameEdit",function() {
					var channelNameText=$(this).parent().closest(".groupChannelName").find(".channelNameText"); 
					var channelDescriptionText=$(this).parent().closest(".groupChannelName").find(".channelDescriptionText"); 
					var changeChannelNameText=$(this).parent().closest(".groupChannelName").find(".changeChannelNameText"); 
					var categoryName=$(this).attr("data-name"); 
					var categoryId=$(this).attr("data-id"); 
					var parentId=$(this).attr("data-parent"); 
					var orderBy=$(this).attr("data-order-by"); 
					var categoryDescription=channelDescriptionText.html(); 
					channelNameText.hide(); 
					changeChannelNameText.show(); 
					changeChannelNameText.append("<form id=\"changeChannelName\">"
						+"<div class=\"form-group\">"
							+"<input type=\"phone\" style=\"font-size:18px;\" name=\"categoryName\" value=\""+categoryName+"\" class=\"form-control\" placeholder=\"Nhập tên danh mục...\" required /><input type=\"hidden\" name=\"categoryId\" value=\""+categoryId+"\"><input type=\"hidden\" name=\"parentId\" value=\""+parentId+"\">"
							+"<label class=\"error\" for=\"categoryName\"></label>"
						+"</div>"
						+"<div class=\"form-group\">"
							+"<textarea name=\"categoryDescription\" class=\"form-control\" placeholder=\"Mô tả danh mục...\">"+categoryDescription+"</textarea>"
							+"<label class=\"error\" for=\"categoryDescription\"></label>"
						+"</div>"
						+"<div class=\"form-group\">"
							+"<div class=\"addCategorySelect\"></div>"
						+"</div>" 
						+"<div class=\"form-group\">"
							+"<input type=\"number\" name=\"categoryOrderBy\" value=\""+orderBy+"\" class=\"form-control\" placeholder=\"Số thứ tự sắp xếp\">"
							+"<label class=\"error\" for=\"categoryOrderBy\"></label>"
						+"</div>"
						+"<div class=\"form-group\">"
							+"<button type=\"submit\" class=\"btn btn-xs btn-primary btnSaveChannelName\" data-id=\"\"><i class=\"fa fa-check\"></i> Lưu</button> "
							+"<button type=\"button\" class=\"btn btn-xs btn-default btnCancelChannelname\">Hủy</button>"
						+"</div>"
						+"</form>"); 
					var $validator = jQuery(".groupChannelName #changeChannelName").validate({
						highlight: function(element) {
						  jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
						},
						success: function(element) {
						  jQuery(element).closest(".form-group").removeClass("has-error");
						}
					});
					$.ajax({
						url: "'.route("channel.category.select",$channel["domain"]->domain).'",
						type: "GET",
						dataType: "html",
						success: function (data) {
							$("#changeChannelName .addCategorySelect").append("<select class=\"form-control\" name=\"categoryParentId\" id=\"categoryParentId\">"+data+"</select>"); 
						}
					});
					$(".groupChannelName").on("click",".btnSaveChannelName",function() {
						var $valid = jQuery(".groupChannelName #changeChannelName").valid();
						if(!$valid) {
							$validator.focusInvalid();
							return false;
						}else{
							$(".groupChannelName #preloaderInBox").css("display", "block"); 
							var formData = new FormData();
							formData.append("idCategory", $(".groupChannelName #changeChannelName input[name=categoryId]").val()); 
							formData.append("categoryName", $(".groupChannelName #changeChannelName input[name=categoryName]").val()); 
							formData.append("parentId", $(".groupChannelName #changeChannelName select[name=categoryParentId]").val()); 
							formData.append("categoryDescription", $(".groupChannelName #changeChannelName textarea[name=categoryDescription]").val()); 
							formData.append("categoryOrderBy", $(".groupChannelName #changeChannelName input[name=categoryOrderBy]").val()); 
							$.ajax({
								url: "'.route("channel.category.save",$channel["domain"]->domain).'",
								headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
								type: "post",
								cache: false,
								contentType: false,
								processData: false,
								data: formData,
								dataType:"json",
								success:function(result){
									//console.log(result); 
									$(".groupChannelName #preloaderInBox").css("display", "none"); 
									if(result.success==true){
										jQuery.gritter.add({
											title: "Thông báo!",
											text: result.msg, 
											class_name: "growl-success",
											sticky: false,
											time: ""
										});
										location.reload(); 
									}else{
										jQuery.gritter.add({
											title: "Thông báo!",
											text: result.msg, 
											class_name: "growl-danger",
											sticky: false,
											time: ""
										});
									}
								},
								error: function(result) { 
									jQuery.gritter.add({
										title: "Thông báo!",
										text: "Không thể cập nhật! ", 
										class_name: "growl-danger",
										sticky: false,
										time: ""
									});
								}
							});
						}
						return false;
					});
					return false;
				}); 
				$(".groupChannelName").on("click",".btnChannelNameDel",function() {
					var formData = new FormData();
					formData.append("categoryId", $(this).attr("data-id")); 
					$.ajax({
						url: "'.route("channel.category.delete",$channel["domain"]->domain).'",
						headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
						type: "post",
						cache: false,
						contentType: false,
						processData: false,
						data: formData,
						dataType:"json",
						success:function(result){
							//console.log(result); 
							$(".groupChannelName #preloaderInBox").css("display", "none"); 
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
								text: result.message, 
								class_name: "growl-danger",
								sticky: false,
								time: ""
							});
						}
					});
					return false; 
				});
				$(".groupChannelName").on("click",".btnCancelChannelname",function() {
					var channelNameText=$(this).parent().closest(".groupChannelName").find(".channelNameText"); 
					var changeChannelNameText=$(this).parent().closest(".groupChannelName").find(".changeChannelNameText"); 
					channelNameText.show(); 
					changeChannelNameText.empty(); 
				}); 
			}); 
		', $dependencies);
	?>
@endif
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel section-content">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<div class="groupChannelName" style="position:relative;">
			<div id="preloaderInBox" style="display:none;">
				<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
			</div>
			<div class="channelNameText">
				<h1>
					{!!$data['category']->category_name!!}
					@if($channel['security']==true)<span><a href="" data-name="{!!$data['category']->category_name!!}" data-id="{!!$data['category']->id!!}" data-parent="{!!$data['category']->parent_id!!}" data-order-by="{!!$data['category']->joinChannel->order_by!!}" class="btnChannelNameEdit"><i class="fa fa-pencil"></i> sửa</a></span> <span><a href="" data-name="{!!$data['category']->category_name!!}" data-id="{!!$data['category']->id!!}" class="btnChannelNameDel text-danger"><i class="fa fa-trash-o"></i> xóa</a></span>@endif
				</h1>
				<small><span class="channelDescriptionText">{!!$data['category']->category_description!!}</span></small>
			</div>
			<div class="changeChannelNameText"></div>
		</div>
	</div>
	<div class="contentpanel">
		@if(count($data['category']->parent)>0)
		<ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
			<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"
   itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span itemprop="name">Trang chủ</span></a></li> 
			@foreach($data['category']->parent as $parent) 
				@if(count($parent->parent)>0)
					@foreach($parent->parent as $subParent) 
						<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],$subParent->getSlug->slug_value))}}"><span itemprop="name">{!!$subParent->category_name!!}</span></a></li>
					@endforeach
				@endif
				<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],$parent->getSlug->slug_value))}}"><span itemprop="name">{!!$parent->category_name!!}</span></a></li>
			@endforeach
		</ol> 
		@endif
		
		@if(count($getPost)>0)
		<div class="panel panel-primary panel-program">
			<div class="panel-heading heading-program dropdown">
				<h3 class="panel-title categoryParentTitle"><a href="{{route('channel.slug',array($channel['domainPrimary'],$data['category']->getSlug->slug_value))}}"><i class="glyphicon glyphicon-book"></i> {{$data['category']->category_name}}</a></h3> 
				@if(count($data['category']->children)>0)
					<small>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Xem thêm <span class="fa fa-angle-down"></span></a>
							 <ul class="dropdown-menu" style="padding:0px;">
							@foreach($data['category']->children as $catChild) 
								@if(count($catChild->postsJoinParent)>0) 
									<a class="list-group-item" href="{{route('channel.slug',array($channel['domainPrimary'],$catChild->getSlug->slug_value))}}"><i class="glyphicon glyphicon-folder-open"></i> {{$catChild->category_name}}</a>
								@endif 
								@if(count($catChild->children)>0) 
									@foreach($catChild->children as $subChild) 
										@if(count($subChild->postsJoinParent)>0) 
											<a class="list-group-item" href="{{route('channel.slug',array($channel['domainPrimary'],$subChild->getSlug->slug_value))}}"><i class="glyphicon glyphicon-folder-open"></i> {!!$subChild->category_name!!}</a>
										@endif
									@endforeach
								@endif
							@endforeach
						</ul>
					</small>
				@endif
			</div>
			<div class="panel-body listItem">
				<div class="row form-group">
					@foreach($getPost->chunk(3) as $chunk)
						<div class="row">
							@foreach($chunk as $post)
								{!!Theme::partial('listPost', array('post' => $post))!!}
							@endforeach
						</div>
					@endforeach
				</div>
			</div>
			<div id="load_item_page" class="text-center">
				<input id="curentPage" class="curentPage" type="hidden" value="{{$getPost->currentPage()}}" autocomplete="off"/>
				<input id="totalPage" class="totalPage" type="hidden" value="{{$getPost->total()}}" autocomplete="off"/>
				<input id="urlPage" class="urlPage" type="hidden" value="{{Request::url()}}" autocomplete="off"/>
				<input id="nextPage" class="nextPage" type="hidden" value="{{$getPost->nextPageUrl()}}" autocomplete="off"/>
				<input id="perPage" class="perPage" type="hidden" value="{{$getPost->perPage()}}" autocomplete="off"/>
				<input id="lastPage" class="lastPage" type="hidden" value="{{$getPost->lastPage()}}" autocomplete="off"/>
				@if(strlen($getPost->nextPageUrl())>0)
					<div class="panel-footer text-center">
						<div class="click-more">
							<button class="btn btn-success btn-xs" id="loading-page"><i class="fa fa-spinner fa-spin"></i> Loading</button> 
							<a href="{{$getPost->nextPageUrl()}}"><i class="glyphicon glyphicon-hand-right viewMore"></i> Xem thêm...</a>
						</div>
					</div>
				@endif
			</div>
		</div>
		@else 
			<div class="panel panel-default">
				<div class="panel-body text-center">
					<strong>{{$data['category']->category_name}}</strong> Không có bài viết nào <a href="{{route('channel.home',$channel['domainPrimary'])}}">Trở về {{$channel['info']->channel_name}}</a>
				</div>
			</div>
		@endif
	</div>
</div><!-- mainpanel -->
</section>