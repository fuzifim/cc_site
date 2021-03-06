@extends('inc.master')
@section('seo')
<?php 
	$data_seo = array(
		'title' => 'Cài đặt thông tin liên hệ',
		'keywords' => config('app.keywords_default'),
		'description' => config('app.description_default'),
		'og_title' => 'Cài đặt thông tin liên hệ',
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
								<a data-toggle="dropdown" href="{{route('user.templateType.setting',array($temp_set->id,'contact'))}}" itemprop="item"><span itemprop="name"><i class="glyphicon glyphicon-map-marker"></i> Thông tin liên hệ</span></a>
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
                              <form id="post-about-form" action="{{ route('user.templateType.setting',array($temp_set->id,'contact'))}}" method="post" accept-charset="utf-8" role="form" class="form-horizontal theme_setting_form" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                    <input type="hidden" name="id_template" value="{{$id_template}}" >
                                    <div class="website_info_company clear" style="display: block;">
										<div class="group_container_contact_view clear">
											@include('include.company_info')
										</div>
										<div class="form-group">
											<div class="col-md-12 col-ms-12 ">
												<button id="send" class="btn btn-primary pull-right" type="submit" name="send">Lưu <i class="glyphicon glyphicon-ok"></i>
												</button>
											</div>
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
<script src="{{ asset('/js/bootstrap-formhelpers.js') }}"></script>
<link type="text/css" href="{{asset('css/bootstrap-formhelpers.min.css')}}" rel="stylesheet"/>
@endsection