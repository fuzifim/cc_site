<?
	$channel['theme']->setTitle('Danh sách thành viên');
?>
@include('themes.admin.inc.header')
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				@include('themes.admin.partial.menuManageService')
			</div>
			<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
				<div class="form-group">
					<?
						$roles=\App\Role::get(); 
					?>
					<div class="input-group">
						<div class="input-group-btn search-panel">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
								<span id="search_concept">Lọc theo</span> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li><a href="#"><i class="glyphicon glyphicon-user"></i> <span class="roleName">Tất cả</span> <span class="label label-info">{!!count($getChannelRole)!!}</span></a></li>
								@foreach($roles as $role)
									<li><a href="#contains"><span><i class="glyphicon glyphicon-user"></i> <span class="roleName">{!!$role->display_name!!}</span> <span class="label label-info">{!!\App\Model\Channel_role::where('parent_id','=',$channel['info']->id)->where('role_id','=',$role->id)->count()!!}</span></span></a></li>
								@endforeach
							</ul>
						</div>
						<input type="hidden" name="search_param" value="all" id="search_param">         
						<input type="text" class="form-control" name="x" placeholder="Tìm kiếm...">
						<span class="input-group-btn">
							<button class="btn btn-primary" type="button"><span class="glyphicon glyphicon-search"></span></button>
						</span>
					</div>
				</div>
				@if(count($getChannelRole)>0)
					<ul class="list-group listMember">
						@foreach($getChannelRole as $channelRole) 
						<?
							$getUserInfo=\App\User::find($channelRole->user_id); 
						?>
						<li class="list-group-item">
							<a href="#" class="close dropdown dropdown-toggle"  data-toggle="dropdown"><i class="glyphicon glyphicon-option-vertical"></i>
								<ul class="dropdown-menu" style="padding:0px;right:0px;left:inherit;">
									<a class="list-group-item roleMember" data-id="{{$getUserInfo->id}}" data-dismiss="alert" href="#"><i class="glyphicon glyphicon-edit"></i> Vai trò</a> 
								</ul>
							</a> 
							<i class="glyphicon glyphicon-user"></i> <span class="text-success">{{$getUserInfo->name}}</span> <small><i class="glyphicon glyphicon-time"></i> {!!Site::Date($getUserInfo->created_at)!!}</small>
						</li>
						@endforeach
					</ul>
				@endif
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(e){
    $('.search-panel .dropdown-menu').find('a').click(function(e) {
		e.preventDefault();
		var param = $(this).attr("href").replace("#","");
		var concept = $(this).find('.roleName').text();
		$('.search-panel span#search_concept').text(concept);
		$('.input-group #search_param').val(param);
	});
});
$('.listMember').on("click",".roleMember",function() {
	var userId=$(this).attr('data-id'); 
	getRoleMember(userId); 
}); 
function getRoleMember(userId){
	$('#myModal .modal-title').empty(); 
	$('#myModal .modal-body').empty(); 
	$('#myModal .modal-footer').empty(); 
	$('#myModal .modal-body').append('<div class="messageAlert"></div>'); 
	var formData = new FormData();
	formData.append("userId", userId); 
	$.ajax({
		url: "{{route('channel.members.role.get',$channel['domain']->domain)}}",
		headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
		type: 'post',
		cache: false,
		contentType: false,
		processData: false,
		dataType:'json',
		data: formData,
		success:function(result){
			console.log(result); 
			$('#myModal .modal-title').html('<i class="glyphicon glyphicon-check"></i> Vai trò của <span class="text-info">'+result.member.name+'</span>');  
			$('#myModal .modal-body').append('<div class="row listRole"></div>'); 
			if(result.success==true){
				$.each(result.roles, function(i,item){ 
					if(result.getRole.role_id==item.id){
						$('#myModal .modal-body .listRole').append(''
							+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group">'
								+'<div class="checkbox text-danger">'
									+'<label><input type="radio" name="roleOptions" data-type="roleManager" value="'+item.id+'" checked> '+item.display_name+'<small>(Đang sử dụng)</small></label>'
								+'</div>'
							+'</div></div>'
						+''); 
					}else{
						$('#myModal .modal-body .listRole').append(''
							+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group">'
								+'<div class="checkbox text-primary">'
									+'<label><input type="radio" name="roleOptions" data-type="roleManager" value="'+item.id+'" > '+item.display_name+'</label>'
								+'</div>'
							+'</div></div>'
						+''); 
					}
					
				});
			}else{
				$.each(result.roles, function(i,item){ 
					$('#myModal .modal-body .listRole').append(''
						+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group">'
							+'<div class="checkbox text-primary">'
								+'<label><input type="radio" name="roleOptions" data-type="roleManager" value="'+item.id+'" > '+item.display_name+'</label>'
							+'</div>'
						+'</div></div>'
					+''); 
				});
			}
		},
		error: function(result) {
		}
	});
	$('#myModal .modal-footer').append('<div class="text-right"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button> <button type="button" class="btn btn-primary" id="btnSaveMemberRole" data-id="'+userId+'"><i class="glyphicon glyphicon-ok"></i> Lưu</button></div>'); 
	$('#myModal').modal('show');
}
$('#myModal').on("click","#btnSaveMemberRole",function() {
	var roleId=$('input[name=roleOptions]:checked', '#myModal').val(); 
	var formData = new FormData();
	formData.append("userId", $(this).attr('data-id')); 
	formData.append("roleId", roleId); 
	$.ajax({
		url: "{{route('channel.members.role.save',$channel['domain']->domain)}}",
		headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
		type: 'post',
		cache: false,
		contentType: false,
		processData: false,
		dataType:'json',
		data: formData,
		success:function(result){
			//console.log(result); 
			//location.reload(); 
			getRoleMember(result.userId); 
		},
		error: function(result) {
		}
	});
}); 
$('.btnAddUserManager').click(function(){
	var formData = new FormData();
	formData.append("userId", $(this).attr('data-id')); 
	$.ajax({
		url: "{{route('channel.profile.addusermanager',$channel['domain']->domain)}}",
		headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
		type: 'post',
		cache: false,
		contentType: false,
		processData: false,
		dataType:'json',
		data: formData,
		success:function(result){
			//console.log(result); 
			location.reload(); 
		},
		error: function(result) {
		}
	});
}); 
$('#btnRemoveUserManager').click(function(){
	var formData = new FormData();
	formData.append("userId", $(this).attr('data-id')); 
	$.ajax({
		url: "{{route('channel.profile.removeusermanager',$channel['domain']->domain)}}",
		headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
		type: 'post',
		cache: false,
		contentType: false,
		processData: false,
		dataType:'json',
		data: formData,
		success:function(result){
			//console.log(result); 
			location.reload(); 
		},
		error: function(result) {
		}
	});
}); 
</script>
@include('themes.admin.inc.footer')