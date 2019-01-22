@if(count($categories)>0)
    @foreach($categories as $category)
    @if(!empty(WebService::getAdsbyCategory(substr(WebService::getCategoryParentByID($category->id), 0, -1))))
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 container_group_category_home clear">
            <div id="block-restaurant-{{$category->id}}" class="cat_home clear categories_ads_home">
                <div class="category_header_home clear">
                    <h2 class="block__title"><a href="{{route('front.categories.ads',array($category->SolrID,$category->id))}}"><img width="20" height="20" src="{{$category->icon}}"/><span class="title_name">{{$category->name}}</span></a></h2>
                    <div class="block__nav">
                        <ul>
                            <?php
                              // $category_string_id_child=substr(WebService::getCategoryParentByID($category->id), 0, -1);
                               //dd($category_string_id_child);
                             ?>
                             @if(!empty(WebService::getListCategoryAdsByID(substr(WebService::getCategoryParentByID($category->id), 0, -1))))
                                @foreach(WebService::getListCategoryAdsByID(substr(WebService::getCategoryParentByID($category->id), 0, -1)) as $child_cat)
                                    <li><a href="{{route('front.categories.ads',array($child_cat->SolrID,$child_cat->id))}}">{{$child_cat->name}}</a></li>
                                @endforeach
                             @endif
                            <li><a href="{{route('front.categories.ads',array($category->SolrID,$category->id))}}">Xem tất cả <i class="fa fa-long-arrow-right"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="category_content_home clear">
                    @if(!empty(WebService::getAdsbyCategory(substr(WebService::getCategoryParentByID($category->id), 0, -1))))
                    <div class="row products_home flexslider flexslider_category_list ">
                        <ul class="products__inner clear slides">
                          @foreach(WebService::getAdsbyCategory(substr(WebService::getCategoryParentByID($category->id), 0, -1)) as $products)
                            <li id="product-{{$category->id}}-wrapper" class="col-md-3 col-lg-2 col-sm-3 col-xs-12  product-wrapper_home _tracking">
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
                                                <a href="{{route('front.ads.detail',$products->ads_slug)}}">{{ str_limit(strip_tags($products->ads_title), $limit = 75, $end = '...') }}</a>
                                            </h3>
                                        </div>

                                        <div class="product__info clear">
											 @if(!empty(WebService::getUserDomainbyAds($products->template_setting_id)->domain) && !empty(WebService::getUserDomainbyAds($products->template_setting_id)->name))
                                            <div class="product__user clear">
                                                  <div class="product__views">
                                                     <i class="fa fa-user" aria-hidden="true"></i>
													<a href="{!!WebService::getUserDomainbyAds($products->template_setting_id)->domain!!}">{!!WebService::getUserDomainbyAds($products->template_setting_id)->name!!}</a>
												</div>
                                             </div>
											@endif 
                                             @if(!empty($products->ads_price))
                                                <div class="product__price clear">
                                                    <span class="price">
                                                        <span itemprop="price" class="price__value">{!!Site::price($products->ads_price)!!}</span>
                                                        <span class="price__symbol">đ</span>
                                                        @if(!empty($products->discount))
                                                            <span class="price__discount">-{!!Site::percentPrice($products->ads_price,$products->discount)!!}</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                 @if(!empty($products->discount))
                                                  <div class="product__price product__price--list-price clear">
                                                     <span class="price price--list-price">
                                                             <span class="price__value">{!!Site::price($products->discount)!!}</span><span class="price__symbol">đ</span>
                                                     </span>
                                                     <span class="view__asl_show"><i class="fa-eye fa"></i> {{$products->ads_view}}</span>
                                                  </div>
                                                  @endif
                                            @else
                                              <div class="product_show_timeline clear">
                                                    <span class="view__line"><i class="fa-eye fa"></i> {{$products->ads_view}}</span> <span class="date__line"><i class="fa fa-calendar"></i> {!!WebService::time_request($products->updated_at)!!}</span>
                                              </div>
                                            @endif
                                        </div>


                                    </div>
                            </li>
                          @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    @endforeach
@endif