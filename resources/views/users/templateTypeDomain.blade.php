@extends('inc.master')
@section('seo')
<?php 
	$data_seo = array(
		'title' => 'Cài đặt tên miền',
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
								<a data-toggle="dropdown" href="{{route('user.templateType.setting',array($temp_set->id,'domain'))}}" itemprop="item"><span itemprop="name"><i class="glyphicon glyphicon-cog"></i> Cài đặt tên miền</span></a>
								<span class="caret"></span>
								@include('partials.menu_dropdown_setting_channel')
                            </li>
						</ol>
                    </div>
                </div>
            </div>
            <div class="theme_setting_user clear" >
                <div class="row no-gutter">
                    <div class="col-lg-12 col-md-12 col-xs-12">
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
					</div>
					<div class="col-lg-12 col-md-12 col-xs-12">
						<div id="post-form-container" class="col-sm-12 col-md-12 col-lg-12 template_setting_website">
						  <form id="post-about-form" action="{{ route('user.templateType.setting',array($temp_set->id,'domain'))}}" method="post" accept-charset="utf-8" role="form" class="" enctype="multipart/form-data">
								<input type="hidden" name="_token" value="{{ csrf_token() }}" >
								<input type="hidden" name="id_template" value="{{$id_template}}" >
								<div class="row">
									<div class="col-md-8">
										<div class="panel panel-default">
											<div class="panel-body">
												<div class="form-group">
													Bạn cũng có thể thay đổi tên miền <code>{{$domainPrimary}}</code> đang sử dụng bằng cách nhập địa chỉ tên miền khác mà bạn đã đăng ký <code>vd: example.com</code>
													<p>và sau đó trỏ record A của tên miền về địa chỉ máy chủ: <code>128.199.243.16</code> hoặc đổi Name Server về: <code>ns1.cungcap.net</code>, <code>ns2.cungcap.net</code></p>
												</div>
												<div class="form-group">
													<div class="input-group">
														<span class="input-group-addon" style="font-weight:bold;">http://</span>
														<input type="text" required="required" class="form-control" placeholder="vd: example.com" name="domain_map" id="domain_map" value="" style="z-index:0; "/>
													</div>
												</div>
												<div class="form-group">
													<button type="submit" class="btn btn-primary pull-right" name="btnSave"><i class="glyphicon glyphicon-send"></i> Thêm</button>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h3 class="panel-title">Tên miền đã tạo</h3>
											</div>
											<div class="panel-body">
												<p><code>Tên miền đang sử dụng <a class="" href="//{{$domainPrimary}}">{{$domainPrimary}}</a></code></p>
												<ul class="list-group">
													@foreach($domain_map as $domains)
													<li class="list-group-item"><span class="badge"><a href="#"><i class="glyphicon glyphicon-remove" style="color:#fff;"></i></a></span> {{$domains->domain}}</li>
													@endforeach
												</ul>
											</div>
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
<!--MutiSelect-->
<script src="{{asset('js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
<link type="text/css" href="{{asset('css/bootstrap-multiselect.css')}}" rel="stylesheet"/>
<!--End MutiSelect-->
@endsection