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
                <div class="col-md-8">
                        @if($ads->category_option_ads_id==5)
                            @if(strlen($ads->link_youtube)>0)
                                <div class="embed-responsive embed-responsive-4by3"><iframe class="embed-responsive-item" frameborder="0" src="{{$ads->link_youtube}}" allowfullscreen="true"></iframe></div>
                                    
                            @endif
                        @else
                            <div class="carousel slide article-slide single_content_entry" id="article-photo-carousel">

                              <!-- Wrapper for slides -->
                              <div class="carousel-inner cont-slider">
                                <?php
                                    $i=0;
                                     $manager = new Intervention\Image\ImageManager(array('driver' => 'gd'));
                                     $type = 'jpg';
                                    foreach ($gallery as $image) {
                                    $image_galler = $manager->make($image->file_url)->resize(720,480)->encode('jpg');
                                    $base64_galler = 'data:image/' . $type . ';base64,' . base64_encode($image_galler);
                                ?>
                                <div class="item @if($i==0) active @endif">
                                  <img class="img-responsive img-gallery-post img-fluid lazy-load" alt="{{$ads->ads_title}}" src="{!!$base64_galler!!}"/>
                                </div>
                                <?php
                                    $i=$i+1; 
                                    }
                                ?>
                              </div>
                              <!-- Indicators -->
                              <ol class="carousel-indicators">
                                <?php
                                    $i=0; 
                                    foreach ($gallery as $image) {
                                    $image_galler_thum = $manager->make($image->file_url)->resize(259, 160)->encode('jpg');
                                    $base64_galler_thum = 'data:image/' . $type . ';base64,' . base64_encode($image_galler_thum);
                                ?>
                                <li class="@if($i==0) active @endif" data-slide-to="<?=$i; ?>" data-target="#article-photo-carousel">
                                  <img alt="{{$ads->ads_title}} thumnail" src="{!!$base64_galler_thum!!}"/>
                                </li>
                                <?php
                                     $i=$i+1; 
                                    }
                                ?>
                              </ol>
                            </div>
                        @endif
                    <div class="panel panel-default">
                        <div class="panel-body">
                        <h1 class="title_post">{{ $ads->ads_title }}</h1> 
                        <div class="company-chanel-post">
                            <div class="chanel-text-view">
                                <strong>{{ $ads->ads_view }} lượt xem</strong>
                            </div>
                            <div class="chanel-action pull-right">
                                <button type="button" class="btn btn-default"><i class="glyphicon glyphicon-thumbs-up"></i> 500</button> 
                                <button type="button" class="btn btn-default"><i class="glyphicon glyphicon-thumbs-down"></i> 2</button>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="info-chanel">
                                <div class="row">
                                    <div class="col-xs-4 col-sm-4 col-md-2">
                                        <a href="{{route('member.home',$ads->domain_forder)}}">
                                            @if ($ads->avata != '')
                                                {!! HTML::image($ads->avata,'',array('class'=>'img-responsive img-thumbnail avata-chanel')) !!}
                                            @else
                                                {!! HTML::image('img/avata.png','',array('class'=>'img-responsive img-thumbnail avata-chanel')) !!}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="col-xs-8 col-sm-8 col-md-10">
                                        <h4 class="chanel-name-post"><a href="{{route('member.home',$ads->domain_forder)}}" title="">{{$ads->title_shop}}</a></h4>
                                        <small><cite title="@if ($ads->address_shop != ''){{$ads->address_shop}} @else Đang cập nhật địa chỉ... @endif"><i class="glyphicon glyphicon-map-marker"></i> @if ($ads->address_shop != ''){{$ads->address_shop}} @else Chưa cập nhật địa chỉ... @endif</cite></small>
                                        <p>
                                            <i class="glyphicon glyphicon-globe"></i> <a href="{{route('member.home',$ads->domain_forder)}}">{{route('member.home',$ads->domain_forder)}}</a></p>
                                        <!-- Split button -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary">
                                                Hành động</button>
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
                                                <span class="caret"></span><span class="sr-only">Hành động</span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                            @if (Auth::check())
                                                @if(Auth::user()->id != $ads->user_id)
                                                <li><a onclick="$('#post-message').slideToggle()"><i class="fa fa-envelope"></i> Gửi tin nhắn</a></li>
                                                @endif
                                            @else
                                                <li><a href="#!login" data-toggle="modal" data-target="#myModal" data-backdrop="true"><i class="fa fa-envelope"></i> Gửi tin nhắn</a></li>
                                            @endif
                                                <li class="divider"></li>
                                                <li><a href="#"><i class="glyphicon glyphicon-thumbs-up"></i> Quan tâm</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
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
                                        <li><strong>Đăng bởi: </strong>
                                        @if($ads->template_setting_id>0)
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                        @if(!empty(WebService::getUserDomainbyAds_user($ads->template_setting_id)))		
                                             <a href="{{route('username.profile',array(WebService::getUserDomainbyAds_user($ads->template_setting_id)->name,WebService::getUserDomainbyAds_user($ads->template_setting_id)->id))}}">{!!WebService::getUserDomainbyAds_user($ads->template_setting_id)->name!!}</a>
                                        @endif 
                                        @endif</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
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
                                </div>
                            </div>
                            <div class="post-description">
                                @if(!empty($ads->ads_description))
                         {!! str_limit(strip_tags(htmlspecialchars_decode($ads->ads_description)), $limit = 500, $end='..') !!} 
                                <div class="view-more-post">
                                    <a target='_blank' href="{{route('member.details-post',array($ads->domain_forder,$ads->id,$ads->ads_slug))}}"><i class='fa fa-tags'></i> Xem đầy đủ bài viết</a>
                                </div>
                                @else
                         <small><i>Không có nội dung mô tả</i></small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Chia sẻ lên: </label>
                                    <div class="col-sm-9">
                                        <a class="btn btn-primary" rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u={{Request::url()}}&amp;t={{$ads->ads_title}}" id="fb-share"><i class="fa fa-facebook"></i> <span class="hidden-xs">Facebook</span></a> 
                                        <a class="btn btn-primary" rel="nofollow" title="{{ $ads->ads_title }}" href="https://twitter.com/share?url={{Request::url()}}&amp;text={{$ads->ads_title}}&amp;via=[via]&amp;hashtags=[hashtags]"><i class="fa fa-twitter"></i> <span class="hidden-xs">Twitter</span></a> 
                                        <a class="btn btn-primary" rel="nofollow" title="{{ $ads->ads_title }}" href="https://plus.google.com/share?url={{Request::url()}}"><i class="fa fa-google-plus"></i> <span class="hidden-xs">Google+</span></a> 
                                        <a class="btn btn-primary" rel="nofollow" title="{{ $ads->ads_title }}" href="https://pinterest.com/pin/create/bookmarklet/?media=https://maps.googleapis.com/maps/api/staticmap?center=80%2C+Nguy%E1%BB%85n+Tr%C3%A3i+-+Ph%C6%B0%E1%BB%9Dng+9+-+Th%C3%A0nh+ph%E1%BB%91++C%C3%A0+Mau+-+C%C3%A0+Mau&amp;zoom=13&amp;size=600x300&amp;maptype=roadmap&amp;markers=color:blue%7Clabel:S%7C40.702147,-74.015794&amp;markers=color:green%7Clabel:G%7C40.711614,-74.012318&amp;markers=color:red%7Clabel:C%7C40.718217,-73.998284&amp;key=AIzaSyCWQHmA-JZNKyDlxbb0o3XZ6RkDcxhoM0k&amp;url={{Request::url()}}&amp;is_video=[is_video]&amp;description={{$ads->ads_title}}"><i class="fa fa-pinterest"></i> <span class="hidden-xs">Pinterest</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="text-success">15 Bình luận</span>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="widget-area no-padding blank">
                                        <div class="status-upload">
                                            <form>
                                                <textarea placeholder="Viết bình luận công khai" ></textarea>
                                                <ul>
                                                    <li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Audio"><i class="fa fa-music"></i></a></li>
                                                    <li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Video"><i class="fa fa-video-camera"></i></a></li>
                                                    <li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Sound Record"><i class="fa fa-microphone"></i></a></li>
                                                    <li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Picture"><i class="fa fa-picture-o"></i></a></li>
                                                </ul>
                                                <button type="submit" class="btn btn-success green"><i class="glyphicon glyphicon-comment"></i> Đăng</button>
                                            </form>
                                        </div><!-- Status Upload  -->
                                    </div><!-- Widget Area -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="blog-comment">
                                        <hr/>
                                        <ul class="comments">
                                        <li class="clearfix">
                                          <img src="http://bootdey.com/img/Content/user_1.jpg" class="avatar" alt="">
                                          <div class="post-comments">
                                              <p class="meta">Dec 18, 2014 <a href="#">JohnDoe</a> says : <i class="pull-right"><a href="#"><small>Reply</small></a></i></p>
                                              <p>
                                                  Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                  Etiam a sapien odio, sit amet
                                              </p>
                                          </div>
                                        </li>
                                        <li class="clearfix">
                                          <img src="http://bootdey.com/img/Content/user_2.jpg" class="avatar" alt="">
                                          <div class="post-comments">
                                              <p class="meta">Dec 19, 2014 <a href="#">JohnDoe</a> says : <i class="pull-right"><a href="#"><small>Reply</small></a></i></p>
                                              <p>
                                                  Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                  Etiam a sapien odio, sit amet
                                              </p>
                                          </div>
                                        
                                          <ul class="comments">
                                              <li class="clearfix">
                                                  <img src="http://bootdey.com/img/Content/user_3.jpg" class="avatar" alt="">
                                                  <div class="post-comments">
                                                      <p class="meta">Dec 20, 2014 <a href="#">JohnDoe</a> says : <i class="pull-right"><a href="#"><small>Reply</small></a></i></p>
                                                      <p>
                                                          Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                          Etiam a sapien odio, sit amet
                                                      </p>
                                                  </div>
                                              </li>
                                          </ul>
                                        </li>
                                        </ul>
                                        <div class="view-more-comment">
                                            <a class="btn btn-primary" href="#" title=""><i class="glyphicon glyphicon-repeat"></i> Tải thêm bình luận</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    @include('partials.adsmember')
                    @if(!empty($ads->fanpage))
                     <div class="fanpage_details clear">
                        <div class="fb-page" data-href="{{$ads->fanpage}}" data-tabs="friend" data-height="200" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"></div>
                     </div>
                    @endif
                    @include('partials.ads_cate_realate')
                    @include('partials.top-right-detail') 
                    @include('partials.adsrelate')
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

