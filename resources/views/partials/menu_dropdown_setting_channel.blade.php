<ul class="dropdown-menu">
	<li><a href="{{route('user.templateType.setting',array($temp_set->id,'general'))}}">
		<i class="glyphicon glyphicon-cog"></i> Cài đặt thông tin</a>
	</li>
	<li><a href="{{route('user.templateType.setting',array($temp_set->id,'image'))}}">
		<i class="glyphicon glyphicon-picture"></i> Cài đặt hình ảnh</a>
	</li>
	<li><a href="{{route('user.templateType.setting',array($temp_set->id,'contact'))}}">
		<i class="glyphicon glyphicon-map-marker"></i> Thông tin liên hệ</a>
	</li>
	<li><a href="{{route('user.templateType.setting',array($temp_set->id,'social'))}}">
		<i class="glyphicon glyphicon-share"></i> Mạng xã hội</a>
	</li>
	<li><a href="{{route('user.templateType.setting',array($temp_set->id,'domain'))}}">
		<i class="glyphicon glyphicon-globe"></i> Tên miền</a>
	</li>
</ul>