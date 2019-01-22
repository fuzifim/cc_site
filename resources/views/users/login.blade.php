@extends('inc.master')
@section('seo')
	<?php 
		$data_seo = array(
			'title' => 'Đăng nhập',
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
{!! Breadcrumbs::render('login') !!}
</div>
<div class="section">
    <div class="container-fluid xyz">
        @include('partials.message')    
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-primary">
				<div class="panel-heading">
					Đăng nhập
					<div class="pull-right"><a href="{{route('front.user.emailpassword')}}" style="color:#fff; font-size:12px; "><i class="fa fa-key"></i> Quên mật khẩu</a> | <a href="{{route('register')}}" style="color:#fff; font-size:12px; ">Đăng ký</a></div>
				</div>     
				<div style="padding-top:30px" class="panel-body" >
					<form id="login-form" method="post" action="{{route('post.login.request')}}" accept-charset="utf-8" role="form" class="form-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <span id="msg-login" class="text-danger center-block"></span>
						<div style="margin-bottom: 25px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<input placeholder="Email hoặc điện thoại" id="email" name="email" type="text" class="form-control" value="{{old('email')}}">                                       
						</div>
						<div style="margin-bottom: 25px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<input placeholder="Mật khẩu" id="password" type="password" name="password" class="form-control">
						</div>
						<div class="form-group">
						  <div class="checkbox">
							<label>
							  <input type="checkbox" name="remember" value="1" id="remember" {{ old('remember') ? 'checked' : '' }}>  Giữ chế độ đăng nhập
							</label>
						  </div>
						</div>
                            <div class="row no-gutter">
                                <div class="col-md-4 col-sm-4 col-md-4 col-xs-6">
                                     <button type="submit" class="btn btn-primary"><i class="fa fa-unlock-alt"></i> Đăng nhập</button>
                                </div>
                                <div class="col-md-4 col-sm-4 col-md-4 col-xs-3">
                                      <a href="{{ url('/social/facebook/authorize') }}" class="btn btn-primary"><span class="menu-icon fa fa-facebook"></span>  <span class="hidden-xs">Với Facebook</span></a>
                                </div>
                                <div class="col-md-4 col-sm-4 col-md-4 col-xs-3">
                                    <a href="{{ url('/social/google/authorize') }}" class="btn btn-primary"><span class="menu-icon fa fa-google"></span> <span class="hidden-xs">Với Google</span></a>
                                </div>
                            </div> 
					</form>    
				</div>                     
			</div>  
		</div>
    </div>
</div>   
@endsection