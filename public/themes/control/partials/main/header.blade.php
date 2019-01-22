<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>{!! Theme::get('title') !!}</title>
<meta charset="utf-8">
<meta name="keywords" content="{!! Theme::get('keywords') !!}">
<meta name="description" content="{!! Theme::get('description') !!}">
<meta name="robots" content="index,follow,noodp" />
@if(!empty(Theme::get('canonical')))<link rel="canonical" href="{!! Theme::get('canonical') !!}" />@endif 
<meta name="author" content="{{$channel['domain']->domain}}" />
<meta name="root" content="{{route('channel.home',$channel['domain']->domain)}}" />
<link rel="icon" href="{!!Theme::asset()->url('img/favicon.png')!!}?v=2" />
<meta name="_token" content="{{ csrf_token() }}">
<meta name="copyright" content="Copyright &copy {{date('Y')}} {{$channel['domain']->domain}}.?All Right Reserved." />
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