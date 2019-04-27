<?
	Theme::setTitle('404 '.$domainName);
	Theme::setKeywords($domainName.' not found');
	Theme::setDescription($domainName.' Not found, please try again');
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
?>
<meta name="revisit-after" content="1 days">
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('title') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
		<div class="panel panel-default">
			<div class="panel panel-body channelTheme">
			This domain {{$domainName}} not found, please try again! 
			</div>
		</div>
	</div>

</div><!-- mainpanel -->
</section>