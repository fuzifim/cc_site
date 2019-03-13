<?
	$channel['theme']->setTitle('Đăng nhập');
	$channel['theme']->setKeywords('Đăng nhập '.$channel['info']->channel_name);
	$channel['theme']->setDescription('');
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
?>
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
		<form class="form-login mainbox" style="">
			<div class="form-group">
				<a href="{{route('channel.home',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-chevron-left"></i> Trở về {{$channel['info']->channel_name}}</a>
			</div>
			<div class="panel panel-default" style="position:relative;"> 
				<div id="preloaderInBox" style="display:none;">
					<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
				</div>
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
					<div class="form-group">
						<a href="{{route('login.social.authorize',array(config('app.url'),'facebook','i'=>$channel['info']->id,'url'=>$channel['domainPrimary']))}}" class="btn btn-xs btn-primary"><span class="fa fa-facebook"></span>  <span class=""> Với Facebook</span></a> 
						<a href="{{route('login.social.authorize',array(config('app.url'),'google','i'=>$channel['info']->id,'url'=>$channel['domainPrimary']))}}" class="btn btn-xs btn-danger"><span class="fa fa-google"></span> <span class="">Với Google</span></a>
					</div>
				</div>    
				<div class="panel-footer text-right">
					<button type="submit" class="btn btn-primary" id="btnLogin"><i class="fa fa-unlock-alt"></i> Đăng nhập</button>
				</div>					
			</div>
		</form>
	</div>
</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom', '
		$(".form-login").submit(function(e) {
			$(".form-login .message").empty(); 
			var rootUrl=$("meta[name=root]").attr("content"); 
			var Email=$(".form-login input[name=email]").val(); 
			var Password=$(".form-login input[name=password]").val();  
			var rootUrl=$("meta[name=root]").attr("content"); 
			var formData = new FormData();
			formData.append("email", Email); 
			formData.append("password", Password); 
			$("#preloaderInBox").css("display", "block"); 
			$.ajax({
				url: rootUrl+"/login",
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				type: "post",
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				dataType:"json",
				success:function(result){
					$("#preloaderInBox").css("display", "none");  
					if(result.success==false){
						$(".form-login .message").append("<div class=\"alert alert-danger alert-dismissable\" id=\"alertError\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"+result.message+"</div>"); 
					}else if(result.success==true){
						$(".form-login .message").append("<div class=\"alert alert-success alert-dismissable\" id=\"alertError\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"+result.message+"</div>"); 
						//location.reload(); 
						window.location.href = result.return_url;
					}
				},
				error: function(result) {
				}
			});
			e.preventDefault();
		});
	', $dependencies);
?>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('onload',' 
		var docHeight = jQuery(document).height();
		if(docHeight > jQuery(".mainpanel").height())
		jQuery(".mainpanel").height(docHeight);
	', $dependencies);
?>