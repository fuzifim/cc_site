<?
	$channel['theme']->setTitle('Đăng nhập');
?>
@include('themes.admin.inc.header')
	<div class="section">
		<div class="container">
			<div class="row">
			<div class="form-login mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2" style="">
				<div class="form-group">
					<a href="{{route('channel.home',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-chevron-left"></i> Trở về {{$channel['info']->channel_name}}</a>
				</div>
				<div class="panel panel-default"> 
					<div class="panel-body" >
						<div class="message"></div>
						<div style="margin-bottom: 25px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<input placeholder="Email hoặc điện thoại" id="email" name="email" type="text" class="form-control" value="{{old('email')}}">                                       
						</div>
						<div style="margin-bottom: 25px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<input placeholder="Mật khẩu" id="password" type="password" name="password" class="form-control">
						</div>
						<div class="form-group">
							<a href="{{route('forgot.password',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-lock"></i> Quên mật khẩu</a> | <a href="{{route('channel.register',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-ok-sign"></i> Đăng ký</a>
						</div>
					</div>    
					<div class="panel-footer text-right">
						<button type="button" class="btn btn-primary" id="btnLogin"><i class="fa fa-unlock-alt"></i> Đăng nhập</button>
					</div>					
				</div>
			</div>
			</div>
		</div>
	</div>
	<script>
	$('.form-login').on("click","#btnLogin",function() {
		$('.form-login .message').empty(); 
		var rootUrl=$('meta[name=root]').attr('content'); 
		var Email=$('.form-login input[name=email]').val(); 
		var Password=$('.form-login input[name=password]').val();  
		var rootUrl=$('meta[name=root]').attr('content'); 
		var formData = new FormData();
		formData.append("email", Email); 
		formData.append("password", Password); 
		$.ajax({
			url: rootUrl+"/login",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				console.log(result); 
				if(result.success==false){
					$('.form-login .message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				}else if(result.success==true){
					$('.form-login .message').append('<div class="alert alert-success alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					//location.reload(); 
					window.location.href = result.return_url;
				}
			},
			error: function(result) {
			}
		});
	});
	</script>
@include('themes.admin.inc.footer')