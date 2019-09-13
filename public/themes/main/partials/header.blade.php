
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{!! Theme::get('title') !!}</title>
        <meta charset="utf-8">
        <meta name="keywords" content="{!! Theme::get('keywords') !!}">
        <meta name="description" content="{!! Theme::get('description') !!}">
		@if(Theme::get('noindex'))<meta name="robots" content="noindex">@else<meta name="robots" content="index,follow,noodp" />@endif
		@if(!empty(Theme::get('canonical')))<link rel="canonical" href="{!! Theme::get('canonical') !!}" />@endif 
		@if(!empty(Theme::get('amp')))<link rel="amphtml" href="{!! Theme::get('amp') !!}" />@endif
		<meta name="author" content="{{$channel['domainPrimary']}}" />
		<meta name="root" content="{{route('channel.home',$channel['domainPrimary'])}}" />
		<link rel="icon" href="{!!Theme::asset()->url('img/favicon.png')!!}?v=3" />
		<meta name="_token" content="{{ csrf_token() }}">
		<meta name="copyright" content="Copyright &copy {{date('Y')}} {{$channel['domainPrimary']}}.　All Right Reserved." />
		<meta http-equiv="Content-Language" content="{{Lang::locale()}}" />
		<meta name="robots" content="notranslate"/>
		<meta name="distribution" content="Global" />
		<meta name="RATING" content="GENERAL" />
		<meta property="fb:pages" content="1531540343840372" />
		<meta property="og:locale" content="{{Lang::locale()}}">
		<meta property="og:title" content="{!! Theme::get('title') !!}">
		<meta property="og:description" content="{!! Theme::get('description') !!}">
		<meta property="og:type" content="{!! Theme::get('type') !!}">
		@if(!empty(Theme::get('canonical')))<meta property="og:url" content="{!! Theme::get('canonical') !!}">@endif
		<meta property="og:image" content="{!! Theme::get('image') !!}" />
		<meta property="og:image:width" content="720" />
		<meta property="og:image:height" content="480" />
		<meta name="google-site-verification" content="1vCnzxB3YhO_OfTffci7DZWSdxsnt7YGzgRvZiqtZUs" />
		@if(Theme::get('video'))<meta property="og:video" content="{!! Theme::get('video') !!}" />@endif 
		<link rel="alternate" type="application/rss+xml" title="Cung Cấp RSS" href="https://{{config('app.url')}}/rss/" />
        {!! Theme::asset()->styles() !!}
		<link media="all" type="text/css" rel="stylesheet" href="{!!Theme::asset()->url('css/style.default.css')!!}?v=90">
		@if(!empty(Theme::get('ads')))
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<script>
				(adsbygoogle = window.adsbygoogle || []).push({
					google_ad_client: "ca-pub-6739685874678212",
					enable_page_level_ads: true
				});
			</script>
		@endif
    </head>
	<div id="fb-root"></div>
	@if($channel['detectLang']=='vn')
	<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v4.0&appId=1506437109671064&autoLogAppEvents=1"></script>
    @else
		<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v4.0&appId=1506437109671064&autoLogAppEvents=1"></script>
	@endif
	<body class="">
	<!-- Preloader -->