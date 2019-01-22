                                        <div class="row">
                                        <?php $i=0; ?>
                                        @foreach($ads as $listProducts)
										<?php  //dd($template);?>
                                                      <div class="col-xs-12 col-sm-6 col-md-3">
                                                            <div class="@if($i==0) active @endif item-post  row">
                                                                <div class="col-xs-4 col-sm-12 col-md-12">
                                                                    <div class="portfolio_utube_item_image">
                                                                     @if(strlen($listProducts->ads_thumbnail))
                                                                     <?php
                                                                     $manager = new Intervention\Image\ImageManager(array('driver' => 'gd'));
                                                                       $image = $manager->make(public_path($listProducts->ads_thumbnail))->resize(282, 160)->encode('jpg');
                                                                       $type = 'jpg';
                                                                       $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image);?>
                                                                        <a href="{{route('front.ads.detail',array(App\Model\Regions::find($template->regionsID)->iso,App\Model\Subregions::find($template->subRegionsID)->subregions_name_slug,$template->title_shop_slug,$listProducts->ads_slug))}}">
                                                                            <img src="{!!$base64!!}" class="img-fluid lazy-load" alt="{{$listProducts->ads_title}}"/>
                                                                        </a>
                                                                     @endif
                                                                @if(strlen($template->regionsID)>0 && strlen($template->subRegionsID)>0 )
                                                                      <a class="icon" href="{{route('front.ads.detail',array(App\Model\Regions::find($template->regionsID)->iso,App\Model\Subregions::find($template->subRegionsID)->subregions_name_slug,$template->title_shop_slug,$listProducts->ads_slug))}}" data-toggle="tooltip" data-placement="left" title="Hình ảnh">
                                                                      @if(strlen(WebService::getCategoryOptionbyTemplates($listProducts->category_option_ads_id))>0)
                                                                            <?php echo htmlspecialchars_decode(WebService::getCategoryOptionbyTemplates($listProducts->category_option_ads_id)->category_icon_span);?>
                                                                      @else
                                                                            <i class="glyphicon glyphicon-list-alt"></i>
                                                                      @endif
                                                                      </a>
                                                                @endif
                                                                   </div>
                                                                </div>
                                                                <div class="col-xs-8 col-sm-12 col-md-12">
                                                                    <div class="portfolio_utube_item_caption clear">
                                                                    @if(strlen($template->regionsID)>0 && strlen($template->subRegionsID)>0 )
                                                                       <a href="{{route('front.ads.detail',array(App\Model\Regions::find($template->regionsID)->iso,App\Model\Subregions::find($template->subRegionsID)->subregions_name_slug,$template->title_shop_slug,$listProducts->ads_slug))}}">{{$listProducts->ads_title}}</a>
                                                                    @endif   
                                                                       @if(!empty($listProducts->ads_price))
                                                                            <div class="product__price clear">
                                                                                <span class="price">
                                                                                     <span itemprop="price" class="price__value">{!!Site::price($listProducts->ads_price)!!}</span>
                                                                                     <span class="price__symbol">đ</span>
                                                                                     @if(!empty($listProducts->discount))
                                                                                            <span class="price__discount">-{!!Site::percentPrice($listProducts->ads_price,$listProducts->discount)!!}</span>
                                                                                     @endif
                                                                                </span>
                                                                            </div>
                                                                            @if(!empty($listProducts->discount))
                                                                              <div class="product__price product__price--list-price clear">
                                                                                  <span class="price price--list-price">
                                                                                       <span class="price__value">{!!Site::price($listProducts->discount)!!}</span><span class="price__symbol">đ</span>
                                                                                  </span>
                                                                               </div>
                                                                            @endif
                                                                       @endif

                                                                        <div class="portfolio_utube_item_caption_author">
                                                                            <span>bởi</span>
                                                                            <a href="{!!WebService::getUserDomainbyAds($listProducts->template_setting_id)->domain!!}">{!!WebService::getUserDomainbyAds($listProducts->template_setting_id)->name!!}</a>
                                                                            <i class="fa fa-check-square" data-toggle="tooltip" data-placement="top" title="Verified"></i>
                                                                        </div>
                                                                        <ul>
                                                                            <li>{{$listProducts->ads_view}} lượt xem</li>
                                                                            <li><span>.</span></li>
                                                                            <li>{!!WebService::time_request($listProducts->updated_at)!!}</li>
                                                                        </ul>
                                                                   </div>
                                                                </div>
                                                            </div>
                                                           
                                                      </div>
                                                
                                                   <?php $i=$i+1; ?>
                                        @endforeach
                                        </div><!--item-->