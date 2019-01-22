<?
	if(!empty($post->id)){
		$title='Sửa tin'; 
		$description=''; 
	}else{
		$title='Đăng tin'; 
		$description='Đăng tin lên '.$channel['info']->channel_name; 
	}
	$channel['theme']->setTitle($title);
	$channel['theme']->setKeywords($title);
	$channel['theme']->setDescription($description); 
	$disable=true;
?>
{!!Theme::asset()->usePath()->add('jquery.tagsinput', 'css/jquery.tagsinput.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}

{!!Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.tagsinput.min', 'js/jquery.tagsinput.min.js', array('core-script'))!!}
{!!Theme::asset()->add('summernote', 'assets/js/summernote/dist/summernote.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->add('summernote', 'assets/js/summernote/dist/summernote.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->add('summernote-vi-VN', 'assets/js/summernote/lang/summernote-vi-VN.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->add('summernote-image-attributes', 'assets/js/summernote/dist/summernote-image-attributes.js', array('core-script'))!!}

	
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
		@if($disable==true)
			<div class="form-group text-center">
				<div class="alert alert-danger">
					<h4>Thông báo tính năng này đang bảo trì</h4> 
					<p>Tính năng này đang bảo trì, vui lòng quay lại trong thời gian sớm nhất. Xin lỗi vì sự bất tiện này, mọi thông tin vui lòng liên hệ contact@cungcap.net. Xin cảm ơn! </a></p>
				</div>
			</div>
		@endif
		@if(empty($post->id) && $channel['info']->channel_parent_id!=0)
		@if($channel['totalSize']>=$channel['limitSize'] || $channel['totalPosts']>=$channel['limitPosts'] && $channel['info']->channel_parent_id!=0)
			<div class="form-group text-center">
				<div class="alert alert-danger">
					<h4>Tài khoản của bạn vượt mức giới hạn dung lượng</h4> 
					<p>Không thể đăng bài viết mới. <a href="{{route('channel.statistics',$channel['domain']->domain)}}" class="btn label label-warning btnViewStaticts"><i class="glyphicon glyphicon-hand-right"></i> xem thống kê</a></p>
				</div>
			</div>
		@endif
		@endif
		<form id="postForm" class="form-group" style="position:relative; ">
			@if($disable==true)
				<div class="notificationLimit"></div>
			@endif
			@if(empty($post->id) && $channel['info']->channel_parent_id!=0)
			@if($channel['totalSize']>=$channel['limitSize'] || $channel['totalPosts']>=$channel['limitPosts'] && $channel['info']->channel_parent_id!=0)
				<div class="notificationLimit"></div>
			@endif
			@endif
			<input type="hidden" value="@if(!empty($post->id)){{$post->id}}@endif" name="postId">
			<div class="form-group">
				<input id="item-title" name="postTitle" style="font-size:18px;" value="@if(!empty($post->posts_title)){{$post->posts_title}}@endif" type="text" class="form-control title-post-edit" placeholder="Nhập tiêu đề tin..." required>
				<label class="error" for="postTitle"></label>
			</div>
			<div class="form-group">
				<div class="addCategoryForm"></div>
				<div class="addNewCategoryElementPost"></div>
			</div>
			<div class="mb5">
					<ul class="nav nav-tabs nav-justified">
						<li class="active"><a data-toggle="tab" href="#media"><i class="glyphicon glyphicon-camera"></i> Ảnh/ Video/ File</a></li>
						<li><a data-toggle="tab" href="#location"><i class="glyphicon glyphicon-map-marker"></i> Khu vực</a></li>
						<li><a data-toggle="tab" href="#keyword"><i class="glyphicon glyphicon-search"></i> Từ khóa</a></li>
						<li><a data-toggle="tab" href="#price"><i class="fa fa-money"></i> Giá</a></li>
						<li><a data-toggle="tab" href="#contact"><i class="glyphicon glyphicon-envelope"></i> Liên hệ</a></li>
					</ul>
					<div class="tab-content">
						<div id="media" class="mt5 tab-pane fade active in">
							<div class="image-wapper mb5">
								<div class="image-wapper-label">
									Thêm Ảnh/ Video/ File
								</div>
								<div class="image-wapper-take">
									<div class="jfu-container" id="jfu-plugin-b22da094fc3c-45e7-f95f-6c1af9d2d458"><span class="jfu-btn-upload"><span><span style="position:relative; cursor:pointer"> <i class="fa fa-camera camera-add-image"></i><i class="fa fa-plus-circle plus-add-image"></i></span></span><input id="postMedia" name="postMedia[]" type="file" multiple="" class="input-file jfu-input-file" accept="image/*" data-bind="uploader: UploadOptions" id="b22da094fc3c-45e7-f95f-6c1af9d2d458"></span></div>
								</div>
							</div>
							<div class="row form-group fileMedia">
							@if(!empty($post->id))
								@if(count($post->gallery)>0)
								@foreach ($post->gallery as $image)
									@if($image->media->media_type == "image/jpeg" || $image->media->media_type == "image/jpg" || $image->media->media_type == "image/png" || $image->media->media_type == "image/gif") 
										<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 itemFile" style="position:relative;"><a href="" class="delMediaData" style="position:absolute; bottom:0px; right:0px;" data-id="{{$image->media->id}}"><span class="label label-danger"><i class="fa fa-trash-o"></i> xóa</span></a><img class="img-thumbnail img-responsive imgItemInsert" src="{{$image->media->media_url_small}}"></div>
									@elseif($image->media->media_storage == "youtube")
										<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 itemFile" style="position:relative;">
											<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="{{$image->media->media_url}}" frameborder="0" allowfullscreen></iframe></div>
										</div>
									@elseif($image->media->media_storage == "video")
										<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 itemFile" style="position:relative;">
										<a href="" class="delMediaData" style="position:absolute; bottom:0px; right:0px;z-index:1; " data-id="{{$image->media->id}}"><span class="label label-danger"><i class="fa fa-trash-o"></i> xóa</span></a>
											<a href="{{$image->media->media_url}}" class="btnViewVideo"><span class="btnPlayVideoClickSmall"><i class="glyphicon glyphicon-play"></i></span><img class="img-thumbnail img-responsive" src="{{config('app.link_media').$image->media->media_path.'thumb/'.$image->media->media_id_random.'.png'}}" ></a>
										</div>
									@endif
								@endforeach
								@endif
							@endif
							</div>
							<div class="listFile">
							@if(!empty($post->id))
								@if(count($post->gallery)>0)
								@foreach ($post->gallery as $file)
									@if($file->media->media_storage == "files")
										<li class="list-group-item itemFile"><a href="{{config('app.link_media').$file->media->media_name}}"><i class="glyphicon glyphicon-download-alt"></i></a> <a href="" class="delMediaData" data-id="{{$file->media->id}}"><span class="label label-danger"><i class="fa fa-trash-o"></i> xóa</span></a>{{$file->media->media_name}}</li>
									@endif
								@endforeach
								@endif
							@endif
							</div>
						</div>
						
						<div id="location" class="mt5 tab-pane fade">
							<div class="row">
								<div class="col-sm-6">
									<input type="hidden" name="idRegion" value="@if(!empty($post->joinRegion->region->id)){{$post->joinRegion->region->id}}@else{{704}}@endif">
									<input type="hidden" name="regionIso" value="vn">
									<div class="addSelectRegion"></div>
									<div class="mb5"></div>
								</div>
								<div class="col-sm-6">
									<input type="hidden" name="idSubRegion" value="@if(!empty($post->joinSubRegion->subRegion->id)){{$post->joinSubRegion->subRegion->id}}@endif">
									<div class="addSelectSubRegion"></div>
									<div class="mb5"></div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<input type="hidden" name="idDistrict" value="@if(!empty($post->joinDistrict->district->id)){{$post->joinDistrict->district->id}}@endif">
									<div class="addSelectDistrict"></div>
									<div class="mb5"></div>
								</div>
								<div class="col-sm-6">
									<input type="hidden" name="idWard" value="@if(!empty($post->joinWard->ward->id)){{$post->joinWard->ward->id}}@endif">
									<div class="addSelectWard"></div>
									<div class="mb5"></div>
								</div>
							</div>
						</div>
						<div id="keyword" class="mt5 tab-pane fade">
							<div class="appendKeyWords">
								<div class="form-group addInput">
									<div class="input-group">
										<input type="text" value="" placeholder="Nhập từ khóa cần lấy ý tưởng..." name="inputKeyword" class="form-control">
										<span class="input-group-btn"><button type="button" class="btn btn-primary btnResearch"><i class="glyphicon glyphicon-search"></i> <span class="hidden-xs">lấy ý tưởng</span></button></span>
									</div>
								</div>
								<div class="form-group">
									<?php
										$valueKeyword='';
									?>
									@if(!empty($post->id))
										@if(count($post->joinKeywords)>0)
											@foreach($post->joinKeywords as $joinKeywords)
												<?
													$valueKeyword.=$joinKeywords->getKeyword->keyword.',';
												?>
											@endforeach
										@endif
									@endif
									<input name="tags" id="tags" class="form-control" value="{{$valueKeyword}}">
								</div>
							</div>
						</div>
						<div id="price" class="mt5 tab-pane fade">
							<div class="form-group">
								<label>Giá bán</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
									<input type="number" name="price" class="form-control" value="@if(!empty($post->price->posts_attribute_value)){{$post->price->posts_attribute_value}}@endif" placeholder="Vd: 500000">
									<div class="input-group-btn">
										<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">@if(!empty($channel['info']->channel_currency)){{$channel['info']->channelCurrency->currency_name}}@elseif(!empty($channel['info']->joinAddress[0]->address->joinRegion->region->currency_name)){{$channel['info']->joinAddress[0]->address->joinRegion->region->currency_name}}@else<span>VNĐ</span>@endif <span class="caret"></span></button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label>Giá giảm</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
									<input type="number" name="priceSale" class="form-control" value="" placeholder="Vd: 450000">
									<div class="input-group-btn">
										<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">@if(!empty($channel['info']->channel_currency)){{$channel['info']->channelCurrency->currency_name}}@elseif(!empty($channel['info']->joinAddress[0]->address->joinRegion->region->currency_name)){{$channel['info']->joinAddress[0]->address->joinRegion->region->currency_name}}@else<span>VNĐ</span>@endif <span class="caret"></span></button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label>Số lượng</label>
								<input type="number" name="quanlityProduct" class="form-control" value="" placeholder="Nhập số lượng...">
							</div>
						</div>
						<div id="contact" class="mt5 tab-pane fade">
							<?php
								if(!empty($post->id) && !empty($post->contact)){
									$postContact=json_decode($post->contact->value);
								} 
							?>
							<div class="form-group">
								<label>Họ tên(*)</label>
								<input type="text" name="postContact[]" data-type="contactName" class="form-control" value="@if(!empty($postContact->contactName)){{$postContact->contactName}}@endif" placeholder="Nhập họ tên...">
							</div>
							<div class="form-group">
								<label>Email</label>
								<input type="email" name="postContact[]" data-type="contactEmail" class="form-control" value="@if(!empty($postContact->contactEmail)){{$postContact->contactEmail}}@endif" placeholder="Địa chỉ Email">
							</div>
							<div class="form-group">
								<label>Số điện thoại</label>
								<input type="phone" name="postContact[]" data-type="contactPhone" class="form-control" value="@if(!empty($postContact->contactPhone)){{$postContact->contactPhone}}@endif" placeholder="Số điện thoại">
							</div>
							<div class="form-group">
								<label>Địa chỉ liên hệ</label>
								<input type="text" name="postContact[]" data-type="contactAddress" class="form-control" value="@if(!empty($postContact->contactAddress)){{$postContact->contactAddress}}@endif" placeholder="Địa chỉ liên hệ">
							</div>
						</div>
					</div>
				</div>
			<div class="form-group">
				<label class="error" for="postContent"></label>
				<textarea name="postContent" id="summernote" class="form-control" required>@if(!empty($post->posts_description)){{$post->posts_description}}@endif</textarea>
			</div>
			@if(Auth::check() && !empty(Auth::user()->joinOauthFacebook->access_token))
			<div class="form-group">
				<?
					$this->fb = new \Facebook\Facebook([
					  'app_id' => env('FACEBOOK_APP_ID'),
					  'app_secret' => env('FACEBOOK_APP_SECRET'),
					  'default_graph_version' => 'v3.0',
					]); 
					$me = $this->fb->get('me',
						Auth::user()->joinOauthFacebook->access_token); 
					$pages = $this->fb->get('me/accounts',
						Auth::user()->joinOauthFacebook->access_token); 
					//dd($me->getDecodedBody()); 
					$dataPage=$pages->getDecodedBody(); 
				?>
				<div class="ckbox ckbox-success"><input type="checkbox" class="filled-in" name="checkPostToFacebook" value="facebook" id="checkPostToFacebook" checked><label for="checkPostToFacebook"> Đăng lên facebook</label></div>
				<select class="selectPage" data-placeholder="Chọn trang để đăng..." name="selectPostToFacebook">
					<option value="{{$me->getDecodedBody()['id']}}" selected>{{$me->getDecodedBody()['name']}}</option>
				  @foreach($dataPage['data'] as $page) 
					<option value="{{$page['id']}}">{{$page['name']}}</option>
				  @endforeach
				 </select>
			</div>
			@endif
			<div class="form-group text-right">
				@if(!empty($post->id))<a class="btn btn-danger postDelete" href="#" style="margin-right:20px; "><i class="glyphicon glyphicon-trash"></i> xóa</a> @endif
				<button class="btn btn-primary" type="submit" name="savePost" id="savePost"><i class="glyphicon glyphicon-ok"></i> Lưu</button> 
			</div>
		</form>
	</div>

</div><!-- mainpanel -->
</section>
<?
	if(!empty($post->id)){
		if($channel['info']->channel_parent_id==0){
			if(count($post->postsJoinField)>0){
				$defaultSelect=[]; 
				foreach($post->postsJoinField as $key=>$cat){
					$defaultSelect[$key]['id']=$cat->getField->id; 
					$defaultSelect[$key]['text']=$cat->getField->name; 
				}
				$defaultSelectJson=json_encode($defaultSelect); 
			}else{
				$defaultSelectJson="[]"; 
			}
		}else{
			if(count($post->postsJoinCategory)>0){
				$defaultSelect=[]; 
				foreach($post->postsJoinCategory as $key=>$cat){
					$defaultSelect[$key]['id']=$cat->getCategory->id; 
					$defaultSelect[$key]['text']=$cat->getCategory->category_name; 
				}
				$defaultSelectJson=json_encode($defaultSelect); 
			}else{
				$defaultSelectJson="[]"; 
			}
		}
	}else{
		$defaultSelectJson="[]"; 
	}
	$dependencies = array(); 
	if($channel['info']->channel_parent_id==0){
		$channel['theme']->asset()->writeScript('customCategoryMaster','
			jQuery(document).ready(function(){
				"use strict"; 
				function getCategory(){
					$(".addCategoryForm").empty(); 
					$(".addCategoryForm").show(); 
					$.ajax({
						url: "'.route("field.get.select",$channel["domainPrimary"]).'",
						type: "GET",
						dataType: "html",
						success: function (data) {
							$(".addCategoryForm").append("<div class=\"form-group\"><select class=\"\" data-placeholder=\"Chọn danh mục...\" name=\"categoryId\" id=\"categoryId\" multiple required>"+data+"</select></div>"); 
							jQuery(".addCategoryForm #categoryId").select2({
								width: "100%"
							}); 
							$(".addCategoryForm #categoryId").data().select2.updateSelection('.$defaultSelectJson.'); 
						}
					});
				} 
				getCategory(); 
			});
		', $dependencies);
	}else{
		$channel['theme']->asset()->writeScript('customCategorySite','
			jQuery(document).ready(function(){
				"use strict"; 
				function getCategory(){
					$(".addCategoryForm").empty(); 
					$(".addCategoryForm").show(); 
					$.ajax({
						url: "'.route("channel.category.select",$channel["domainPrimary"]).'",
						type: "GET",
						dataType: "html",
						success: function (data) {
							$(".addCategoryForm").append("<div class=\"form-group\"><select class=\"\" data-placeholder=\"Chọn danh mục...\" name=\"categoryId\" id=\"categoryId\" multiple required>"+data+"</select></div>"); 
							jQuery(".addCategoryForm #categoryId").select2({
								width: "100%"
							}); 
							$(".addCategoryForm #categoryId").data().select2.updateSelection('.$defaultSelectJson.'); 
						}
					});
				} 
				getCategory(); 
			});
		', $dependencies);
	}
	$channel['theme']->asset()->writeScript('customJsUser','
		jQuery(document).ready(function(){
			"use strict"; 
			$(window).bind("beforeunload",function(){
				return "are you sure you want to leave?";
			}); 
			var $validator = jQuery("#postForm").validate({
				highlight: function(element) {
				  jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
				},
				success: function(element) {
				  jQuery(element).closest(".form-group").removeClass("has-error");
				}
			}); 
			function formatSelectFacebook(icon) {
				var originalOption = icon.element;
				return "<i class=\"fa fa-facebook-square\"></i> " + icon.text;
			}
			// Select2
			jQuery(".selectPage").select2({
				width: "100%",
				formatResult: formatSelectFacebook
			}); 
			var tag=jQuery(".appendKeyWords #tags").tagsInput({
				placeholderColor:"#999",
				width:"auto",
				height:"auto",
				"defaultText":"thêm từ..."
			}); 
			getRegions(); 
			$(".addSelectRegion").on("change",".selectRegion",function() {
				getSubregion($(this).val()); 
				getDistrict(0); 
				getWard(0); 
				$("input[name=regionIso]").val($(this).find("option:selected").attr("data-iso")); 
				$("input[name=idRegion]").val($(this).val()); 
				$("input[name=idSubRegion]").val(0); 
				$("input[name=idDistrict]").val(0); 
			});
			$(".addSelectSubRegion").on("change",".selectSubRegion",function() {
				getDistrict($(this).val()); 
				getWard(0);
				$("input[name=idSubRegion]").val($(this).val()); 
				$("input[name=idDistrict]").val(0); 
			});
			$(".addSelectDistrict").on("change",".selectDistrict",function() {
				getWard($(this).val()); 
				$("input[name=idDistrict]").val($(this).val()); 
				$("input[name=idWard]").val(0); 
			});
			$(".addSelectWard").on("change",".selectWard",function() {
				$("input[name=idWard]").val($(this).val()); 
			});
			function getRegions(){
				$(".addSelectRegion").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải quốc gia, vui lòng chờ...</small></div>"); 
				$.ajax({
					url: "'.route("regions.json.list",$channel["domain"]->domain).'",
					type: "GET",
					dataType: "json",
					success: function (result) {
						$(".addSelectRegion .loading").empty(); 
						if(result.success==true){
							getSubregion($("input[name=idRegion]").val()); 
							$(".addSelectRegion").append("<div class=\"input-group\"><span class=\"input-group-addon\"><i class=\"flag flag-"+$("input[name=regionIso]").val()+"\"></i></span><select class=\"selectRegion\" data-placeholder=\"Chọn quốc gia...\" name=\"channelRegion\" required>"
							+"<option value=\"\"></option></select></div><label class=\"error\" for=\"channelRegion\"></label>"); 
							$.each(result.region, function(i, item) {
								if(item.id==$("input[name=idRegion]").val()){
									$(".addSelectRegion .selectRegion").append("<option value="+item.id+" data-icon=\"flag-"+item.iso.toLowerCase()+"\"  data-iso="+item.iso.toLowerCase()+" selected>"+item.country+"</option>");
								}else{
									$(".addSelectRegion .selectRegion").append("<option value="+item.id+"  data-icon=\"flag-"+item.iso.toLowerCase()+"\"  data-iso="+item.iso.toLowerCase()+" >"+item.country+"</option>");
								}
							}); 
							function format(icon) {
								var originalOption = icon.element;
								return "<i class=\"flag " + $(originalOption).data("icon") + "\"></i> " + icon.text;
							}
							jQuery(".addSelectRegion .selectRegion").select2({
								width: "100%",
								formatResult: format
							});
						}else{
							
						}
					}
				});
			} 
			function getSubregion(idRegion){
				$(".addSelectSubRegion").empty(); 
				$(".addSelectSubRegion").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải thành phố, vui lòng chờ...</small></div>"); 
				var formData = new FormData();
				formData.append("idRegion", idRegion); 
				$.ajax({
					url: "'.route("subregion.json.list.post",$channel["domain"]->domain).'",
					type: "POST",
					cache: false,
					contentType: false,
					processData: false,
					dataType:"json",
					data:formData,
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					success: function (result) {
						$(".addSelectSubRegion .loading").empty(); 
						$(".addSelectRegion .input-group-addon").html("<i class=\"flag flag-"+$("input[name=regionIso]").val()+"\"></i>"); 
						if(result.success==true){
							getDistrict($("input[name=idSubRegion]").val()); 
							$(".addSelectSubRegion").append("<select class=\"selectSubRegion\" data-placeholder=\"Chọn thành phố...\" name=\"channelSubRegion\">"
							+"<option value=\"\"></option></select><label class=\"error\" for=\"channelSubRegion\"></label>"); 
							$.each(result.subregion, function(i, item) {
								if(item.id==$("input[name=idSubRegion]").val()){
									$(".addSelectSubRegion .selectSubRegion").append("<option value="+item.id+" selected>"+item.subregions_name+"</option>");
								}else{
									$(".addSelectSubRegion .selectSubRegion").append("<option value="+item.id+">"+item.subregions_name+"</option>");
								}
							}); 
							function format(icon) {
								var originalOption = icon.element;
								return "<i class=\"fa fa-map-marker\"></i> " + icon.text;
							}
							jQuery(".addSelectSubRegion .selectSubRegion").select2({
								width: "100%",
								formatResult: format
							});
						}else{
							$(".addSelectSubRegion").empty(); 
						}
					}
				});
			}
			function getDistrict(idSubRegion){
				$(".addSelectDistrict").empty(); 
				$(".addSelectDistrict").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải quận huyện, vui lòng chờ...</small></div>"); 
				var formData = new FormData();
				formData.append("idSubRegion", idSubRegion); 
				$.ajax({
					url: "'.route("district.json.list.post",$channel["domain"]->domain).'",
					type: "POST",
					cache: false,
					contentType: false,
					processData: false,
					dataType:"json",
					data:formData,
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					success: function (result) {
						$(".addSelectDistrict .loading").empty(); 
						if(result.success==true){
							getWard($("input[name=idDistrict]").val()); 
							$(".addSelectDistrict").append("<select class=\"selectDistrict\" data-placeholder=\"Chọn quận huyện...\" name=\"channelDistrict\">"
								+"<option value=\"\"></option></select><label class=\"error\" for=\"channelDistrict\"></label>"); 
							$.each(result.district, function(i, item) {
								if(item.id==$("input[name=idDistrict]").val()){
									$(".addSelectDistrict .selectDistrict").append("<option value="+item.id+" selected>"+item.district_name+"</option>");
								}else{
									$(".addSelectDistrict .selectDistrict").append("<option value="+item.id+">"+item.district_name+"</option>");
								}
							}); 
							function format(icon) {
								var originalOption = icon.element;
								return "<i class=\"fa fa-map-marker\"></i> " + icon.text;
							}
							jQuery(".addSelectDistrict .selectDistrict").select2({
								width: "100%",
								formatResult: format
							});
						}else{
							$(".addSelectDistrict").empty(); 
						}
					}
				});
			}
			function getWard(idDistrict){
				$(".addSelectWard").empty(); 
				$(".addSelectWard").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải phường xã, vui lòng chờ...</small></div>"); 
				var formData = new FormData();
				formData.append("idDistrict", idDistrict); 
				$.ajax({
					url: "'.route("ward.json.list.post",$channel["domain"]->domain).'",
					type: "POST",
					cache: false,
					contentType: false,
					processData: false,
					dataType:"json",
					data:formData,
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					success: function (result) {
						//console.log(result); 
						$(".addSelectWard .loading").empty(); 
						if(result.success==true){
							$(".addSelectWard").append("<select class=\"selectWard\" data-placeholder=\"Chọn phường xã...\" name=\"channelWard\">"
								+"<option value=\"\"></option></select><label class=\"error\" for=\"channelWard\"></label>"); 
							$.each(result.ward, function(i, item) {
								if(item.id==$("input[name=idWard]").val()){
									$(".addSelectWard .selectWard").append("<option value="+item.id+" selected>"+item.ward_name+"</option>");
								}else{
									$(".addSelectWard .selectWard").append("<option value="+item.id+">"+item.ward_name+"</option>");
								}
							}); 
							function format(icon) {
								var originalOption = icon.element;
								return "<i class=\"fa fa-map-marker\"></i> " + icon.text;
							}
							jQuery(".addSelectWard .selectWard").select2({
								width: "100%",
								formatResult: format
							});
						}else{
							$(".addSelectWard").empty(); 
						}
					}
				});
			}
			$(".btnKeyReSearch").click(function(){
				if($(".btnKeyReSearch").hasClass("label-primary")){
					$(".btnKeyReSearch").removeClass("label-primary").addClass("label-default"); 
					$(".appendKeyWords").removeClass("hidden"); 
					var postTitle=$("input[name=postTitle]").val(); 
					$(".appendKeyWords input[name=inputKeyword]").val(); 
					
				}else{
					$(".btnKeyReSearch").removeClass("label-default").addClass("label-primary"); 
					$(".appendKeyWords").addClass("hidden"); 
				}
			}); 
			$(".btnResearch").click(function(){
				var inputKeyword=$(".appendKeyWords input[name=inputKeyword]").val(); 
				if(inputKeyword.length){ 
					$(".btnResearch").addClass("disabled"); 
					$(".btnResearch").css("position","relative"); 
					$(".btnResearch").append("<div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div>"); 
					$.ajax({
						url: "//suggestqueries.google.com/complete/search?client=chrome&q="+encodeURIComponent(inputKeyword),
						type: "GET", 
						dataType: "jsonp",
						success: function(result){
							console.log(result); 
							if(result[1].length<=0){
								jQuery.gritter.add({
									title: "Thông báo!",
									text: "Không tìm thấy từ khóa liên quan nào đến "+result[0], 
									class_name: "growl-warning",
									sticky: false,
									time: ""
								});
							}else{
								$.each(result[1], function( index, value ) {
									//tag.tagsInput("add", {label:"123"}); 
									tag.addTag(value);
								}); 
							}
							//$(".appendKeyWords #tags").val(result[1].toString()); 
							//tag.tagsInput("add", {label:"123"});
							$(".btnResearch #preloaderInBox").remove(); 
							$(".btnResearch").removeClass("disabled"); 
						},
						error: function(result) {
							jQuery.gritter.add({
								title: "Thông báo!",
								text: "Không thể sử dụng tính năng này!", 
								class_name: "growl-danger",
								sticky: false,
								time: ""
							});
							$(".btnResearch #preloaderInBox").remove(); 
							$(".btnResearch").removeClass("disabled"); 
						}
					}); 
				}else{
					jQuery.gritter.add({
						title: "Thông báo!",
						text: "Bạn phải nhập từ khoá trước khi sử dụng tính năng này!", 
						class_name: "growl-danger",
						sticky: false,
						time: ""
					});
					$(".btnResearch #preloaderInBox").remove(); 
					$(".btnKeyReSearch").removeClass("disabled"); 
				}
			}); 
			$(".btnKeyReSearch").click(function(){
				$(".appendKeyWords .addKeyWord").remove(); 
				var postTitle=$("input[name=postTitle]").val(); 
				var keywords=[]; 
				$(".btnKeyReSearch").addClass("disabled"); 
				$(".btnKeyReSearch").css("position","relative"); 
				$(".btnKeyReSearch").append("<div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div>"); 
				if($(".appendKeyWords .addInput").length<=0){
					$(".appendKeyWords").append("<div class=\"form-group addInput\"><div class=\"input-group\"><input type=\"text\" value=\""+postTitle+"\" name=\"inputKeyword\" class=\"form-control\"><span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-primary\"><i class=\"glyphicon glyphicon-search\"></i> <span class=\"hidden-xs\">lấy ý tưởng</span></button></span></div></div>"); 
				}
				var inputKeyword=$(".appendKeyWords input[name=inputKeyword]").val(); 
				if(inputKeyword.length) { 
					$(".appendKeyWords").append("<div class=\"addKeyWord\"></div>"); 
					 $.ajax({
						url: "http://suggestqueries.google.com/complete/search?client=chrome&q="+encodeURIComponent(inputKeyword),
						type: "GET", 
						dataType: "jsonp",
						success: function(result){
							console.log(result); 
							if(result[1].length<=0){
								jQuery.gritter.add({
									title: "Thông báo!",
									text: "Không tìm thấy từ khóa liên quan nào đến "+result[0], 
									class_name: "growl-warning",
									sticky: false,
									time: ""
								});
							}
							$(".appendKeyWords .addKeyWord").append("<div class=\"form-group\"><input name=\"tags\" id=\"tags\" class=\"form-control\" value=\""+result[1].toString()+"\" /></div>"); 
							jQuery(".appendKeyWords #tags").tagsInput({width:"auto",height:"auto","defaultText":"từ khóa..."}); 
							$(".btnKeyReSearch #preloaderInBox").remove(); 
							$(".btnKeyReSearch").removeClass("disabled"); 
						},
						error: function(result) {
							jQuery.gritter.add({
								title: "Thông báo!",
								text: "Không thể sử dụng tính năng này!", 
								class_name: "growl-danger",
								sticky: false,
								time: ""
							});
							$(".btnKeyReSearch #preloaderInBox").remove(); 
							$(".btnKeyReSearch").removeClass("disabled"); 
						}
					}); 
				}else{
					jQuery.gritter.add({
						title: "Thông báo!",
						text: "Bạn cần phải nhập tiêu đề trước khi sử dụng tính năng này!", 
						class_name: "growl-warning",
						sticky: false,
						time: ""
					});
					$(".btnKeyReSearch #preloaderInBox").remove(); 
					$(".btnKeyReSearch").removeClass("disabled"); 
				}
			}); 
			$(".btnViewStaticts").click(function(){
				$(window).unbind("beforeunload"); 
			}); 
			$("#btnAddNewCategoryPost").click(function(){
				$("#btnAddNewCategoryPost").addClass("disabled"); 
				$("#btnAddNewCategoryPost").css("position", "relative"); 
				$("#btnAddNewCategoryPost").append("<div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div>"); 
				$(".addCategoryForm").hide(); 
				$(".addNewCategoryElementPost").show();  
				$(".addNewCategoryElementPost").empty(); 
				$(".addNewCategoryElementPost").append("<form id=\"addCategoryForm\"></form>"); 
				$.ajax({
					url: "'.route("channel.category.select",$channel["domain"]->domain).'",
					type: "GET",
					dataType: "html",
					success: function (data) {
						$("#btnAddNewCategoryPost").removeClass("disabled"); 
						$("#btnAddNewCategoryPost #preloaderInBox").remove(); 
						$(".addNewCategoryElementPost #addCategoryForm").append("<div class=\"form-group\">"
							+"<input type=\"text\" name=\"category\" value=\"\" class=\"form-control\" placeholder=\"Nhập tên danh mục...\" required />"
							+"<label class=\"error\" for=\"category\"></label></div>");
						$(".addNewCategoryElementPost #addCategoryForm").append("<div class=\"form-group\">"
							+"<textarea class=\"form-control\" type=\"textarea\" name=\"categoryDescription\" placeholder=\"Mô tả tên danh mục...\" /></textarea>"
							+"<label class=\"error\" for=\"categoryDescription\"></label></div>");
						$(".addNewCategoryElementPost #addCategoryForm").append("<div class=\"form-group\"><select class=\"form-control\" name=\"categoryParentId\" id=\"categoryParentId\">"+data+"</select></div>"); 
						$(".addNewCategoryElementPost #addCategoryForm").append("<div class=\"pull-right\"><button type=\"submit\" class=\"btn btn-xs btn-primary btnSaveCategory\" data-id=\"\"><i class=\"fa fa-check\"></i> Lưu</button> <button type=\"button\" class=\"btn btn-xs btn-default btnCancelCategory\">Hủy</button> </div>");
						var $validator = jQuery(".addNewCategoryElementPost #addCategoryForm").validate({
							highlight: function(element) {
							  jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
							},
							success: function(element) {
							  jQuery(element).closest(".form-group").removeClass("has-error");
							}
						});
						$(".addNewCategoryElementPost .btnSaveCategory").click(function(){
							var $valid = jQuery(".addNewCategoryElementPost #addCategoryForm").valid();
							if(!$valid) {
								$validator.focusInvalid();
								return false;
							}else{
								$(".addNewCategoryElementPost").append("<div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div>"); 
								var formData = new FormData();
								formData.append("categoryName", $(".addNewCategoryElementPost #addCategoryForm input[name=category]").val()); 
								formData.append("categoryDescription", $(".addNewCategoryElementPost #addCategoryForm textarea[name=categoryDescription]").val()); 
								formData.append("parentId", $(".addNewCategoryElementPost #addCategoryForm select[name=categoryParentId]").val()); 
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
											getCategory(); 
											$(".addNewCategoryElementPost #preloaderInBox").remove();  
											$(".addNewCategoryElementPost").empty(); 
											$(".addNewCategoryElementPost").hide(); 
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
			$(".addNewCategoryElementPost").on("click",".btnCancelCategory",function() { 
				$(".addCategoryForm").show(); 
				var addNewCategoryElementPost=$(this).parent().closest(".addNewCategoryElementPost"); 
				addNewCategoryElementPost.hide(); 
				addNewCategoryElementPost.empty(); 
			}); 
			$(".postDelete").click(function(){
				if(confirm("Bạn có chắc muốn xóa?")){
					$(window).unbind("beforeunload"); 
					var formData = new FormData();
					formData.append("postId", $("#postForm input[name=postId]").val()); 
					$.ajax({
						url: "'.route("channel.post.delete",$channel["domain"]->domain).'",
						headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
						type: "post",
						cache: false,
						contentType: false,
						processData: false,
						data: formData,
						dataType:"json",
						success:function(result){
							//console.log(result); 
							window.location.href="'.route("channel.trash",$channel["domain"]->domain).'"; 
						},
						error: function(result) {
						}
					});
				}
			}); 
			function uniqId() {
			  return Math.round(new Date().getTime() + (Math.random() * 100));
			}
			$("#postMedia").on("change", function (e) {
				e.preventDefault();
				var files = $("#postMedia").prop("files");  
				var totalFile=files.length; 
				if(totalFile>0){
					for(var i=0;i<totalFile;i++)
					{
						var id=uniqId(); 
						$(".fileMedia").css("position", "relative"); 
						$(".fileMedia").append("<div class=\"col-lg-2 col-md-2 col-sm-3 col-xs-4 itemFile itemId-"+id+"\" style=\"position:relative;min-height:70px; \"><div id=\"preloaderInBox\"><span class=\"label label-primary\"></span><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div></div>"); 
						var formData = new FormData(); 
						var itemId=id; 
						formData.append("file", files[i]); 
						formData.append("itemId", id); 
						var xhrRequest = $.ajax({
							xhr: function()
							{
								var xhr = new window.XMLHttpRequest();
								//Upload progress
								xhr.upload.addEventListener("progress", function(evt){
								  if (evt.lengthComputable) {
									var percentComplete = evt.loaded / evt.total;
									$(".fileMedia .itemId-"+id+" #preloaderInBox span").text(parseInt((100 * evt.loaded / evt.total)) + "%"); 
									if(percentComplete==1){
										$(".fileMedia .itemId-"+id+" #preloaderInBox span").text("Đang xử lý..."); 
									}
								  }
								}, false);
								//Download progress
								xhr.addEventListener("progress", function(evt){
								  if (evt.lengthComputable) {
									var percentComplete = evt.loaded / evt.total;
									//Do something with download progress
									console.log(percentComplete);
								  }
								}, false);
								return xhr;
							},
							url: "'.route("upload.tmp",$channel["domain"]->domain).'",
							headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
							type: "post",
							cache: false,
							contentType: false,
							processData: false,
							data: formData,
							dataType:"json",
							success:function(result){ 
								if(result.success==true){
									$("#postMedia").val(""); 
									if(result.mimeType=="image"){
										$(".fileMedia .itemId-"+result.itemId).append("<a href=\"\" class=\"delMedia imgItemInsert\" data-mediaIdRandom=\"\" data-file=\""+result.file_tmp+"\" data-type=\"image\" data-destinationPath=\""+result.destinationPath+"\" style=\"position:absolute; bottom:0px; right:0px;\"><span class=\"label label-danger\"><i class=\"fa fa-trash-o\"></i> xóa</span></a><img class=\"img-thumbnail img-responsive\" src=\""+result.url_small+"\" >"); 
										$(".fileMedia .itemId-"+result.itemId+" #preloaderInBox").remove(); 
									}else if(result.mimeType=="video"){
										var urlMedia="'.config('app.link_media').'";
										var urlThumb=urlMedia+result.destinationPath+"video/"+result.media_id_random+".png";
										$(".fileMedia .itemId-"+result.itemId).append("<a href=\"\" class=\"imgItemInsert delMedia\" data-mediaIdRandom=\""+result.media_id_random+"\" data-file=\""+result.file_tmp+"\"  data-type=\"video\" data-destinationPath=\""+result.destinationPath+"\" style=\"position:absolute; bottom:0px; right:0px;z-index:1;\"><span class=\"label label-danger\"><i class=\"fa fa-trash-o\"></i> xóa</span></a><a href=\""+result.url+"\" class=\"btnViewVideo\"><span class=\"btnPlayVideoClickSmall\"><i class=\"glyphicon glyphicon-play\"></i></span><img class=\"img-thumbnail img-responsive\" src=\""+urlThumb+"\" ></a>"); 
										$(".fileMedia .itemId-"+result.itemId+" #preloaderInBox").remove(); 
									}else if(result.mimeType=="files"){
										
									}
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
									text: "Lỗi không thể tải file, vui lòng thử lại! ", 
									class_name: "growl-danger",
									sticky: false,
									time: ""
								});
							}
						}); 
					}
				}
			}); 
			$(".fileMedia").on("click",".btnViewVideo",function() {
				var videoUrl=$(this).attr("href"); 
				$("#myModal .modal-header").empty(); 
				$("#myModal .modal-title").empty(); 
				$("#myModal .modal-body").empty(); 
				$("#myModal .modal-footer").empty(); 
				$("#myModal .modal-body").addClass("nopadding"); 
				$("#myModal .modal-body").append("<div align=\"center\" class=\"\"><video class=\"img-responsive\" controls autoplay><source src=\""+videoUrl+"\" type=\"video/mp4\">Your browser does not support the video tag.</video></div>"); 
				$("#myModal .modal-footer").append("<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Đóng</button>"); 
				$("#myModal").modal("show"); 
				return false; 
			});
			$(".fileMedia").on("click",".delMediaData",function() {
				if(confirm("Bạn có chắc muốn xóa?")){
					var mediaId= $(this).attr("data-id"); 
					var formData = new FormData();
					formData.append("mediaId", mediaId); 
					formData.append("postId", $("#postForm input[name=postId]").val()); 
					formData.append("postAttributeType", "gallery"); 
					$(this).parent().closest(".itemFile").remove(); 
					$(".fileMedia").append("<div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div>"); 
					$.ajax({ 
						url: "'.route("post.attribute.media.delete",$channel["domain"]->domain).'",
						type: "post", 
						headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
						cache: false,
						contentType: false,
						processData: false,
						data: formData,
						dataType:"json",
						success:function(result){
							$(".fileMedia #preloaderInBox").css("display", "none"); 
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-success",
								sticky: false,
								time: ""
							});
						},
						error: function(result) {
						}
					}); 
				}
				return false; 
			});
			$(".fileMedia").on("click",".delMedia",function() {
				$(this).parent().closest(".itemFile").remove(); 
				var formData = new FormData();
				formData.append("fileTmp", $(this).attr("data-file")); 
				formData.append("fileType", $(this).attr("data-type"));  
				formData.append("mediaIdRandom", $(this).attr("data-mediaIdRandom"));
				formData.append("destinationPath", $(this).attr("data-destinationPath")); 
				$.ajax({
					url: "'.route("tmp.delete",$channel["domain"]->domain).'",
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					type: "post",
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					dataType:"json",
					success:function(result){ 
						//console.log(result); 
					},
					error: function(result) {
					}
				}); 
				return false; 
			}); 
			$( "#addAttribute" ).click(function() {
				$("#myModal .modal-title").empty(); 
				$("#myModal .modal-body").empty(); 
				$("#myModal .modal-footer").empty(); 
				$("#myModal .modal-title").text("Thêm thuộc tính"); 
				$("#myModal .modal-body").append(""
				+"<div class=\"form-group\">"
					+"<button class=\"btn btn-xs btn-primary\" id=\"addAttributePrice\"><i class=\"glyphicon glyphicon-plus\"></i> Thêm giá</button>"
				+"</div>"); 
				$("#myModal .modal-footer").append("<div class=\"text-right\"><button type=\"button\" class=\"btn btn-primary\" id=\"btnSaveAttribute\"><i class=\"glyphicon glyphicon-ok\"></i> Lưu</button></div>"); 
				$("#myModal").modal("show"); 
				return false; 
			}); 
			$("#myModal").on("click","#addAttributePrice",function() {
				var attributePrice = $("#myModal").find("#attributePrice"); 
				if(attributePrice.length <= 0){
					$("#myModal .modal-body").append(""
					+"<div class=\"form-group\" id=\"attributePrice\">"
						+"<div class=\"\"><code><i>Giá không khoảng cách, không ký tự. Vd: 500000 (=500.000VND)</i></code></div>"
						+"<div class=\"input-group\">"
							+"<span class=\"input-group-addon\">VND</span>"
							+"<input type=\"number\" name=\"attributePrice\" class=\"form-control\" value=\"\" placeholder=\"Vd: 500000\">"
							+"<span class=\"input-group-addon\"><button type=\"button\" class=\"btn btn-xs btn-danger\" id=\"delElementPrice\" style=\"padding:0px 5px;\"><i class=\"glyphicon glyphicon-remove\"></i></button></span>"
						+"</div>"
					+"</div>");
				}
			}); 
			$("#myModal").on("click","#btnSaveAttribute",function() {
				$("#postForm .addAttributePrice").empty(); 
				var attributePrice=$("#myModal #attributePrice input[name=attributePrice]").val(); 
				if(attributePrice){
					$("#postForm .addAttributePrice").append("<span class=\"label label-success labelPrice\"><strong>"+parseInt(attributePrice).toLocaleString()+"<sup>VND</sup></strong> <a href=\"\" class=\"delPrice text-danger\"><i class=\"fa fa-times fa-1\" aria-hidden=\"true\"></i></a></span><input type=\"hidden\" name=\"price\" value=\""+attributePrice+"\">"); 
					$("#myModal").modal("hide"); 
				}else{
					jQuery.gritter.add({
						title: "Thông báo!",
						text: "Chưa nhập giá! ", 
						class_name: "growl-danger",
						sticky: false,
						time: ""
					});
				}
			}); 
			$("#postForm").on("click",".delPrice",function() {
				$(this).parent().closest(".labelPrice").remove(); 
				return false; 
			}); 
			$("#postForm").on("click",".delPriceData",function() {
				var formData = new FormData();
				formData.append("postId", $("#postForm input[name=postId]").val()); 
				$.ajax({
					url: "'.route("channel.post.attribute.delete.price",$channel["domain"]->domain).'",
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					type: "post",
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					dataType:"json",
					success:function(result){ 
						if(result.success==true){
							$("#postForm .addAttributePrice").empty(); 
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-success",
								sticky: false,
								time: ""
							});
						}
					},
					error: function(result) {
					}
				});
				return false; 
			});
			$("#postForm").on("click","#addOptions",function() {
				return false; 
			}); 
			$("#postForm #summernote").summernote(
				{
					popover: {
						image: [
							["custom", ["imageAttributes"]],
							["imagesize", ["imageSize100", "imageSize50", "imageSize25"]],
							["float", ["floatLeft", "floatRight", "floatNone"]],
							["remove", ["removeMedia"]]
						  ],
						  link: [
							["link", ["linkDialogShow", "unlink"]]
						  ],
						  air: [
							["color", ["color"]],
							["font", ["bold", "underline", "clear"]],
							["para", ["ul", "paragraph"]],
							["table", ["table"]],
							["insert", ["link", "picture"]]
						  ]
					},
					lang: "vi-VN", 
					imageAttributes:{
						imageDialogLayout:"default", // default|horizontal
						icon:"<i class=\"note-icon-pencil\"/>",
						removeEmpty:false // true = remove attributes | false = leave empty if present
					},
					displayFields:{
						imageBasic:true,  // show/hide Title, Source, Alt fields
						imageExtra:false, // show/hide Alt, Class, Style, Role fields
						linkBasic:true,   // show/hide URL and Target fields for link
						linkExtra:false   // show/hide Class, Rel, Role fields for link
					},
					placeholder: "Bạn đang viết gì? ", 
					dialogsInBody: true, 
					focus: true,
					minHeight: 150,   //set editable area"s height 
					enterHtml: "<br>",
					//height:250,
					//minHeight:null,
					//maxHeight:null,
					toolbar: [
						["font", ["bold", "italic", "underline", "clear"]],
						["fontname", ["fontname","style"]],
						["color", ["color"]],
						["table", ["table"]],
						["para", ["ul", "ol", "paragraph"]],
						["height", ["height"]],
						["insert", ["link", "picture", "video", "hr"]],
						["view", ["fullscreen", "codeview"]]
					],
					codemirror: { // codemirror options
						theme: "monokai"
					}, 
					callbacks: {
						onImageUpload: function (files){
								uploadImage(files[0]);
						}
					}
			});
			function uploadImage(file,editor,welEditable) {
				$(".add_message_summernote" ).empty(); 
				var postTitle=$("input[name=postTitle]").val(); 
				var postContent=$("textarea[name=postContent]").val(); 
				if(postTitle.length) {
					var formData = new FormData(); 
					formData.append("file", file); 
					formData.append("postId", $("#postForm input[name=postId]").val()); 
					formData.append("postTitle", postTitle); 
					formData.append("postContent", postContent); 
					$.ajax({
						url: "'.route("channel.upload.file",$channel["domain"]->domain).'",
						headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")}, 
						type: "post",
						cache: false,
						contentType: false,
						processData: false,
						data: formData,
						dataType:"json",
						success:function(result){
							console.log(result); 
							if(result.success==true){
								if(result.MimeType.match("image.*")){
									$("#summernote").summernote("editor.insertImage", result.url, function ($image) {
										$image.addClass("imageShow"); 
										$image.attr("url-lg", result.url);
									});
								}else if(result.MimeType.match("video.*")){
									
								}else if(result.media_storage=="files"){
									
								}
								jQuery.gritter.add({
									title: "Thông báo!",
									text: result.message, 
									class_name: "growl-success",
									sticky: false,
									time: ""
								});
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
				}else{
					jQuery.gritter.add({
						title: "Thông báo!",
						text: "Vui lòng nhập tiêu đề bài viết trước khi tải Ảnh hay Video", 
						class_name: "growl-danger",
						sticky: false,
						time: ""
					});
				}
			}
			$("#savePost").on("click", function () {
				var $valid = jQuery("#postForm").valid();
				if(!$valid) {
					$validator.focusInvalid();
					return false;
				}else{
					$("#postForm").css("position", "relative"); 
					$("#postForm").append("<div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div>"); 
					var arr = [];
					$(".fileMedia .imgItemInsert").each(function() {
						if($(this).attr("data-type")=="image"){
							var fileTmp=$(this).attr("data-file"); 
							var mediaIdRandom=$(this).attr("data-mediaIdRandom"); 
							var destinationPath=$(this).attr("data-destinationPath"); 
							var item={"type":"image","fileTmp":fileTmp,"mediaIdRandom":mediaIdRandom,"destinationPath":destinationPath}; 
							arr.push(item);
						}else if($(this).attr("data-type")=="video"){
							var fileTmp=$(this).attr("data-file"); 
							var mediaIdRandom=$(this).attr("data-mediaIdRandom"); 
							var destinationPath=$(this).attr("data-destinationPath"); 
							var item={"type":"video","fileTmp":fileTmp,"mediaIdRandom":mediaIdRandom,"destinationPath":destinationPath}; 
							arr.push(item);
						}else if($(this).attr("data-type")=="files"){
							var fileTmp=$(this).attr("data-file"); 
							var destinationPath=$(this).attr("data-destinationPath"); 
							var item={"type":"files","fileTmp":fileTmp,"destinationPath":destinationPath}; 
							arr.push(item);
						}
					}); 
					var mediaJson=JSON.stringify(arr); 
					var idRegion=$("input[name=idRegion]").val(); 
					var idSubRegion=$("input[name=idSubRegion]").val(); 
					var idDistrict=$("input[name=idDistrict]").val(); 
					var idWard=$("input[name=idWard]").val(); 
					var dataContactJson={};
					$.each($("input[name=\"postContact[]\"]"), function(i,item){ 
						dataContactJson[$(this).attr("data-type")] = item.value; 
					});
					var dataContact=JSON.stringify(dataContactJson); 
					var postTitle=$("input[name=postTitle]").val(); 
					var categoryId=$("select[name=categoryId]").select2("val"); 
					var postContent=$("textarea[name=postContent]").val(); 
					var price=$("input[name=price]").val(); 
					var quanlityProduct=$("input[name=quanlityProduct]").val(); 
					var keywords=$(".appendKeyWords #tags").val(); 
					var formData = new FormData();
					formData.append("postId", $("input[name=postId]").val()); 
					formData.append("postTitle", postTitle); 
					formData.append("postContent", postContent); 
					formData.append("categoryId", categoryId); 
					formData.append("medias", mediaJson); 
					formData.append("price", price); 
					formData.append("quanlityProduct", quanlityProduct); 
					formData.append("keywords", keywords); 
					formData.append("idRegion", idRegion); 
					formData.append("idSubRegion", idSubRegion); 
					formData.append("idDistrict", idDistrict); 
					formData.append("idWard", idWard); 
					formData.append("dataContact", dataContact); 
					if($("input[name=\"checkPostToFacebook[]\"]").attr("checked", true)) {
						formData.append("selectPostToFacebook", $("select[name=\"selectPostToFacebook\"]").val()); 
					}
					$.ajax({
						url: "'.route("post.add.request",$channel["domain"]->domain).'",
						headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
						type: "post",
						cache: false,
						contentType: false,
						processData: false,
						data: formData,
						dataType:"json",
						success:function(result){ 
							console.log(result); 
							if(result.success==true){
								$(window).unbind("beforeunload"); 
								window.location.href = result.link;
								//$("#postForm #preloaderInBox").css("display", "none"); 
							}else{
								//$("#postForm #preloaderInBox").css("display", "none"); 
								jQuery.gritter.add({
									title: "Thông báo!",
									text: result.message, 
									class_name: "growl-danger",
									sticky: false,
									time: ""
								});
								if(result.urlEdit){
									$(window).unbind("beforeunload"); 
									window.location.href = result.urlEdit;
								}else{
									$("#postForm #preloaderInBox").css("display", "none"); 
								}
							}
						},
						error: function(result) {
							$("#postForm #preloaderInBox").css("display", "none"); 
							jQuery.gritter.add({
								title: "Thông báo!",
								text: "Không thể đăng bài, vui lòng thử lại! ", 
								class_name: "growl-danger",
								sticky: false,
								time: ""
							});
							$("#postForm #preloaderInBox").css("display", "none"); 
						}
					}); 
				}
				return false; 
			});
		}); 
	', $dependencies);
?>
@if($channel['security']==true)
<?
	if(!empty($channel['info']->joinAddress[0]->address->joinRegion->region->id)){
		$regionDefaultId=$channel['info']->joinAddress[0]->address->joinRegion->region->id; 
		$regionDefaultIso=mb_strtolower($channel['info']->joinAddress[0]->address->joinRegion->region->iso); 
	}else{
		$regionDefaultId=""; 
		$regionDefaultIso=""; 
	}
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		
	', $dependencies);
?>
@endif