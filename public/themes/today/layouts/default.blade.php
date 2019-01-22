<!DOCTYPE html>
<html>
    <head>
        {!! Theme::partial('header') !!}
        {!! Theme::content() !!}
        {!! Theme::partial('footer') !!}
        {!! Theme::asset()->container('footer')->scripts() !!}
        {!! Theme::asset()->scripts() !!}
    </body>
</html>
