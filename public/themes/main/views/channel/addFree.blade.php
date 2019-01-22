<?
	$channel['theme']->setTitle('Kích hoạt website miễn phí');
	$channel['theme']->setKeywords('Kích hoạt website miễn phí');
	$channel['theme']->setDescription('Kích hoạt website miễn phí của bạn. '); 
	if(!empty($channel['info']->channelAttributeBanner[0]->media->media_url)){$channel['theme']->setImage($channel['info']->channelAttributeBanner[0]->media->media_url);} 
?>
<?
	if($channel['info']->domainJoinPrimary->domain->domain_primary!='default'){
		if(count($channel['info']->domainAll)>0){
			foreach($channel['info']->domainAll as $domain){
				if($domain->domain->domain_primary=='default'){
					$domainPrimaryParent=$domain->domain->domain; 
				}
			}
		}else{
			$domainPrimaryParent=$channel['info']->domainJoinPrimary->domain->domain; 
		}
	}else{
		$domainPrimaryParent=$channel['info']->domainJoinPrimary->domain->domain; 
	}
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.sparkline', 'js/jquery.sparkline.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('retina', 'js/retina.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel" style="position:relative; ">
		<div id="preloaderInBox" style="display:none;">
			<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
		</div>
		<div class="alert alert-warning">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<strong>Thông báo!</strong> Gói miễn phí đang được bảo trì, vui lòng chọn gói cài đặt khác.
		</div>
	</div>

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		
	', $dependencies);
?>