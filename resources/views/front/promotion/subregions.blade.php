@extends('inc.master')
@section('seo')
<?php $data_seo = array(
         'title' => 'Thông tin khuyến mãi tại '.$subregion->subregions_name.config('app.title_seo'),
         'keywords' => $regions->country,
         'description' => 'Thông tin khuyến mãi tại '.$subregion->subregions_name.' - '.$regions->country,
         'og_title' => 'Thông tin khuyến mãi tại '.$subregion->subregions_name.config('app.title_seo'),
          'og_description' => 'Thông tin khuyến mãi tại '.$subregion->subregions_name.' - '.$regions->country,
          'og_url' =>Request::url(),
          'og_img' => asset('img/logo.png'),
           'current_url' =>Request::url()
        );
$seo = WebService::getSEO($data_seo);
?>
@include('partials.seo')
@endsection
@section('content')
<!--<div class="breadcrumbs_container pd-body clear">
	<div class=" container clear">
		{!! Breadcrumbs::render('front.location.contry',$regions) !!}
	</div>
</div>-->
<div id="wrapper">
            <div id="sidebar-wrapper" class="menu_home clear">
                {!!WebService::leftMenuRender()!!}
            </div><!--sidebar-wrapper-->
            <div id="page-content-wrapper" class="page_content_primary clear">
                <div class="container-fluid entry_container xyz">
                     <div class="row no-gutter mrb-5 country_option_cs">
                         <div class="col-lg-12">
                             <div class="breadcrumbs_container pd-body clear">
                                 <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                                     <li class="dropdown" itemprop="itemListElement">
                                         <a data-toggle="dropdown" href="{{route('front.address.contry.promotion',$regions->iso)}}" itemprop="item"><span itemprop="name"><i class="flag-icon flag-icon-{{mb_strtolower($regions->iso)}}"></i> {{$regions->country}}</span></a>
                                         @if(count($regions_all)>0)
                                             <span class="caret"></span>
                                                 <ul class="dropdown-menu">
                                                      @foreach($regions_all as $regions_list)
                                                      <li><a href="{{route('front.address.contry.promotion',$regions_list->iso)}}">
                                                        <i class="fa fa-folder fa-fw"></i> {{$regions_list->country}}</a>
                                                      </li>
                                                      @endforeach
                                                 </ul>
                                         @endif
                                     </li>
                                      <li class="dropdown" itemprop="itemListElement">
                                          <a data-toggle="dropdown" href="{{route('front.address.subregion.promotion',array($regions->iso,$subregion->subregions_name_slug))}}" itemprop="item"><span itemprop="name">{{$subregion->subregions_name}}</span></a>
                                          @if(count($subregion_cs)>0)
                                              <span class="caret"></span>
                                                  <ul class="dropdown-menu">
                                                       @foreach($subregion_cs as $subregion_list)
                                                       <li><a href="{{route('front.address.subregion.promotion',array($regions->iso,$subregion_list->subregions_name_slug))}}">
                                                         <i class="fa fa-folder fa-fw"></i> {{$subregion_list->subregions_name}}</a>
                                                       </li>
                                                       @endforeach
                                                  </ul>
                                          @endif
                                      </li>
                                 </ol>
                             </div>
                         </div>
                     </div>


                        <!--Content promotion-->
                          <?php $i=0; ?>
								<div class="row row_region_promotion no-gutter clear">
								<h1 class="hidden">Thông tin khuyến mãi tại {{$subregion->subregions_name}} - {{$regions->country}}</h1>
								@if(empty($ads) || count($ads)<=0)
								  <div class="col-xs-12 col-sm-12 col-md-12">
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											Khuyến mãi tại <strong>{{$subregion->subregions_name}} - {{$regions->country}}</strong> chưa có nội dung. Bạn có muốn đăng nội dung lên thông tin khuyến mãi này không? Click vào <a href="{{route('front.ads.create_fast')}}" rel="nofollow">đăng tin</a> để nhập thông tin khuyến mãi.
										</div>
								  </div>	
								@else	
								<div class="container_region_promotion clear">
                                    <div class="alert alert-danger">
                                       <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>	
                                       Thông tin khuyến mãi tại {{$subregion->subregions_name}} - {{$regions->country}}
                                    </div>
                                  @foreach($ads as $listProducts)
                                            <?php  //dd($template);?>
                                                      <div class="col-xs-12 col-sm-6 col-md-3">
                                                            <div class="item-post item-post-promotion clear">

                                                                    <div class="portfolio_utube_item_image thumbnail_item_promotion clear">
                                                                     @if(strlen($listProducts->ads_thumbnail))
                                                                        <a href="{{route('front.ads.detail',array(App\Model\Regions::find($listProducts->regionsID)->iso,App\Model\Subregions::find($listProducts->subRegionsID)->subregions_name_slug,$listProducts->ads_slug))}}" class="coverImagePostThumbnail">
                                                                            <img src="{{$listProducts->ads_thumbnail}}" class="img-fluid lazy-load" alt="{{$listProducts->ads_title}}"/>
                                                                        </a>
                                                                     @endif
                                                                @if(strlen($listProducts->regionsID)>0 && strlen($listProducts->subRegionsID)>0 )
                                                                      <a class="icon" href="{{route('front.ads.detail',array(App\Model\Regions::find($listProducts->regionsID)->iso,App\Model\Subregions::find($listProducts->subRegionsID)->subregions_name_slug,$listProducts->ads_slug))}}" data-toggle="tooltip" data-placement="left" title="Hình ảnh">
                                                                      @if(strlen(WebService::getCategoryOptionbyTemplates($listProducts->category_option_ads_id))>0)
                                                                            <?php echo htmlspecialchars_decode(WebService::getCategoryOptionbyTemplates($listProducts->category_option_ads_id)->category_icon_span);?>
                                                                      @else
                                                                            <i class="glyphicon glyphicon-list-alt"></i>
                                                                      @endif
                                                                      </a>
                                                                @endif
                                                                   </div>


                                                                    <div class="portfolio_utube_item_caption clear">
                                                                    @if(strlen($listProducts->regionsID)>0 && strlen($listProducts->subRegionsID)>0 )
                                                                       <a href="{{route('front.ads.detail',array(App\Model\Regions::find($listProducts->regionsID)->iso,App\Model\Subregions::find($listProducts->subRegionsID)->subregions_name_slug,$listProducts->ads_slug))}}}">{{$listProducts->ads_title}}</a>
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
                                                                            @if(!empty($listProducts->domain))
                                                                              <a href="{{$listProducts->domain}}">
                                                                                 <span>{{$listProducts->title}}</span>
                                                                              </a>
                                                                              @endif
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

                                            <?php $i=$i+1;
												if($i%4==0):
											?>
											</div><div class="container_region_promotion clear">	
											<?php endif; ?>
                                  @endforeach
								  </div><!--Container_promotion-->
								@endif  
								</div>
                          <!--End content promotion-->
							<div class="row row_page_promotion no-gutter clear">
							    <div class="col-xs-12 col-sm-12 col-md-12">	
									<div class="page-navi text-center clear">
										<div class="clear page_navi_container">@include('include.pagination', ['paginator' => $ads])</div>
									</div>
							    </div>	
					 	  </div><!--row_page_promotion-->	
                </div><!--container-fluid-->
            </div>
    </div><!--wrapper-->

@endsection