<?
	$channel['theme']->setTitle('Xác nhận quên mật khẩu');
	$channel['theme']->setKeywords('');
	$channel['theme']->setDescription('Xác nhận quên mật khẩu '.$channel['info']->channel_name);
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'));
?>
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>Xác nhận quên mật khẩu</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
		<form class="formForgot" method="post" action="{{route("forgot.password.reset.request",$channel["domainPrimary"])}}">
			<div class="form-group">
				<a href="{{route('channel.login',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-chevron-left"></i> Trở về trang đăng nhập</a>
			</div>
			<div class="panel panel-default mainbox"> 
				<div id="preloaderInBox">
					<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label" for="email">Địa chỉ email</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
							<input id="email" name="email" value="{{$infoToken->email}}" type="email" class="form-control" placeholder="" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label" for="passwordNew">Mật khẩu mới</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<input id="passwordNew" name="passwordNew" value="" type="password" class="form-control" placeholder="Nhập mật khẩu mới">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label" for="rePasswordNew">Nhập lại mật khẩu</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<input id="rePasswordNew" name="rePasswordNew" value="" type="password" class="form-control" placeholder="Nhập lại mật khẩu mới">
						</div>
					</div>
				</div>
				<div class="panel-footer text-right">
					<button id="changePasswordNew" class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok"></i> Lưu </button>
				</div>					
			</div>
		</form>
	</div>
</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom', '
		$(".mainbox #preloaderInBox").css("display","none"); 
		$(".formForgot").submit(function(e) {
			$(".mainbox").css("position","relative"); 
			$(".mainbox #preloaderInBox").css("display", "block"); 
			$("#changePasswordNew").addClass("disable"); 
			var formData = new FormData();
			formData.append("email", $("input[name=email]").val()); 
			formData.append("password", $("input[name=passwordNew]").val()); 
			formData.append("password_confirmation", $("input[name=rePasswordNew]").val()); 
			formData.append("token", "'.$token.'"); 
			$.ajax({
				url: "'.route("forgot.password.reset.request",$channel["domainPrimary"]).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				data:formData,
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) { 
					if(result.success==true){
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						}); 
						$(".mainbox #preloaderInBox").css("display", "none"); 
						$("#changePasswordNew").removeClass("disable"); 
					}else{
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-danger",
							sticky: false,
							time: ""
						}); 
						$(".mainbox #preloaderInBox").css("display", "none"); 
						$("#changePasswordNew").removeClass("disable"); 
					}
				},
				error: function(result) { 
					jQuery.gritter.add({
						title: "Thông báo!",
						text: "Không thể gửi yêu cầu, vui lòng thử lại. ", 
						class_name: "growl-danger",
						sticky: false,
						time: ""
					}); 
					$(".mainbox #preloaderInBox").css("display", "none"); 
					$("#changePasswordNew").removeClass("disable"); 
				}
			}); 
			e.preventDefault();
		});
	', $dependencies);
?>