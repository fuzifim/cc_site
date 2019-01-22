@extends('inc.master')
@section('seo')
<?php $data_seo = array(
	'title' => config('app.title_default'),
	'keywords' => config('app.keywords_default'),
	'description' => config('app.description_default'),
	'og_type' => 'website',
	'og_site_name' => config('app.appname'),
	'og_title' => config('app.title_default'),
	'og_description' => config('app.description_default'),
	'og_url' => '',
	'og_img' => '',
	'current_url' =>url('/')
);
$seo = WebService::getSEO($data_seo); 
?>
@include('partials.seo')
@endsection

@section('content')

<div class="site-logo hidden-xs">
	<div class="container">
		<div class="col-xs-12 col-sm-4 col-md-4">
			<a href="{{url('/')}}"><img src="{{asset('assets/img/logo.png')}}" alt="Logo {{config('app.appname')}}"></a>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-8">
			<div class="pull-right" style="line-height: 120px; font-size: 18px; font-weight: bold;">
				<span class="glyphicon glyphicon-envelope"></span> contact@dakenh.net
			</div>
		</div>
	</div>
</div>
<div class="site-head">
	<div class="panel-headding" style="text-align:center; background:#00a6cf; color:#fff; padding:5px 0px;   ">
		<h1>@lang('main.multichannel')</h1>
		<p>@lang('main.welcome')</p>
	</div>
</div>
<div class="section-head"  style="padding:20px 0px; color:#fff; background-attachment: fixed !important; background-position: 50% 0;  background-repeat: no-repeat; background-size: cover; overflow: hidden; background-image: url({{asset('assets/img/banner-home.jpg')}});">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-4">
				<img class="img-responsive img-thumbnail" src="{{asset('assets/img/content-marketing.jpg')}}">
			</div>
			<div class="col-xs-12 col-sm-6 col-md-8" style="text-align: justify; padding:0px 20px; ">
				<p>Quảng cáo đa kênh là trên đó bạn có thể đăng tải thông tin giới thiệu sản phẩm, dịch vụ, ngành nghề... lên website đồng thời thông tin được chia sẻ lên các trang mạng xã hội Google, Facebook, Youtube... </p>
				<p><strong>Tính năng nổi bật của Quảng cáo đa kênh:</strong> </p>
				<ul>
					<li>Tạo website và tích hợp tên miền tự động</li>
					<li>Tối ưu chuẩn SEO, hỗ trợ tốt trên mọi thiết bị...</li>
					<li>Đăng Bài viết, Sản phẩm, Hình ảnh, Video... dễ dàng. </li>
					<li>Thông tin được quảng cáo trên Website, Google, Facebook, Youtube... </li>
					<li>Không giới hạn dung lượng.</li>
					<li>Và còn nhiều hơn nữa...</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="section-register">
	<div class="container">
		<div class="col-xs-12 col-sm-6 col-md-6 text-center" style="visibility: visible; animation-name: fadeInUp;">
			<h3 class="pack-price">Hoàn toàn miễn phí</h3>
			<p>Khởi tạo website của bạn chỉ trong 30s </p>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-6" style="visibility: visible; animation-name: fadeInUp;">
			<div class="text-center">
				<a href="{{route('front.theme.show')}}" class="btn btn-default btn-register"><i class="glyphicon glyphicon-ok"></i> Tạo website</a>
			</div>
		</div>
	</div>
</div>

@endsection