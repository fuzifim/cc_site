<!DOCTYPE html>
<html>
    <head>
        {!! Theme::partial('header') !!}
        {!! Theme::content() !!}
        {!! Theme::partial('footer') !!}
        {!! Theme::asset()->container('footer')->scripts() !!}
		{!!Theme::partial('footerBeforeScript')!!}
        {!! Theme::asset()->scripts() !!}
		{!!Theme::partial('footerAfterScript')!!}
    </body>
</html>
