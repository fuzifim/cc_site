<?
	$channel['theme']->setTitle('');
	$channel['theme']->setKeywords('');
	$channel['theme']->setDescription(''); 
	//$channel['theme']->setCanonical(route("post.list",$channel["domainPrimary"]));
?>
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
<div class="container">
	{!!$getChannel->render()!!}
	<div class="panel panel-default">
		<div class="panel-body">
			@if(count($getChannel)>0)
				<ul class="channelList">
				@foreach($getChannel as $subChannel)
					<?
						if($subChannel->domainJoinPrimary->domain->domain_primary!='default'){
							if(count($subChannel->domainAll)>0){
								foreach($subChannel->domainAll as $domain){
									if($domain->domain->domain_primary=='default'){
										$domainPrimary=$domain->domain->domain; 
									}
								}
							}else{
								$domainPrimary=$subChannel->domainJoinPrimary->domain->domain; 
							}
						}else{
							$domainPrimary=$subChannel->domainJoinPrimary->domain->domain; 
						}
					?>
					<li>
						@if(!empty($subChannel->channelAttributeLogo->media->media_name))<a class="pull-left" href="{{route('channel.home',$domainPrimary)}}">
						  <img class="media-object channel-thumb" src="{{config('app.link_media').$subChannel->channelAttributeLogo->media->media_path.'xs/'.$subChannel->channelAttributeLogo->media->media_name}}" alt="">
						</a>@endif
						<h4 class="sender">
						  <a href="{{route('channel.home',$domainPrimary)}}">{{$subChannel->channel_name}}</a>
						</h4>
						<p><small><a href="{{route('channel.home',$domainPrimary)}}"><i class="glyphicon glyphicon-link"></i> {{$domainPrimary}}</a></small></p> 
						<p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> {{WebService::time_request($subChannel->channel_updated_at)}} - <span class="text-danger">{{$subChannel->channel_view}} lượt xem</span></small></p>
					</li>
				@endforeach
				</ul>
			@endif
		</div>
	</div>
</div>