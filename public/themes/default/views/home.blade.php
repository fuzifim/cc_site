<?
	if(!empty($channel['info']->getSeo->channel_attribute_value)){
		$seoJson=json_decode($channel['info']->getSeo->channel_attribute_value); 
		if(!empty($seoJson->metaTitle)){
			$metaTitle=$seoJson->metaTitle; 
		}else{
			$metaTitle=$channel['info']->channel_name; 
		}
		if(!empty($seoJson->metaDescription)){
			$metaDescription=$seoJson->metaDescription; 
		}else{
			$metaDescription=$channel['info']->channel_description;
		}
	}else{
		$metaTitle=$channel['info']->channel_name; 
		$metaDescription=$channel['info']->channel_description;
	} 
	$channel['theme']->setTitle(html_entity_decode($metaTitle));
	if(!empty($channel['info']->channel_keywords)){
		$channel['theme']->setKeywords($channel['info']->channel_keywords);
	}else{
		$channel['theme']->setKeywords(html_entity_decode($metaTitle));
	}
	$channel['theme']->setDescription(str_limit(strip_tags(html_entity_decode($metaDescription),""), $limit = 250, $end='...')); 
	//$channel['theme']->setCanonical(route("channel.home",$channel["domainPrimary"]));
	if(count($channel['info']->channelAttributeBanner)>0 && !empty($channel['info']->channelAttributeBanner[0]->media->media_name)){
		$channel['theme']->setImage(config('app.link_media').$channel['info']->channelAttributeBanner[0]->media->media_path.'thumb/'.$channel['info']->channelAttributeBanner[0]->media->media_name);
	}else{
		$channel['theme']->setImage('http://'.$channel["domainPrimary"].Theme::asset()->url('img/cungcap.png')); 
	}
?>
{!!Theme::asset()->usePath()->add('css.bootstrap', 'css/bootstrap.min.css', array('core-style'))!!}
{!!Theme::asset()->usePath()->add('css.font.awesome', 'css/font-awesome.min.css', array('core-style'))!!}
{!!Theme::asset()->usePath()->add('css.smartadmin-production-plugins', 'css/smartadmin-production-plugins.min.css', array('core-style'))!!}
{!!Theme::asset()->usePath()->add('css.smartadmin-production', 'css/smartadmin-production.min.css', array('core-style'))!!}
{!!Theme::asset()->usePath()->add('css.smartadmin-skins', 'css/smartadmin-skins.min.css', array('core-style'))!!}
{!!Theme::asset()->usePath()->add('css.smartadmin-rtl', 'css/smartadmin-rtl.min.css', array('core-style'))!!}
{!!Theme::asset()->usePath()->add('css.demo', 'css/demo.min.css', array('core-style'))!!}
{!!Theme::asset()->usePath()->add('css.style', 'css/style.css', array('core-style'))!!}
{!!Theme::partial('leftPanel')!!}
<div id="main" role="main">
	<div id="content">
		<form method="post" class="padding-bottom-10" onsubmit="return false;">
			<div class="form-group">
				<input class="form-control" placeholder="Bạn cung cấp gì?" type="text">
			</div>
			<textarea rows="2" class="form-control" placeholder="Mô tả nội dung cung cấp..."></textarea>
			<div class="margin-top-10">
				<button type="submit" class="btn btn-sm btn-primary pull-right">
					Đăng lên
				</button>
				<a href="javascript:void(0);" class="btn btn-link profile-link-btn" rel="tooltip" data-placement="bottom" title="" data-original-title="Ảnh/ Video/ File"><i class="fa fa-camera"></i></a>
				<a href="javascript:void(0);" class="btn btn-link profile-link-btn" rel="tooltip" data-placement="bottom" title="" data-original-title="Thêm thẻ"><i class="fa fa-tags"></i></a>
				<a href="javascript:void(0);" class="btn btn-link profile-link-btn" rel="tooltip" data-placement="bottom" title="" data-original-title="Thêm địa chỉ"><i class="fa fa-map-marker"></i></a>
				<a href="javascript:void(0);" class="btn btn-link profile-link-btn" rel="tooltip" data-placement="bottom" title="" data-original-title="Thêm Giá"><i class="fa fa-dollar"></i></a>
			</div>
		</form>
		@foreach($posts->chunk(3) as $chunk)
		{!!Theme::partial('listPostChannel', array('chunk' => $chunk))!!}
		@endforeach
		{!!Theme::partial('pagination', array('paginator' => $posts))!!}
	</div>
</div>
{!!Theme::partial('pageFooter')!!}