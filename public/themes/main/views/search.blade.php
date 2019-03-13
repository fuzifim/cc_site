<?
	Theme::setTitle($keyword);
	Theme::setCanonical(route('keyword.show',array(config('app.url'),WebService::characterReplaceUrl($keyword)))); 
	Theme::setSearch($keyword);
	Theme::setDescription('Kết quả tìm kiếm ('.$keyword.' - '.WebService::vn_str_filter($keyword).') trên '.$channel['info']->channel_name); 
	Theme::setNoindex('noindex');
	Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.sparkline', 'js/jquery.sparkline.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('retina', 'js/retina.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('swiper.min', 'library/swiper/js/swiper.min.js', array('core-script'));
?>
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
{!!Theme::partial('formSearch', array('region' => $channel['region']))!!}
	@if(!empty($keyword))
		<ol class="breadcrumb mb5">
			<li class="breadcrumb-item"><a href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> Cung Cấp</a></li> 
			<li class="breadcrumb-item"><a href="{{route('search.query',$channel['domainPrimary'])}}">Tìm kiếm</a></li> 
			<li class="breadcrumb-item active">{!!$keyword!!}</li> 
			<script type="application/ld+json">
				{
				"@context": "http://schema.org",
				"@type": "BreadcrumbList",
				"itemListElement": [
						{
						"@type": "ListItem",
						"position": 1,
						"item": {
						"@id": "{{route('channel.home',$channel['domainPrimary'])}}",
						"name": "Trang chủ"
						}
						},{
							"@type": "ListItem",
							"position": 2,
							"item": {
							"@id": "{{route('search.query',$channel['domainPrimary'])}}",
							"name": "Tìm kiếm"
							}
						}
					]
				}
			</script>
		</ol> 
	@endif
	<div class="pageheader">
		<h1>{!!$keyword!!}</h1>
		<span><small>@if(count($getPosts)>0)Khoảng {{$getPosts->total()}} kết quả cho từ khóa {!!$keyword!!}@elseif(count($getItems)>0)Khoảng {{$getItems->total()}} kết quả cho từ khóa {!!$keyword!!} @endif</small></span>
	</div>
	<div class="contentpanel">
		@if(count($getItems)>0 && count($affiliateSearch)<=0 && count($domainSearch)<=0)
			{!!Theme::partial('search.search_1', array('getItems' => $getItems,'affiliateSearch'=>$affiliateSearch,'domainSearch'=>$domainSearch,'ads'=>$ads))!!} 
		@elseif(count($getItems)>0 && count($affiliateSearch)>0 && count($domainSearch)<=0)
			{!!Theme::partial('search.search_2', array('domainSearch'=>$domainSearch,'ads'=>$ads))!!} 
		@elseif(count($getItems)>0 && count($affiliateSearch)>0 && count($domainSearch)>0)
			{!!Theme::partial('search.search_3', array('getItems' => $getItems,'affiliateSearch'=>$affiliateSearch,'domainSearch'=>$domainSearch,'ads'=>$ads,'newsSearch'=>$newsSearch))!!} 
		@elseif(count($getItems)<=0 && count($domainSearch)>0)
			{!!Theme::partial('search.search_4', array('domainSearch'=>$domainSearch,'ads'=>$ads))!!} 
		@elseif(count($getItems)<=0 && count($affiliateSearch)>0 && count($domainSearch)>0)
			{!!Theme::partial('search.search_5', array('getItems'=>$getItems,'affiliateSearch'=>$affiliateSearch,'ads'=>$ads))!!} 
		@else 
			<div class="alert alert-warning">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<strong>Thông báo!</strong> Không tìm thấy kết quả tìm kiếm nào.
			</div>
		@endif
	</div>
</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		var swiper = new Swiper(".swiper-container", {
			slidesPerView: 2,
			slidesPerColumn: 1,
			spaceBetween: 20,
			navigation: {
				nextEl: ".carousel_control_right",
				prevEl: ".carousel_control_left",
			},
			autoplay: {
                        delay: 5000,
                      },
		});
	', $dependencies);
?>
<?

	$dependencies = array(); 
	Theme::asset()->writeScript('customaff',' 
		/*$(".htag").each(function() {
			$(this).text($(this).text());
		});*/
		$(".siteLink").click(function(){
			window.open(jQuery.parseJSON($(this).attr("data-url")),"_blank")
			return false; 
		});
		$(".playVideoYoutube").click(function(){
			var idVideo=$(this).attr("data-id"); 
			$("#modalYoutube .modal-title").empty(); 
			$("#modalYoutube .modal-body").empty(); 
			$("#modalYoutube .modal-title").html($(this).attr("data-title")); 
			$("#modalYoutube .modal-body").append("<div class=\"embed-responsive embed-responsive-16by9\"><iframe class=\"embed-responsive-item\" src=\"//www.youtube.com/embed/"+idVideo+"\"></iframe></div>"); 
			$("#modalYoutube").modal("show"); 
			return false; 
		});
	', $dependencies);
?>