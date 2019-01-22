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
<div class="breadcrumbs_container pd-body clear">
	<div class="breadcrumbs_inc clear">
		{!! Breadcrumbs::render('user.manage.ads') !!}
	</div>
</div>

<div class="manage_post_ads clear">

	<div class="row">
		
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
		@include('partials.usersidebar')
		
		<div id="post-item" ng-controller="UserAdsManager" ng-init="getPosts()" class="col-sm-12 col-md-9 col-lg-9 clear post_manage_user public_page_profile">
			<h2>Quản lý tin</h2>
			<div class="row" style="display:none;">
                <div id="form-manage-container" class="col-sm-12 col-md-12 col-lg-12">
                    <form action="" class="form-inline ">
                        <div class="form-group clear">
                            <div class="row" >
                                <div class="col-md-4 col-sm-12">
                                    <input ng-model="keywords" placeholder="Tên bài viết" name="s_key" type="text" class="form-control text_manage_search">
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <select ng-model="category" name="s_category" id="category" class="form-control cat_manage_s">
                                        <option value="">Chọn danh mục</option>
                                       {!! WebService::tree_option($category) !!}
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <button id="search_tbl_userManage" type="button" class="btn btn-primary btn-sm">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
			</div>
			<p class="clearfix"></p>
			
			 <div id="post-form-container" class="clear container_manage_list_news">
                @foreach($datas as $data)

			        <div class="media clear">
				      <div class="media-left">
				        @if(!empty($data->ads_thumbnail))
					        <a href="{{route('front.ads.edit',array($data->template_setting_id,$data->id))}}">
					        	<img  src="{{$data->ads_thumbnail}}" alt="" class="img-responsive" width="80">
					        </a>
					    @else
					        <a href="{{route('front.ads.edit',array($data->template_setting_id,$data->id))}}">
                            		<img  src="{{url('img/noimg.gif')}}" alt="" class="img-responsive" width="80">
                            </a>
					    @endif
				      </div>
				      <div class="media-body">
				        <h4 class="media-heading">
				        	@if(empty($data->ads_title))
				        		<a href="{{route('front.ads.edit',array($data->template_setting_id,$data->id))}}">Tin Nháp</a>
				        	@else
				        	    <a href="{{route('front.ads.edit',array($data->template_setting_id,$data->id))}}">{{$data->ads_title}}</a>
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
								
								<a href="{{route('front.ads.edit',array($data->template_setting_id,$data->id))}}"><i class="fa fa-edit"></i> Sửa</a>
								<a href="{{route('front.ads.destroy',array($data->template_setting_id,$data->id))}}"><i class="fa fa-remove"></i> Xóa</a>
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
				      </div>
			        </div>
			     @endforeach
			        @include('include.pagination', ['paginator' => $datas])
			        <!--<div class="page-navi pull-right"><?php //echo $datas->render();?></div> -->
			    </div><!-- end # register-form-container -->
		    
			
			
		</div><!-- end post-ads -->
	</div><!-- end row -->
</div><!-- end container -->


@endsection
