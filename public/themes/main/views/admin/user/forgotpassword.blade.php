<?
	$channel['theme']->setTitle('Quyên mật khẩu');
	$channel['theme']->setKeywords('');
	$channel['theme']->setDescription('Quyên mật khẩu '.$channel['info']->channel_name);
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'));
?>
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{{ __('base.password_reset') }}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
		<div class="card-body">
			@if (session('status'))
				<div class="alert alert-success" role="alert">
					{{ session('status') }}
				</div>
			@endif
			<form class="formForgot" method="post" action="{{ route('password.email',config('app.url')) }}">
				@csrf
				<div class="form-group">
					<a href="{{route('channel.login',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-chevron-left"></i> Trở về trang đăng nhập</a>
				</div>
				<div class="panel panel-default mainbox">
					<div class="panel-body">
						<div class="message"></div>
						<label class="control-label" for="item-channelEmail">Nhập email</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
							<input id="email" name="email" value="{{ old('email') }}" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Nhập email để nhận lại mật khẩu mới" required>
						</div>
						@if ($errors->has('email'))
							<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
						@endif
					</div>
					<div class="panel-footer text-right">
						<button id="sendEmailForgotPassword" class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok"></i> Gửi yêu cầu </button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div><!-- mainpanel -->
</section>