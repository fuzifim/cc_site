<?
	$channel['theme']->setTitle('Kích hoạt tài khoản');
	$channel['theme']->setKeywords('Kích hoạt tài khoản');
	$channel['theme']->setDescription('Kích hoạt tài khoản đăng ký trên '.$channel['info']->channel_name); 
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
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
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
		<div class="row-pad-5">
			@if($activeStatus=='active')
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<strong>Thông báo!</strong> Kích hoạt tài khoản thành công!
				</div>
			@elseif($activeStatus=='activated')
				<div class="alert alert-warning">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<strong>Thông báo!</strong> Tài khoản này đã được kích hoạt! 
				</div>
			@elseif($activeStatus=='noUser')
				<div class="alert alert-warning">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<strong>Thông báo!</strong> Không tìm thấy tài khoản hoặc tài khoản đã được kích hoạt! 
				</div>
			@elseif($activeStatus=='noFindCode')
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<strong>Thông báo!</strong> Không tìm thấy mã kích hoạt hoặc tài khoản này đã được kích hoạt! 
				</div>
			@endif
		</div>
	</div>

</div><!-- mainpanel -->
{!!Theme::partial('rightpanel', array('title' => 'Header'))!!}
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('onload',' 
		var docHeight = jQuery(document).height();
		if(docHeight > jQuery(".mainpanel").height())
		jQuery(".mainpanel").height(docHeight);
	', $dependencies);
?>