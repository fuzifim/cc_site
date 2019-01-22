
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{!! Theme::get('title') !!}</title>
        <meta charset="utf-8">
        <meta name="keywords" content="{!! Theme::get('keywords') !!}">
        <meta name="description" content="{!! Theme::get('description') !!}">
		<meta name="robots" content="index,follow,noodp" />
		@if(!empty(Theme::get('canonical')))<link rel="canonical" href="{!! Theme::get('canonical') !!}" />@endif 
		@if(!empty(Theme::get('canonicalamp')))<link rel="amphtml" href="{!! Theme::get('canonicalamp') !!}">@endif 
		<meta name="author" content="{{$channel['domain']->domain}}" />
		<meta name="root" content="{{route('channel.home',$channel['domain']->domain)}}" />
		<link rel="icon" href="@if(!empty($channel['info']->channelAttributeLogo->media->media_name)){{config('app.link_media').$channel['info']->channelAttributeLogo->media->media_path.'xs/'.$channel['info']->channelAttributeLogo->media->media_name}}@else {!!Theme::asset()->url('img/favicon.png')!!}?v=3 @endif" />
		<meta name="_token" content="{{ csrf_token() }}">
		<meta name="copyright" content="Copyright &copy {{date('Y')}} {{$channel['domain']->domain}}.　All Right Reserved." />
		<meta http-equiv="Content-Language" content="{{Lang::locale()}}" />
		<meta name="robots" content="notranslate"/>
		<meta name="distribution" content="Global" />
		<meta name="RATING" content="GENERAL" />
		<meta property="og:locale" content="{{Lang::locale()}}">
		<meta property="og:title" content="{!! Theme::get('title') !!}">
		<meta property="og:description" content="{!! Theme::get('description') !!}">
		<meta property="og:type" content="{!! Theme::get('type') !!}">
		<meta property="og:url" content="{!! Theme::get('url') !!}">
		<meta property="og:image" content="{!! Theme::get('image') !!}" />
		<meta property="og:image:width" content="720" />
		<meta property="og:image:height" content="480" />
		@if(Theme::get('video'))<meta property="og:video" content="{!! Theme::get('video') !!}" />@endif
        {!! Theme::asset()->styles() !!}
		<link media="all" type="text/css" rel="stylesheet" href="{!!Theme::asset()->url('css/style.default.css')!!}?v=57">
		<style>
			@if(!empty($channel['color']->channelTitle)) 
				section{background:{{$channel['color']->channelTitle}}; }  
				.nav-bracket > li > a{color:{{$channel['color']->channelTitleText}}; }
				.nav-bracket .children > li > a{color:{{$channel['color']->channelTitleText}}; }
				.sidebartitle{color:{{$channel['color']->channelTitleText}}; }
				.contentpanel .panel-primary .panel-heading{background-color:{{$channel['color']->channelTitle}} !important;border-color:{{$channel['color']->channelTitle}} !important;color:{{$channel['color']->channelTitleText}} !important;}
				.contentpanel .panel-primary .heading-program .categoryParentTitle a{color:{{$channel['color']->channelTitleText}} !important;}
				.contentpanel .panel-primary .heading-program .dropdown-toggle{color:{{$channel['color']->channelTitleText}} !important;}
			@else
				.contentpanel .panel-primary{border-color:#d82731 !important;}
				.contentpanel .panel-primary .panel-heading{background-color:#d82731 !important;border-color:#d82731 !important;}
				.contentpanel .panel-primary .heading-program .categoryParentTitle a{color:#fff !important;}
				.contentpanel .panel-primary .heading-program .dropdown-toggle{color:#fff !important;}
			@endif
			@if(!empty($channel['color']->channelMenu))
				.pageheader h1{color:{{$channel['color']->channelMenuText}};}
				.pageheader{background:{{$channel['color']->channelMenu}} !important;} 
			@endif
			@if(!empty($channel['color']->channelFooter))
				.siteFooter{background:{{$channel['color']->channelFooter}} !important; color:{{$channel['color']->channelFooterText}}; }
				.siteFooter h3 a{color:{{$channel['color']->channelFooterText}};}
			@endif
			@if(!empty($channel['info']->channelAttributeBackground->media->media_url))
				body{background: url({{$channel['info']->channelAttributeBackground->media->media_url}}) !important; background-attachment: fixed !important; background-position:center top; background-size:cover; background-repeat:no-repeat;}
			@endif
		</style>
		@if(!empty($channel['color']->headerScript)){!!$channel['color']->headerScript!!}@endif 
    </head>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = 'https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.11&appId=1506437109671064&autoLogAppEvents=1';
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
    <body class="@if($channel['info']->channel_parent_id!=0) fixed @endif">