<div class="col-sm-12 col-md-3 col-lg-3" >
    @if(!isset($template_setting)&& empty($template_setting))
	<div class="panel  panel-primary  panel-custom">
		<div class="panel-body">
			<div class="row">
				<div id="crop-avatar">
					<div id="user-avata" class="avatar-view col-sm-12 col-md-12 col-lg-12">
						@if (Auth::user()->avata != "")
							{!! HTML::image( Auth::user()->avata,'',array('class'=>'avanta_defaul img-responsive img-thumbnail','id'=>'user-avatar')) !!}
						@else
							{!! HTML::image('img/avata.png','',array('class'=>'avanta_defaul','id'=>'user-avatar'))!!}
						@endif
						<span class="avatar-change"><i class="fa fa-image"></i> Đổi ảnh</span>
					</div>
				</div>
				<div class="col-sm-12 col-md-12 col-lg-12 manage_user_top">
				    <p class="author_user_manage clear"><span class="text-danger"><strong>{{ Auth::user()->name }}</strong></span></p>
					 <p class="author_user_manage clear">
					    <a href="{{ route ('logout')}}" class=""><i class="fa fa-user-times"></i> Đăng xuất</a>
					</p>
				</div>
                <div class="col-md-12 col-lg-12">
                	<small> <a class="btn btn-danger btn-xs ds_fb_access" href="{{route('facebook.loginaccess')}}"> <i aria-hidden="true" class="fa fa-plug"></i> Kết nối với Facebook</a> </small>
                </div>
			       {{-- @if(WebService::getVipByID(Auth::user()->id) !=0)
					<div class="col-md-12 col-lg-12">
					<small>@if($user_fb)<a href="{{route('user.autopost.disconnect')}}" class="btn btn-success btn-xs" title="Ngắt kết nối facebook"> <i class="fa fa-check-square-o" aria-hidden="true"></i> Đã kết nối Facebook </a> @else <a href="{{route('facebook.loginaccess')}}" class="btn btn-danger btn-xs"> <i class="fa fa-plug" aria-hidden="true"></i> Kết nối với Facebook</a> @endif</small>
					</div>
					@endif
					--}}
			</div>
		</div>
		<div class="panel-footer clear">
			<a class="hoso_tab" href="{{ route('user.profile') }}"><i class="fa fa-edit"></i> Hồ sơ</a>
			<a class="doimatkhau_tab" href="{{ route('user.changepassword') }}" class=""><i class="fa fa-key"></i> Đổi mật khẩu</a>
		</div>
	</div>

    <div class="panel panel-default">
         <div class="panel-heading"> <span class="fa fa-globe"></span><b> Tạo website</b></div>
         <div class="panel-body">
                 <small class="web_create_introdure">Tạo website chỉ trong 30s, miễn phí tên miền dạng tenmien.cungcap.net; tenmien.30s.co; tenmien.crviet.com hoặc tạo website theo tên miền riêng. </small>
                 <a rel="nofollow" role="button" class="btn btn-primary btn-sm active" href="{{ route('user.createweb') }}"><i class="fa fa-facebook"></i> Tạo website mới</a>
         </div>
    </div>

    @else
    <div class="panel panel-primary f15 panel-custom">
    	<div class="panel-heading"><i class="glyphicon glyphicon-phone"></i> <strong>WEBSITE</strong></div>
    	<div class="panel-body list-group f13 pd_bottom_none">
               <a href="{{route('user.manager')}}" target="_top"><i class="menu-icon glyphicon glyphicon-th-large"></i> Dashboad</a>
        </div>
    	<div class="panel-body list-group f13">
            <a href="{{$template_setting->domain}}" target="_blank"><i class="menu-icon fa fa-globe"></i> {{$template_setting->domain}}</a>
    	</div>
    </div>

	<div class="panel panel-primary f15 panel-custom">
		<div class="panel-heading"><i class="glyphicon glyphicon-phone"></i> <strong>Quản lý Tin</strong></div>
		<div class="panel-body list-group f13">


				<a href="{{ route('front.ads.create',$template_setting->id) }}" class="list-group-item"><i class="fa fa-plus-circle"></i> Đăng Tin</a>

			    <a href="{{ route('user.ads.manager',$template_setting->id) }}" class="list-group-item"><i class="fa fa-file-text-o"></i> Tin đã đăng</a>

			<!--<a href="{{ route('user.fb.comment') }}" class="list-group-item"><i class="fa fa-facebook-square"></i> Quản lý FB Post</a>-->

		</div>
	</div>



	<div class="panel panel-primary f15 panel-custom">
		<div class="panel-heading"><i class="glyphicon glyphicon-globe"></i> <strong>Quản lý Website</strong></div>
		<div class="panel-body list-group f13">
			<a href="{{ route('user.template.setting',$template_setting->id) }}" class="list-group-item"><i class="fa fa-cog"></i> Cấu hình Website </a>
		</div>
		
	</div>
   <?php /* ?>
    <div class="panel panel-primary f15 panel-custom">
		<div class="panel-heading"><i class="glyphicon glyphicon-flag"></i> <strong>Cấu hình Auto Post</strong></div>
		<div class="panel-body list-group f13">
				<a href="{{ route('user.autopost') }}" class="list-group-item"><i class="fa fa-file-text-o"></i> Cấu hình Auto Post</a>
				<a href="{{ route('user.autopost.setting') }}" class="list-group-item"><i class="fa fa-file-text-o"></i> Thêm cấu hình</a>
		</div>
	
	</div>
    <?php */ ?>
    @endif

</div><!-- end left -->

