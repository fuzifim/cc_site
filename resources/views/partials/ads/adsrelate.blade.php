@if(count($adsrelate)>0)
<div class="panel panel-info item-relate">
	<div class="panel-heading">Bài viết liên quan</div>
	<div class="panel-body">
        
            @foreach($adsrelate as $post)
				@if(strlen($post->ads_thumbnail))
					<div id="product-{{$post->id}}-wrapper" class="product_releated_item product-wrapper_releated_details _tracking">
						<div id="product-{{$post->ads_id}}-item" class="product_details_item clear">
							<div class="row">
								<div class="col-xs-4 col-sm-4 col-md-4">
									<a href="{{route('front.ads.detail',array(App\Model\Regions::find($post->regionsID)->iso,App\Model\Subregions::find($post->subRegionsID)->subregions_name_slug,$post->ads_slug))}}">
										 <img class="img-responsive" src="{{$post->ads_thumbnail}}" alt="{{$post->ads_title}}"/>
									</a>
								</div>
								<div class="col-xs-8 col-sm-8 col-md-8">
									 <h3 class="product__title">
										<a href="{{route('front.ads.detail',array(App\Model\Regions::find($post->regionsID)->iso,App\Model\Subregions::find($post->subRegionsID)->subregions_name_slug,$post->ads_slug))}}">{{ str_limit(strip_tags($post->ads_title), $limit = 45, $end = '...') }}</a>
									 </h3>
									 <span class="adsrelate-view">{{ $post->ads_view }} lượt xem</span>
									@if(!empty($post->ads_price))
									   <div class="product__price">
											<span class="price">
												<span itemprop="price" class="price__value">{!!Site::price($post->ads_price)!!}</span>
												<span class="price__symbol">đ</span>
												@if(!empty($post->discount))
													<span class="price__discount">-{!!Site::percentPrice($post->ads_price,$post->discount)!!}</span>
												@endif
											</span>
										</div>
									@endif
									@if(!empty($post->discount))
										<div style="display: inline-block;" class="product__price product__price--list-price">
											<span class="price price--list-price">
												<span class="price__value">{!!Site::price($post->discount)!!}</span><span class="price__symbol">đ</span>
											</span>
										</div>
									@endif
									<?
										$getDomain=App\Model\Domain::where('site_id','=',$post->template_setting_id)->where('status','=','active')->where('domain_primary','=','default')->first(); 
										if(isset($getDomain->domain)){
											$domainPrimary=$getDomain->domain; 
										}else{
											$domainPrimary=$post->domain; 
										}
									?>
									@if(!empty($post->logo))
										<img class="img-thumbnail img-thumbnail-small" width="16" src="{{$post->logo}}">
									@else
										<span class="glyphicon glyphicon-globe" style="font-size: 16px;"></span>
									@endif
									<a href="{{route('member.home',$domainPrimary)}}"><small>{{$post->title}}</small></a> 
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