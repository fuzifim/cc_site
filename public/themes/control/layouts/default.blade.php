<!DOCTYPE html>
<html>
    <head>
		@if($channel['info']->channelService->id!=1 && $channel['info']->channel_date_end<\Carbon\Carbon::now()->format('Y-m-d H:i:s'))
			{!! Theme::partial('siteExpires') !!}
		@elseif($channel['info']->channel_status!='delete')
        {!! Theme::partial('header') !!}
        {!! Theme::content() !!}
		
        {!! Theme::partial('footer') !!}

        {!! Theme::asset()->container('footer')->scripts() !!}
		{!!Theme::partial('footerBeforeScript')!!}
        {!! Theme::asset()->scripts() !!}
		{!!Theme::partial('footerAfterScript')!!}
		
		@else
			{!! Theme::partial('siteDelete') !!}
		@endif
    </body>
</html>
