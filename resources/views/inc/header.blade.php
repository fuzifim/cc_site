<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" class="ie" dir="ltr" lang="{{Lang::locale()}}">
<![endif]-->
<!--[if IE 7]>
<html id="ie7" class="ie" dir="ltr" lang="{{Lang::locale()}}">
<![endif]-->
<!--[if IE 8]>
<html id="ie8" class="ie" dir="ltr" lang="{{Lang::locale()}}">
<![endif]-->
<!--[if IE 9]>
<html class="ie" dir="ltr" lang="{{Lang::locale()}}">
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html id="noIE"  itemscope="" itemtype="webpage" lang="{{Lang::locale()}}">
<!--<![endif]-->
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link rel="shortcut icon" href="{{asset('assets/img/favicon.png?v2')}}" type="image/x-icon" />
<link rel="apple-touch-icon" href="{{asset('assets/img/favicon.png?v2')}}" type="image/x-icon"  />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<base href="{{ url('/') }}"/>
@yield('seo')
<meta name="robots" content="index,follow,noodp" />
<meta name="author" content="{{config('app.url')}}" />
<meta name="copyright" content="Copyright &copy {{date('Y')}} {{config('app.url')}}.　All Right Reserved." />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Language" content="{{Lang::locale()}}" />
<meta name="robots" content="notranslate"/>
<link rev="made" href="mailto:{{config('app.emailfix')}}" />
<meta name="distribution" content="Global" />
<meta name="RATING" content="GENERAL" />
<meta property="og:locale" content="{{Lang::locale()}}">
<link rel="INDEX" href="/" />
<!--[if lt IE 9]>
	<script src="{{asset('assets/js/html5.js')}}" type="text/javascript"></script>
	<script src="{{asset('assets/js/selectivizr.js')}}" type="text/javascript"></script>
	<script src="{{asset('assets/js/css3-mediaqueries.js')}}" type="text/javascript"></script>
<![endif]-->
<!--[if lt IE 10]>
   <script src="{{asset('assets/js/matchMedia.js')}}" type="text/javascript"></script>
<![endif]-->
<script src="{{asset('assets/js/jquery.min.1.11.3.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<link rel="stylesheet" id="bootstrap-css-css"  href="{{asset('assets/css/bootstrap.min.css')}}" type="text/css" media="all" />
<link rel="stylesheet" id="wplc-font-awesome-css"  href="{{asset('assets/css/font-awesome.min.css')}}" type="text/css" media="all" />
<script src="{{asset('assets/js/jquery.autocomplete.min.js')}}" type="text/javascript"></script>
<link type="text/css" href="{{asset('assets/css/custom.css')}}" rel="stylesheet"/>
<meta name="_token" content="{{ csrf_token() }}">
</head>
<body ng-app="application" ng-controller="MessageController" class="site-master">
	<span ng-bind="getMessageUnread"></span>
	<header id="masthead" class="site-header" role="banner">
		 <div class="navbar navbar-inverse navbar-top">
			<div class="navbar-header">
				 <a class="navbar-brand logo_header" href="{{url('/')}}"><img src="{{asset('assets/img/logo-mobile.png')}}" alt="{{config('app.appname')}}"></a>
				<ul class="navbar-toggle menu-mobile">
					<li class="menu-mobile-item"><a class="btn btn-sm btn-default" data-toggle="collapse" data-target=".navbar-collapse" >
					@if(Auth::check())
						@if (Auth::user()->avata != "")
							<img class="img-thumbnail img-thumbnail-small" src="{{Auth::user()->avata}}" width="18">
						@else
							<span class="glyphicon glyphicon-user" style="color:#de1a42; "></span>
						@endif
					@else
						<i class="glyphicon glyphicon-list"></i>
					@endif 
						Menu <span class="glyphicon glyphicon-menu-down"></span></a>
					</li>
				</ul>
			</div><!--navbar-header-->
			<div class="navbar-collapse collapse">
				 <ul class="nav navbar-nav navbar-right">
						<li><a href="{{url('/')}}"><i class="glyphicon glyphicon-home"></i> @lang('main.home')</a></li>
						<li><a href="{{url('/')}}"><i class="glyphicon glyphicon-road"></i> @lang('main.about_us')</a></li>
						<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="{{url('/')}}"><i class="glyphicon glyphicon-retweet"></i> @lang('main.service') <i class="glyphicon glyphicon-menu-down"></i></a>
							<ul class="dropdown-menu">
								<li><a href="#"><i class="glyphicon glyphicon-globe"></i> Đăng ký tên miền</a></li>
								<li><a href="#"><i class="glyphicon glyphicon-cloud"></i> Lưu trữ dữ liệu - Cloud Server</a></li>
								<li><a href="#"><i class="glyphicon glyphicon-envelope"></i> Tạo email doanh nghiệp</a></li>
							</ul>
						</li>
						<li><a href="{{url('/')}}"><i class="glyphicon glyphicon-shopping-cart"></i> @lang('main.product')</a></li>
						<li><a href="{{url('/')}}"><i class="glyphicon glyphicon-exclamation-sign"></i> @lang('main.news')</a></li>
						<li><a href="{{url('/')}}"><i class="glyphicon glyphicon-envelope"></i> @lang('main.contact')</a></li>
						@if (Auth::check())
							<li id="account_head" class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
									@if (Auth::user()->avata != "")
										<img class="img-thumbnail img-thumbnail-small" src="{{Auth::user()->avata}}" width="18">
									@else
										<span class="glyphicon glyphicon-user" style="color:#de1a42; "></span>
									@endif  
									<span class="user_head">{{Auth::user()->name}}</span> <i class="glyphicon glyphicon-menu-down"></i>
								</a>
								@include('partials.menu_dropdown_dashboard')
							</li>
						@else
							<li><a href="{{route('user.login.page')}}"><i class="glyphicon glyphicon-user"></i> Đăng nhập</a></li>
						@endif
				 </ul>
			</div>
		 </div><!--navbar-inverse-->
	</header>