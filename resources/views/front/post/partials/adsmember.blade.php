@if(count($adsmember)>0)
<div class="panel panel-info item-relate">
	<div class="panel-heading">Bài viết khác</div>
	<div class="panel-body">
        
            @foreach($adsmember as $ads_top)
				@if(strlen($ads_top->ads_thumbnail))
					<div id="product-{{$ads_top->id}}-wrapper" class="product_releated_item product-wrapper_releated_details _tracking">
						<div id="product-{{$ads_top->ads_id}}-item" class="product_details_item clear">
							<div class="row">
								<div class="col-xs-4 col-sm-4 col-md-4">
									<a href="{{route('front.ads.detail',array(App\Model\Regions::find($ads_top->regionsID)->iso,App\Model\Subregions::find($ads_top->subRegionsID)->subregions_name_slug,$ads_top->ads_slug))}}">
										 <img class="img-responsive" src="{{$ads_top->ads_thumbnail}}" alt="{{$ads_top->ads_title}}"/>
									</a>
								</div>
								<div class="col-xs-8 col-sm-8 col-md-8">
									 <h3 class="product__title">
										<a href="{{route('front.ads.detail',array(App\Model\Regions::find($ads_top->regionsID)->iso,App\Model\Subregions::find($ads_top->subRegionsID)->subregions_name_slug,$ads_top->ads_slug))}}">{{ str_limit(strip_tags($ads_top->ads_title), $limit = 45, $end = '...') }}</a>
									 </h3>
									 <span class="adsrelate-view">{{ $ads_top->ads_view }} lượt xem</span>
									@if(!empty($ads_top->ads_price))
									   <div class="product__price">
											<span class="price">
												<span itemprop="price" class="price__value">{!!Site::price($ads_top->ads_price)!!}</span>
												<span class="price__symbol">đ</span>
												@if(!empty($ads_top->discount))
													<span class="price__discount">-{!!Site::percentPrice($ads_top->ads_price,$ads_top->discount)!!}</span>
												@endif
											</span>
										</div>
									@endif
									@if(!empty($ads_top->discount))
										<div style="display: inline-block;" class="product__price product__price--list-price">
											<span class="price price--list-price">
												<span class="price__value">{!!Site::price($ads_top->discount)!!}</span><span class="price__symbol">đ</span>
											</span>
										</div>
									@endif
									<?
										$getDomain=App\Model\Domain::where('site_id','=',$ads_top->template_setting_id)->where('status','=','active')->where('domain_primary','=','default')->first(); 
										if(isset($getDomain->domain)){
											$domainPrimary=$getDomain->domain; 
										}else{
											$domainPrimary=$ads_top->domain; 
										}
									?>
									<i class="glyphicon glyphicon-globe"></i> <a href="{{route('member.home',$domainPrimary)}}">{{$ads_top->title}}</a>
								</div>
							</div>
						</div>
					</div>
				@endif
            @endforeach
        
	</div>
    <div class="panel-footer">
        <a href="#" class="view-more-ads"><i class="glyphicon glyphicon-chevron-down"></i> Hiển thị thêm</a>
    </div>
</div>
@endif