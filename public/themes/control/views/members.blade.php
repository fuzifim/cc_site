<?
	$channel['theme']->setTitle('Quản lý thành viên');
	$channel['theme']->setKeywords('Quản lý thành viên');
	$channel['theme']->setDescription('Quản lý thành viên '.$channel['info']->channel_name); 
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->add('bootstrap-colorpicker', 'assets/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js', array('core-script'))!!}
@if($channel['security']==true)
{!!Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'))!!}
@endif
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
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
						<li><a href="#"><i class="glyphicon glyphicon-user"></i> <span class="roleName">Tất cả</span> <span class="label label-info">{!!$getUsers->total()!!}</span></a></li>
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
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">Khoảng {{$getUsers->total()}} kết quả</div>
			</div><!-- panel-heading -->
			@if(count($getUsers)>0)
				<ul class="list-group listMember">
					@foreach($getUsers as $user) 
					<li class="list-group-item @if($user->user_status=='active')list-group-item-success @endif">
						<a href="#" class="close dropdown dropdown-toggle"  data-toggle="dropdown"><i class="glyphicon glyphicon-chevron-down"></i>
							<ul class="dropdown-menu" style="padding:0px;right:0px;left:inherit;">
								<a class="list-group-item roleMember" data-id="{{$user->id}}" data-dismiss="alert" href="#"><i class="glyphicon glyphicon-edit"></i> Vai trò</a> 
							</ul>
						</a> 
						<i class="glyphicon glyphicon-user"></i> <span class="text-success">{{$user->name}}</span> <small>Đăng ký: <i class="glyphicon glyphicon-time"></i> {!!Site::Date($user->created_at)!!}</small> <small>Cập nhật: <i class="glyphicon glyphicon-time"></i> {!!Site::Date($user->updated_at)!!}</small>
						<p><i class="fa fa-envelope-o"></i> {{$user->email}} - <i class="fa fa-phone"></i> {{$user->phone}}</p>
					</li>
					@endforeach
				</ul>
			@endif
			<div class="panel-footer">
				{!!$getUsers->render()!!}
			</div>
		</div>
	</div>

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		jQuery(document).ready(function(){
			"use strict"; 
			$(".listMember").on("click",".roleMember",function() {
				var userId=$(this).attr("data-id"); 
				getRoleMember(userId); 
			}); 
			function getRoleMember(userId){
				$("#myModal .modal-title").empty(); 
				$("#myModal .modal-body").empty(); 
				$("#myModal .modal-footer").empty(); 
				$("#myModal .modal-body").append("<div class=\"messageAlert\"></div>"); 
				var formData = new FormData();
				formData.append("userId", userId); 
				$.ajax({
					url: "'.route("channel.members.role.get",$channel["domain"]->domain).'",
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					type: "post",
					cache: false,
					contentType: false,
					processData: false,
					dataType:"json",
					data: formData,
					success:function(result){
						console.log(result); 
						$("#myModal .modal-title").html("<i class=\"glyphicon glyphicon-check\"></i> Vai trò của <span class=\"text-info\">"+result.member.name+"</span>");  
						$("#myModal .modal-body").append("<div class=\"row listRole\"></div>"); 
						if(result.success==true){
							$.each(result.roles, function(i,item){ 
								if(result.getRole.role_id==item.id){
									$("#myModal .modal-body .listRole").append(""
										+"<div class=\"col-lg-3 col-md-3 col-sm-6 col-xs-12\"><div class=\"form-group\">"
											+"<div class=\"checkbox text-danger\">"
												+"<label><input type=\"radio\" name=\"roleOptions\" data-type=\"roleManager\" value=\""+item.id+"\" checked> "+item.display_name+"<small>(Đang sử dụng)</small></label>"
											+"</div>"
										+"</div></div>"
									+""); 
								}else{
									$("#myModal .modal-body .listRole").append(""
										+"<div class=\"col-lg-3 col-md-3 col-sm-6 col-xs-12\"><div class=\"form-group\">"
											+"<div class=\"checkbox text-primary\">"
												+"<label><input type=\"radio\" name=\"roleOptions\" data-type=\"roleManager\" value=\""+item.id+"\" > "+item.display_name+"</label>"
											+"</div>"
										+"</div></div>"
									+""); 
								}
								
							});
						}else{
							$.each(result.roles, function(i,item){ 
								$("#myModal .modal-body .listRole").append(""
									+"<div class=\"col-lg-3 col-md-3 col-sm-6 col-xs-12\"><div class=\"form-group\">"
										+"<div class=\"checkbox text-primary\">"
											+"<label><input type=\"radio\" name=\"roleOptions\" data-type=\"roleManager\" value=\""+item.id+"\" > "+item.display_name+"</label>"
										+"</div>"
									+"</div></div>"
								+""); 
							});
						}
					},
					error: function(result) {
					}
				});
				$("#myModal .modal-footer").append("<div class=\"text-right\"><button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Đóng</button> <button type=\"button\" class=\"btn btn-primary\" id=\"btnSaveMemberRole\" data-id=\""+userId+"\"><i class=\"glyphicon glyphicon-ok\"></i> Lưu</button></div>"); 
				$("#myModal").modal("show");
			}
			$("#myModal").on("click","#btnSaveMemberRole",function() {
				var roleId=$("input[name=roleOptions]:checked", "#myModal").val(); 
				var formData = new FormData();
				formData.append("userId", $(this).attr("data-id")); 
				formData.append("roleId", roleId); 
				$.ajax({
					url: "'.route("channel.members.role.save",$channel["domain"]->domain).'",
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					type: "post",
					cache: false,
					contentType: false,
					processData: false,
					dataType:"json",
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
			$(".btnAddUserManager").click(function(){
				var formData = new FormData();
				formData.append("userId", $(this).attr("data-id")); 
				$.ajax({
					url: "'.route("channel.profile.addusermanager",$channel["domain"]->domain).'",
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					type: "post",
					cache: false,
					contentType: false,
					processData: false,
					dataType:"json",
					data: formData,
					success:function(result){
						//console.log(result); 
						location.reload(); 
					},
					error: function(result) {
					}
				});
			}); 
			$("#btnRemoveUserManager").click(function(){
				var formData = new FormData();
				formData.append("userId", $(this).attr("data-id")); 
				$.ajax({
					url: "'.route("channel.profile.removeusermanager",$channel["domain"]->domain).'",
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					type: "post",
					cache: false,
					contentType: false,
					processData: false,
					dataType:"json",
					data: formData,
					success:function(result){
						//console.log(result); 
						location.reload(); 
					},
					error: function(result) {
					}
				});
			}); 
		}); 
	', $dependencies);
?>
@if($channel['security']==true)
<?

	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('customAdmin','
	', $dependencies);
?>
@endif
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('onload',' 
	', $dependencies);
?>