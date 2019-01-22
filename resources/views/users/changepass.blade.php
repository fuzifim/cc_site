@extends('inc.master')
@section('seo')
<?php 
	$data_seo = array(
		'title' => 'Đổi mật khẩu | '.config('app.seo_title'),
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
	<div id="page-content-wrapper" class="page_content_primary clear">
        <div class="container-fluid entry_container xyz">
			<div class="row no-gutter mrb-5 country_option">
                <div class="col-lg-12">
                    <div class="breadcrumbs_inc clear">
                        {!! Breadcrumbs::render('user.change.password') !!}
                    </div>
                </div>
            </div>
			<div class="row no-gutter">
				<div class="col-lg-12 col-md-12 col-xs-12">
					<div id="changpass_user_set" class="changepass_inc clear">
						
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
							<h2>Đổi mật khẩu</h2>
							<div id="user-change-pass-container" class="clear change_pass_review">
                                
									<form id="change-pass-form" action="{{ route('post.user.changepassword') }}" method="post" accept-charset="utf-8" role="form" class="form-horizontal">
										<input type="hidden" name="_token" value="{{ csrf_token() }}" >

										<div class="form-group">
											<label class="col-sm-3 control-label" for="old-password">Mật khẩu cũ<span class="text-danger">(*)</span></label>
											<div class="col-sm-6">
												<input placeholder="" id="old-password" name="old_password" value="{{ old('old_pass_word') }}" type="text" class="form-control">
											</div>
										</div>

										<div class="form-group">
											<label class="col-sm-3 control-label" for="new-password">Mật khẩu mới<span class="text-danger">(*)</span></label>
											<div class="col-sm-6">
												<input placeholder="" id="new-password" name="new_password" value="{{ old('new_pass_word') }}" type="password" class="form-control">
											</div>
										</div>

										<div class="form-group">
											<label class="col-sm-3 control-label" for="re-new-password">Nhắc lại Mật khẩu mới<span class="text-danger">(*)</span></label>
											<div class="col-sm-6">
												<input placeholder="" id="re-new-password" name="re_new_password" value="{{ old('re_new_pass_word') }}" type="password" class="form-control">
											</div>
										</div>
										
									
										<div class="form-group">
											<div class="col-sm-offset-3 col-md-9 col-ms-9">
												 <button class="btn btn-primary" type="submit" name="send">Lưu
													<i class="fa fa-send"></i>
												  </button>
											</div>
										</div>

									</form>
								
							 </div><!-- end # register-form-container -->
							   
						
					</div><!-- end container -->
			    </div>
			</div>	
		</div>
	</div>		
@endsection