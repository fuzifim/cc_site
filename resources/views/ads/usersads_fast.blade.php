@extends('inc.master')
@section('seo')
<?php 
	$data_seo = array(
		'title' => 'Quản lý tin |'.config('app.seo_title'),
		'keywords' => '',
		'description' => '',
		'og_title' => '',
		'og_description' => '',
		'og_url' => Request::url(),
		'og_sitename' => config('app.appname'),
		'og_img' => '',
		'current_url' => Request::url()
	);
	$seo = WebService::getSEO($data_seo); 
?>
@include('partials.seo')
@endsection
@section('content')
<div id="wrapper" class="manage_post_ads">
	<div id="sidebar-wrapper" class="menu_home clear margin_top_10">
        @include('partials.user_sidebar_new')
    </div><!--sidebar-wrapper-->
	<div id="page-content-wrapper" class="page_content_primary clear">
		<div class="container-fluid entry_container xyz">
			<div class="row no-gutter mrb-5 country_option">
                <div class="col-lg-12">
                    <div class="breadcrumbs_inc clear">
                        {!! Breadcrumbs::render('user.manage.ads') !!}
                    </div>
                </div>
            </div>
			
			<div class="row no-gutter">
				<div id="post-item" ng-controller="UserAdsManager" ng-init="getPosts()" class="col-sm-12 col-md-12 col-lg-12 clear post_manage_user public_page_profile not_bg pd-top-none">
				   <div class="clear manage_container_news_details">
						<h2>Quản lý tin</h2>
						
						<p class="clearfix"></p>
						
						 <div id="post-form-container" class="clear container_manage_list_news">
							@foreach($datas as $data)

								<div class="media clear">
								  <div class="media-left">
									@if(!empty($data->ads_thumbnail))
										<a href="{{route('front.ads.editfast',$data->id)}}">
											<img  src="{{$data->ads_thumbnail}}" alt="" class="img-responsive" width="80">
										</a>
									@else
										<a href="{{route('front.ads.editfast',$data->id)}}">
												<img  src="{{url('img/noimg.gif')}}" alt="" class="img-responsive" width="80">
										</a>
									@endif
								  </div>
								  <div class="media-body">
									<h4 class="media-heading">
										@if(empty($data->ads_title))
											<a href="{{route('front.ads.editfast',$data->id)}}">Tin Nháp</a>
										@else
											<a href="{{route('front.ads.editfast',$data->id)}}">{{$data->ads_title}}</a>
										@endif

									</h4>
										<p>Ngày đăng: {!!Site::Date($data->updated_at,'d-m-Y')!!} </p>
										<p>
										@if($data->ads_status==='unactive')
											<span class="label label-default" ng-if="item.ads_status === 'unactive'">chờ duyệt</span>
										@else
											<span class="label label-success" ng-if="item.ads_status === 'active'">Đang hoạt động</span>
										@endif
											<span >Đã xem: {{$data->ads_view}}</span>
											
											<a href="{{route('front.ads.editfast',$data->id)}}"><i class="fa fa-edit"></i> Sửa</a>
											<a href="{{route('front.ads.destroy_fast',$data->id)}}"><i class="fa fa-remove"></i> Xóa</a>
										</p>
									   <div class="clear content_short">
											<span><u><b>Nội dung ngắn</b></u></span>: {{ str_limit(strip_tags(htmlspecialchars_decode($data->ads_description)), $limit = 250, $end = '...') }}
									   </div>
									   <?php //dd(WebService::getCategoryOptionAds($data->category_option_ads_id)); ?>
										@foreach(WebService::getCategoryOptionAdsbyID($data->id)as $categories_option)
											<div class="clear cat_tin">
												 <span><u><b>Thể loại tin</b></u></span>: {{$categories_option->category_option_ads_title}}
											</div>
										@endforeach
										
									 @if(!empty(WebService::getUserDomainbyAds($data->template_setting_id)->domain))   
									   <div class="clear kenh_tin_list">
							   <span><u><b>Kênh Website:</b></u></span>:<a href="{{WebService::getUserDomainbyAds($data->template_setting_id)->domain}}" target="_blank">{{WebService::getUserDomainbyAds($data->template_setting_id)->domain}}</a>
									  </div>
									  @endif  
										
								  </div>
								</div>
							 @endforeach
								@include('include.pagination', ['paginator' => $datas])
								<!--<div class="page-navi pull-right"><?php //echo $datas->render();?></div> -->
							</div><!-- end # register-form-container -->
					</div>	
				</div><!-- end post-ads -->
			</div><!-- end row -->
		</div><!-- end container -->
	</div>	
</div><!-- end container -->

@endsection
