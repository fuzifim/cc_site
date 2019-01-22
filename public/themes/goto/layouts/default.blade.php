<!DOCTYPE html>
<html>
    <head>
        <title>{!! Theme::get('title') !!}</title>
        <meta charset="utf-8">
		<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
        <meta name="keywords" content="{!! Theme::get('keywords') !!}">
        <meta name="description" content="{!! Theme::get('description') !!}">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" /> 
        {!! Theme::asset()->styles() !!}
        {!! Theme::asset()->scripts() !!}
		<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    </head>
    <body>
        {!! Theme::partial('header') !!}

        <div class="container">
            {!! Theme::content() !!}
        </div>

        {!! Theme::partial('footer') !!}
        {!! Theme::asset()->container('footer')->scripts() !!}
    </body>
</html>
