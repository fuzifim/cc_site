<?
	$channel['theme']->setTitle('Cài đặt giao diện');
	$channel['theme']->setKeywords('Cài đặt giao diện');
	$channel['theme']->setDescription('Cài đặt giao diện '.$channel['info']->channel_name);
	Theme::asset()->add('bootstrap-colorpicker', 'assets/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css', array('core-style'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'));
	Theme::asset()->container('footer')->add('bootstrap-colorpicker', 'assets/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js', array('core-script'));
?>
@if($channel['security']==true)
<?php
Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'));
?>
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
		<div class="panel panel-default">
			<div class="panel-body channelTheme">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"> 
						<h3 class="subtitle"><strong><i class="glyphicon glyphicon-picture"></i> Cài đặt Hình ảnh & Màu sắc</strong></h3>
						<div class="form-group">
							<button type="button" tabindex="500" class="btn btn-xs btn-primary btn-file" style="position: relative; overflow: hidden; margin-bottom:5px; "><i class="glyphicon glyphicon-camera"></i>&nbsp;  <span>Chọn hình nền</span><input id="changeImageBackground" name="" accept="image/*" type="file" class=""></button> 1280x720
							<div class="imgBackground">
								@if(!empty($channel['info']->channelAttributeBackground->media->media_url))
									<div class="form-group">
										<img class="img-responsive" id="imgBackground" src="{{$channel['info']->channelAttributeBackground->media->media_url}}">
									</div>
									<div class="form-group">
										<a href="" class="btn btn-xs btn-danger delBackground"><i class="glyphicon glyphicon-trash"></i> Xóa</a>
									</div>
								@else
									<div class="noneBackground">Chưa cập nhật hình nền</div>
								@endif
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="phone">Màu chủ đạo</label>
							<div id="channelTitle" class="input-group">
								<input type="text" value="@if(!empty($channel['color']->channelTitle)){{$channel['color']->channelTitle}} @else #1D2939 @endif" name="channelColor[]" data-type="channelTitle" class="form-control channelColor" />
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="phone">Màu chữ Menu</label>
							<div id="channelTitleText" class="input-group">
								<input type="text" value="@if(!empty($channel['color']->channelTitleText)){{$channel['color']->channelTitleText}} @else #fff @endif" name="channelColor[]" data-type="channelTitleText" class="form-control channelColor" />
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="phone">Màu nền Tiêu đề</label>
							<div id="channelMenu" class="input-group">
								<input type="text" value="@if(!empty($channel['color']->channelMenu)){{$channel['color']->channelMenu}} @else #f7f7f7 @endif" name="channelColor[]" data-type="channelMenu" class="form-control channelColor" />
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="phone">Màu chữ Tiêu đề</label>
							<div id="channelMenuText" class="input-group">
								<input type="text" value="@if(!empty($channel['color']->channelMenuText)){{$channel['color']->channelMenuText}} @else #1D2939 @endif" name="channelColor[]" data-type="channelMenuText" class="form-control channelColor" />
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="phone">Màu nền Chân trang</label>
							<div id="channelFooter" class="input-group">
								<input type="text" value="@if(!empty($channel['color']->channelFooter)){{$channel['color']->channelFooter}} @else #333333 @endif" name="channelColor[]" data-type="channelFooter" class="form-control channelColor" />
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="phone">Màu chữ Chân trang</label>
							<div id="channelFooterText" class="input-group">
								<input type="text" value="@if(!empty($channel['color']->channelFooterText)){{$channel['color']->channelFooterText}} @else #fff @endif" name="channelColor[]" data-type="channelFooterText" class="form-control channelColor" />
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<h3 class="subtitle"><strong><i class="glyphicon glyphicon-share-alt"></i> Cài đặt Mạng xã hội</strong></h3> 
						<div class="form-group">
							<label class="control-label" for="fanpage">Fanpage Facebook</label>
							<div id="fanpageFacebook" class="input-group">
								<span class="input-group-addon"><i class="fa fa-facebook-square"></i></span>
								<input type="text" value="@if(!empty($channel['color']->fanpageFacebook)){{$channel['color']->fanpageFacebook}} @endif" name="channelColor[]" data-type="fanpageFacebook" class="form-control channelColor"  placeholder="Nhập link Fanpage"/>
							</div>
							<code>Vd: https://www.facebook.com/cungcap.net <br>bạn hãy nhớ là địa chỉ fanpage, không phải là địa chỉ cá nhân nhé!</code>
						</div>
						<div class="form-group">
							<label class="control-label" for="fanpage">Tài khoản Zalo</label>
							<div id="zaloAccount" class="input-group">
								<span class="input-group-addon"><i class="fa fa-comment"></i></span>
								<input type="text" value="@if(!empty($channel['color']->zaloAccount)){{$channel['color']->zaloAccount}} @endif" name="channelColor[]" data-type="zaloAccount" class="form-control channelColor"  placeholder="Nhập tài khoản Zalo"/>
							</div>
							<code>Vd tài khoản Zalo: 0903706288</code>
						</div>
						<div class="form-group">
							<label class="control-label" for="fanpage">Tài khoản Google Plus</label>
							<div id="googlePlus" class="input-group">
								<span class="input-group-addon"><i class="fa fa-google-plus-square"></i></span>
								<input type="text" value="@if(!empty($channel['color']->googlePlus)){{$channel['color']->googlePlus}} @endif" name="channelColor[]" data-type="googlePlus" class="form-control channelColor"  placeholder="Nhập link Google+"/>
							</div>
							<code>Vd: https://plus.google.com/u/0/111972546515360191967</code>
						</div>
						<div class="form-group">
							<h4>Thêm ngôn ngữ</h4>
							<div class="addRegion"></div>
						</div>
						<?
						$serviceJson=json_decode($channel['info']->channelService->attribute_value);
						?>						
						<div class="form-group">
							<label class="control-label" for="fanpage">Chứng chỉ SSL</label>
							@if($channel['domain']->ssl_active=='pending')<button type="button" class="btn btn-sm btn-default" id="activeSSL">Bật SSL</button><p><code>Khi bật chứng chỉ SSL, có thể mất khoảng 1 phút để kích hoạt, vì vậy bạn hãy chờ và refesh lại trình duyệt để sử dụng</code></p>@elseif($channel['domain']->ssl_active=='active')<button type="button" class="btn btn-sm btn-success">Đã Bật SSL</button>@endif
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="headerScript">Header Script</label>
					<textarea class="form-control channelColor" id="headerScript" rows="3" name="channelColor[]" data-type="headerScript">@if(!empty($channel['color']->headerScript)){{$channel['color']->headerScript}}@endif</textarea>
				</div>
				<div class="form-group">
					<label class="control-label" for="footerScript">Footer Script</label>
					<textarea class="form-control channelColor" id="footerScript" rows="3" name="channelColor[]" data-type="footerScript">@if(!empty($channel['color']->footerScript)){{$channel['color']->footerScript}}@endif</textarea>
				</div>
			</div>
			<div class="panel-footer text-right">
				<button type="button" class="btn btn-default" id="btnResetChannelColor"><i class="glyphicon glyphicon-refresh"></i> Trở về mặc định</button>
				<button type="button" class="btn btn-primary" id="btnSaveChannelTheme"><i class="glyphicon glyphicon-ok"></i> Lưu</button>
			</div>
			</div>
		</div>

</div><!-- mainpanel -->
</section>
<?
	$defaultSelectJson="[]"; 
	if(!empty($channel['info']->lang->channel_attribute_value)){
		$langArray=explode(',',$channel['info']->lang->channel_attribute_value); 
		if(count($langArray)>0){
			$defaultSelect=[]; 
			foreach($langArray as $key=>$value){
				$getLang=\App\Model\Regions::find($value); 
				if(!empty($getLang->id)){
					$defaultSelect[$key]['id']=$getLang->id; 
					$defaultSelect[$key]['text']=$getLang->country; 
				}
			}
			$defaultSelectJson=json_encode($defaultSelect); 
		}
	}
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		jQuery(document).ready(function(){
			"use strict"; 
			$("#channelMenu").colorpicker(); 
			$("#channelMenuText").colorpicker();
			$("#channelTitle").colorpicker(); 
			$("#channelTitleText").colorpicker(); 
			$("#channelFooter").colorpicker(); 
			$("#channelFooterText").colorpicker(); 
			$("#activeSSL").click(function(){
				var formData = new FormData();
				$(".channelTheme").append("<div class=\"appendPreload\"><div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div></div>"); 
				$.ajax({
					url: "'.route("channel.ssl.active",$channel["domainPrimary"]).'",
					type: "post", 
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					dataType:"json",
					success:function(result){
						$(".channelTheme #appendPreload").css("display","none"); 
						location.reload();
						
					},
					error: function(result) {
					}
				});
			});
			getRegion(); 
			function getRegion(){
				$(".addRegion").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải ngôn ngữ, vui lòng chờ...</small></div>"); 
				$.ajax({
					url: "'.route('regions.json.list',$channel['domainPrimary']).'",
					type: "GET",
					dataType: "json",
					success: function (result) {
						$(".addRegion .loading").empty(); 
						$(".addRegion").append("<select class=\"selectRegion\" data-placeholder=\"Chọn ngôn ngữ...\" name=\"channelLang\" multiple required>"
							+"<option value=\"\"></option></select><label class=\"error\" for=\"channelLang\"></label>"); 
						$.each(result.region, function(i, item) {
							$(".addRegion .selectRegion").append("<option value="+item.id+"  data-icon=\"flag-"+item.iso.toLowerCase()+"\"  data-iso="+item.iso.toLowerCase()+" >"+item.country+"</option>");
						}); 
						function format(icon) {
							var originalOption = icon.element;
							return "<i class=\"flag " + $(originalOption).data("icon") + "\"></i> " + icon.text;
						}
						jQuery(".addRegion .selectRegion").select2({
							width: "100%",
							formatResult: format
						});
						$(".addRegion .selectRegion").data().select2.updateSelection('.$defaultSelectJson.'); 
					}
				});
			}
			$("#btnSaveChannelTheme").click(function(){
				var dataColorJson={};
				$.each($(".channelColor"), function(i,item){ 
					dataColorJson[$(this).attr("data-type")] = item.value; 
				});
				var dataColor=JSON.stringify(dataColorJson); 
				var channelLang=$("select[name=channelLang]").select2("val");
				var formData = new FormData();
				formData.append("dataColor", dataColor); 
				formData.append("channelLang", channelLang); 
				$(".channelTheme").append("<div class=\"appendPreload\"><div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div></div>"); 
				$.ajax({
					url: "'.route("channel.attribute.color",$channel["domain"]->domain).'",
					type: "post", 
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					dataType:"json",
					success:function(result){
						$(".channelTheme #appendPreload").css("display","none"); 
						location.reload();
						
					},
					error: function(result) {
					}
				});
			});
			$("#btnResetChannelColor").click(function(){
				$(".channelTheme").append("<div class=\"appendPreload\"><div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div></div>"); 
				$.ajax({
					url: "'.route("channel.attribute.color.reset",$channel["domain"]->domain).'",
					type: "post", 
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					cache: false,
					contentType: false,
					processData: false,
					dataType:"json",
					success:function(result){
						$(".channelTheme #appendPreload").css("display","none"); 
						location.reload();
					},
					error: function(result) {
					}
				});
			});
			$(".delBackground").click(function(){
				$.ajax({
					url: "'.route("channel.attribute.media.background.delete",$channel["domain"]->domain).'",
					type: "post", 
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					cache: false,
					contentType: false,
					processData: false,
					dataType:"json",
					success:function(result){
						location.reload(); 
					},
					error: function(result) {
					}
				});
				return false; 
			}); 
			$("#changeImageBackground").bind("change", function(){
				$(".channelTheme").append("<div class=\"appendPreload\"><div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div></div>"); 
				var files = $("#changeImageBackground").prop("files")[0];  
				var formData = new FormData();
				formData.append("file", files);
				formData.append("postType", "background");
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
							url: "'.route("channel.attribute.media.background",$channel["domain"]->domain).'",
							type: "post", 
							headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
							cache: false,
							contentType: false,
							processData: false,
							data: formDataChannel,
							dataType:"json",
							success:function(resultMedia){
								$(".channelTheme #appendPreload").css("display","none"); 
								location.reload();
								$(".imgBackground").empty(); 
								$(".imgBackground").append("<img class=\"img-responsive\" src=\""+result.url+"\">"); 
							},
							error: function(resultMedia) {
							}
						});
					},
					error: function(result) {
					}
				});
				
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