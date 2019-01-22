@extends('inc.master')
@section('seo')
<?php
   $data_seo = array(
		'title' => $ads->ads_title,
		'keywords' => Illuminate\Support\Str::slug($ads->ads_title, $separator = ' '),
		'description' => str_limit(strip_tags($ads->ads_description),120),
		'og_title' => $ads->ads_title,
		'og_description' => str_limit(strip_tags($ads->ads_description),120),
		'og_url' => Request::url(),
		'og_sitename' => config('app.appname'),
		'og_img' => url($ads->ads_thumbnail),
		'current_url' => Request::url()
	    );
	$seo = WebService::getSEO($data_seo);   
?>
@include('partials.seo')
@endsection

@section('content')
<div id="wrapper">
     <div id="sidebar-wrapper" class="menu_home clear">
         {!!WebService::leftMenuRender()!!}
     </div><!--sidebar-wrapper-->

     <div id="page-content-wrapper" class="page_content_primary clear">
         <div class="container-fluid entry_container xyz">
            <div class="row no-gutter mrb-5 country_option">
                <div class="col-lg-12">
                    <div class="breadcrumbs_container pd-body clear">
                      <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                            <li class="dropdown" itemprop="itemListElement">
                                 <a data-toggle="dropdown" href="{{route('front.ads_by_location.contry',$regions->iso)}}" itemprop="item"><span itemprop="name"><i class="flag-icon flag-icon-{{mb_strtolower($regions->iso)}}"></i> {{$regions->country}}</span></a>
                                 @if(count($regions_all)>0)
                                             <span class="caret"></span>
                                                 <ul class="dropdown-menu">
                                                      @foreach($regions_all as $regions_list)
                                                      <li><a href="{{route('front.ads_by_location.contry',$regions_list->iso)}}">
                                                        <i class="fa fa-folder fa-fw"></i> {{$regions_list->country}}</a>
                                                      </li>
                                                      @endforeach
                                                 </ul>
                                 @endif
                            </li>
                            <li class="dropdown" itemprop="itemListElement">
                                          <a data-toggle="dropdown" href="{{route('front.ads_by_location.city',array($regions->iso,$subregions->subregions_name_slug))}}" itemprop="item"><span itemprop="name">{{$subregions->subregions_name}}</span></a>
                                          @if(count($subregions_all)>0)
                                              <span class="caret"></span>
                                                  <ul class="dropdown-menu">
                                                       @foreach($subregions_all as $subregion_list)
                                                       <li><a href="{{route('front.ads_by_location.city',array($regions->iso,$subregion_list->subregions_name_slug))}}">
                                                         <i class="fa fa-folder fa-fw"></i> {{$subregion_list->subregions_name}}</a>
                                                       </li>
                                                       @endforeach
                                                  </ul>
                                          @endif
                            </li>
                             <li itemprop="itemListElement">
                     <a href="{{route('member.home',$ads->domain_forder)}}" itemprop="item" title="{{$ads->title_shop}}"><span itemprop="name">{{$ads->title_shop}}</span></a>
                             </li>
                      </ol>
                    </div>
                </div>
            </div><!--row-->
            <div class="row no-gutter details_baiviet_contention">
                <div class="baiviet entry_details clear">
                    <div class="row_top clear">
                        <div class="item-seller col-md-12 col-lg-12 no-padding">
                            <div class="seller-left col-md-6 col-lg-6">
                                    <div class="avatar col-md-4 col-lg-4 pull-left">
                                        <a href="{{route('member.home',$ads->domain_forder)}}">
                                        @if ($ads->avata != '')
                                                {!! HTML::image($ads->avata,'',array('class'=>'img-responsive img-thumbnail')) !!}
                                            @else
                                                {!! HTML::image('img/avata.png','',array('class'=>'img-responsive img-thumbnail')) !!}
                                            @endif
                                             </a>
                                    </div>
                                    <div class="seller-info col-md-8 col-lg-8 no-padding">
                                        <div class="name">
                                            <a href="{{route('member.home',$ads->domain_forder)}}" target="_blank"><i class="fa-globe fa"></i> Website</a>
                                        </div>
                                        <div class="shop">
                                            {{$ads->title_shop}}
                                        </div>
                                        <div class="time">
                                            Gia nhập vào {!!Site::Date($ads->created)!!}
                                        </div>
                                        <div class="tin-nhan">
                                             @if (Auth::check())
                                                @if(Auth::user()->id != $ads->user_id)
                                                    <div class="send_message text-center col-sm-12 col-lg-12 col-md-12">
                                                        <a onclick="$('#post-message').slideToggle()" class="m-send btn-block" role="button">
                                                            <i class="fa fa-envelope"></i> Gửi tin nhắn
                                                        </a>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="send_message text-center col-sm-12 col-lg-12 col-md-12">
                                                    <a href="#!login" data-toggle="modal" data-target="#myModal" data-backdrop="true" class="m-send btn-block" role="button">
                                                       <i class="fa fa-envelope"></i> Gửi tin nhắn
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                            </div>
                            <div class="seller-right col-md-6 col-lg-6">
                                <ul class="clear">
                                    <li><i class="fa fa-map-marker"></i> @if ($ads->address_shop != ''){{$ads->address_shop}} @else Đang cập nhật địa chỉ... @endif</li>
                                    <li><i class="fa fa-mobile"></i> @if($ads->phone_contact !='')<a href="cal:{{$ads->phone_contact }}">
                                             {{$ads->phone_contact }}
                                            </a>
                                        @else
                                            Đang cập nhật
                                        @endif
                                    </li>
                                    <li><i class="fa fa-envelope"></i> <a target="_blank" href="mailto:{{$ads->email}}">{{$ads->email}}</a> </li>
                                </ul>
                            </div>
                           <div class="seller-introdure col-md-10 col-lg-10">
                            @if(!empty($ads_introdures->ads_description))
                     {!! str_limit(strip_tags(htmlspecialchars_decode($ads_introdures->ads_description)), $limit = 500, $end='..') !!}
                            @else
                                <small><i>Đang cập nhật giới thiệu website</i></small>
                            @endif
                            </div>
                		</div>
                    </div>
                	<div class="row clear">
                		<div class="col-xs-12 col-md-9 col-lg-9 item-detail">
                			<h1>{{ $ads->ads_title }}</h1>
                			<div class="post-content clearfix clear">
                			    @if($ads->category_option_ads_id==5)
                			        @if(strlen($ads->link_youtube)>0)
                			        <div class="clear content_videos_inc">
                                      <div class="col-sm-12 col-lg-7 col-md-7 no-padding">
                                        <div class="video-container">
                                            <iframe height="315" frameborder="0" width="100%" src="{{$ads->link_youtube}}" allowfullscreen="true"></iframe>
                                        </div>
                                      </div>
                                      <div id="item-info" class="col-sm-12 col-lg-5 col-md-5">
                                                        <div class="row_box clear">
                                                             <div class="panel panel-default clear">
                                                              @if(!empty($ads->discount) || !empty($ads->ads_price) )
                                                                <div class="panel-heading col-lg-12 col-md-12">

                                                                        <div class="product__info clear">
                                                                           @if(!empty($ads->ads_price))
                                                                             <div class="product__price">
                                                                                  <span class="price">
                                                                                    <span class="price__value" itemprop="price">Giá: {!!Site::price($ads->ads_price)!!}</span>
                                                                                    <span class="price__symbol">đ</span>
                                                                                    @if(!empty($ads->discount))
                                                                                    <span class="price__discount">-{!!Site::percentPrice($ads->ads_price,$ads->discount)!!}</span>
                                                                                    @endif
                                                                                  </span>
                                                                             </div>
                                                                            @endif
                                                                              @if(!empty($ads->discount))
                                                                                 <div class="product__price product__price--list-price" style="display: inline-block;">
                                                                                  <span class="price price--list-price">
                                                                                       <span class="price__value">{!!Site::price($ads->discount)!!}</span><span class="price__symbol">đ</span>
                                                                                   </span>
                                                                                  </div>
                                                                              @endif
                                                                        </div>
                                                                </div>
                                                                @endif
                                                             <div class="panel-body">
                                                            <ul class="clear details_desc">
                                                            <li><strong>Ngày đăng: </strong>{!!Site::Date($ads->created_at)!!}</li>
                                        <?php //dd($get_districts); ?>
                                                            @if(!empty($subregions) || !empty($regions))
                                                            <li><strong>Nơi đăng: </strong>
                                                            @if(!empty($subregions))
                                                            <a href="{{route('front.ads_by_location.city',array($regions->iso,$subregions->subregions_name_slug))}}">{{$subregions->subregions_name}}</a>,
                                                            @endif
                                                            @if(!empty($regions))
                                                            <a href="{{route('front.ads_by_location.contry',$regions->iso)}}">{{$regions->country}}</a>
                                                            @endif</li> @endif
                                                            <li><strong>Số điện thoại: </strong><a href="tel:{{ $ads->phone_contact }}"> {{ $ads->phone_contact }}</a></li>
                                                            <li><strong>Facebook: </strong><a href="{{ $ads->user_face }}" target="_blank"> {{str_replace(array('facebook.com','https://','//','/',"www."),array(''), $ads->user_face)  }}</a></li>
                                                            <li><strong>Lượt xem: </strong> {{ $ads->ads_view }}</li>
                                                            </ul>
                                                          </div>
                                                        </div>
                                                        </div>
                                                        <div class="row_box clear">
                                              <p class="bg-warning padding-10">CUNGCAP.NET không bán hàng trực tiếp, quý khách mua hàng xin vui lòng liên lạc với người bán.</p>
                                                                <div class="share">
                                                                Chia sẻ:
                                                            <a href="https://www.facebook.com/dialog/feed?app_id=665386370259388&display=popup&caption={{ $ads->ads_title }}&show_error=true&link={{ route('front.ads.detail',$ads->ads_slug) }}&picture={{ url($ads->ads_thumbnail) }}&redirect_uri={{ route('front.ads.detail',$ads->ads_slug) }}&name={{ $ads->ads_title }}"><i class="fa fa-facebook-square fa-2x"></i></a>

                                                            <a href="https://plus.google.com/share?url={{ route('front.ads.detail',$ads->ads_slug) }}"
                                                              onclick="javascript:window.open('https://plus.google.com/share?url={{ route('front.ads.detail',$ads->ads_slug) }}','',
                                                              'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
                                                              return false;">
                                                              <i class="fa fa-google-plus-square fa-2x"></i>
                                                            </a>

                                                            <a href="https://twitter.com/share?text={{ $ads->ads_title }}&via=cungcap"
                                                                class="twitter"
                                                                target="_blank">
                                                                    <i class="fa fa-twitter-square fa-2x"></i>
                                                                </a>
                                                                 <!-- Your send button code -->
                                                                 <div class="fb-send" data-href="{{ route('front.ads.detail',$ads->ads_slug) }}"  data-layout="button_count"></div>
                                                            </div>
                                                        </div>
                                       </div>
                                    </div>
                                    <div class="clear">
                                    <div class='clear content_readmore'><a class='l' target='_blank' href="{{route('member.details-post',array($ads->domain_forder,$ads->id,$ads->ads_slug))}}"><i class='fa fa-tags'></i> Xem đầy đủ bài viết</a></div>
                                    </div>
                                    <div class="blog-share blog-share-videos">
                                         <span  class="fb-like" data-href="{{Request::url()}}" data-layout="button" data-action="like" data-show-faces="false" data-share="true"></span>
                                             <span class="fb-send" data-href="{{Request::url()}}" data-layout="button_count"></span>
                                             <!-- Place this tag where you want the +1 button to render. -->
                                             <span style="display: inline-block;height: 20px;top: 5px;position: relative;">
                                                                      <span class="g-plusone" data-height="20" data-annotation="inline" data-width="300"></span>
                                                                      </span>
                                    </div>

                                    @endif
                			    @else
                                    <div class="col-sm-12 col-lg-7 col-md-7 no-padding">

                                        <div id="item-gellary">

                                          <!-- Wrapper for slides -->
                                            <?php
                                                foreach ($gallery as $image) {
                                            ?>
                                                    <a href="{{ $image->file_url }}" title="{{ $ads->ads_title }}" data-thumbnail="{{ $image->file_url }}" class="html5lightbox" data-group="gallery">
                                                        <img src="{{ $image->file_url }}" alt="{{ $ads->ads_title }}" width="500">
                                                    </a>

                                           <?php
                                                }
                                            ?>
                                        </div> <!-- end main slide -->
                                        <!-- Controls -->
                                        <ul id="thumbs">
                                                <?php

                                            $i=0;
                                                foreach ($gallery as $image) {
                                            ?>
                                            <li @if ($i == 0) class='active' rel='1' @endif >
                                                <img src="{{ $image->file_url }}" alt="{{ $ads->ads_title }}" width=100>
                                            </li>
                                             <?php
                                                $i++;
                                                }
                                            ?>
                                        </ul>

                                        <div class="text-center">
                                            <p></p>
                                            <span><small><i><b>Click vào ảnh để xem dạng album</b></i></small></span>
                                        </div>
                                    </div>
                                    <div id="item-info" class="col-sm-12 col-lg-5 col-md-5">
                                        <div class="row_box clear">
                                             <div class="panel panel-default clear">
                                              @if(!empty($ads->discount) || !empty($ads->ads_price) )
                                                <div class="panel-heading col-lg-12 col-md-12">

                                                        <div class="product__info clear">
                                                           @if(!empty($ads->ads_price))
                                                             <div class="product__price">
                                                                  <span class="price">
                                                                    <span class="price__value" itemprop="price">Giá: {!!Site::price($ads->ads_price)!!}</span>
                                                                    <span class="price__symbol">đ</span>
                                                                    @if(!empty($ads->discount))
                                                                    <span class="price__discount">-{!!Site::percentPrice($ads->ads_price,$ads->discount)!!}</span>
                                                                    @endif
                                                                  </span>
                                                             </div>
                                                            @endif
                                                              @if(!empty($ads->discount))
                                                                 <div class="product__price product__price--list-price" style="display: inline-block;">
                                                                  <span class="price price--list-price">
                                                                       <span class="price__value">{!!Site::price($ads->discount)!!}</span><span class="price__symbol">đ</span>
                                                                   </span>
                                                                  </div>
                                                              @endif
                                                        </div>
                                                </div>
                                                @endif
                                             <div class="panel-body">
                                            <ul class="clear details_desc">
                                            <li><strong>Ngày đăng: </strong>{!!Site::Date($ads->created_at)!!}</li>
                        <?php //dd($get_districts); ?>
                                           @if(!empty($subregions) || !empty($regions))<li><strong>Nơi đăng: </strong>@if(!empty($subregions))<a href="{{route('front.ads_by_location.city',array($regions->iso,$subregions->subregions_name_slug))}}">{{$subregions->subregions_name}}</a>,@endif @if(!empty($regions))<a href="{{route('front.ads_by_location.contry',$regions->iso)}}">{{$regions->country}}</a>@endif</li> @endif
                                            <li><strong>Số điện thoại: </strong><a href="tel:{{ $ads->phone_contact }}"> {{ $ads->phone_contact }}</a></li>
                                            <li><strong>Facebook: </strong><a href="{{ $ads->user_face }}" target="_blank"> {{str_replace(array('facebook.com','https://','//','/',"www."),array(''), $ads->user_face)  }}</a></li>
                                            <li><strong>Lượt xem: </strong> {{ $ads->ads_view }}</li>
                                            </ul>
                                          </div>
                                        </div>
                                        </div>
                                        <div class="row_box clear">
                              <p class="bg-warning padding-10">CUNGCAP.NET không bán hàng trực tiếp, quý khách mua hàng xin vui lòng liên lạc với người bán.</p>
                                                <div class="share">
                                                Chia sẻ:
                                            <a href="https://www.facebook.com/dialog/feed?app_id=665386370259388&display=popup&caption={{ $ads->ads_title }}&show_error=true&link={{ route('front.ads.detail',$ads->ads_slug) }}&picture={{ url($ads->ads_thumbnail) }}&redirect_uri={{ route('front.ads.detail',$ads->ads_slug) }}&name={{ $ads->ads_title }}"><i class="fa fa-facebook-square fa-2x"></i></a>

                                            <a href="https://plus.google.com/share?url={{ route('front.ads.detail',$ads->ads_slug) }}"
                                              onclick="javascript:window.open('https://plus.google.com/share?url={{ route('front.ads.detail',$ads->ads_slug) }}','',
                                              'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
                                              return false;">
                                              <i class="fa fa-google-plus-square fa-2x"></i>
                                            </a>

                                            <a href="https://twitter.com/share?text={{ $ads->ads_title }}&via=cungcap"
                                                class="twitter"
                                                target="_blank">
                                                    <i class="fa fa-twitter-square fa-2x"></i>
                                                </a>
                                                 <!-- Your send button code -->
                                                 <div class="fb-send" data-href="{{ route('front.ads.detail',$ads->ads_slug) }}"  data-layout="button_count"></div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($ads->lat_shop != '' && $ads->long_shop != '')
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <a href="#" id="view-map" class="btn btn-primary pull-right"><i class="fa fa-map-marker"></i> Mở xem bản đồ</a>
                                        </div>
                                    </div>
                                    <div id="shop-map" style="display:none;">
                                        <p></p>
                                        <div id="map-canvas" style="height:300px;border:1px solid #d1d1d1;"></div>
                                    </div>
                                    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBi9KZruuoyo3xx6w30iy2IV2qk-JpmlD4"></script>
                                    <script>
                                    var geocoder;
                                    var map;
                                    var currentMarker;
                                    function initializeMap() {
                                        geocoder = new google.maps.Geocoder();

                                        // Create an array of styles.

                                          var styles = [
                                            {
                                              stylers: [
                                                { saturation: -100 }
                                              ]
                                            },{
                                              featureType: "road",
                                              elementType: "geometry",
                                              stylers: [
                                              //  { lightness: 100 },
                                                { visibility: "simplified" }
                                              ]
                                            },{
                                              featureType: "road",
                                              elementType: "labels",
                                              stylers: [
                                                { visibility: "off" }
                                              ]
                                            },{
                                                featureType:"transit",
                                                elementType:"geometry.fill",
                                                stylers:[{color:"#289dcc"},
                                                {saturation:0}]
                                             }
                                          ];

                                          // Create a new StyledMapType object, passing it the array of styles,
                                          // as well as the name to be displayed on the map type control.
                                          var styledMap = new google.maps.StyledMapType(styles,
                                            {name: "Google Map"});

                                          // Create a map object, and include the MapTypeId to add
                                          // to the map type control.


                                        var mapOptions = {
                                            zoom: 15,
                                            mapTypeId: google.maps.MapTypeId.ROADMAP,
                                            center: new google.maps.LatLng(<?php echo $ads->lat_shop.','.$ads->long_shop ?>),
                                            mapTypeControlOptions: {
                                                mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
                                            }
                                        };

                                        map = new google.maps.Map(document.getElementById('map-canvas'),
                                            mapOptions);

                                        currentMarker = new google.maps.Marker({
                                            map: map,
                                            position: new google.maps.LatLng(<?php echo $ads->lat_shop.','.$ads->long_shop ?>),
                                            //icon: {
                                            //  path: google.maps.SymbolPath.CIRCLE,
                                            //  scale: 10
                                            //},
                                            draggable: false,
                                        });
                                    } //end init
                                    //google.maps.event.addDomListener(window, 'load', initialize);

                                   jQuery('a#view-map').on('click',function(e){
                                        e.preventDefault();
                                        if(jQuery(this).hasClass('opened')){
                                            jQuery('#shop-map').slideUp();
                                            jQuery(this).removeClass('opened');
                                            jQuery(this).addClass('closed').text('Mở xem bản đồ');
                                        }else{
                                            jQuery('#shop-map').slideDown(500);
                                            jQuery(this).removeClass('closed');
                                            jQuery(this).addClass('opened').text('Đóng xem bản đồ');
                                            initializeMap();
                                        }


                                    });
                                    </script>
                                    @endif
                                    <div class="col-md-12 col-lg-12 col-xs-12">
                                            <p>&nbsp;</p>
                                            <fieldset>
                                                <legend><i class="fa-rss-square fa"></i> THÔNG TIN CHI TIẾT</legend>
                                                <div class="clear entry_content">
                                                    <?php $end="...<div class='clear content_readmore'><a class='r' target='_blank' href=".route('member.details-post',array($ads->domain_forder,$ads->id,$ads->ads_slug))."><i class='fa fa-tags'></i> Xem đầy đủ bài viết</a></div>"; ?>
                                                    {!! str_limit(strip_tags(htmlspecialchars_decode($ads->ads_description)), $limit = 1100, $end) !!}
                                                </div>

                                            </fieldset>
                                        <div class="blog-share">
                                            <span  class="fb-like" data-href="{{ route('front.ads.detail',$ads->ads_slug) }}" data-layout="button" data-action="like" data-show-faces="false" data-share="true"></span>

                                                <span class="fb-send"
                                                    data-href="{{ route('front.ads.detail',$ads->ads_slug) }}"
                                                    data-layout="button_count">
                                                </span>
                                                <!-- Place this tag where you want the +1 button to render. -->
                                                <span style="display: inline-block;height: 20px;top: 0px;position: relative;">
                                                <span class="g-plusone" data-height="20" data-annotation="inline" data-width="300"></span>
                                                </span>
                                        </div>
                                        </div>
                				@endif
                			</div>
                            <div class="comment-face clear">
                				<div class="fb-comments" data-width="100%" data-href="{{Request::url()}}" data-numposts="5" data-colorscheme="light"></div>
                			</div>
                            @include('partials.adsrelate')
                		</div>	<!-- end center End Detail-->
                		<!-- Begin right Detail -->
                		<div id="right-detail" class="col-lg-3 col-md-3">

                            @if(!empty($ads->fanpage))
                             <div class="fanpage_details clear">
                                <div class="fb-page" data-href="{{$ads->fanpage}}" data-tabs="friend" data-height="200" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"></div>
                             </div>
                            @endif
                			@include('partials.top-right-detail')
                			@include('partials.ads_cate_realate')
                		    @include('partials.adsmember')
                		</div><!-- End right -->
                	</div><!-- end row -->
                </div><!-- end container -->

                <div id="post-message">
                	<div id="m-header">
                		<h5>Gửi tin nhắn đến : {{ $ads->name }}
                		<a  href="javascript:void(0)" onclick="$('#post-message').slideToggle()">
                			<i class="fa fa-close"></i>
                		</a>
                		</h5>
                	</div>
                	<div class="m-body">
                		<div class="row">
                			<form id="send-message" action="" method="post" class="form-horizontal" role="form">

                				<input type="hidden" name="_token" value="{{ csrf_token() }}" >
                				<input type="hidden" name="to_user" value="{{ $ads->user_id }}">
                				<p></p>

                				<div class="form-group">
                                    <p style="margin-bottom: 10px;"></p>
                	        		<label class="col-xs-12 col-md-3 col-lg-3 control-label">Tiêu đề</label>
                	        		<div class="col-xs-12 col-md-8 col-lg-8">
                	        			<input type="text" class="form-control" name="title" value=" tôi cần trao đổi thêm về {{ $ads->ads_title }}" disabled>
                	        		</div>
                	        	</div>
                				<div class="form-group">
                					<label class="col-md-3 col-lg-3 control-label" for="message">Tin nhắn</label>
                					<div class="col-sm-12 col-md-8 col-lg-8">
                						<textarea class="form-control" name="message" id="message" cols="10" rows="5"></textarea>
                					</div>
                				</div>

                				<div class="form-group">
                					<div class="col-md-8 col-md-offset-3">
                						<button type="submit" class="btn btn-primary">
                							<i class="fa fa-send"></i>
                							Gửi
                						</button>
                						<button class="btn btn-default" onclick="$('#post-message').slideToggle()">Đóng</button>
                					</div>
                				</div>
                				<div class="form-group">
                					<label class="text-danger col-xs-11 col-xs-offset-1" for="" id="result"></label>
                				</div>

                			</form>
                		</div>
                	</div>
                </div>

                <script type="text/javascript" src="{{ url('lib/html5lightbox/html5lightbox.js') }}"></script>
                <style>
                	#html5-watermark{
                		display: none !important;
                	}
                </style>
                <script type="text/javascript">
                	var currentImage;
                    var currentIndex = -1;
                    var interval;
                    function showImage(index){
                        if(index < $('#item-gellary img').length){
                        	var indexImage = $('#item-gellary img')[index]
                            if(currentImage){
                            	if(currentImage != indexImage ){
                                    $(currentImage).css('z-index',2);
                                    clearTimeout(myTimer);
                                    $(currentImage).fadeOut(250, function() {
                					    myTimer = setTimeout("showNext()", 3000);
                					    $(this).css({'display':'none','z-index':1})
                					});
                                }
                            }
                            $(indexImage).css({'display':'block', 'opacity':1});
                            currentImage = indexImage;
                            currentIndex = index;
                            $('#thumbs li').removeClass('active');
                            $($('#thumbs li')[index]).addClass('active');
                        }
                    }

                    function showNext(){
                        var len = $('#item-gellary img').length;
                        var next = currentIndex < (len-1) ? currentIndex + 1 : 0;
                        showImage(next);
                    }

                    var myTimer;

                    $(document).ready(function() {
                	    myTimer = setTimeout("showNext()", 3000);
                		showNext(); //loads first image
                        $('#thumbs li').bind('click',function(e){
                        	var count = $(this).attr('rel');
                        	showImage(parseInt(count)-1);
                        });
                	});


                	</script>

            </div><!--row-->
         </div><!--container-fluid-->
     </div><!--page-content-wrapper-->
</div><!--wrapper-->









@endsection

