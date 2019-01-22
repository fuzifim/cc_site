<!DOCTYPE html>
<html id="noIE" lang="{{Lang::locale()}}">
    <head>
        <title>{!! Theme::get('title') !!}</title>
        <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="keywords" content="{!! Theme::get('keywords') !!}">
        <meta name="description" content="{!! Theme::get('description') !!}">
		<meta name="robots" content="index,follow,noodp" />
		<meta name="author" content="{{config('app.url')}}" />
		<link rel="shortcut icon" href="{{asset('assets/img/favicon.png?v2')}}" type="image/x-icon" />
		<link rel="apple-touch-icon" href="{{asset('assets/img/favicon.png?v2')}}" type="image/x-icon"  />
		<meta name="root" content="{{route('channel.home',$channel['domain']->domain)}}" />
		<meta name="copyright" content="Copyright &copy {{date('Y')}} {{config('app.url')}}.All Right Reserved." />
		<meta http-equiv="Content-Script-Type" content="text/javascript" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<meta http-equiv="Content-Language" content="{{Lang::locale()}}" />
		<meta name="robots" content="notranslate"/>
		<link rev="made" href="mailto:{{config('app.emailfix')}}" />
		<meta name="distribution" content="Global" />
		<meta name="RATING" content="GENERAL" />
		<meta property="og:locale" content="{{Lang::locale()}}">
		<meta name="_token" content="{{ csrf_token() }}">
		<link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}?v=1">
		<link media="all" type="text/css" rel="stylesheet" href="{{asset('assets/css/custom-admin-site.css')}}?v=3">
		{!!Theme::asset()->add('core-style-multi', 'assets/css/bootstrap-multiselect.css')!!}
		{!!Theme::asset()->add('core-script-jquery', 'assets/js/jquery-1.11.3.min.js')!!}
		{!!Theme::asset()->add('core-script-bootstrap', 'assets/js/bootstrap.min.js')!!}
        {!! Theme::asset()->styles() !!}
        {!! Theme::asset()->scripts() !!}
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
    </head>
    <body>
	<div id="myModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"></h4>
		  </div>
		  <div class="modal-body">
		  </div>
		  <div class="modal-footer">
		  </div>
		</div>
	  </div>
	</div>
	<header>
		<nav class="navbar navbar-default navbar-site">
			<div class="container">
			  <div class="navbar-header">
				<div class="navbar-toggle menu-mobile">
					<a class=" btn-navbar-mobile favicon coverImageThumbnail " href="{{route('channel.home',$channel['domain']->domain)}}"><img class="img-responsive img-thumbnail" src="@if(!empty($channel['info']->channelAttributeLogo->media->media_url)){{$channel['info']->channelAttributeLogo->media->media_url}} @else {{asset('assets/img/logo-default.jpg')}} @endif"></a>
					<button class="btn btn-sm btn-default" style="float:right; " data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"><i class="glyphicon glyphicon-list"></i> Menu</button>
				</div>
			  </div>
			  <div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="{{route('channel.home',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-home"></i> Trang chủ</a></li>
					@foreach($channel['category'] as $menu)
						@if($menu->category->parent_id==0)
							@if($menu->category->children->count())
								<li class="dropdown">
									<a href="{{route('channel.slug',array($channel['domain']->domain,$menu->category->getSlug->slug_value))}}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $menu->category->category_name }} <i class="glyphicon glyphicon-menu-down"></i></a>
									{!! Theme::partial('childItems',array('menus'=>$menu->category->children,'menuParent'=>$menu->category)) !!}
								</li>
							@else
								<li>
									<a href="{{route('channel.slug',array($channel['domain']->domain,$menu->category->getSlug->slug_value))}}">{{ $menu->category->category_name }}</a>
								</li>
							@endif
						@endif
					@endforeach
					@if (Auth::check())
						<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-user"></i> {{Auth::user()->name}} <i class="glyphicon glyphicon-menu-down"></i></a>
							<ul class="dropdown-menu">
								@if($channel['security']==true)
									@include('themes.admin.partial.menuManage')
								@endif
								@include('themes.admin.partial.listChannelManage')
								<li><a href="{{route('channel.profile.info',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-user"></i> Tài khoản</a></li>
								<li><a href="{{route('channel.logout',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-log-out"></i> Đăng xuất</a></li>
							</ul>
						</li>
					@else
						<li><a href="{{route('channel.login',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-user"></i> Đăng nhập</a></li>
					@endif
				</ul>
			  </div><!--/.nav-collapse -->
			</div><!--/.container-fluid -->
		</nav>
	</header>
	<div class="section-head" style="">
		<div class="container">
			<div class="row" style="position:relative; ">
				<div class="logo coverImageThumbnail hidden-xs"><a  href="{{route('channel.home',$channel['domain']->domain)}}"><img class="img-responsive img-thumbnail" src="@if(!empty($channel['info']->channelAttributeLogo->media->media_url_xs)){{$channel['info']->channelAttributeLogo->media->media_url_xs}} @else {{asset('assets/img/logo-default.jpg')}} @endif"></a></div>
			</div>
		</div>
		<h1 class="h1-title">{!! Theme::get('title') !!}</h1>
		<span>{!! Theme::get('description') !!}</span>
	</div>
	<div class="message"></div>
	@if($channel['security']==true)
	<div class="container">
		<div class="row">
			<div class="alert alert-warning">
				<strong>Thông báo!</strong> Đã có phiên bản website mới ngày 17/11/2017 <a href="" class="btn btn-danger updateWeb"><i class="glyphicon glyphicon-hand-right"></i> Cập nhật ngay</a>
			</div>
		</div>
	</div>
	@endif
	@if($channel['security']==true)
		@if(!empty($channel['info']->getCartOrderChannel->id) && count($channel['info']->getCartOrderChannel->attribute)>0)
			<div class="container">
				<div class="row">
					<div class="alert alert-warning">
						<strong>Thông báo!</strong> Bạn có đơn hàng chưa thanh toán. <a href="{{route('cart.show',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-hand-right"></i> Xem đơn hàng</a>
					</div>
				</div>
			</div>
		@endif
	@endif
<script>
	$(".updateWeb").click(function(){
		$.ajax({
			url: "{{route("channel.update",$channel["domain"]->domain)}}",
			headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
			type: "post",
			cache: false,
			contentType: false,
			processData: false,
			dataType:"json",
			success:function(result){
				$(".message" ).append( '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					$('html, body').animate({scrollTop: $(".message").offset().top}, 'slow'); 
					setTimeout(function(){
						window.location.href="{{route('channel.home',$channel['domain']->domain)}}"; 
					},1000);
				
			},
			error: function(result) {
			}
		});
		return false; 
	}); 
</script>