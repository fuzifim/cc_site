<?
	$channel['theme']->setTitle('Quên mật khẩu');
?>
@include('themes.admin.inc.header')
<div class="section">
	<div class="container">
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="message"></div>
					<label class="control-label" for="item-channelEmail">Nhập email</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
						<input id="email" name="email" value="" type="email" class="form-control" placeholder="Nhập email để nhận lại mật khẩu mới">
					</div>
				</div>
				<div class="panel-footer text-right">
					<button id="sendEmailForgotPassword" class="btn btn-primary" type="button"><i class="glyphicon glyphicon-ok"></i> Gửi yêu cầu </button>
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
	$('#sendEmailForgotPassword').click(function () {
		$('#loading').css('visibility', 'visible'); 
		$('.message').empty(); 
		var formData = new FormData();
		formData.append("email", $('input[name=email]').val()); 
        $.ajax({
            url: "{{route('forgot.password.request',$channel['domain']->domain)}}",
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
					$('.message').append('<div class="alert alert-success alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				}else{
					$('.message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				}
            }
        });
    });
</script>
@include('themes.admin.inc.footer')