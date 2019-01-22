@extends('inc.master')
@section('seo')
	<?php 
		$data_seo = array(
			'title' => 'Đăng ký',
			'keywords' => '',
			'description' => '',
			'og_type' => '',
			'og_site_name' => '',
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
<div class="section">
	<ol class="breadcrumb">
		<li class="dropdown" itemprop="itemListElement">
			 <a href="{{route('home')}}" itemprop="item"><span itemprop="name"><i class="glyphicon glyphicon-home"></i> <span class="hidden-xs">Trang chủ</span></span></a>
		</li>
		<li itemprop="itemListElement"><span itemprop="name">Đăng ký</span></li>
	</ol>
</div>
<div class="container clear">
	<div class="row">
		@include('partials.message')
			<form id="register-form" action="{{ route('post.register') }}" method="post" accept-charset="utf-8" role="form" class="">
				<div id="register-page" class="panel">
					<div id="register-form-container" class="panel-body">
						<div class="clear"></div>
						@if (count($errors) > 0)
							<div class="alert alert-danger">
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif	
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="utm" value="@if(isset($utm)) {{$utm}} @endif">
						<div class="form-group">
						   <label for="name" class="control-label">Tên tài khoản</label>
						   <input placeholder="Nhập tên tài khoản của bạn" id="name" name="name" value="{{ old('name') }}" type="text" class="form-control">
							<p id="subdomain" class="text-muted"></p>
						</div>
						<div class="form-group">
						   <label for="phone" class="control-label">Số điện thoại</label>
						   <input placeholder="09xxxxxxxx" id="phone" name="phone" value="{{ old('phone') }}" type="text" class="form-control">
						</div>	
						<div class="form-group">
							<label class="control-label" for="email">Email</label>
							<input placeholder="abc@xzy.com" id="email" name="email" type="email" value="{{ old('email') }}" class="form-control">
						</div>
						  
						<div class="form-group">
							<label class="control-label" for="password">Mật khẩu</label>
							<input placeholder="Mật khẩu" id="password" name="password" type="password" class="form-control">
						</div>


						<div class="form-group">
							<label class="control-label" for="password_confirmation">Nhập lại mật khẩu</label>
							<input placeholder="Nhập lại mật khẩu" id="password_confirmation" name="password_confirmation" type="password"  class="form-control">
						</div>
						  

						<div class="form-group">
							<input type="checkbox" class="filled-in" name="accept_term" id="accept-term"/>
							<label for="filled-in-box">
								Đồng ý <a href="#!">Điều khoản của chúng tôi</a>
							</label>
						</div>
						<div class="form-group">
							{!! Recaptcha::render() !!}
						</div>
					</div><!-- end # register-form-container -->
					<div class="panel-footer text-right">
						<button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> Đăng ký</button>
					</div>
				</div><!-- end # register-page -->
		</form>
	</div><!-- end row -->
</div><!-- end container -->
@endsection