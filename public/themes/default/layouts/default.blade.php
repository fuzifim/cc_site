<!DOCTYPE html>
<html lang="en">

    <head>
        <meta name="keywords" content="{!!Theme::get('keywords')!!}">
        <meta name="description" content="{!!Theme::get('description')!!}">
        <meta name="author" content="{!!Theme::get('author')!!}">
        <title>{!!Theme::get('title')!!}</title>
         {!! Theme::asset()->styles() !!}
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
    </head>
    <body class="fixed-header smart-style-3 desktop-detected pace-done container">
		{!!Theme::partial('header')!!}
        {!!Theme::content()!!}
		{!!Theme::partial('footer')!!}
		{!! Theme::asset()->scripts() !!} 
		{!! Theme::asset()->container('footer')->scripts() !!}
    </body>
</html>


