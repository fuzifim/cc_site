<?
	$channel['theme']->setTitle('Đăng ký');
	$channel['theme']->setKeywords('Đăng ký tài khoản');
	$channel['theme']->setDescription('Đăng ký thành viên trên '.$channel['info']->channel_name);
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
		<div class="panel panel-default formRegister">
			<form id="formRegister">
				<div class="addMediaPreload"></div>
				<div class="panel-body">
					<div class="form-group">
						<a href="{{route('channel.login',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-chevron-left"></i> Trở về trang đăng nhập</a>
					</div>
					<div class="message"></div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<input placeholder="Nhập tên của bạn" id="name" name="name" value="" type="text" class="form-control" required>
						</div>
						<label class="error" for="name"></label>
					</div>
					<div class="form-group">
						<label for="phone" class="control-label">Số điện thoại</label>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1"><i class="flag flag-@if(!empty($channel['region']->iso)){{mb_strtolower($channel['region']->iso)}}@endif"></i></span>
							<input placeholder="09xxxxxxxx" id="phone" name="phone" value="" type="number" class="form-control" required>
						</div>
						<label class="error" for="phone"></label>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
							<input placeholder="Địa chỉ email của bạn" id="email" name="email" type="email" value="" class="form-control" required>
						</div>
						<label class="error" for="email"></label>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<input placeholder="Mật khẩu" id="password" name="password" type="password" class="form-control" required>
						</div>
						<label class="error" for="password"></label>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<input placeholder="Nhập lại mật khẩu" id="password_confirmation" name="password_confirmation" type="password"  class="form-control" required>
						</div>
						<label class="error" for="password_confirmation"></label>
					</div>
					<div class="form-group">
						@if(!empty($channel['region']->id))
							<input type="hidden" id="region" name="channelRegion" value="{{$channel['region']->id}}">
						@endif
					</div>
					<div class="form-group">
						<input type="checkbox" class="filled-in" name="accept_term" id="accept-term"/>
						<label for="filled-in-box">
							Đồng ý <a href="http://cungcap.net/dieu-khoan-su-dung">Điều khoản của chúng tôi</a>
						</label>
					</div>
				</div>
				<div class="panel-footer text-right">
					<button type="submit" class="btn btn-primary" id="btnRegister"><i class="glyphicon glyphicon-ok"></i> Đăng ký</button>
				</div>
			</form>
		</div>
	</div>
</div><!-- mainpanel -->
{!!Theme::partial('rightpanel', array('title' => 'Header'))!!}
<meta name="urlRegionList" content="{{route('regions.json.list',$channel['domainPrimary'])}}">
<meta name="urlSubRegionList" content="{{route('channel.home',$channel['domainPrimary'])}}/regions/json/subregion/list/">
</section>
<?
	$dependencies = array();
	$channel['theme']->asset()->writeScript('custom', '
	var $validator = jQuery("#formRegister").validate({
		highlight: function(element) {
		  jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
		},
		success: function(element) {
		  jQuery(element).closest(".form-group").removeClass("has-error");
		}
	});
	$(".formRegister").on("click","#btnRegister",function() {
		var $valid = jQuery("#formRegister").valid();
		if(!$valid) {
			$validator.focusInvalid();
			return false;
		}else{
			$(".addMediaPreload" ).append( "<div class=\"appendPreload\"><div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div></div>" );
			$(".formRegister .message").empty();
			var rootUrl=$("meta[name=root]").attr("content");
			var fullName=$(".formRegister input[name=name]").val();
			var phone=$(".formRegister input[name=phone]").val();
			var email=$(".formRegister input[name=email]").val();
			var password=$(".formRegister input[name=password]").val();
			var password_confirmation=$(".formRegister input[name=password_confirmation]").val();
			var region=$(".formRegister input[name=channelRegion]").val();
			var subRegion=$(".formRegister input[name=channelSubregion]").val();
			var rootUrl=$("meta[name=root]").attr("content");
			var formData = new FormData();
			formData.append("fullName", fullName);
			formData.append("phone", phone);
			formData.append("email", email);
			formData.append("password", password);
			formData.append("password_confirmation", password_confirmation);
			formData.append("region", region);
			formData.append("subRegion", subRegion);
			$.ajax({
				url: rootUrl+"/register",
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				type: "post",
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				dataType:"json",
				success:function(result){
					console.log(result);
					if(result.success==false){
						$(".addMediaPreload").empty();
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message,
							class_name: "growl-danger",
							sticky: false,
							time: ""
						});
					}else if(result.success==true){
						$(".addMediaPreload").empty();
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message,
							class_name: "growl-success",
							sticky: false,
							time: ""
						});
						window.location.href = result.return_url;
					}
				},
				error: function(result) {
				}
			});
		}
		return false;
	});
	$("#changeRegion").click(function () {
		$("#loading").css("visibility", "visible");
		$(".message").empty();
		$("#myModal .modal-title").empty();
		$("#myModal .modal-body").empty();
		$("#myModal .modal-footer").empty();
		var urlRegionList=$("meta[name=urlRegionList]").attr("content");
        $.ajax({
            url: urlRegionList,
            type: "GET",
            dataType: "json",
            success: function (result) {
				$("#loading").css("visibility", "hidden");
				$("#myModal .modal-title").text(result.message);
				$("#myModal .modal-body").append("<div class=\"row addContentRegion\"></div>");
				$.each(result.region, function(i, item) {
					$("#myModal .modal-body .addContentRegion").append("<div class=\"col-lg-4 col-md-4 col-sm-6 col-xs-6\"><div class=\"form-group\"><button class=\"btn btn-xs checkRegion\" style=\"white-space: inherit; background: none; text-align:left; \" type=\"button\" name=\"checkRegion\" value=\""+item.country+"\" data-id=\""+item.id+"\" data-flagiso=\""+item.iso.toLowerCase()+"\" id=\"checkRegion\"> <i class=\"flag flag-16 flag-"+item.iso.toLowerCase()+"\"></i> "+item.country+"</button></div></div>");
				})
				$("#myModal").modal("show");
            }
        });
    });
	$("#changeSubregion").click(function () {
		$("#loading").css("visibility", "visible");
		$(".message").empty();
		$("#myModal .modal-title").empty();
		$("#myModal .modal-body").empty();
		$("#myModal .modal-footer").empty();
		var idRegion=$("#region").val();
		var urlSubRegionList=$("meta[name=urlSubRegionList]").attr("content");
		$.ajax({
            url: urlSubRegionList+idRegion,
            type: "GET",
            dataType: "json",
            success: function (result) {
				$("#loading").css("visibility", "hidden");
				$("#myModal .modal-title").text(result.message);
				$("#myModal .modal-body").append("<div class=\"row addContentRegion\"></div>");
				$.each(result.subregion, function(i, item) {
					$("#myModal .modal-body .addContentRegion").append("<div class=\"col-lg-4 col-md-4 col-sm-6 col-xs-6\"><div class=\"form-group\"><button class=\"btn btn-xs checkSubRegion\" style=\"white-space: inherit; background: none; text-align:left; \" type=\"button\" name=\"checkSubRegion\" value=\""+item.subregions_name+"\" data-id=\""+item.id+"\" id=\"checkSubRegion\"><i class=\"glyphicon glyphicon-map-marker\"></i> "+item.subregions_name+"</button></div></div>");
				})
				$("#myModal").modal("show");
            }
        });
	});
	$("#myModal").on("click",".checkRegion",function() {
		var regionName=$(this).val();
		var regionId=$(this).attr("data-id");
		var flagIso=$(this).attr("data-flagiso");
		$("#region").val(regionId);
		$("#channelRegionName").text(regionName);
		$("#flagIso").html("<i class=\"flag flag-16 flag-"+flagIso+"\"></i>");
		$("#myModal").modal("hide");
	});
	$("#myModal").on("click",".checkSubRegion",function() {
		var subRegionName=$(this).val();
		var subRegionId=$(this).attr("data-id");
		var flagIso=$(this).attr("data-flagiso");
		$("#subRegion").val(subRegionId);
		$("#subRegionName").html("<i class=\"glyphicon glyphicon-map-marker\"></i> "+subRegionName);
		$("#myModal").modal("hide");
	});
	', $dependencies);
?>
<?
	$dependencies = array();
	$channel['theme']->asset()->writeScript('onload','
	', $dependencies);
?>