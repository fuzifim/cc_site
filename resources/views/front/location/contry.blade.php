@extends('inc.master')
@section('seo')
<?php $data_seo = array(
         'title' => $regions->country." - ".config('app.title_seo'),
         'keywords' => $regions->country,
         'description' => '',
         'og_title' => $regions->country.config('app.title_seo'),
          'og_description' => '',
          'og_url' =>Request::url(),
          'og_img' => asset('img/logo.png'),
           'current_url' =>Request::url()
        );
$seo = WebService::getSEO($data_seo);
?>
@include('partials.seo')
@endsection
@section('content')
<div class="breadcrumbs_container pd-body clear">
	<div class=" container clear">
		{!! Breadcrumbs::render('front.location.contry',$regions) !!}
	</div>
</div>

<div class="container details_company content_homepage category_c_company clear">
     <div class="row no-gutter cp_group_content">
    	@include('partials.message')
        @if (count($errors) > 0)
    		<div class="alert alert-danger">
    		   <strong>Thông báo!</strong> Có lỗi xảy ra.<br><br>
    		   <ul>
    		       @foreach ($errors->all() as $error)
    		         <li>{{ $error }}</li>
    		       @endforeach
    		   </ul>
    		</div>
    	@endif
    	<div class="col-md-12 col-sm-12 col-xs-12 item_cat_page find_contry_group find_location_container">
            <div class="content_details_cp_group content_location_ads clear">
                <ol itemtype="http://schema.org/BreadcrumbList" itemscope="" class="breadcrumb">
                        	<li class="dropdown" itemprop="itemListElement">
                        	    <a data-toggle="dropdown" href="{{url('/location/VN')}}" itemprop="item"><span itemprop="name">Việt Nam</span></a>
                        	    @if(count($subregion_cs)>0)
                        	    <span class="caret"></span>
                                <ul class="dropdown-menu">
                                    @foreach($subregion_cs as $subregion_c)
                                     <li><a href="{{url('/location').$subregion_c->SolrID}}">
                                        <i class="fa fa-folder fa-fw"></i> {{$subregion_c->subregions_name}}</a>
                                     </li>
                                    @endforeach
                                </ul>
                                @endif
                        	 </li>
                        </ol>
                <h1>{{$regions->country}}</h1>
                <!--begin code-->
                <div class="posts-content content_group_category clear">
                            <div id="container_inc_list" class="row">
                            @foreach($ads as $products)
                                <div class="post-item col-lg-3 col-md-3 col-sm-4 col-xs-6 item">
                                    <div id="product-{{$products->id}}-item" class="product_details_item clear">
                                        @if(!empty($products->ads_thumbnail))
                                        <div class="product__image clear">
                                                        <a href="{{route('front.ads.detail',$products->ads_slug)}}">
                                                            <img src="{{$products->ads_thumbnail}}"/>
                                                            <div class="item__meta">
                                                                  <span class="view">Xem Ngay</span>
                                                             </div>

                                                        </a>
                                         </div>
                                         @endif
                                            <div class="product__header clear">
                                                        <h3 class="product__title">
                                                            <a href="{{route('front.ads.detail',$products->ads_slug)}}">{{ str_limit(strip_tags($products->ads_title), $limit = 60, $end = '...') }}</a>
                                                        </h3>
                                                    </div>

                                            @if(empty($products->ads_thumbnail))
                                            <div class="product__description clear">
                                                {{ str_limit(strip_tags($products->ads_description), $limit = 60, $end = '...') }}
                                            </div>
                                            @endif


                                              <div class="product__info clear">
                                                        <div class="product__user">
                                                             <div class="product__views">
                                                                <i class="fa fa-user" aria-hidden="true"></i>
                                                               <a href="{!!WebService::getUserDomainbyAds($products->template_setting_id)->domain!!}">{!!WebService::getUserDomainbyAds($products->template_setting_id)->name!!}</a>
                                                             </div>
                                                        </div>
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

                                                        @if(!empty($products->discount))
                                                            <div class="product__price product__price--list-price">
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
                                </div>
                            @endforeach
                            </div>
                            <div class="page-navi pull-right clear">
                                <div class="clear page_navi_container">@include('include.pagination', ['paginator' => $ads])</div>
                            </div>
                        </div>
                <!--End code-->
            </div>

        </div>
     </div>

</div>


@endsection