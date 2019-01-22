
<ul class="sidebar-nav nav-pills nav-stacked" id="menu">
    <li>
        <a href="{{route('user.manager')}}"><i class="glyphicon glyphicon-dashboard"></i> Quản lý</a>
    </li>
    
    <li role="separator" class="divider"></li>
    <li>
        <a href="{{ route('user.profile') }}">
            @if (Auth::user()->avata != "")
                {!! HTML::image( Auth::user()->avata,'',array('class'=>'avata_top_defaul','id'=>'user-avatar')) !!}
            @else
                {!! HTML::image('img/avata.png','',array('class'=>'avata_top_defaul','id'=>'user-avatar'))!!}
            @endif  
            Hồ sơ</a>
    </li>
    <li>
        <a href="{{ route('user.changepassword') }}"><i class="glyphicon glyphicon-lock"></i> Đổi mật khẩu</a>
    </li>
    <li>
        <a href="{{route('facebook.loginaccess')}}"><i class="fa fa-facebook"></i> Kế nối Facebook</a>
    </li>
    {{-- @if(WebService::getVipByID(Auth::user()->id) !=0)
    <li>
        @if($user_fb)<a href="{{route('user.autopost.disconnect')}}"><span class="fa-stack fa-lg"><i class="fa fa-check-square-o" aria-hidden="true"></i></span>Đã kết nối Facebook</a> @else <a href="{{route('facebook.loginaccess')}}"><span class="fa-stack fa-lg"><i class="fa fa-check-square-o" aria-hidden="true"></i></span>Kết nối với Facebook</a> @endif
    </li>
    @endif
	--}}
    <li>
        <a href="{{ route ('logout')}}"> <i class="glyphicon glyphicon-log-out"></i> Đăng xuất</a>
    </li>
</ul>