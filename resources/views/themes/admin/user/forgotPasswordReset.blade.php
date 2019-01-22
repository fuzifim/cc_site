<?
	$channel['theme']->setTitle('Đổi mật khẩu');
?>
@include('themes.admin.inc.header')
<div class="section">
	<div class="container">
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="message"></div>
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
					<button id="changePasswordNew" class="btn btn-primary" type="button"><i class="glyphicon glyphicon-ok"></i> Lưu </button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="loading">
	<ul class="bokeh">
		<li></li>
		<li></li>
		<li></li>
	</ul>
</div>
<script>
	$('#changePasswordNew').click(function () {
		$('#loading').css('visibility', 'visible'); 
		$('.message').empty(); 
		var formData = new FormData();
		formData.append("email", $('input[name=email]').val()); 
		formData.append("password", $('input[name=passwordNew]').val()); 
		formData.append("password_confirmation", $('input[name=rePasswordNew]').val()); 
		formData.append("token", "{{$token}}"); 
        $.ajax({
            url: "{{route('forgot.password.reset.request',$channel['domain']->domain)}}",
            type: "POST",
            cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
            success: function (result) {
				$('#loading').css('visibility', 'hidden');
				//console.log(result); 
				if(result.success==true){
					window.location.href="{{route('channel.home',$channel['domain']->domain)}}";
				}else{
					if(result.type=='validation'){
						$('.message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>'); 
						var res = jQuery.parseJSON(JSON.stringify(result.message)); 
						jQuery.each(res, function(i, val) {
								$('#alertError').append('<li>'+val+'</li>');
						});
					}else{
						$('.message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					}
				}
            }
        });
    });
</script>
@include('themes.admin.inc.footer')