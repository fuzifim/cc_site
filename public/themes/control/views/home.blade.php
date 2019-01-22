<?
	if(!empty($channel['info']->getSeo->channel_attribute_value)){
		$seoJson=json_decode($channel['info']->getSeo->channel_attribute_value); 
		if(!empty($seoJson->metaTitle)){
			$metaTitle=$seoJson->metaTitle; 
		}else{
			$metaTitle=$channel['info']->channel_name; 
		}
		if(!empty($seoJson->metaDescription)){
			$metaDescription=$seoJson->metaDescription; 
		}else{
			$metaDescription=$channel['info']->channel_description;
		}
	}else{
		$metaTitle=$channel['info']->channel_name; 
		$metaDescription=$channel['info']->channel_description;
	} 
	$channel['theme']->setTitle(html_entity_decode($metaTitle));
	if(!empty($channel['info']->channel_keywords)){
		$channel['theme']->setKeywords($channel['info']->channel_keywords);
	}else{
		$channel['theme']->setKeywords(html_entity_decode($metaTitle));
	}
	$channel['theme']->setDescription(str_limit(strip_tags(html_entity_decode($metaDescription),""), $limit = 250, $end='...')); 
	//$channel['theme']->setCanonical(route("channel.home",$channel["domainPrimary"]));
	if(count($channel['info']->channelAttributeBanner)>0 && !empty($channel['info']->channelAttributeBanner[0]->media->media_name)){
		$channel['theme']->setImage(config('app.link_media').$channel['info']->channelAttributeBanner[0]->media->media_path.$channel['info']->channelAttributeBanner[0]->media->media_name);
	}else{
		$channel['theme']->setImage('http://'.$channel["domainPrimary"].Theme::asset()->url('img/cungcap.jpg')); 
	}
?>
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('swiper.min', 'library/swiper/js/swiper.min.js', array('core-script'))!!}
@if(Auth::check())
	
@endif
@if($channel['security']==true)
	{!!Theme::asset()->add('summernote', 'assets/js/summernote/dist/summernote.css', array('core-style'))!!}
	{!!Theme::asset()->usePath()->add('jquery.tagsinput', 'css/jquery.tagsinput.css', array('core-style'))!!}
	{!!Theme::asset()->container('footer')->add('summernote', 'assets/js/summernote/dist/summernote.js', array('core-script'))!!}
	{!!Theme::asset()->container('footer')->add('summernote-vi-VN', 'assets/js/summernote/lang/summernote-vi-VN.js', array('core-script'))!!}
	{!!Theme::asset()->container('footer')->usePath()->add('jquery.tagsinput.min', 'js/jquery.tagsinput.min.js', array('core-script'))!!}
	<?
		$dependencies = array(); 
		$channel['theme']->asset()->writeScript('changeChannel','
			jQuery(document).ready(function(){
				"use strict"; 
				$(".groupChannelName").on("click",".btnChannelNameEdit",function() {
					var channelNameText=$(this).parent().closest(".groupChannelName").find(".channelNameText"); 
					var channelDescriptionText=$(this).parent().closest(".groupChannelName").find(".channelDescriptionText"); 
					var channelKeywordsInput=$(this).parent().closest(".groupChannelName").find(".channelKeywordsInput"); 
					var changeChannelNameText=$(this).parent().closest(".groupChannelName").find(".changeChannelNameText"); 
					var channelName=$(this).attr("data-name"); 
					var channelDescription=channelDescriptionText.html(); 
					var channelKeywords=channelKeywordsInput.val(); 
					channelNameText.hide(); 
					changeChannelNameText.show(); 
					changeChannelNameText.append("<form id=\"changeChannelName\">"
						+"<div class=\"form-group\">"
							+"<input type=\"phone\" style=\"font-size:18px;\" name=\"channelName\" value=\""+channelName+"\" class=\"form-control\" placeholder=\"Nhập tên công ty, cửa hàng, tên website...\" required />"
							+"<label class=\"error\" for=\"channelName\"></label>"
						+"</div>"
						+"<div class=\"form-group\">"
							+"<textarea name=\"channelDescription\" id=\"summernote\" class=\"form-control\" placeholder=\"Mô tả kênh website...\" required >"+channelDescription+"</textarea>"
							+"<label class=\"error\" for=\"channelDescription\"></label>"
						+"</div>"
						+"<div class=\"form-group\">"
							+"<input name=\"channelKeywords\" id=\"channelKeywords\" class=\"form-control\" value=\""+channelKeywords+"\" placeholder=\"Từ khóa...\" >"
							+"<label class=\"error\" for=\"channelKeywords\"></label>"
						+"</div>"
						+"<div class=\"form-group\">"
							+"<button type=\"submit\" class=\"btn btn-xs btn-primary btnSaveChannelName\" data-id=\"\"><i class=\"fa fa-check\"></i> Lưu</button> "
							+"<button type=\"button\" class=\"btn btn-xs btn-default btnCancelChannelname\">Hủy</button>"
						+"</div>"
						+"</form>"); 
					jQuery(".groupChannelName #changeChannelName #channelKeywords").tagsInput({
						placeholderColor:"#999",
						width:"auto",
						height:"auto",
						"defaultText":"thêm từ..."
					}); 
					var $validator = jQuery(".groupChannelName #changeChannelName").validate({
						highlight: function(element) {
						  jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
						},
						success: function(element) {
						  jQuery(element).closest(".form-group").removeClass("has-error");
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
							formData.append("channelName", $(".groupChannelName #changeChannelName input[name=channelName]").val()); 
							formData.append("channelDescription", $(".groupChannelName #changeChannelName textarea[name=channelDescription]").val()); 
							formData.append("channelKeywords", $(".groupChannelName #changeChannelName input[name=channelKeywords]").val()); 
							$.ajax({
								url: "'.route("channel.name.save",$channel["domain"]->domain).'",
								headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
								type: "post",
								cache: false,
								contentType: false,
								processData: false,
								data: formData,
								dataType:"json",
								success:function(result){
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
								}
							});
						}
						return false;
					});
					$(".groupChannelName #summernote").summernote(
						{
							popover: {
								image: [
									["custom", ["imageAttributes"]],
									["imagesize", ["imageSize100", "imageSize50", "imageSize25"]],
									["float", ["floatLeft", "floatRight", "floatNone"]],
									["remove", ["removeMedia"]]
								],
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
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<div class="groupChannelName">
			<div id="preloaderInBox">
				<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
			</div>
			<div class="channelNameText">
				<h1>
					{!!$channel['info']->channel_name!!}
					@if($channel['security']==true)<span><a href="" data-name="{!!$channel['info']->channel_name!!}" class="btnChannelNameEdit"><i class="fa fa-pencil"></i> sửa</a></span>@endif
				</h1>
				<span class="channelDescriptionText">{!!html_entity_decode($channel['info']->channel_description)!!}</span>
				<input type="hidden" class="channelKeywordsInput" value="{!!$channel['info']->channel_keywords!!}">
			</div>
			<div class="changeChannelNameText"></div>
		</div>
	</div>
	<div class="contentpanel section-content">
		@if($channel['info']->channel_parent_id!=0)
		<div class="form-group mb5">
			@if(!empty($channel['nameFanpageFacebook']))<div class="fb-like" data-href="https://www.facebook.com/{{$channel['nameFanpageFacebook']}}" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="false"></div>@endif
			<button type="button" class="btn btn-xs btn-default"><strong class="text-danger"><i class="fa fa-eye"></i> {!!Site::price($channel['info']->channel_view)!!} lượt xem</strong></button>
		</div>
		<div class="section-banner">
			<div class="form-group">
				@if($channel['security']==true)
					<div id="preloaderInBox" style="display:none;">
						<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
					</div>
					<div class="groupChangeBanner" style="position: absolute; overflow: hidden; bottom:5px; right:5px; z-index:999; display:none; ">
						<button type="button" tabindex="500" class="btn btn-xs btn-primary btn-file"><i class="glyphicon glyphicon-camera"></i>&nbsp;  <span>Thêm ảnh bìa</span><input id="changeImageCove" name="changeImageCove" type="file" multiple="" class=""></button>
					</div>
					<?
						$dependencies = array(); 
						$channel['theme']->asset()->writeScript('changeBanner', '
							jQuery(document).ready(function(){
							"use strict"; 
							jQuery(".section-banner").hover(function(){
								var t = jQuery(this);
								t.find(".groupChangeBanner").show(); 
								t.find(".delBannerChannel").show();
								t.find(".btn-file").show();
							}, function() {
								var t = jQuery(this);
								t.find(".groupChangeBanner").hide();
								t.find(".delBannerChannel").hide();
								t.find(".btn-file").hide();
							});
							$("#changeImageCove").bind("change", function(){
								$(".section-banner #preloaderInBox").css("display", "block"); 
								var files = $("#changeImageCove").prop("files")[0];  
								var formData = new FormData();
								formData.append("file", files); 
								formData.append("postType", "banner");  
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
										if(result.success==true){
											var formDataChannel = new FormData();
											formDataChannel.append("channelAttributeType", "banner"); 
											formDataChannel.append("channelAttributeValue", result.id); 
											$.ajax({
												url: "'.route("channel.attribute.media.add",$channel["domain"]->domain).'",
												type: "post", 
												headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
												cache: false,
												contentType: false,
												processData: false,
												data: formDataChannel,
												dataType:"json",
												success:function(resultMedia){
													$(".section-banner #preloaderInBox").css("display", "none"); 
													location.reload(); 
												},
												error: function(resultMedia) { 
													jQuery.gritter.add({
														title: "Thông báo!",
														text: "Lỗi không thể thêm ảnh! ", 
														class_name: "growl-danger",
														sticky: false,
														time: ""
													});
												}
											});
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
											text: "Lỗi không thể tải ảnh! ", 
											class_name: "growl-danger",
											sticky: false,
											time: ""
										});
									}
								});
							});
							$("#channelBanner").on("click",".delBannerChannel",function() {
								$(".section-banner #preloaderInBox").css("display", "block"); 
								var mediaId= $(this).attr("data-id"); 
								var formData = new FormData();
								formData.append("mediaId", mediaId); 
								formData.append("channelAttributeType", "banner"); 
								$.ajax({
									url: "'.route("channel.attribute.media.delete",$channel["domain"]->domain).'",
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
											//getChannelAttributeImageCover(); 
											$(".section-banner #preloaderInBox").css("display", "none"); 
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
											text: "Không thể xóa! ", 
											class_name: "growl-danger",
											sticky: false,
											time: ""
										});
									}
								}); 
								return false; 
							});
						});
						', $dependencies);
					?>
				@endif
				@if(count($channel['info']->channelAttributeBanner)<=0)
				<div id="channelBanner" class="swiper-container" style="text-align: center;padding: 60px 0px;border: solid 1px #999;background:#999;">
					<i class="preview glyphicon glyphicon-picture" style="font-size: 180px;color: #666;"></i>
				</div>
				@else
				<div id="channelBanner" class="swiper-container">
					<div class="swiper-wrapper postGallery">
						<? $i=0; ?>
						@foreach($channel['info']->channelAttributeBanner as $banner)
						<? $i++;?>
						<div class="swiper-slide @if($i==1) active @endif">
							@if($channel['security']==true)
								<a href="" class="delBannerChannel"  data-id="{{$banner->media->id}}" style="position:absolute; right:5px; top:5px; display:none;z-index:1;"><span class="label label-danger"><i class="glyphicon glyphicon-trash"></i> Xóa</span></a>
							@endif
							<img class="imageShow img-responsive bannerSite lazy" src="@if(!empty($banner->media->media_name)){{config('app.link_media').$banner->media->media_path.$banner->media->media_name}}@endif" url-lg="{{config('app.link_media').$banner->media->media_path.$banner->media->media_name}}" alt="{!!$channel['info']->channel_name!!}">
						</div>
						@endforeach
						
					</div>
					@if(count($channel['info']->channelAttributeBanner)>=2)
					<?
						$dependencies = array(); 
						$channel['theme']->asset()->writeScript('groupCarouselControl', '
							jQuery(document).ready(function(){
							"use strict"; 
							$(".groupCarouselControl").show(); 
						});
						', $dependencies);
					?>
					@else
						<?
							$dependencies = array(); 
							$channel['theme']->asset()->writeScript('groupCarouselControl', '
								jQuery(document).ready(function(){
								"use strict"; 
								$(".groupCarouselControl").hide(); 
							});
							', $dependencies);
						?>
					@endif
					<div class="groupCarouselControl">
						<a class="left carousel-control carousel_control_left" href="#channelBanner" role="button" data-slide="prev">
							<span class="fa fa-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="right carousel-control carousel_control_right" href="#channelBanner" role="button" data-slide="next">
							<span class="fa fa-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</a>
					</div>
				</div>
				@endif
			</div>
		</div>
		@endif
		@if($channel['info']->channel_parent_id==0)
			{!!Theme::asset()->container('footer')->usePath()->add('bootstrap-wizard', 'js/bootstrap-wizard.min.js', array('core-script'))!!}
			{!!Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'))!!}
			<div class="formRegisterChannel mb10">
				<div id="validationWizard" class="basic-wizard" style="position:relative;">
					<div id="preloaderInBox" style="display:none;">
						<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
					</div>
					<ul class="stepwizard-row">
						<li class="stepwizard-step">
							<a class="btn btn-default btn-circle disabled" href="#vtab1" data-toggle="tab">1</a>
						</li>
						<li class="stepwizard-step">
							<a class="btn btn-default btn-circle disabled"href="#vtab2" data-toggle="tab">2</a>
						</li>
						<li class="stepwizard-step">
							<a class="btn btn-default btn-circle disabled"  href="#vtab3" data-toggle="tab">3</a>
						</li> 
						
						<li class="stepwizard-step">
							<a class="btn btn-default btn-circle disabled"href="#vtab4" data-toggle="tab">4</a>
						</li>
						<li class="stepwizard-step">
							<a class="btn btn-default btn-circle disabled"href="#vtab5" data-toggle="tab">5</a>
						</li>
					</ul>
					<form class="form" id="form1" method="post" action="{{route('channel.create.request',$channel['domain']->domain)}}">
						<div class="tab-content">
							<div class="tab-pane" id="vtab1">
								<?
									if(Session::has('channelDomain')){
										$channelDomain=Session::get('channelDomain'); 
									}
								?>
								<div class="form-group">
									<div class="input-group">
									  <input type="text" name="channelDomain" id="channelDomain" class="form-control" value="@if(!empty($channelDomain)){!!$channelDomain!!}@endif" placeholder="Nhập địa chỉ tên miền" required >
									  <span class="input-group-addon">.{{$channel['domainPrimary']}}</span>
									</div>
									<code id="changeDomain">@if(!empty($channelDomain))http://{!!$channelDomain!!}.{{$channel['domainPrimary']}}@endif</code>
									<label class="error" for="channelDomain"></label>
								</div>
							</div>
							<div class="tab-pane" id="vtab2">
								<div class="row-pad-5 pricingGroup">
								<?
									$getService=\App\Model\Services::find(2); 
									
								?>
								@foreach($getService->attributeAll as $attribute)
									<?
										$attributeJson=json_decode($attribute->attribute_value); 
									?>
									<div class="col-xs-12 col-sm-6 col-md-4 appendpricing">
										<div class="list-group-item btn pricingPackge @if($attribute->id==19) active @endif">
											<div class="text-center">
												<h3 class="">{{$attribute->name}}</h3>
											</div>
											<div class="text-center">
												@if(!empty($attribute->price_sale))
													<h4 style="text-decoration: line-through;color:#000;"><strong>{{Site::price($attribute->price_re_order+$attribute->price_order)}} <sup>đ</sup></strong></h4>
													<h1 class="nopadding nomargin"><strong>{{Site::price($attribute->price_sale)}} <sup>đ</sup></strong></h1>
												/ {{$attribute->per}}
												@else
													<h1 class="nopadding nomargin"><strong>{{Site::price($attribute->price_re_order+$attribute->price_order)}} <sup>đ</sup></strong></h1>
												/ {{$attribute->per}}
												@endif
												
											</div>
											<div class="price-features">
												<ul class="list-group">
													<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> <strong>Dung lượng: </strong> {{$attributeJson->limit_cloud}}MB</li>
													<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> <strong>{{Site::price($attributeJson->limit_post)}}</strong> Bài viết</li>
													<li class="list-group-item list-group-item-info">@if($attribute->id==1)<i class="glyphicon glyphicon-remove text-danger"></i>@else<i class="fa fa-check text-success"></i>@endif Tên miền riêng</li>
													<li class="list-group-item list-group-item-info">@if($attribute->id==1)<i class="glyphicon glyphicon-remove text-danger"></i>@else<i class="fa fa-check text-success"></i>@endif Bỏ quảng cáo</li>
												</ul>
											</div>
											<div class="rdio rdio-primary">
												<input type="radio" name="channelPackge" value="{{$attribute->id}}" id="radioPrimary{{$attribute->id}}" @if($attribute->id==19)checked="checked"@endif>
												<label for="radioPrimary{{$attribute->id}}">Chọn</label>
											</div>
										</div>
									</div>
									@endforeach
								</div>
							</div>
							<div class="tab-pane" id="vtab3">
								<?
									if(Session::has('channelInfo')){
										$channelInfo=Session::get('channelInfo'); 
									}
								?>
								<div class="form-group">
									<input type="text" id="channelName" name="channelName" value="@if(!empty($channelInfo['channelName'])){!!$channelInfo['channelName']!!}@endif" class="form-control" placeholder="Tên website..." required />
								</div>
								<div class="form-group">
									<input type="text" name="channelDescription" value="@if(!empty($channelInfo['channelDescription'])){!!$channelInfo['channelDescription']!!}@endif" class="form-control" placeholder="Mô tả website, cửa hàng..." required />
								</div>
							</div>
							<div class="tab-pane" id="vtab4">
								<?
									if(Session::has('channelRegion')){
										$channelRegion=Session::get('channelRegion'); 
									}
								?>
								<div class="form-group">
									<div class="addFields"></div>
								</div>
								<div class="row mb5">
									<div class="col-sm-12">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
											<input type="text" name="channelAddress" value="@if(!empty($channelRegion['channelAddress'])){!!$channelRegion['channelAddress']!!}@endif" class="form-control" placeholder="Địa chỉ đường, số nhà công ty, cửa hàng..." required />
										</div>
										<label class="error" for="channelAddress"></label>
									</div>
								</div>
								<div class="row mb5">
									<div class="col-sm-6">
										<input type="hidden" name="idRegion" value="@if(!empty($channelRegion['channelRegion'])){!!$channelRegion['channelRegion']!!}@else{{$channel['info']->channelJoinRegion->region->id}}@endif">
										<input type="hidden" name="regionIso" value="{{mb_strtolower($channel['info']->channelJoinRegion->region->iso)}}">
										<div class="addSelectRegion"></div>
										<div class="mb10"></div>
									</div>
									<div class="col-sm-6">
										<input type="hidden" name="idSubRegion" value="@if(!empty($channelRegion['channelSubRegion'])){!!$channelRegion['channelSubRegion']!!}@else{{$channel['info']->channelJoinSubRegion->subregion->id}}@endif">
										<div class="addSelectSubRegion"></div>
										<div class="mb10"></div>
									</div>
								</div>
								<div class="row mb5">
									<div class="col-sm-6">
										<input type="hidden" name="idDistrict" value="">
										<div class="addSelectDistrict"></div>
										<div class="mb10"></div>
									</div>
									<div class="col-sm-6">
										<input type="hidden" name="idWard" value="">
										<div class="addSelectWard"></div>
										<div class="mb10"></div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="vtab5">
								<div class="row mb5">
									<div class="col-sm-6">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-phone"></i></span>
											<input type="phone" class="form-control" name="channelPhone" value="@if(!empty($sessionChannel['channelPhone'])){!!$sessionChannel['channelPhone']!!}@elseif(Auth::check()){{Auth::user()->phone}}@endif" placeholder="Số điện thoại..." required>
										</div>
										<label class="error" for="channelPhone"></label>
										<div class="mb10"></div>
									</div>
									<div class="col-sm-6">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
											<input type="email" class="form-control" name="channelEmail" value="@if(!empty($sessionChannel['channelEmail'])){!!$sessionChannel['channelEmail']!!}@elseif(Auth::check()){{Auth::user()->email}}@endif" placeholder="Địa chỉ email..." required>
										</div>
										<label class="error" for="channelEmail"></label>
										<div class="mb10"></div>
									</div>
									@if(!Auth::check())
									<div class="col-sm-6">
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
												<input placeholder="Mật khẩu" id="password" name="password" type="password" class="form-control" required>
											</div>
											<label class="error" for="password"></label>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
												<input placeholder="Nhập lại mật khẩu" id="password_confirmation" name="password_confirmation" type="password"  class="form-control" required>
											</div>
											<label class="error" for="password_confirmation"></label>
										</div>
									</div>
									@endif
								</div>
								<div class="form-group text-right">
									<input type="checkbox" class="filled-in" name="accept_term" id="accept-term"/>
									<label for="filled-in-box">
										Đồng ý <a href="http://cungcap.net/vi/dieu-khoan-su-dung">Điều khoản của chúng tôi</a>
									</label>
								</div>
							</div>
						</div>
						<div class="text-right">
							<ul class="pager wizard">
								<li class="previous hidden"><a href="javascript:void(0)"><i class="fa fa-chevron-left"></i> Quay lại</a></li>
								<li class="next"><button type="button" class="btn btn-sm btn-primary btn-next pull-right"><span class="textNext">Tạo website</span> <i class="fa fa-chevron-right"></i></button></li>
							</ul>
						</div>
					</form>
				</div>
			</div><!-- panel -->
			<?
				$dependencies = array(); 
				$channel['theme']->asset()->writeScript('custom',' 
				function convertToSlug(title)
				{
				  //Đổi chữ hoa thành chữ thường
					slug = title.toLowerCase();
				 
					//Đổi ký tự có dấu thành không dấu
					slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, "a");
					slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, "e");
					slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, "i");
					slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, "o");
					slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, "u");
					slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, "y");
					slug = slug.replace(/đ/gi, "d");
					//Xóa các ký tự đặt biệt
					slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\"|\"|\:|\;|_/gi, "");
					//Đổi khoảng trắng thành ký tự gạch ngang
					slug = slug.replace(/ /gi, "");
					//Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
					//Phòng trường hợp người nhập vào quá nhiều ký tự trắng
					slug = slug.replace(/\-\-\-\-\-/gi, "-");
					slug = slug.replace(/\-\-\-\-/gi, "-");
					slug = slug.replace(/\-\-\-/gi, "-");
					slug = slug.replace(/\-\-/gi, "-");
					//Xóa các ký tự gạch ngang ở đầu và cuối
					slug = "@" + slug + "@";
					slug = slug.replace(/\@\-|\-\@|\@/gi, "");
					//In slug ra textbox có id “slug”
					
				  return slug;
				}
				$("#channelDomain").keyup(function () {
					if($(this).val().length>=3){
						var appendDomain="'.$channel['domainPrimary'].'";
						$("#changeDomain").html("http://"+convertToSlug($(this).val())+"."+appendDomain);
					}else{
						$("#changeDomain").empty(); 
					}
				});
				$(".appendpricing").on("click",".pricingPackge",function() {
					$(".appendpricing .pricingPackge").not(this).removeClass("active"); 
					$(".appendpricing .pricingPackge").not(this).find("input").prop("checked",false);
					$(this).addClass("active");
					$(this).find("input").prop("checked",true);
				}); 
				function getPackge(){
					$.ajax({
						url: "'.route('channel.packge.json',$channel['domainPrimary']).'",
						type: "GET",
						dataType: "json",
						success: function (result) {
							$.each(JSON.parse(result.data), function(i, item) {
								if(item.id==26){
									$(".appendPackge").append("<div class=\"col-lg-4 col-md-4 col-sm-6 col-xs-12\">"
									+"<div class=\"form-group\">"
										+"<div class=\"list-group-item text-center active btn packgeCheck\">"
											+"<h4 class=\"list-group-item-heading\">"+item.name+"</h4>"
											+"<div class=\"list-group-item-text\">"
												+"<p>Phí Đăng ký: <strong>"+(parseInt(item.price_re_order)+parseInt(item.price_order)).toLocaleString()+"<sup>đ</sup></strong>/ năm</p>"
												+"<p>Phí Duy trì: <strong>"+parseInt(item.price_re_order).toLocaleString()+"<sup>đ</sup></strong>/ năm</p>"
												+"<p><i class=\"glyphicon glyphicon-cloud\"></i> "+parseInt($.parseJSON(item.attribute_value).limit_cloud).toLocaleString()+" MB</p>"
												+"<p><i class=\"glyphicon glyphicon-check\"></i> "+parseInt($.parseJSON(item.attribute_value).limit_post).toLocaleString()+" Bài viết</p>"
											+"</div>"
											+"<input type=\"radio\" class=\"hidden\" value=\""+item.id+"\" name=\"channelPackge\" checked>"
										+"</div>"
									+"</div>"
								+"</div>"); 
								}else{
									$(".appendPackge").append("<div class=\"col-lg-4 col-md-4 col-sm-6 col-xs-12\">"
									+"<div class=\"form-group\">"
										+"<div class=\"list-group-item text-center btn packgeCheck\">"
											+"<h4 class=\"list-group-item-heading\">"+item.name+"</h4>"
											+"<div class=\"list-group-item-text\">"
												+"<p>Phí Đăng ký: <strong>"+(parseInt(item.price_re_order)+parseInt(item.price_order)).toLocaleString()+"<sup>đ</sup></strong>/ năm</p>"
												+"<p>Phí Duy trì: <strong>"+parseInt(item.price_re_order).toLocaleString()+"<sup>đ</sup></strong>/ năm</p>"
												+"<p><i class=\"glyphicon glyphicon-cloud\"></i> "+parseInt($.parseJSON(item.attribute_value).limit_cloud).toLocaleString()+" MB</p>"
												+"<p><i class=\"glyphicon glyphicon-check\"></i> "+parseInt($.parseJSON(item.attribute_value).limit_post).toLocaleString()+" Bài viết</p>"
											+"</div>"
											+"<input type=\"radio\" class=\"hidden\" value=\""+item.id+"\" name=\"channelPackge\">"
										+"</div>"
									+"</div>"
								+"</div>"); 
								}
							}); 
						}
					});
				}
				getPackge(); 
				$(".appendPackge").on("click",".packgeCheck",function() {
					$(".appendPackge .packgeCheck").not(this).removeClass("active"); 
					$(".appendPackge .packgeCheck").not(this).find("input").prop("checked",false);
					$(this).addClass("active");
					$(this).find("input").prop("checked",true);
				}); 
				getFields(); 
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
				function getFields(){
					$(".addFields").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải lĩnh vực, vui lòng chờ...</small></div>"); 
					$.ajax({
						url: "'.route('channel.json.fields',$channel['domainPrimary']).'",
						type: "GET",
						dataType: "json",
						success: function (result) {
							$(".addFields .loading").empty(); 
							$(".addFields").append("<select class=\"selectField\" data-placeholder=\"Chọn lĩnh vực hoạt động...\" name=\"channelFields\" multiple required>"
								+"<option value=\"\"></option></select><label class=\"error\" for=\"channelFields\"></label>"); 
							$.each(result.fields, function(i, item) {
								$(".addFields .selectField").append("<option value="+item.id+">"+item.name+"</option>");
							}); 
							jQuery(".addFields .selectField").select2({
								width: "100%"
							});
						}
					});
				}
				function getRegions(){
					$(".addSelectRegion").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải quốc gia, vui lòng chờ...</small></div>"); 
					$.ajax({
						url: "'.route("regions.json.list",$channel["domainPrimary"]).'",
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
						url: "'.route("subregion.json.list.post",$channel["domainPrimary"]).'",
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
						url: "'.route("district.json.list.post",$channel["domainPrimary"]).'",
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
									if(item.id=='.$channel['info']->channelJoinSubRegion->subregion->id.'){
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
						url: "'.route("ward.json.list.post",$channel["domainPrimary"]).'",
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
									if(item.id=='.$channel['info']->channelJoinSubRegion->subregion->id.'){
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
				jQuery(document).ready(function(){
					"use strict";
					// With Form Validation Wizard
					var $validator = jQuery("#form1").validate({
						highlight: function(element) {
						  jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
						},
						success: function(element) {
						  jQuery(element).closest(".form-group").removeClass("has-error");
						}
					});
					jQuery("#validationWizard").bootstrapWizard({
						withVisible:      false,
						tabClass:         "stepwizard",
						onNext: function(tab, navigation, index) {
							var $valid = jQuery("#form1").valid();
							if(!$valid) {
								$validator.focusInvalid();
								return false;
							}else{
								var move = false; 
								if(index==1){
									$(".previous").removeClass("hidden"); 
									$(".textNext").text("Tiếp tục"); 
									var channelDomain=$("input[name=channelDomain]").val(); 
									var formData = new FormData();
									formData.append("channelDomain", channelDomain); 
									$("#validationWizard #preloaderInBox").css("display", "block"); 
									$.ajax({
										url: "'.route("channel.add.step1",$channel["domainPrimary"]).'",
										type: "POST",
										cache: false,
										contentType: false,
										processData: false,
										dataType:"json",
										data:formData,
										headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
										success: function (result) {
											console.log(result); 
											if(result.success==true){
												move= true; 
												$("#validationWizard #preloaderInBox").css("display", "none"); 
												$("#validationWizard").bootstrapWizard("show",index); 
												$("html, body").animate({scrollTop: $("#validationWizard").offset().top}, "slow");
											}else{
												$("#validationWizard #preloaderInBox").css("display", "none");  
												move=false; 
												jQuery.gritter.add({
													title: "Thông báo!",
													text: result.message, 
													class_name: "growl-danger",
													sticky: false,
													time: ""
												});
											}
										}
									});
								}else if(index==2){
									$(".textNext").text("Tiếp tục"); 
									var channelPackge=$("input[name=channelPackge]").val(); 
									var formData = new FormData();
									formData.append("channelPackge", channelPackge); 
									$("#validationWizard #preloaderInBox").css("display", "block"); 
									$.ajax({
										url: "'.route("channel.add.step2",$channel["domainPrimary"]).'",
										type: "POST",
										cache: false,
										contentType: false,
										processData: false,
										dataType:"json",
										data:formData,
										headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
										success: function (result) {
											console.log(result); 
											if(result.success==true){
												move= true; 
												$("#validationWizard #preloaderInBox").css("display", "none"); 
												$("#validationWizard").bootstrapWizard("show",index); 
												$("html, body").animate({scrollTop: $("#validationWizard").offset().top}, "slow");
											}else{
												$("#validationWizard #preloaderInBox").css("display", "none");  
												move=false; 
												jQuery.gritter.add({
													title: "Thông báo!",
													text: result.message, 
													class_name: "growl-danger",
													sticky: false,
													time: ""
												});
											}
										}
									});
								}else if(index==3){
									$(".textNext").text("Tiếp tục"); 
									
									var channelName=$("input[name=channelName]").val(); 
									var channelDescription=$("input[name=channelDescription]").val(); 
									var formData = new FormData();
									formData.append("channelName", channelName); 
									formData.append("channelDescription", channelDescription); 
									$("#validationWizard #preloaderInBox").css("display", "block"); 
									$.ajax({
										url: "'.route("channel.add.step3",$channel["domainPrimary"]).'",
										type: "POST",
										cache: false,
										contentType: false,
										processData: false,
										dataType:"json",
										data:formData,
										headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
										success: function (result) {
											console.log(result); 
											if(result.success==true){
												move= true; 
												$("#validationWizard #preloaderInBox").css("display", "none"); 
												$("#validationWizard").bootstrapWizard("show",index); 
												$("html, body").animate({scrollTop: $("#validationWizard").offset().top}, "slow");
											}else{
												$("#validationWizard #preloaderInBox").css("display", "none");  
												move=false; 
												jQuery.gritter.add({
													title: "Thông báo!",
													text: result.message, 
													class_name: "growl-danger",
													sticky: false,
													time: ""
												});
											}
										}
									});
								}else if(index==4){
									$(".textNext").text("Tiếp tục"); 
									var channelAddress=$("input[name=channelAddress]").val(); 
									var channelFields=$("select[name=channelFields]").select2("val"); 
									var channelRegion=$("select[name=channelRegion]").select2("val");
									var channelSubRegion=$("select[name=channelSubRegion]").select2("val"); 
									var channelDistrict=$("select[name=channelDistrict]").select2("val");
									var channelWard=$("select[name=channelWard]").select2("val");
									var formData = new FormData();
									formData.append("channelAddress", channelAddress); 
									formData.append("channelFields", channelFields); 
									formData.append("channelRegion", channelRegion); 
									formData.append("channelSubRegion", channelSubRegion); 
									formData.append("channelDistrict", channelDistrict); 
									formData.append("channelWard", channelWard); 
									$("#validationWizard #preloaderInBox").css("display", "block"); 
									$.ajax({
										url: "'.route("channel.add.step4",$channel["domainPrimary"]).'",
										type: "POST",
										cache: false,
										contentType: false,
										processData: false,
										dataType:"json",
										data:formData,
										headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
										success: function (result) {
											console.log(result); 
											if(result.success==true){
												move= true; 
												$("#validationWizard #preloaderInBox").css("display", "none"); 
												$("#validationWizard").bootstrapWizard("show",index); 
												$("html, body").animate({scrollTop: $("#validationWizard").offset().top}, "slow"); 
											}else{
												$("#validationWizard #preloaderInBox").css("display", "none");  
												move=false; 
												jQuery.gritter.add({
													title: "Thông báo!",
													text: result.message, 
													class_name: "growl-danger",
													sticky: false,
													time: ""
												});
											}
										}
									});
								}else if(index==5){
									$(".textNext").text("Tiếp tục"); 
									var channelPhone=$("input[name=channelPhone]").val(); 
									var channelEmail=$("input[name=channelEmail]").val(); 
									var channelPassword=$("input[name=password]").val(); 
									var channelRePassword=$("input[name=password_confirmation]").val(); 
									var formData = new FormData();
									formData.append("channelPhone", channelPhone); 
									formData.append("channelEmail", channelEmail); 
									formData.append("password", channelPassword); 
									formData.append("password_confirmation", channelRePassword); 
									$("#validationWizard #preloaderInBox").css("display", "block"); 
									$.ajax({
										url: "'.route("channel.add.step5",$channel["domainPrimary"]).'",
										type: "POST",
										cache: false,
										contentType: false,
										processData: false,
										dataType:"json",
										data:formData,
										headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
										success: function (result) {
											console.log(result); 
											if(result.success==true){
												if(result.type=="free"){
													$("#validationWizard #preloaderInBox").css("display", "none"); 
													$("#validationWizard").bootstrapWizard("show",index); 
													$("html, body").animate({scrollTop: $("#validationWizard").offset().top}, "slow"); 
													window.location.href = "'.route("channel.me",$channel["domainPrimary"]).'";
												}else{
													$("#validationWizard #preloaderInBox").css("display", "none"); 
													$("#validationWizard").bootstrapWizard("show",index); 
													$("html, body").animate({scrollTop: $("#validationWizard").offset().top}, "slow"); 
													window.location.href = "'.route("pay.cart",$channel["domainPrimary"]).'";
												}
											}else{
												$("#validationWizard #preloaderInBox").css("display", "none");  
												move=false; 
												jQuery.gritter.add({
													title: "Thông báo!",
													text: result.message, 
													class_name: "growl-danger",
													sticky: false,
													time: ""
												});
											}
										}
									});
								}
								return move;
							}
						}
					});
				});
				', $dependencies);
			?>
			<div class="group-section-content">
				<div class="section-content">
					<div class="row-pad-5">
						<div class="col-md-8">
							@if(count($postListNew))
								<div class="panel panel-dark panel-alt widget-slider">
								<div class="panel-body">
									<div id="carousel-2" class="swiper-container" data-ride="carousel">
										<!-- Wrapper for slides -->
										<div class="swiper-wrapper">
										<?
											$i=0; 
										?>
										@foreach($postListNew as $post)
										@if(!empty($post->getSlug->slug_value))
										  <div class="swiper-slide @if($i==1) active @endif">
											<div class="media">
												@if(count($post->gallery)>0)<a href="{{route('channel.slug',array($channel['domainPrimary'],$post->getSlug->slug_value))}}" class="pull-left">
													@if($post->gallery[0]->media->media_storage=='youtube')
														<img src="//img.youtube.com/vi/{{$post->gallery[0]->media->media_name}}/hqdefault.jpg" data-src="" class="padding5 postMediaXs pull-left lazy" alt="" title="" >
													@elseif($post->gallery[0]->media->media_storage=='video')
													<div class="groupThumb" style="position:relative;width: 90px;float: left;">
														<span class="btnPlayVideoClickSmall"><i class="glyphicon glyphicon-play"></i></span>
														<img itemprop="image" class="padding5 postMediaXs lazy" alt="{{$post->posts_title}}" src="{{config('app.link_media').$post->gallery[0]->media->media_path.'xs/'.$post->gallery[0]->media->media_id_random.'.png'}}"/>
													</div>
													@elseif($post->gallery[0]->media->media_storage=='files')
													<img src="{!!asset('assets/img/file.jpg')!!}" class="padding5 media-object postMediaXs lazy" alt="" title="" >
													@else
														<img src="{{config('app.link_media').$post->gallery[0]->media->media_path.'xs/'.$post->gallery[0]->media->media_name}}" data-src="" class="padding5 postMediaXs lazy" alt="" title="" >
													@endif
												</a>@endif
												<div class="media-body">
												  <h2 class="media-heading"><a href="{{route('channel.slug',array($channel['domainPrimary'],$post->getSlug->slug_value))}}">{!!$post->posts_title!!}</a></h2>
												  <div>
													<small><a href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-link"></i> {{$channel['domainPrimary']}}</a></small>
												</div>
												<span><small><i class="glyphicon glyphicon-time"></i> {{WebService::time_request($post->posts_updated_at)}}</small> - <small><i class="fa fa-eye"></i> {{$post->posts_view}} lượt xem</small> </span>
												@if(!empty($post->price->posts_attribute_value))
												<div class="text-danger">
													<strong>Giá bán:  {!!WebService::price($post->price->posts_attribute_value)!!}<sup>đ</sup></strong>
												</div>
												@endif
												</div>
											</div><!-- media -->
										  </div><!-- item -->
										 @endif
										@endforeach
										  
										</div><!-- carousel-inner -->
										<!-- Controls -->
										<a class="left carousel-control carousel_control_left_2" href="#carousel-post-qc" data-slide="prev">
										  <span class="fa fa-angle-left"></span>
										</a>
										<a class="right carousel-control carousel_control_right_2" href="#carousel-post-qc" data-slide="next">
										  <span class="fa fa-angle-right"></span>
										</a>
									</div><!-- carousel -->

								</div><!-- panel-body -->
							  </div>
							@endif
							@if(count($posts)>0)
							<div class="PostlistItem">
								@foreach($posts->chunk(3) as $chunk)
								{!!Theme::partial('listPostChannel', array('chunk' => $chunk))!!}
								@endforeach
							</div>
							<div id="loadPost" class="text-center">
								<input id="curentPage-key" class="curentPage-key" type="hidden" value="{{$posts->currentPage()}}" autocomplete="off"/>
								<input id="totalPage-key" class="totalPage-key" type="hidden" value="{{$posts->total()}}" autocomplete="off"/>
								<input id="urlPage-key" class="urlPage-key" type="hidden" value="{{route('post.list',$channel['domainPrimary'])}}" autocomplete="off"/>
								<input id="nextPage-key" class="nextPage-key" type="hidden" value="{{$posts->nextPageUrl()}}" autocomplete="off"/>
								<input id="perPage-key" class="perPage-key" type="hidden" value="{{$posts->perPage()}}" autocomplete="off"/>
								<input id="lastPage-key" class="lastPage-key" type="hidden" value="{{$posts->lastPage()}}" autocomplete="off"/>
								@if(strlen($posts->nextPageUrl())>0)
									<div class="text-center">
										<a href="{{$posts->nextPageUrl()}}" class="viewMore btn btn-xs"><i class="glyphicon glyphicon-hand-right"></i> Xem thêm...</a>
									</div>
								@endif
							</div>
							<?
								$dependencies = array(); 
								$channel['theme']->asset()->writeScript('morePost', '
									jQuery(document).ready(function(){
									"use strict"; 
									$("#loadPost .viewMore").click(function(){
										var curentPage=parseInt($("#loadPost #curentPage-key").val()); 
										var lastPage=parseInt($("#loadPost #lastPage-key").val()); 
										var pageUrl=$("#loadPost #urlPage-key").val(); 
										var page_int=curentPage+1;
										if(page_int<=lastPage){
											$("#loadPost .viewMore").css("position","relative"); 
											$.ajax({
												type: "GET",
												url: pageUrl+"?page="+page_int,
												dataType: "html",
												contentType: "text/html",
												beforeSend: function() {
													$("#loadPost .viewMore").append("<div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div>"); 
												},
												success: function(data) {
													$("#loadPost #curentPage-key").val(page_int); 
													$(data).find(".PostlistItem").ready(function() {
														var content_ajax = $(data).find(".PostlistItem").html();
														$(".PostlistItem").append(content_ajax); 
														$("#loadPost .viewMore #preloaderInBox").remove(); 
													});
												}
											});
										}else{
											$("#loadPost .viewMore").addClass("hidden");
										}
										return false; 
									}); 
								});
								', $dependencies);
							?>
							@endif
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<div class="fb-page" data-href="https://www.facebook.com/cungcap.net/" data-tabs="messages" data-height="300" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/cungcap.net/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/cungcap.net/">Cung cấp</a></blockquote></div>
							</div>
							<div class="panel panel-default panel-alt widget-messaging">
								<div class="panel-heading">
									<div class="panel-title">{{$getChannel->total()}} Kênh mới</div>
								</div>
								<div class="panel-body">
									@if(count($getChannel)>0)
										<ul class="channelList">
										@foreach($getChannel as $subChannel)
											<?
												if($subChannel->domainJoinPrimary->domain->domain_primary!='default'){
													if(count($subChannel->domainAll)>0){
														foreach($subChannel->domainAll as $domain){
															if($domain->domain->domain_primary=='default'){
																$domainPrimary=$domain->domain->domain; 
															}
														}
													}else{
														$domainPrimary=$subChannel->domainJoinPrimary->domain->domain; 
													}
												}else{
													$domainPrimary=$subChannel->domainJoinPrimary->domain->domain; 
												}
											?>
											<li>
												@if(!empty($subChannel->channelAttributeLogo->media->media_name))<a class="pull-left" href="{{route('channel.home',$domainPrimary)}}">
												  <img class="media-object channel-thumb" src="{{config('app.link_media').$subChannel->channelAttributeLogo->media->media_path.'xs/'.$subChannel->channelAttributeLogo->media->media_name}}" alt="">
												</a>@endif
												<h4 class="sender">
												  <a href="{{route('channel.home',$domainPrimary)}}">{{$subChannel->channel_name}}</a>
												</h4>
												<p><small><a href="{{route('channel.home',$domainPrimary)}}"><i class="glyphicon glyphicon-link"></i> {{$domainPrimary}}</a></small></p> 
												<p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> {{WebService::time_request($subChannel->channel_updated_at)}} - <span class="text-danger">{{$subChannel->channel_view}} lượt xem</span></small></p>
											</li>
										@endforeach
										</ul>
									@endif
								</div>
								<div id="loadChannel" class="text-center">
									<input id="curentPage-key" class="curentPage-key" type="hidden" value="{{$getChannel->currentPage()}}" autocomplete="off"/>
									<input id="totalPage-key" class="totalPage-key" type="hidden" value="{{$getChannel->total()}}" autocomplete="off"/>
									<input id="urlPage-key" class="urlPage-key" type="hidden" value="{{route('channel.list',$channel['domainPrimary'])}}" autocomplete="off"/>
									<input id="nextPage-key" class="nextPage-key" type="hidden" value="{{$getChannel->nextPageUrl()}}" autocomplete="off"/>
									<input id="perPage-key" class="perPage-key" type="hidden" value="{{$getChannel->perPage()}}" autocomplete="off"/>
									<input id="lastPage-key" class="lastPage-key" type="hidden" value="{{$getChannel->lastPage()}}" autocomplete="off"/>
									@if(strlen($getChannel->nextPageUrl())>0)
										<div class="panel-body text-center">
											<a href="{{$getChannel->nextPageUrl()}}" class="viewMore btn btn-xs"><i class="glyphicon glyphicon-hand-right"></i> Xem thêm...</a>
										</div>
									@endif
								</div>
								<?
									$dependencies = array(); 
									$channel['theme']->asset()->writeScript('moreChannel', '
										jQuery(document).ready(function(){
										"use strict"; 
										$("#loadChannel .viewMore").click(function(){
											var curentPage=parseInt($("#loadChannel #curentPage-key").val()); 
											var lastPage=parseInt($("#loadChannel #lastPage-key").val()); 
											var pageUrl=$("#loadChannel #urlPage-key").val(); 
											var page_int=curentPage+1;
											if(page_int<=lastPage){
												$("#loadChannel .viewMore").css("position","relative"); 
												$.ajax({
													type: "GET",
													url: pageUrl+"?page="+page_int,
													dataType: "html",
													contentType: "text/html",
													beforeSend: function() {
														$("#loadChannel .viewMore").append("<div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div>"); 
													},
													success: function(data) {
														$("#loadChannel #curentPage-key").val(page_int); 
														$(data).find(".channelList").ready(function() {
															var content_ajax = $(data).find(".channelList").html();
															$(".channelList").append(content_ajax); 
															$("#loadChannel .viewMore #preloaderInBox").remove(); 
														});
													}
												});
											}else{
												$("#loadChannel .viewMore").addClass("hidden");
											}
											return false; 
										}); 
									});
									', $dependencies);
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		@else
			
			@if(count($channel['info']->joinCategory)>0 && $channel['totalPosts']>0)
			<div class="group-section-content">
				<div class="section-content">
					@foreach($channel['info']->joinCategory as $joinCategory)
						<?
							if(!empty($joinCategory->category->options->attribute_value)){
								$options=json_decode($joinCategory->category->options->attribute_value); 
							}else{
								$options=null; 
							}
						?>
						@if($options!=null && !empty($options->hiddenHomeCategory) && $options->hiddenHomeCategory=='checked')
						@else
							@if($joinCategory->category->parent_id==0)
									<? 
										$categoryId=[]; 
										array_push($categoryId, $joinCategory->category->id); 
									?>
									@if(count($joinCategory->category->children)>0)
										@foreach($joinCategory->category->children as $catChild) 
											<?
												array_push($categoryId, $catChild->id); 
												if(count($catChild->children)>0){
													foreach($catChild->children as $subChild){
														array_push($categoryId, $subChild->id); 
													}
												}
											?>
										@endforeach
									@endif
									<?
										$getPost=\App\Model\Posts::where('posts.posts_status','=','active')
											->join('posts_join_category','posts_join_category.posts_id','=','posts.id')
											->whereIn('posts_join_category.category_id',$categoryId)
											->join('posts_attribute','posts_attribute.posts_parent_id','=','posts.id')
											->where('posts_attribute.posts_attribute_type','=','gallery')
											->groupBy('posts.id')
											->orderBy('posts.posts_updated_at','desc')
											->select('posts.*')
											->get(); 
									?>
									@if(count($getPost)>0)
										<div class="panel panel-primary panel-program">
											<div class="panel-heading heading-program dropdown">
												<h3 class="panel-title categoryParentTitle"><a href="{{route('channel.slug',array($channel['domain']->domain,$joinCategory->category->getSlug->slug_value))}}"><i class="glyphicon glyphicon-book"></i> {!!$joinCategory->category->category_name!!}</a></h3> 
												@if(count($joinCategory->category->children)>0)
													<small>
														<a href="#" class="dropdown-toggle" data-toggle="dropdown">Xem thêm <span class="fa fa-angle-down"></span></a>
														<ul class="dropdown-menu nopadding">
															<a class="list-group-item" href="{{route('channel.slug',array($channel['domain']->domain,$joinCategory->category->getSlug->slug_value))}}"><i class="glyphicon glyphicon-list"></i> Xem tất cả</a>
															@foreach($joinCategory->category->children as $catChild) 
																@if(count($catChild->postsJoinParent)>0) 
																	<a class="list-group-item" href="{{route('channel.slug',array($channel['domain']->domain,$catChild->getSlug->slug_value))}}"><i class="glyphicon glyphicon-folder-open"></i> {!!$catChild->category_name!!}</a>
																@endif 
																@if(count($catChild->children)>0) 
																	@foreach($catChild->children as $subChild) 
																		@if(count($subChild->postsJoinParent)>0) 
																			<a class="list-group-item" href="{{route('channel.slug',array($channel['domain']->domain,$subChild->getSlug->slug_value))}}"><i class="glyphicon glyphicon-folder-open"></i> {!!$subChild->category_name!!}</a>
																		@endif
																	@endforeach
																@endif
															@endforeach
														</ul>
													</small>
												@endif
											</div>
											<div class="panel-body">
												<div class="row">
													@if(count($getPost)==1 && !empty($getPost[0]->getSlug->slug_value))
														<?
															if(!empty($getPost[0]->options->posts_attribute_value)){
																$options=json_decode($getPost[0]->options->posts_attribute_value); 
															}else{
																$options=null; 
															}
														?>
														@if($options!=null && !empty($options->viewFullScreen) && $options->viewFullScreen=='checked')
														@else
														<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 postOne">
															<a class="image img-thumbnail" href="{{route('channel.slug',array($channel['domain']->domain,$getPost[0]->getSlug->slug_value))}}">
															@if($getPost[0]->gallery[0]->media->media_storage=='youtube')
																<img src="//img.youtube.com/vi/{{$getPost[0]->gallery[0]->media->media_name}}/hqdefault.jpg" class="img-responsive imgThumb lazy" alt="{!!$getPost[0]->posts_title!!}" title="" >
															@elseif($getPost[0]->gallery[0]->media->media_storage=='video')
																<div class="groupThumb">
																	<span class="btnPlayVideoClick"><i class="glyphicon glyphicon-play"></i></span>
																	<img itemprop="image" class="img-responsive imgThumb lazy" alt="{{$getPost[0]->posts_title}}" src="{{config('app.link_media').$getPost[0]->gallery[0]->media->media_path.'thumb/'.$getPost[0]->gallery[0]->media->media_id_random.'.png'}}"/>
																</div>
															@elseif($getPost[0]->gallery[0]->media->media_storage=='files')
																<img src="{!!asset('assets/img/file.jpg')!!}" class="img-responsive imgThumb lazy" alt="{!!$getPost[0]->posts_title!!}" title="" >
															@else
																<img src="{{config('app.link_media').$getPost[0]->gallery[0]->media->media_path.'thumb/'.$getPost[0]->gallery[0]->media->media_name}}" class="img-responsive imgThumb lazy" alt="{!!$getPost[0]->posts_title!!}" title="" >
															@endif
															</a>
															<div class="attribute-2 mb5">
																<small><span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($getPost[0]->posts_updated_at)!!}</span> 
																<span class="author"><i class="glyphicon glyphicon-user"></i> {!!$getPost[0]->author->user->name!!}</span></small> 
															</div>
															<div class="timeline-btns">
																<div class="pull-left">
																	<a href="" class="tooltips likeUp text-muted likeUp_{{$getPost[0]->id}} @if(Auth::check())@foreach($getPost[0]->like as $like) @if($like->user_id==Auth::user()->id) text-success @endif @endforeach @endif" data-id="{{$getPost[0]->id}}" data-toggle="tooltip" title="" data-original-title="Thích"><i class="glyphicon glyphicon-thumbs-up"></i> <span class="countLike_{{$getPost[0]->id}}">{{count($getPost[0]->like)}}</span></a>
																	<a href="" class="tooltips likeDown text-muted likeDown_{{$getPost[0]->id}} @if(Auth::check())@foreach($getPost[0]->unLike as $like) @if($like->user_id==Auth::user()->id) text-danger @endif @endforeach @endif" data-id="{{$getPost[0]->id}}" data-toggle="tooltip" title="" data-original-title="Không thích"><i class="glyphicon glyphicon-thumbs-down"></i> <span class="countLikeDown_{{$getPost[0]->id}}">{{count($getPost[0]->unlike)}}</span></a>
																	<a href="" class="tooltips text-muted" data-toggle="tooltip" title="" data-original-title="Add Comment"><i class="glyphicon glyphicon-comment"></i></a>
																	<a href="" class="tooltips btnShare text-muted"  data-title="{!!$getPost[0]->posts_title!!}" data-image="{{$getPost[0]->gallery[0]->media->media_url_thumb}}" data-url="{{route('channel.slug',array($channel['domain']->domain,$getPost[0]->getSlug->slug_value))}}" data-toggle="tooltip" title="" data-original-title="Share"><i class="glyphicon glyphicon-share"></i></a>
																</div>
																<div class="pull-right">
																	<small class="text-muted text-danger"><strong>{{$getPost[0]->posts_view}} lượt xem</strong></small>
																</div>
															</div>
														</div>
														<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
															<h2 class="blog-title-large"><a class="title" href="{{route('channel.slug',array($channel['domain']->domain,$getPost[0]->getSlug->slug_value))}}">{!!$getPost[0]->posts_title!!}</a></h2>
															@if(!empty($getPost[0]->posts_description))
																<div class="form-group">
																	<?
																		$postContent=html_entity_decode($getPost[0]->posts_description); 
																		$postContent=preg_replace('/(<p[^>]*>)(.*?)(<\/p>)/i', '$2<p>', $postContent);
																		$postContent=WebService::limit_string(strip_tags($postContent,"<p><br>"), $limit = 500); 
																		$postContent=str_replace('????', '', $postContent);
																		
																	?>
																	{!!$postContent!!} <a href="{{route('channel.slug',array($channel['domain']->domain,$getPost[0]->getSlug->slug_value))}}"><i class="glyphicon glyphicon-hand-right"></i> Xem thêm</a>
																</div>
															@endif
														</div>
														@endif
													@else
														@foreach($getPost->take(6)->chunk(3) as $chunk)
															<div class="row">
																@foreach($chunk as $post)
																	{!!Theme::partial('listPost', array('post' => $post))!!}
																@endforeach
															</div>
														@endforeach
													@endif
												</div>
											</div>
											<div class="panel-footer text-center">
												<a class="" href="{{route('channel.slug',array($channel['domain']->domain,$joinCategory->category->getSlug->slug_value))}}"><i class="glyphicon glyphicon-hand-right"></i> Xem tất cả</a>
											</div>
										</div>
									@endif
							@endif
						@endif
					@endforeach
				</div>
			</div>
			@else
			<div class="section">
				<div class="panel panel-default" style="margin-top:15px;">
					<div class="panel-body">
						<h4 class="text-center">Website chưa cập nhật thông tin</h4>
						<div class="text-center"><a href="{{route('post.add',$channel['domain']->domain)}}" class="btn btn-success"><i class="glyphicon glyphicon-edit"></i> Đăng bài</a></div>
					</div>
				</div>
			</div>
			@endif
		@endif
	</div>

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('loadLazy','
		$(function() {
			$(".lazy").lazy();
		}); 
		var swiper = new Swiper(".swiper-container", {
			navigation: {
				nextEl: ".carousel_control_right",
				prevEl: ".carousel_control_left",
			},
			autoplay: {
                        delay: 5000,
                      },
		});
		var swiper = new Swiper("#carousel-2", {
			navigation: {
				nextEl: ".carousel_control_right_2",
				prevEl: ".carousel_control_left_2",
			},
		});
	', $dependencies);
?>