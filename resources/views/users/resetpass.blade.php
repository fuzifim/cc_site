@extends('inc.master')
@section('seo')
	<?php
		$data_seo = array(
			'title' => 'Đổi mật khẩu '.Config::get('app.seo_title'),
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
	<div class=" container clear">
		{!! Breadcrumbs::render('requestchangepass') !!}
	</div>
</div>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-body">
			@if (count($errors) > 0)
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<form id="email-password" class="" role="form" method="POST" action="{{ route('user.postresetpassword') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="token" value="{{ $token }}">
				<div class="form-group">
					<label class="col-md-4 control-label">E-Mail Address</label>
					<div class="col-md-6">
						<input type="email" class="form-control" name="email" value="{{ old('email') }}">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-4 control-label">Password</label>
					<div class="col-md-6">
						<input type="password" class="form-control" name="password">
					</div>
				</div>

				<div class="form-group">
					 <label class="col-md-4 control-label">Confirm Password</label>
					 <div class="col-md-6">
						<input type="password" class="form-control" name="password_confirmation">
					 </div>
				</div>

				<div class="form-group">
					<div class="col-md-6 col-md-offset-4">
						<button type="submit" class="btn btn-primary">
								 Reset Password
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection