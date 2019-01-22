<div class="panel panel-dark panel-alt widget-slider">
	<div class="panel-body">
		<div id="carousel-2" class="swiper-container" data-ride="carousel">
			<!-- Wrapper for slides -->
			<div class="swiper-wrapper">
			<?
				$i=0; 
			?>
			@foreach($affiliateSearch as $aff)
			  <div class="swiper-slide @if($i==1) active @endif">
				<div class="media">
					<a href="#" class="pull-left">
						<?
							if($aff->campaign=='vuivui.com'){
								$linkImage=str_replace('https:///Products/Images/', 'https://cdn.tgdd.vn/Products/Images/', $aff->image); 
							}else{
								$linkImage=$aff->image; 
							}
						?>
						<img src="{{$linkImage}}" data-src="" class="padding5 postMediaXs pull-left lazy" alt="" title="" >
					</a>
					<div class="media-body">
					  <h2 class="media-heading"><a href="#">{!!$aff->title!!}</a></h2>
					</div>
				</div><!-- media -->
			  </div><!-- item -->
			@endforeach
			  
			</div><!-- carousel-inner -->
			<!-- Controls -->
			<a class="left carousel-control carousel_control_left_2" href="#carousel-post-qc" data-slide="prev">
			  <span class="fa fa-angle-left"></span>
			</a>
			<a class="right carousel-control carousel_control_right_2" href="#carousel-post-qc" data-slide="next">
			  <span class="fa fa-angle-right"></span>
			</a>
		</div><!-- carousel -->

	</div><!-- panel-body -->
</div>