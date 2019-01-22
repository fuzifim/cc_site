@extends('inc.master')
@section('seo')
	<?php
		$data_seo = array(
			'title' => 'Yêu cầu đổi mật khẩu '.Config::get('app.seo_title'),
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
<div class="breadcrumbs_container pd-body clear">
	<div class=" container clear">
		{!! Breadcrumbs::render('requestchangepass') !!}
	</div>
</div>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-body">
			@include('partials.message') 
			<div class="alert alert-info alert-dismissible fade in" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
				<strong>Nhập Email của bạn. Chúng tôi sẽ gửi đến email của bạn đường dẫn để đổi mật khẩu của bạn?</strong>
			</div>
			@if (count($errors) > 0)
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<form id="email-password" class="" role="form" method="POST" action="{{ route('front.user.sendemailpassword') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="input-group">
					<input type="email" placeholder="Nhập địa chỉ email của bạn" class="form-control" name="email" value="{{ old('email') }}">
					<div class="input-group-btn">
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-send"></i> Gửi yêu cầu
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection