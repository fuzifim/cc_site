<div class="list-group-item media widget-slider">
	<h5 class="subtitle mb5">Top products</h5>
	<div id="carousel-2" class="swiper-container" data-ride="carousel">
		<div class="swiper-wrapper">
		<?
			$i=0; 
		?>
		@foreach($affiliateSearch as $aff)
		<div class="swiper-slide @if($i==1) active @endif">
			<?
				if($aff->campaign=='vuivui.com'){
					$linkImage=str_replace('https:///Products/Images/', 'https://cdn.tgdd.vn/Products/Images/', $aff->image); 
				}else{
					$linkImage=$aff->image; 
				}
			?>
			<a class="image img-thumbnail siteLink" data-url='@if(!empty($aff->deeplink)){{json_encode($aff->deeplink)}}@endif' target="_blank" href="javascript:void(0);"><img src="{{$linkImage}}" data-src="" class="postMediaXs" alt="" title="" ></a> 
			<h3 class="blog-title"><a class="title siteLink" data-url='@if(!empty($aff->deeplink)){{json_encode($aff->deeplink)}}@endif' target="_blank" href="javascript:void(0);"><span class="text-primary">{!!$aff->title!!}</a></h3>
		</div>
		@endforeach
		</div>
		<a class="left carousel-control carousel_control_left_2" href="#carousel-post-qc" data-slide="prev">
		  <span class="fa fa-angle-left"></span>
		</a>
		<a class="right carousel-control carousel_control_right_2" href="#carousel-post-qc" data-slide="next">
		  <span class="fa fa-angle-right"></span>
		</a>
	</div>
</div>
