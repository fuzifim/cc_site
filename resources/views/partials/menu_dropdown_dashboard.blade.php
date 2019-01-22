<ul id="menu_right_postion" class="dropdown-menu">
	<li><a href="#" rel="nofollow"><span class="fa fa-dashboard"></span><span class="title"> Bảng điều khiển</span></a></li>
	@if(count(WebService::getChannelByUser(Auth::user()->id))>0)
		@foreach(WebService::getChannelByUser(Auth::user()->id) as $channel)
			<li>	
				<a href="{{route('front.channel.info',$channel->channel_id)}}">@if(strlen($channel->media_url)>0)<img src="{{$channel->media_url}}" alt="" class="avata_top_defaul"/> @else <i class="glyphicon glyphicon-globe"></i> @endif<span class="title"> {{$channel->channel_name}}</span></a>
			</li>
		@endforeach 
	@else
		<li><a class="" href="#"><span class="glyphicon glyphicon-plus"></span> <span>Tạo kênh mới</span></a></li>
	@endif
	<li><a href="#">
		@if (Auth::user()->avata != "")
			{!! HTML::image( Auth::user()->avata,'',array('class'=>'avata_top_defaul','id'=>'user-avatar')) !!}
		@else
			{!! HTML::image('img/avata.png','',array('class'=>'avata_top_defaul','id'=>'user-avatar'))!!}
		@endif  
		<span class="user_sidebar"><span class="title">{{Auth::user()->name}}</span></span></a>
	</li>
	<li><a href="#" class=""><i class="fa fa-envelope-o"></i> <span class="title">Tin nhắn <span class="label label-pill label-danger" ng-bind="messageUnread">0</span></span></a></li>
	<li><a href="#"><i class="glyphicon glyphicon-thumbs-up"></i> <span class="title"> Đã thích</span></a></li>
	@if (Auth::check())	
		@if(Auth::user()->user_level_id=='agency')
			<li><a href="#" rel="nofollow"><span class="glyphicon glyphicon-qrcode"></span> <span class="title"> Mã giảm giá</span></a></li>
		@endif
		@if(Auth::user()->user_level_id=='admin')
			<li><a href="#"><i class="fa fa-users" aria-hidden="true"></i> Thành viên</a></li>
		@endif
	@endif
	<li><a href="#"><i class="glyphicon glyphicon-credit-card"></i> <span class="title"> Thanh toán</span></a></li>
	<li><a href="#"><i class="glyphicon glyphicon-lock"></i> <span class="title"> Đổi mật khẩu</span></a></li>
	<li><a href="#"><i class="glyphicon glyphicon-log-out"></i> <span class="title"> Đăng xuất</span></a></li>
</ul>