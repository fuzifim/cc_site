@extends('inc.master')
@section('seo')
<?php 
	$data_seo = array(
		'title' => 'Cài đặt thông tin kênh',
		'keywords' => config('app.keywords_default'),
		'description' => config('app.description_default'),
		'og_title' => 'Cài đặt thông tin kênh',
		'og_description' => config('app.description_default'),
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
    <div id="page-content-wrapper" class="page_content_primary clear ">
        <div class="container-fluid entry_container xyz">
            <div class="row no-gutter mrb-5 country_option">
                <div class="col-lg-12">
                    <div class="breadcrumbs_inc clear">
						<ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                            <li class="dropdown hidden-xs" itemprop="itemListElement">
                                 <a href="{{route('home')}}" itemprop="item"><span itemprop="name"><i class="glyphicon glyphicon-home"></i> <span class="hidden-xs">Trang chủ</span></span></a>
                            </li>
							<li class="dropdown active" itemprop="itemListElement"><a data-toggle="dropdown" itemprop="item" href=""><span class="glyphicon glyphicon-cog"></span> Bảng điều khiển</a> <span class="caret"></span>
								@include('partials.menu_dropdown_dashboard')
							</li>
                            <li class="dropdown" itemprop="itemListElement">
								<a data-toggle="dropdown" href="{{route('user.templateType.setting',array($temp_set->id,'general'))}}" itemprop="item"><span itemprop="name"><i class="glyphicon glyphicon-cog"></i> Cài đặt thông tin</span></a>
								<span class="caret"></span>
								@include('partials.menu_dropdown_setting_channel')
                            </li>
						</ol>
                    </div>
                </div>
            </div>
            <div class="theme_setting_user clear" >
                <div class="row no-gutter">
                    <div id="post-fanpage" class="col-lg-12 col-md-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
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
                            <div id="post-form-container" class="col-sm-12 col-md-12 col-lg-12 template_setting_website">
                              <form id="post-about-form" action="{{ route('user.templateType.setting',array($temp_set->id,'general'))}}" method="post" accept-charset="utf-8" role="form" class="" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                    <input type="hidden" name="id_template" value="{{$id_template}}" >
									<div class="form-group">
										<label class="control-label" for="phone">Địa chỉ kênh: </label>
										<a class="pd_5_website" href="//{{$domainPrimary}}"><i class="glyphicon glyphicon-link"></i> {{$domainPrimary}}</a> <a href="{{ route('user.templateType.setting',array($temp_set->id,'domain'))}}" style="color:red; "><i class="glyphicon glyphicon-edit"></i> Sửa</a>
									</div>
									<div class="form-group">
										<label class="control-label" for="text-area">Tên kênh: <span class="text-danger">(*)</span> </label>
										<input type="text" placeholder="Ví dụ: Cung cấp đậu phộng" data-toggle="tooltip" data-placement="top" title="Ví dụ: Cung cấp đậu phộng" class="form-control" name="channel_name" value="@if($temp_set->title == '')Blog @else{!!$temp_set->title!!}@endif"/>
									</div>
									
									<div class="form-group">
										<label class="control-label" for="phone">Giới thiệu:</label>
										<textarea name="channel_about"  s="20" rows="5" class="form-control">{{$temp_set->description}}</textarea>
									 </div>
									<div class="form-group mutiselected_group">
										<label class="control-label" for="ads-cate">Lĩnh vực hoạt động: <span class="text-danger">(*)</span></label>
										<div class="select_muti_template_cate">
										   <select ng-model="[]" id="template_cate_id_add" name="template_cate_id[]" class="form-control"  multiple="multiple">
												@if (count($user_categories) > 0)
												   {!! WebService::showMenuCategory($user_categories,$template_join_category,0,"") !!}
												@endif;
											</select>
										</div>
									</div>
									<script type="text/javascript">
										jQuery(document).ready(function($) {
											 $('#template_cate_id_add').multiselect({
												enableClickableOptGroups: true,
												enableCollapsibleOptGroups: true,
												enableFiltering: true,
												includeSelectAllOption: true,
												maxHeight: 200
											 })
										 }); 
									 </script>
									<div class="form-group">
										<label class="control-label" for="phone">Từ khóa: </label>
										<input name="keywords"  s="20" rows="5" class="form-control" value="{{$temp_set->keyword}}">
									 </div>
                                    <div class="form-group">
                                        <div class="col-md-12 col-ms-12 ">
                                            <button id="send" class="btn btn-primary pull-right" type="submit" name="send">Lưu <i class="glyphicon glyphicon-ok"></i>
                                            </button>
                                        </div>
                                    </div>
                              </form>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
<!--MutiSelect-->
<script src="{{asset('js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
<link type="text/css" href="{{asset('css/bootstrap-multiselect.css')}}" rel="stylesheet"/>
<!--End MutiSelect-->
@endsection