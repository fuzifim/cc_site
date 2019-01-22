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
		<div class="container">
			<div class="row form-group">
				<div class="text-center text-success"><small>Page loaded in {{ (microtime(true) - LARAVEL_START) }} seconds.</small></div>
			</div>
		</div>
    </body>
</html>
