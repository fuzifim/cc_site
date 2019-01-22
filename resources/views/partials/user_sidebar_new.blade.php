
<div class="col-sm-12 col-md-12 col-lg-12 container_sidebar_new">
  	<div class="container_group_manager clear">
    	<div class="panel-group" id="accordion">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span class="glyphicon glyphicon-th"></span> <span data-toggle="collapse" data-parent="#accordion" class="title">Quản lý</span>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse in">
                        <div class="panel-body">
							<ul class="list-group">
								<li class="list-group-item">
									<span class="fa fa-dashboard"></span><a href="{{route('user.manager')}}" rel="nofollow"><span class="title"> Bảng điều khiển</span></a>
								</li>
								@if(count(WebService::getDomainby_user(Auth::user()->id))>0)
									@foreach(WebService::getDomainby_user(Auth::user()->id) as $domain_menus)
										<li class="list-group-item">
											@if(strlen($domain_menus->logo)>0)<img src="{{$domain_menus->logo}}" alt="" class="avata_top_defaul"/> @else <img src="{{asset('img/noimg.gif')}}" alt="" class="avata_top_defaul"> @endif<a href="{{route('user.templateType.setting',array($domain_menus->id,'general'))}}"><span class="title">{{$domain_menus->title}}</span></a>
										</li>
									@endforeach 
								@else
									<li class="list-group-item">
										<a class="btn btn-success btn-sm" href="{{route('user.createweb')}}"><i class="glyphicon glyphicon-plus"></i> Tạo kênh mới</a>
									</li>
								@endif
							</ul>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span class="glyphicon glyphicon-user"></span> <span data-toggle="collapse" data-parent="#accordion" class="title">Tài Khoản</span>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse in">
                        <div class="panel-body">
							<ul class="list-group">
								<li class="list-group-item">
									<a href="{{ route('user.profile') }}">@if (Auth::user()->avata != "")
											{!! HTML::image( Auth::user()->avata,'',array('class'=>'avata_top_defaul','id'=>'user-avatar')) !!}
										@else
											{!! HTML::image('img/avata.png','',array('class'=>'avata_top_defaul','id'=>'user-avatar'))!!}
										@endif  
										<span class="user_sidebar"><span class="title">{{Auth::user()->name}}</span></span>
									</a>
								</li>
								<li class="list-group-item">
									<i class="fa fa-envelope-o"></i> <a href="{{ route('front.message') }}" class=""> <span class="title">Tin nhắn <span class="label label-pill label-danger" ng-bind="messageUnread">0</span></span></a>
								</li>
								<li class="list-group-item">
									<i class="glyphicon glyphicon-thumbs-up"></i><a href="{{route('front.ads_by_location.contry_like','VN')}}"><span class="title"> Đã thích</span></a>
								</li>
								@if (Auth::check())	
									@if(Auth::user()->user_level_id=='agency')
										<li class="list-group-item">
											<span class="glyphicon glyphicon-qrcode"></span><a href="{{route('front.ads.magiamGia')}}" rel="nofollow"><span class="title"> Mã giảm giá</span></a>
										</li>
									@endif 
									@if(Auth::user()->user_level_id=='admin')
										<li class="list-group-item">
											<a href="{{route('front.admin.members')}}"><i class="fa fa-users" aria-hidden="true"></i> Thành viên</a>
										</li>
									@endif
								@endif
								<li class="list-group-item">
									<i class="glyphicon glyphicon-credit-card"></i><a href="{{route('user.payment')}}"><span class="title"> Thanh toán</span></a>
								</li>
								<li class="list-group-item">
									<i class="fa fa-facebook"></i><a href="{{route('facebook.loginaccess')}}"><span class="title"> Kết nối Facebook</span></a>
								</li>
								<li class="list-group-item">
									<i class="glyphicon glyphicon-lock"></i><a href="{{ route('user.changepassword') }}"><span class="title"> Đổi mật khẩu</span></a>
								</li>
								<li class="list-group-item">
									<i class="glyphicon glyphicon-log-out"></i><a href="{{ route ('logout')}}"><span class="title"> Đăng xuất</span></a>
								</li>
							</ul>
                        </div>
                    </div>
                </div>
            </div>	
    </div><!--container_group_manager-->
</div><!--container_sidebar_new-->