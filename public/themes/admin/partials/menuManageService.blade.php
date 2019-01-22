<div class="panel-group newDomain" id="menuManager">
	<div class="panel panel-primary" style="border-radius:0;border:none;">
		<div class="panel-heading" style="border-radius:0;">
			<a data-toggle="collapse" data-parent="#menuManager" href="#dashboard" class="close"><i class="glyphicon glyphicon-chevron-down"></i></a>
			<h4 class="panel-title">Quản lý kênh</h4>
		</div>
		<div id="dashboard" class="panel-collapse collapse in">
			<a href="{{route('channel.admin.dashboard',$channel['domain']->domain)}}" class="list-group-item {{(\Request::route()->getName()=='channel.admin.dashboard') ? 'list-group-item-info' : ''}}"><i class="glyphicon glyphicon-cog"></i> Bảng điều khiển</a>
			<a href="{{route('channel.post.add',$channel['domain']->domain)}}" class="list-group-item {{(\Request::route()->getName()=='channel.post.add') ? 'list-group-item-info' : ''}}"><i class="glyphicon glyphicon-edit"></i> Đăng bài</a>
			<a href="{{route('channel.category.list',$channel['domain']->domain)}}" class="list-group-item {{(\Request::route()->getName()=='channel.category.list') ? 'list-group-item-info' : ''}}"><i class="glyphicon glyphicon-folder-open"></i> Danh mục</a>
			<a href="{{route('channel.admin.setting',$channel['domain']->domain)}}" class="list-group-item {{(\Request::route()->getName()=='channel.admin.setting') ? 'list-group-item-info' : ''}}"><i class="glyphicon glyphicon-cog"></i> Cài đặt chung</a>
			<a href="{{route('channel.admin.contact',$channel['domain']->domain)}}" class="list-group-item {{(\Request::route()->getName()=='channel.admin.contact') ? 'list-group-item-info' : ''}}"><i class="glyphicon glyphicon-envelope"></i> Cài đặt liên hệ</a>
			<a href="{{route('channel.admin.theme',$channel['domain']->domain)}}" class="list-group-item {{(\Request::route()->getName()=='channel.admin.theme') ? 'list-group-item-info' : ''}}"><i class="glyphicon glyphicon-picture"></i> Cài đặt giao diện</a>
			<a href="{{route('channel.members.list',$channel['domain']->domain)}}" class="list-group-item {{(\Request::route()->getName()=='channel.members.list') ? 'list-group-item-info' : ''}}"><i class="glyphicon glyphicon-user"></i><i class="glyphicon glyphicon-user"></i> Thành viên</a>
			<a href="{{route('channel.trash.list',$channel['domain']->domain)}}" class="list-group-item {{(\Request::route()->getName()=='channel.trash.list') ? 'list-group-item-info' : ''}}"><i class="glyphicon glyphicon-trash"></i> Thùng rác</a>
		</div>
	</div>
	<div class="panel panel-primary" style="border-radius:0;border:none;">
		<div class="panel-heading" style="border-radius:0;">
			<a data-toggle="collapse" data-parent="#menuManager" href="#service" class="close"><i class="glyphicon glyphicon-chevron-down"></i></a>
			<h4 class="panel-title">Tiện ích</h4>
		</div>
		<div id="service" class="panel-collapse collapse in">
			<a href="{{route('channel.domain.list',$channel['domain']->domain)}}" class="list-group-item {{(\Request::route()->getName()=='channel.domain.list') ? 'list-group-item-info' : ''}}"><i class="glyphicon glyphicon-globe"></i> Tên miền</a>
			<a href="{{route('channel.hosting.list',$channel['domain']->domain)}}" class="list-group-item {{(\Request::route()->getName()=='channel.hosting.list') ? 'list-group-item-info' : ''}}"><i class="glyphicon glyphicon-hdd"></i> Hosting</a>
			<a href="{{route('channel.cloud.list',$channel['domain']->domain)}}" class="list-group-item {{(\Request::route()->getName()=='channel.cloud.list') ? 'list-group-item-info' : ''}}"><i class="glyphicon glyphicon-cloud"></i> Cloud Server</a>
			<a href="{{route('channel.mail_server.list',$channel['domain']->domain)}}" class="list-group-item {{(\Request::route()->getName()=='channel.mail_server.list') ? 'list-group-item-info' : ''}}"><i class="glyphicon glyphicon-envelope"></i> Email Server</a>
			@role(['admin','manage'])
			<a href="{{route('service.list.expired',$channel['domain']->domain)}}" class="list-group-item {{(\Request::route()->getName()=='service.list.expired') ? 'list-group-item-info' : ''}}"><i class="glyphicon glyphicon-time"></i> Dịch vụ sắp hết hạn</a>
			@endrole
		</div>
	</div>
	@role(['admin','manage'])
	<div class="panel panel-primary" style="border-radius:0;border:none;">
		<div class="panel-heading" style="border-radius:0;">
			<a data-toggle="collapse" data-parent="#menuManager" href="#tools" class="close"><i class="glyphicon glyphicon-chevron-down"></i></a>
			<h4 class="panel-title">Công cụ</h4>
		</div>
		<div id="tools" class="panel-collapse collapse in">
			<a href="{{route('tools.email.list',$channel['domain']->domain)}}" class="list-group-item {{(\Request::route()->getName()=='tools.email.list') ? 'list-group-item-info' : ''}}"><i class="glyphicon glyphicon-envelope"></i> Email marketing</a>
		</div>
	</div>
	@endrole
</div>