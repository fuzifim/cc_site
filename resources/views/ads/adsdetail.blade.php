@extends('inc.master')
@section('seo')
<?php
   $data_seo = array(
		'title' => $ads->ads_title,
		'keywords' => Illuminate\Support\Str::slug($ads->ads_title, $separator = ' '),
		'description' => str_limit(strip_tags(htmlspecialchars_decode($ads->ads_description)), $limit = 120, $end = ''),
		'og_title' => $ads->ads_title,
		'og_description' => str_limit(strip_tags(htmlspecialchars_decode($ads->ads_description)), $limit = 120, $end = ''),
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
					<div class="row no-gutter">
						<div class="col-md-12">
							<form name="frm_search" id="frm_search" <?php if(isset($txt) && strlen($txt)>0){?> action="http://cungcap.net/search/<?php echo addslashes($txt); ?>" <?php }else{?> action="" <?php } ?> class="" role="search">
								<div class="input-group" id="adv-search">
									<div class="input-group-btn">
										<div class="btn-group" role="group">
											<div class="dropdown dropdown-lg">
												<ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
													<li class="dropdown" itemprop="itemListElement">
														<a class="btn btn-default dropdown-toggle btn-menu" itemprop="item" href="{{route('front.ads_by_location.contry','VN')}}" data-toggle="dropdown" aria-expanded="false"><span><i class="flag-icon flag-icon-{{mb_strtolower('VN')}}"></i> <span class="hidden-xs" itemprop="name">Việt Nam</span> <span class="glyphicon glyphicon-menu-down"></span></a>
													   @if(count($regions_all)>0)
														<ul class="dropdown-menu">
															  @foreach($regions_all as $regions_list)
															  <li><a href="{{route('front.ads_by_location.contry',$regions_list->iso)}}">
																<i class="fa fa-folder fa-fw"></i> {{$regions_list->country}}</a>
															  </li>
															  @endforeach
														</ul>
														@endif
													</li>
													<li class="dropdown hidden-xs" itemprop="itemListElement">
														<a class="btn btn-default dropdown-toggle btn-menu" itemprop="item" data-toggle="dropdown" href="{{route('front.ads_by_location.city',array($regions->iso,$subregions->subregions_name_slug))}}"><span itemprop="name">{{$subregions->subregions_name}}</span> <span class="glyphicon glyphicon-menu-down"></span></a>
														  @if(count($subregions_all)>0)
																<ul class="dropdown-menu">
																   @foreach($subregions_all as $subregion_list)
																   <li><a href="{{route('front.ads_by_location.city',array($regions->iso,$subregion_list->subregions_name_slug))}}">
																	 <i class="fa fa-folder fa-fw"></i> {{$subregion_list->subregions_name}}</a>
																   </li>
																   @endforeach
																</ul>
														  @endif
													</li>
												</ol>
											</div>
											<input id="txt_search" type="text" class="form-control" placeholder="{{ $ads->ads_title }}" value="<?php if(isset($txt) && strlen($txt)>0){ echo $txt;}?>"/>
											<button id="search_btn" type="submit" class="btn btn-primary btn-search"><span class="glyphicon glyphicon-search"></span></button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
                </div>
            </div><!--row-->
            <div class="row no-gutter details_baiviet_contention">
                <div class="col-md-8">
					@if($ads->category_option_ads_id==5)
						@if(strlen($ads->link_youtube)>0)
							<div class="embed-responsive embed-responsive-4by3"><iframe class="embed-responsive-item" frameborder="0" src="{{$ads->link_youtube}}" allowfullscreen="true"></iframe></div>
						@endif
					@else
						@if(count($medias)>0)
							<div class="panel carousel slide article-slide single_content_entry" id="article-photo-carousel">
								<!-- Wrapper for slides -->
								  <div class="carousel-inner cont-slider">
									<?php
										$i=0;
										foreach ($medias as $image) {
									?>
									<div class="item coverImageGalleryPostDetail @if($i==0) active @endif">
									  <img itemprop="image" class="img-responsive img-fluid lazy-load" alt="{{$ads->ads_title}}" src="{{$image}}"/>
									</div>
									<?php
										$i=$i+1; 
										}
									?>
								  </div>
								  <a class="left carousel-control portfolio_utube_carousel_control_left" href="#article-photo-carousel" role="button" data-slide="prev">
									<span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
									<span class="sr-only">Previous</span>
								  </a>
								  <a class="right carousel-control portfolio_utube_carousel_control_right" href="#article-photo-carousel" role="button" data-slide="next">
									<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
									<span class="sr-only">Next</span>
								  </a>
							</div>
						@endif
					@endif
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h1>{{ $ads->ads_title }}</h1> 
                            <div class="company-chanel-post">
                                <div class="chanel-text-view">
                                    <strong>{{ $ads->ads_view }} lượt xem</strong>
                                </div>
                                <div class="chanel-action pull-right">
                                    <input class="url_current_product_{{$ads->id}}" value="{{url('/')}}" type="hidden" />
                                    <input class="url_current_like_product_{{$ads->id}}" value="{!!WebService::getLike_post($ads->id)!!}" type="hidden" />
                                    <input class="url_current_unlike_product_{{$ads->id}}" value="{!!WebService::getUnLike_post($ads->id)!!}" type="hidden" />
                                    <div class="group_like_unlike_post_button clear">
                                       <div class="like_post_set pull-left">
                                            @if(WebService::check_user_post_like($ads->id))
                                                <button type="button" class="btn btn-xs btn-primary" onclick="setLikePost({{$ads->id}},'unlike')"><i class="glyphicon glyphicon-thumbs-up"></i> {!!WebService::getLike_post($ads->id)!!}</button>
                                            @else
                                                <button type="button" class="btn btn-xs btn-primary" onclick="setLikePost({{$ads->id}},'like')"><i class="glyphicon glyphicon-thumbs-up"></i> {!!WebService::getLike_post($ads->id)!!}</button>
                                            @endif
                                       </div>
                                       <div class="unlike_post_set pull-left">
                                            @if(WebService::check_user_post_unlike($ads->id))
                                                <button type="button" class="btn btn-xs btn-danger" onclick="setUnLikePost({{$ads->id}},'unlike')"><i class="glyphicon glyphicon-thumbs-down"></i> {!!WebService::getUnLike_post($ads->id)!!}</button>
                                            @else
                                                <button type="button" class="btn btn-xs btn-danger" onclick="setUnLikePost({{$ads->id}},'like')"><i class="glyphicon glyphicon-thumbs-down"></i> {!!WebService::getUnLike_post($ads->id)!!}</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="post-description">
                                @if(!empty(strip_tags(htmlspecialchars_decode($ads->ads_description))))
								{!! str_limit(strip_tags(htmlspecialchars_decode($ads->ads_description),"<p><br><b><i><font>"), $limit = 1000, $end='...') !!} <a href="{{route('member.details-post',array($domainPrimary,$ads->id,$ads->ads_slug))}}"> Xem thêm</a>
                                @else
								<small class="no-content"><i>Không có nội dung mô tả</i></small>
                                @endif
                            </div>
							<div class="form-group">
								@if(!empty($ads->discount) || !empty($ads->ads_price) )
								   @if(!empty($ads->ads_price))
									 <div class="product__price">
										  <span class="price">
											<span class="price__value" itemprop="price">Giá: {!!Site::price($ads->ads_price)!!}</span>
											<span class="price__symbol" itemprop="priceCurrency" content="VND">đ</span>
											@if(!empty($ads->discount))
											<span class="price__discount">-{!!Site::percentPrice($ads->ads_price,$ads->discount)!!}</span>
											@endif
										  </span>
									 </div>
									<button class="btn btn-success"><i class="glyphicon glyphicon-shopping-cart"></i> Đặt mua</button>
									@endif
									  @if(!empty($ads->discount))
										 <div class="product__price product__price--list-price" style="display: inline-block;">
										  <span class="price price--list-price">
											   <span class="price__value">{!!Site::price($ads->discount)!!}</span><span class="price__symbol">đ</span>
										   </span>
										  </div>
									  @endif
								@endif
							</div>
							<div class="form-group text-right" style="margin-top:10px; padding-top: 10px; border-top: solid 1px #dadada;">
                                <label class="control-label" style="text-align: right;">Chia sẻ<span class="hidden-xs"> lên:</span> </label>
								<a class="btn btn-xs btn-primary" rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u={{Request::url()}}&amp;t={{$ads->ads_title}}" id="fb-share"><i class="fa fa-facebook"></i> Face<span class="hidden-xs">book</span></a> 
								<a class="btn btn-xs btn-primary" rel="nofollow" title="{{ $ads->ads_title }}" href="https://twitter.com/share?url={{Request::url()}}&amp;text={{$ads->ads_title}}&amp;via=[via]&amp;hashtags=[hashtags]"><i class="fa fa-twitter"></i> Twit<span class="hidden-xs">ter</span></a> 
								<a class="btn btn-xs btn-primary" rel="nofollow" title="{{ $ads->ads_title }}" href="https://plus.google.com/share?url={{Request::url()}}"><i class="fa fa-google-plus"></i> Goo<span class="hidden-xs">gle+</span></a> 
								<a class="btn btn-xs btn-primary" rel="nofollow" title="{{ $ads->ads_title }}" href="https://pinterest.com/pin/create/bookmarklet/?media={{url($ads->ads_thumbnail)}}&amp;url={{Request::url()}}&amp;is_video=[is_video]&amp;description={{$ads->ads_title}}"><i class="fa fa-pinterest"></i> Pin<span class="hidden-xs">terest</span></a>
                            </div>
                            <div class="post-description">
                                <div class="col-md-6">
                                    <ul class="clear details_desc">
										@if (Auth::check())
                                           @if(Auth::user()->id == $ads->user_id)
                                            <li><a href="{{route('front.ads.editfast',$ads->id)}}" style="color:red; "><i class="glyphicon glyphicon-edit"></i> Sửa bài</a></li>
                                            @endif
                                        @endif
										<li><h3 class="chanel-name-post"><a href="{{route('member.home',$domainPrimary)}}" title="">
										@if(!empty($ads->logo))
											<img class="avata-chanel" src="{{$ads->logo}}">
										@else
											<span class="glyphicon glyphicon-picture" style="font-size: 20px;"></span>
										@endif
										 {{$ads->title}}</a></h3></li> 
										<li><strong>Website:</strong> <a href="{{route('member.home',$domainPrimary)}}">{{route('member.home',$domainPrimary)}}</a></li> 
										@if(isset($company))<li><strong>Doanh nghiệp:</strong> <a href="{{route('front.customer.details',array($company->id_auto,Str::slug($company->customers_company)))}}">@if(!empty($ads->title_shop)){{$ads->title_shop}}@else{{$company->customers_company}}@endif</a></li>@endif
										<li><strong>Địa chỉ: </strong> <small><cite title="@if ($ads->address_shop != ''){{$ads->address_shop}} @else Đang cập nhật địa chỉ... @endif"><i class="glyphicon glyphicon-map-marker"></i> @if ($ads->address_shop != ''){{$ads->address_shop}} @else Chưa cập nhật địa chỉ... @endif</cite></small></li>
										@if(!empty($subregions) || !empty($regions))
                                        <li><strong>Khu vực: </strong>
                                        @if(!empty($subregions))
                                        <a href="{{route('front.ads_by_location.city',array($regions->iso,$subregions->subregions_name_slug))}}">{{$subregions->subregions_name}}</a>,
                                        @endif
                                        @if(!empty($regions))
                                        <a href="{{route('front.ads_by_location.contry',$regions->iso)}}">{{$regions->country}}</a>
                                        @endif</li> @endif
                                        <li><strong>Ngày đăng: </strong><time class="rev" datetime="{!!Site::Date($ads->updated_at)!!}">{!!WebService::time_request($ads->updated_at)!!}</time></li>
                                        <li><strong>Người đăng: </strong>
                                             <a href="{{route('username.profile',$ads->user_id)}}">{{$ads->name}}</a>
                                        </li>
										<li><strong>Số điện thoại: </strong><a href="tel:{{ $ads->phone_contact }}"> {{ $ads->phone_contact }}</a></li>
                                        @if(!empty($ads->user_face))<li><strong>Facebook: </strong><a href="{{ $ads->user_face }}" target="_blank"> {{str_replace(array('facebook.com','https://','//','/',"www."),array(''), $ads->user_face)  }}</a></li>@endif
                                    </ul>
                                </div>
                            </div>
							<script type="application/ld+json">
								{
								  "@context": "http://schema.org/",
								  "@type": "Review",
								  "itemReviewed": {
									"@type": "Thing",
									"name": "{{ $ads->ads_title }}"
								  },
								  "author": {
									"@type": "Person",
									"name": "{{$ads->title}}"
								  },
								  "reviewRating": {
									"@type": "Rating",
									"ratingValue": "10", 
									"bestRating": "10"
								  },
								  "publisher": {
									"@type": "Organization",
									"name": "{{ $ads->ads_title }}"
								  }
								}
							</script>
                        </div>
                    </div>
                    <!--Comment-->
                   <div class="panel panel-default">
                        <div class="panel-body">
                             @include('include.comment_post',array('id'=>$ads->id))
                        </div>
                   </div>
                    <!--Comment-->
                </div>
                <div class="col-md-4">
                    @include('partials.adsmember')
                    
                    @if(!empty($ads->fanpage))
                     <div class="fanpage_details clear">
                        <div class="fb-like-box" data-href="{{$ads->fanpage}}" data-colorscheme="dark" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
                     </div>
                    @endif
                    @include('partials.adsrelate')
                    @include('partials.ads_cate_realate')
                    @include('partials.top-right-detail') 
                    
                </div>
                
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
                    // Stop carousel
                    $('.carousel').carousel({
                      interval: false
                    });
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

