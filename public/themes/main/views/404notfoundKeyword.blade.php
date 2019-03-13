<?
	Theme::setTitle($keyword); 
	Theme::setSearch($keyword); 
	Theme::setCanonical(str_replace(' ', '+', urldecode(route('keyword.show',array(config('app.url'),preg_replace('/([+])\\1+/', '$1',rtrim(str_replace(' ', '+', preg_replace('/[^\w\s]+/u',' ' ,$keyword)), '+'))))))); 
	Theme::setKeywords($keyword.' not found');
	Theme::setDescription($keyword.' Not found, please try again');
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
?>
<meta name="revisit-after" content="1 days">
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
{!!Theme::partial('formSearch', array('region' => $channel['region']))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('title') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
		<div class="panel panel-default">
			<div class="panel panel-body channelTheme">
				{{$keyword}} not found, please try again! 
			</div>
		</div>
	</div>

</div><!-- mainpanel -->
</section>