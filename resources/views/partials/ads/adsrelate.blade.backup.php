<div class="panel panel-info item-relate">
	<div class="panel-heading">Có thể bạn quan tâm</div>
	<div class="panel-body">
        <div class="row">
            @if(count($adsrelate)>0)
                <div class="row products_home">
                   <div id="products_related_content_read" class="products__inner clear">
                       @foreach($adsrelate as $products)
                           <div id="product-{{$products->id}}-wrapper" class="col-md-6 product_releated_item product-wrapper_releated_details _tracking">
                              <div class="product_releated_indetails clear">
                                <div id="product-{{$products->ads_id}}-item" class="product_details_item clear">
                                     <div class="product__image clear">
                                          <a href="{{route('front.ads.detail',$products->ads_slug)}}">
                                             <img src="{{$products->ads_thumbnail}}"/>
                                             <div class="item__meta">
                                                    <span class="view">Xem Ngay</span>
                                             </div>
                                          </a>
                                     </div>
                                     <div class="product__header clear">
                                         <h3 class="product__title">
                                            <a href="{{route('front.ads.detail',$products->ads_slug)}}">{{ str_limit(strip_tags($products->ads_title), $limit = 45, $end = '...') }}</a>
                                         </h3>
                                     </div>
                                     <div class="product__info clear">
                                        @if(!empty($products->ads_price))
                                           <div class="product__price">
                                                <span class="price">
                                                    <span itemprop="price" class="price__value">{!!Site::price($products->ads_price)!!}</span>
                                                    <span class="price__symbol">đ</span>
                                                    @if(!empty($products->discount))
                                                        <span class="price__discount">-{!!Site::percentPrice($products->ads_price,$products->discount)!!}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                        @if(!empty($products->discount))
                                            <div style="display: inline-block;" class="product__price product__price--list-price">
                                                <span class="price price--list-price">
                                                    <span class="price__value">{!!Site::price($products->discount)!!}</span><span class="price__symbol">đ</span>
                                                </span>
                                            </div>
                                        @endif
                                        @if($products->template_setting_id>0)
                                        <div class="product__user">
                                             <div class="product__views">
                                                <i class="fa fa-user" aria-hidden="true"></i>
                                        @if(!empty(WebService::getUserDomainbyAds($products->template_setting_id)))		
                                             <a href="{!!WebService::getUserDomainbyAds($products->template_setting_id)->domain!!}">{!!WebService::getUserDomainbyAds($products->template_setting_id)->name!!}</a>
                                        @endif 
                                             </div>
                                        </div>
                                        @endif
                                     </div>
                                </div>
                               </div>
                            </div>
                       @endforeach
                   </div>
                </div>
            @endif
        </div>
	</div>
</div>
