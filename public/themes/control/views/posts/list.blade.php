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
@if(count($posts)>0)
<div class="container">
	{!!$posts->render()!!}
	<div class="PostlistItem">
		@foreach($posts->chunk(3) as $chunk)
		{!!Theme::partial('listPostChannel', array('chunk' => $chunk))!!}
		@endforeach
	</div>
</div>
@endif