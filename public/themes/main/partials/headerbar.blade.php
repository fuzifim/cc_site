<nav class="navbar navbar-default headerbar mb5">
	<div class="container-fluid">
	  <div class="navbar-header">
		<div class="pull-right">
			<button type="button" class="navbar-toggle collapsed btn-top-nav" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"><i class="fa fa-bars"></i></button> 
		</div>
		@if(Cart::getContent()->count()>0)<span class="pull-right badge badge-danger visible-xs">{{Cart::getContent()->count()}}</span>@endif
		<a class="navbar-brand logo_header" href="https://cungcap.net"><img class="" id="logoChannel" src="https://{!! config('app.url') !!}/assets/img/logo-red-white.svg" alt="{!!$channel['info']->channel_name!!}" title="{!!$channel['info']->channel_name!!}"></a>
	  </div>
		<div id="navbarSearch" class="collapse" aria-expanded="false" style="height: 1px;">
		  
		</div>
	  <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
		<ul class="nav navbar-nav navbar-right">
		@if (Auth::check())
		  <li  class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-user"></i> {{Auth::user()->name}} @if(Cart::getContent()->count()>0)<span class="pull-right badge badge-danger">{{Cart::getContent()->count()}}</span>@else <span class="fa fa-angle-down"></span>@endif</a>
			<ul class="dropdown-menu">
				<!--<li><a href="{{route('post.me',$channel['domainPrimary'])}}" class=""><i class="fa fa-bars"></i><span class=""> Tin đã đăng</span></a></li>-->
				<li class="dropdown-header">Quản lý dịch vụ của tôi</li>
				<li><a href="{{route('channel.me',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-cloud-upload"></i> Website của tôi</a></li>
					@if(Auth::user()->hasRole(['admin', 'manage']))
						<li><a href="#"><i class="glyphicon glyphicon-globe"></i> Mục tin</a></li>
					@endif
				{{--<li><a href="{{route('channel.domain.list',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-globe"></i> Tên miền</a></li>--}}
				{{--<li><a href="{{route('channel.hosting.list',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-hdd"></i> Web Hosting</a></li>--}}
				{{--<li><a href="{{route('channel.mailserver.list',$channel['domainPrimary'])}}"><i class="fa fa-envelope-o"></i> Email Server</a></li>--}}
				{{--<li><a href="{{route('channel.cloud.list',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-cloud"></i> Cloud Server</a></li>--}}
				<li class="divider"></li>
				<li class="dropdown-header">Tài khoản</li>
				<li><a href="{{route('channel.profile.info',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-user"></i> Hồ sơ</a></li>
				@if(Cart::getContent()->count()>0)<li><a href="{{route('pay.cart',$channel['domainPrimary'])}}"><span class="pull-right badge badge-danger">{{Cart::getContent()->count()}}</span> <i class="glyphicon glyphicon-shopping-cart"></i> Giỏ hàng</a></li>@endif
				<li><a href="{{route('pay.history',$channel['domainPrimary'])}}"><i class="glyphicon  glyphicon-credit-card"></i> Thanh toán </a></li>
				{{--<li><a href="http://help.cungcap.net/" target="_blank"><i class="glyphicon glyphicon-question-sign"></i> Trợ giúp</a></li>--}}
				<li><a href="{{route('channel.logout',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-log-out"></i> Đăng xuất</a></li>
			</ul>
		  </li>
		@else 
			<li class="active"><a href="https://cungcap.net" class=""><i class="glyphicon glyphicon-user"></i><span class=""> Đăng nhập</span></a></li>
		@endif
		</ul>
	  </div><!--/.nav-collapse -->
	</div><!--/.container-fluid -->
</nav>

@if(Auth::check())
	@if(Auth::user()->user_status!='active' && !empty(Auth::user()->email))
		<div class="alert alert-warning">
			Xin chào <strong>{!!Auth::user()->name!!}!</strong> Tài khoản của bạn chưa được kích hoạt, vui lòng kiểm tra email đăng ký <strong>{{Auth::user()->email}}</strong> để nhận mã kích hoạt tài khoản hoặc truy cập vào <a href="{{route('channel.profile.info',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-user"></i> Hồ sơ</a> để thay đổi email hoặc gửi lại mã kích hoạt!  
		</div>
	@elseif(Auth::user()->user_status!='active' && empty(Auth::user()->email))
		<div class="alert alert-warning">
			Xin chào <strong>{!!Auth::user()->name!!}!</strong> Tài khoản của bạn chưa được kích hoạt, vui lòng truy cập vào <a href="{{route('channel.profile.info',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-user"></i> Hồ sơ</a> thêm địa chỉ email và gửi mã kích hoạt vào email!  
		</div>
	@endif
@endif
@if(Auth::check())
<?
	if(!empty($channel['info']->joinAddress[0]->address->joinRegion->region->id)){
		$regionDefaultId=$channel['info']->joinAddress[0]->address->joinRegion->region->id; 
		$regionDefaultIso=mb_strtolower($channel['info']->joinAddress[0]->address->joinRegion->region->iso); 
	}else{
		$regionDefaultId=""; 
		$regionDefaultIso=""; 
	}
	$dependencies = array(); 
	Theme::asset()->writeScript('custom','
		$("#btnJoinChannel").click(function() {
			var rootUrl=$("meta[name=root]").attr("content"); 
			$.ajax({
				url: "'.route("channel.profile.joinchannel",$channel["domainPrimary"]).'",
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				type: "post",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				success:function(result){
					jQuery.gritter.add({
						title: "Thông báo!",
						text: "Gia nhập thành công! ", 
						class_name: "growl-success",
						sticky: false,
						time: ""
					});
					location.reload(); 
				},
				error: function(result) {
				}
			});
		}); 
	', $dependencies);
?>
@endif