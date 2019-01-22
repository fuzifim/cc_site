<?
	$channel['theme']->setTitle('Thông tin tài khoản');
	$channel['theme']->setKeywords('Thông tin tài khoản');
	$channel['theme']->setDescription(' '); 
?>
<?
	$user=Auth::user(); 
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
		<div class="row-pad-5">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
				<button type="button" tabindex="500" class="btn btn-xs btn-primary btn-file" style="position: relative; overflow: hidden; margin-bottom:5px; "><i class="glyphicon glyphicon-camera"></i>&nbsp;  <span>Thay đổi ảnh đại diện</span><input id="changeAvata" name="" type="file" class=""></button> 300x300
				<div class="form-group text-center">
					<div class="addMediaPreload"></div>
					<img class="img-responsive img-thumbnail" id="avataUser" style="width:100%; " src="@if(!empty($user->getAvata->media->media_url)){{$user->getAvata->media->media_url}} @elseif(!empty($user->avata)){{$user->avata}}@else{{asset('assets/img/no-avata.png')}}@endif"> 
				</div>
				<div class="list-group">
				@if($user->hasRole(['admin','manage','staff'])==false && $channel['info']->channel_parent_id==0)
					<button type="button" class="btn-block list-group-item active" id="joinStaff"><i class="glyphicon glyphicon-ok"></i> Tham gia phát triển</button>
				@endif
					<button class="btn-block list-group-item" type="button" id="changePassword"><i class="glyphicon glyphicon-lock"></i> Đổi mật khẩu</button>
				@if($channel['info']->id==137)
					<!--<button class="list-group-item" type="button" id="userNote"><i class="glyphicon glyphicon-list-alt"></i> Báo cáo</button>-->
				@endif
				@if($channel['info']->channel_parent_id==0)
					@role(['admin','manage','staff'])
						<button type="button" class="btn-block list-group-item" id="profileBank"><i class="glyphicon glyphicon-credit-card"></i> Tài khoản ngân hàng</button>
						<button type="button" class="btn-block list-group-item active" id="profileSales"><i class="glyphicon glyphicon-stats"></i> Thống kê đơn hàng</button> 
						<button type="button" class="btn-block list-group-item" id="profileCustomers"><i class="glyphicon glyphicon-briefcase"></i> Danh sách khách hàng</button>
						<button type="button" class="btn-block list-group-item" id="profileVoucher"><i class="glyphicon glyphicon-piggy-bank"></i> Mã khuyến mãi</button>
					@endrole
				@endif
				</div>
			</div>
			<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
				<form id="formProfile">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="message"></div>
							@if($channel['info']->id==137)
								<?
									$showNote=false; 
									$getRoleChannel=\App\Model\Channel_role::where('parent_id','=',$channel['info']->id)->where('user_id','=',$user->id)->first(); 
									if(!empty($getRoleChannel->id)){
										$getRole=\App\Role::findOrFail($getRoleChannel->role_id); 
										$role_permissions = $getRole->perms()->get();
										foreach ($role_permissions as $permission) {
											if ($permission->name == 'note_create') {
												$showNote=true;
											}
										}
									}
								?>
								@if($showNote==true)
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-lg-4 text-center">
											<div class="form-group">
												<button class="btn btn-info btn-block reportWeek" type="button"><span><i class="glyphicon glyphicon-calendar"></i> Báo cáo tuần</span></button>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-lg-4 text-center">
											<div class="form-group">
												<button class="btn btn-info btn-block reportMonth" type="button"><span><i class="glyphicon glyphicon-calendar"></i> Báo cáo quý</span></button>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-lg-4 text-center">
											<div class="form-group">
												<button class="btn btn-info btn-block reportYear" type="button"><span><i class="glyphicon glyphicon-calendar"></i> Báo cáo năm</span></button>
											</div>
										</div>
										
									</div>
								@endif
							@endif
							<div class="form-group">
								<label for="name" class="control-label">Tên tài khoản</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
									<input placeholder="Tên tài khoản" id="name" name="name" value="{{Auth::user()->name}}" type="text" class="form-control" required>
								</div>
								<label class="error" for="name"></label>
							</div>
							<div class="form-group">
								<label for="phone" class="control-label">Số điện thoại</label>
								<div class="input-group">
									<span class="input-group-addon" id="basic-addon1"><i class="flag flag-16 flag-{{mb_strtolower($channel['info']->joinAddress[0]->address->joinRegion->region->iso)}}"></i></span>
									<input placeholder="Số điện thoại" id="phone" name="phone" value="@if(!empty($user->phone)){{$user->phone}}@endif" type="text" class="form-control" required>
								</div>
								<label class="error" for="phone"></label>
							</div>
							<div class="form-group">
								<label for="email" class="control-label">Email</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
									<input placeholder="Địa chỉ email" id="email" name="email" value="@if(!empty($user->email)){{$user->email}}@endif" type="text" class="form-control" required>
								</div>
								<label class="error" for="email"></label>
								@if(Auth::user()->user_status!='active')<div class="groupResend alert alert-warning fade in alert-dismissable"><span class="showResend text-danger">Tài khoản của bạn chưa được kích hoạt. <a href="#" class="label label-primary btnResend"><i class="glyphicon glyphicon-hand-right"></i> Nhận mã kích hoạt</a></span></div>@endif
							</div>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
									<input type="text" name="userAddress" value="@if(!empty($user->joinAddress->address->id)){!!$user->joinAddress->address->address!!}@elseif(!empty($channel['info']->joinAddress[0]->address->address)){!!$channel['info']->joinAddress[0]->address->address!!}@endif" class="form-control" placeholder="Địa chỉ đường, số nhà công ty, cửa hàng..." required />
								</div>
								<label class="error" for="userAddress"></label>
							</div>
							<div class="form-group row-pad-5">
								<div class="col-sm-6">
									<input type="hidden" name="idRegion" value="@if(!empty($user->joinAddress->address->joinRegion->region->id)){!!$user->joinAddress->address->joinRegion->region->id!!}@elseif(!empty($channel['info']->joinAddress[0]->address->joinRegion->region->id)){{$channel['info']->joinAddress[0]->address->joinRegion->region->id}}@endif">
									<input type="hidden" name="regionIso" value="@if(!empty($user->joinAddress->address->joinRegion->region->iso)){!!mb_strtolower($user->joinAddress->address->joinRegion->region->iso)!!}@elseif(!empty($channel['info']->joinAddress[0]->address->joinRegion->region->iso)){{mb_strtolower($channel['info']->joinAddress[0]->address->joinRegion->region->iso)}}@endif">
									<div class="addSelectRegion"></div>
									<div class="mb10"></div>
								</div>
								<div class="col-sm-6">
									<input type="hidden" name="idSubRegion" value="@if(!empty($user->joinAddress->address->joinSubRegion->subregion->id)){!!$user->joinAddress->address->joinSubRegion->subregion->id!!}@elseif(!empty($channel['info']->joinAddress[0]->address->joinSubRegion->subregion->id)){{$channel['info']->joinAddress[0]->address->joinSubRegion->subregion->id}}@endif">
									<div class="addSelectSubRegion"></div>
									<div class="mb10"></div>
								</div>
							</div>
							<div class="form-group row-pad-5">
								<div class="col-sm-6">
									<input type="hidden" name="idDistrict" value="@if(!empty($user->joinAddress->address->joinDistrict->district->id)){!!$user->joinAddress->address->joinDistrict->district->id!!}@elseif(!empty($channel['info']->joinAddress[0]->address->joinDistrict->district->id)){{$channel['info']->joinAddress[0]->address->joinDistrict->district->id}}@endif">
									<div class="addSelectDistrict"></div>
									<div class="mb10"></div>
								</div>
								<div class="col-sm-6">
									<input type="hidden" name="idWard" value="@if(!empty($user->joinAddress->address->joinWard->ward->id)){!!$user->joinAddress->address->joinWard->ward->id!!}@elseif(!empty($channel['info']->joinAddress[0]->address->joinWard->ward->id)){{$channel['info']->joinAddress[0]->address->joinWard->ward->id}}@endif">
									<div class="addSelectWard"></div>
									<div class="mb10"></div>
								</div>
							</div>
						</div>
						<div class="panel-footer text-right">
							<button type="submit" class="btn btn-primary" id="btnProfileSave"><i class="glyphicon glyphicon-ok"></i> Lưu</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

</div><!-- mainpanel -->
{!!Theme::partial('rightpanel', array('title' => 'Header'))!!}
</section>
<?
	if(!empty($user->joinAddress->address->joinSubRegion->subregion->id)){
		$defaultSubRegionId=$user->joinAddress->address->joinSubRegion->subregion->id; 
	}elseif(!empty($channel['info']->joinAddress[0]->address->joinSubRegion->subregion->id)){
		$defaultSubRegionId=$channel['info']->joinAddress[0]->address->joinSubRegion->subregion->id; 
	}else{
		$defaultSubRegionId=0; 
	}
	if(!empty($user->joinAddress->address->joinDistrict->district->id)){
		$defaultDistrictId=$user->joinAddress->address->joinDistrict->district->id; 
	}elseif(!empty($channel['info']->joinAddress[0]->address->joinDistrict->district->id)){
		$defaultDistrictId=$channel['info']->joinAddress[0]->address->joinDistrict->district->id; 
	}else{
		$defaultDistrictId=0; 
	}
	if(!empty($user->joinAddress->address->joinWard->ward->id)){
		$defaultWardId=$user->joinAddress->address->joinWard->ward->id; 
	}elseif(!empty($channel['info']->joinAddress[0]->address->joinWard->ward->id)){
		$defaultWardId=$channel['info']->joinAddress[0]->address->joinWard->ward->id; 
	}else{
		$defaultWardId=0; 
	}
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		var $validator = jQuery("#formProfile").validate({
			highlight: function(element) {
			  jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
			},
			success: function(element) {
			  jQuery(element).closest(".form-group").removeClass("has-error");
			}
		});

		$(".groupResend").on("click",".btnResend",function() {
			$(".groupResend").css("position","rilative"); 
			$(".groupResend").append( "<div class=\"appendPreload\"><div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div></div>" );
			$.ajax({
				url: "'.route("channel.profile.resend",$channel["domain"]->domain).'",
				type: "POST", 
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				success:function(result){
					console.log(result); 
					if(result.success==true){
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						}); 
						$(".groupResend").empty(); 
						$(".groupResend").append("<span class=\"text-success\">Đã gửi mã kích hoạt đến địa chỉ email <strong>'.$user->email.'</strong>, bạn hãy kiểm tra email và kích hoạt tài khoản. Nếu chưa nhận được <a href=\"\" class=\"label label-primary btnResend\"><i class=\"glyphicon glyphicon-hand-right\"></i> Gửi lại mã</a> </span>"); 
					}else{
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-danger",
							sticky: false,
							time: ""
						});
						$(".groupResend .appendPreload").remove(); 
					}
				},
				error: function(result) {
					jQuery.gritter.add({
						title: "Thông báo!",
						text: "Không thể gửi yêu cầu! ", 
						class_name: "growl-danger",
						sticky: false,
						time: ""
					});
					$(".groupResend .appendPreload").remove(); 
				}
			});
		}); 
		$("#changePassword").click(function(){
			$("#myModal .modal-title").empty(); 
			$("#myModal .modal-body").empty(); 
			$("#myModal .modal-footer").empty(); 
			$("#myModal .modal-title").html("<i class=\"glyphicon glyphicon-lock\"></i> Đổi mật khẩu");  
			$("#myModal .modal-body").append(""
				+"<div class=\"form-group\">"
					+"<label for=\"password\" class=\"control-label\">Mật khẩu cũ</label>"
					+"<div class=\"input-group\">"
						+"<span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-lock\"></i></span>"
						+"<input placeholder=\"Mật khẩu cũ\" id=\"password\" name=\"password\" value=\"\" type=\"password\" class=\"form-control\">"
					+"</div>"
				+"</div>"
				+"<div class=\"form-group\">"
					+"<label for=\"passwordNew\" class=\"control-label\">Mật khẩu mới</label>"
					+"<div class=\"input-group\">"
						+"<span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-lock\"></i></span>"
						+"<input placeholder=\"Mật khẩu mới\" id=\"passwordNew\" name=\"passwordNew\" value=\"\" type=\"password\" class=\"form-control\">"
					+"</div>"
				+"</div>"
			+""); 
			$("#myModal .modal-footer").append("<div class=\"text-right\"><button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Đóng</button> <button type=\"button\" class=\"btn btn-primary\" id=\"btnSavePassword\"><i class=\"glyphicon glyphicon-ok\"></i> Lưu</button></div>"); 
			$("#myModal").modal("show");
		}); 
		$("#myModal").on("click","#btnSavePassword",function() {
			var oldPass=$("#myModal input[name=password]").val(); 
			var newPass=$("#myModal input[name=passwordNew]").val(); 
			var formData = new FormData();
			formData.append("oldPass", oldPass); 
			formData.append("newPass", newPass); 
			$.ajax({
				url: "'.route("channel.profile.changepass",$channel["domain"]->domain).'",
				type: "POST", 
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				dataType:"json",
				success:function(result){
					console.log(result); 
					if(result.success==true){
						$(".message").append("<div class=\"alert alert-success alert-dismissable\" id=\"alertError\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"+result.message+"</div>"); 
						$("#myModal").modal("hide");
						$("#alertError").alert();
						$("#alertError").fadeTo(2000, 500).slideUp(500, function(){
							$("#alertError").slideUp(500); 
							location.reload(); 
						});
					}else{
						$(".message").append("<div class=\"alert alert-danger alert-dismissable\" id=\"alertError\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"+result.message+"</div>"); 
						$("#myModal").modal("hide");
					}
				},
				error: function(result) {
				}
			});
		});
		$("#joinStaff").click(function(){
			$.ajax({
				url: "'.route("channel.profile.joinstaff",$channel["domain"]->domain).'",
				type: "POST", 
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				success:function(result){
					console.log(result); 
					if(result.success==true){
						$(".message").append("<div class=\"alert alert-success alert-dismissable\" id=\"alertError\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"+result.message+"</div>"); 
						$("#alertError").alert();
						$("#alertError").fadeTo(2000, 500).slideUp(500, function(){
							$("#alertError").slideUp(500); 
							location.reload(); 
						});
					}
				},
				error: function(result) {
				}
			});
		});
		$("#profileBank").click(function(){
			$("#myModal .modal-title").empty(); 
			$("#myModal .modal-body").empty(); 
			$("#myModal .modal-footer").empty(); 
			$("#myModal .modal-title").html("<i class=\"glyphicon glyphicon-credit-card\"></i> Tài khoản ngân hàng");  
			$.ajax({
				url: "'.route("channel.profile.bank",$channel["domain"]->domain).'",
				type: "GET", 
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				success:function(result){
					console.log(result); 
					$("#myModal .modal-body").append("<div class=\"messageBank\"></div>"
					+"<div class=\"form-group\">"
						+"<label for=\"bankName\" class=\"control-label\">Tên tài khoản</label>"
						+"<input class=\"form-control\" type=\"text\" id=\"bankName\" name=\"bank[]\" data-type=\"bankName\" value=\""+result.bank.bankName+"\" placeholder=\"Tên tài khoản\">"
					+"</div>"
					+"<div class=\"form-group\">"
						+"<label for=\"bankNumber\" class=\"control-label\">Số tài khoản</label>"
						+"<input class=\"form-control\" type=\"text\" id=\"bankNumber\" name=\"bank[]\"  data-type=\"bankNumber\" value=\""+result.bank.bankNumber+"\" placeholder=\"Số tài khoản\">"
					+"</div>"
					+"<div class=\"form-group\">"
						+"<label for=\"bankCompany\" class=\"control-label\">Ngân hàng</label>"
						+"<input class=\"form-control\" type=\"text\" id=\"bankCompany\" name=\"bank[]\"  data-type=\"bankCompany\" value=\""+result.bank.bankCompany+"\" placeholder=\"Tên ngân hàng\">"
					+"</div>"
					+"<div class=\"form-group\">"
						+"<label for=\"bankRegion\" class=\"control-label\">Chi nhánh/ Khu vực</label>"
						+"<input class=\"form-control\" type=\"text\" id=\"bankRegion\" name=\"bank[]\"  data-type=\"bankRegion\" value=\""+result.bank.bankRegion+"\" placeholder=\"Chi nhánh/ Khu vực\">"
					+"</div>"
					+""); 
					$("#myModal .modal-footer").append("<div class=\"text-right\"><button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Đóng</button> <button type=\"button\" class=\"btn btn-primary\" id=\"btnSaveBank\"><i class=\"glyphicon glyphicon-ok\"></i> Lưu</button></div>"); 
					$("#myModal").modal("show");
				},
				error: function(result) {
				}
			});
		}); 
		$("#myModal").on("click","#btnSaveBank",function() {
			var bankJson={}; 
			$.each($("input[name=bank[]]"), function(i,item){ 
				bankJson[$(this).attr("data-type")] = item.value; 
			});
			var dataBank=JSON.stringify(bankJson); 
			var formData = new FormData();
			formData.append("dataBank", dataBank); 
			$.ajax({
				url: "'.route("channel.profile.bank.request",$channel["domain"]->domain).'",
				type: "post", 
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				dataType:"json",
				success:function(result){
					if(result.success==true){
						$("#myModal .messageBank").append("<div class=\"alert alert-success alert-dismissable\" id=\"alertError\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"+result.message+"</div>"); 
						$("#myModal .messageBank").fadeTo(2000, 500).slideUp(500, function(){
							$("#myModal .messageBank").slideUp(500);
						});
					}else{
						$("#myModal .messageBank").append("<div class=\"alert alert-danger alert-dismissable\" id=\"alertError\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"+result.message+"</div>"); 
					}
				},
				error: function(result) {
				}
			});
		});
		$("#profileVoucher").click(function(){
			$("#myModal .modal-title").empty(); 
			$("#myModal .modal-body").empty(); 
			$("#myModal .modal-footer").empty(); 
			$("#myModal .modal-title").html("<i class=\"glyphicon glyphicon-piggy-bank\"></i> Mã khuyến mãi");  
			$.ajax({
				url: "'.route("channel.profile.voucher",$channel["domain"]->domain).'",
				type: "GET", 
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				success:function(result){
					//console.log(result); 
					$("#myModal .modal-body").append(""
					+"<div class=\"form-group\">"
						+"<div class=\"form-group text-center\">Mã khuyến mãi</div>"
						+"<div class=\"text-center\" style=\"font-size:42px;\"><strong><code>"+result.voucherCode+"</code></strong></div>"
					+"</div>"); 
					$("#myModal .modal-footer").append("<div class=\"text-right\"><button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Đóng</button></div>"); 
					$("#myModal").modal("show");
				},
				error: function(result) {
				}
			});
		}); 
		$("#profileCustomers").click(function(){
			$("#myModal .modal-title").empty(); 
			$("#myModal .modal-body").empty(); 
			$("#myModal .modal-footer").empty(); 
			$("#myModal .modal-title").html("<i class=\"glyphicon glyphicon-briefcase\"></i> Thống kê khách hàng");  
			$.ajax({
				url: "'.route("channel.profile.customers",$channel["domain"]->domain).'",
				type: "GET", 
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				success:function(result){
					//console.log(result); 
					$("#myModal .modal-body").append("<div class=\"list-group contentData\"></div>"); 
					$.each(result.customer, function(i, item) {
						$(".contentData").append("<div class=\"list-group-item\"><span class=\"numberCount\" style=\"font-size:20px; font-weight:bold; \">"+(i+1)+" - "+item.channelName+"</span>"
							+"<div class=\"\"><i class=\"glyphicon glyphicon-map-marker\"></i> "+item.channelCompany.company_address+"</div>"
							+"<div class=\"\"><i class=\"glyphicon glyphicon-earphone\"></i> "+item.channelPhone.phone_number+"</div>"
							+"<div class=\"\"><i class=\"glyphicon glyphicon-envelope\"></i> "+item.channelEmail.email_address+"</div>"
							+"<div class=\"\"><a href=\"//"+item.channelDomain.domain+"\"><i class=\"glyphicon glyphicon-globe\"></i> "+item.channelDomain.domain+"</a></div>"
						+"</div>");
					})
					$("#myModal .modal-footer").append("<div class=\"text-right\"><button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Đóng</button></div>"); 
					$("#myModal").modal("show");
				},
				error: function(result) {
				}
			});
		}); 
		$("#profileSales").click(function(){
			$("#myModal .modal-title").empty(); 
			$("#myModal .modal-body").empty(); 
			$("#myModal .modal-footer").empty(); 
			$("#myModal .modal-title").html("<i class=\"glyphicon glyphicon-stats\"></i> Thống kê đơn hàng");  
			$.ajax({
				url: "'.route("channel.profile.sales",$channel["domain"]->domain).'",
				type: "GET", 
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				success:function(result){
					//console.log(result); 
					$("#myModal .modal-body").append(""
					+"<div class=\"form-group\">"
						+"<div class=\"form-group text-center\">Tổng số đơn hàng đăng ký thành công</div>"
						+"<div class=\"text-center\" style=\"font-size:42px;\"><strong>"+result.totalOrder+"</strong></div>"
					+"</div>"); 
					$("#myModal .modal-footer").append("<div class=\"text-right\"><button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Đóng</button></div>"); 
					$("#myModal").modal("show");
				},
				error: function(result) {
				}
			});
		}); 
		$("#btnProfileSave").click(function(){
			var $valid = jQuery("#formProfile").valid();
			if(!$valid) {
				$validator.focusInvalid();
				return false;
			}else{
				var name=$("input[name=name]").val(); 
				var phone=$("input[name=phone]").val(); 
				var email=$("input[name=email]").val(); 
				var address=$("input[name=userAddress]").val(); 
				var region=$("input[name=idRegion]").val();  
				var subRegion=$("input[name=idSubRegion]").val(); 
				var district=$("input[name=idDistrict]").val();  
				var ward=$("input[name=idWard]").val();  
				var formData = new FormData();
				formData.append("name", name); 
				formData.append("phone", phone); 
				formData.append("email", email); 
				formData.append("address", address); 
				formData.append("region", region); 
				formData.append("subRegion", subRegion); 
				formData.append("district", district); 
				formData.append("ward", ward); 
				$.ajax({
					url: "'.route("profile.save",$channel["domain"]->domain).'",
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					type: "POST",
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					dataType:"json",
					success:function(result){
						console.log(result); 
						if(result.success==false){
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-danger",
								sticky: false,
								time: ""
							});
						}else if(result.success==true){
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-success",
								sticky: false,
								time: ""
							});
							location.reload(); 
						}
						
					},
					error: function(result) {
						jQuery.gritter.add({
							title: "Thông báo!",
							text: "Không thể cập nhật thông tin, vui lòng thử lại! ", 
							class_name: "growl-danger",
							sticky: false,
							time: ""
						});
					}
				});
				return false; 
			}
		});
		$("#changeAvata").bind("change", function(){
			var files = $("#changeAvata").prop("files")[0];  
			$(".addMediaPreload" ).append( "<div class=\"appendPreload\"><div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div></div>" );
			var formData = new FormData();
			formData.append("file", files); 
			$.ajax({
				url: "'.route("channel.upload.file",$channel["domain"]->domain).'",
				type: "post", 
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				dataType:"json",
				success:function(result){
					console.log(result); 
					var formDataChannel = new FormData();
					formDataChannel.append("mediaId", result.id); 
					$.ajax({
						url: "'.route("profile.logo.change",$channel["domain"]->domain).'",
						type: "post", 
						headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
						cache: false,
						contentType: false,
						processData: false,
						data: formDataChannel,
						dataType:"json",
						success:function(resultMedia){
							$(".addMediaPreload").empty(); 
							$("#avataUser").attr("src", result.url); 
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-success",
								sticky: false,
								time: ""
							});
						},
						error: function(resultMedia) {
						}
					});
				},
				error: function(result) {
				}
			});
			
		});
		getRegions(); 
		$(".addSelectRegion").on("change",".selectRegion",function() {
			getSubregion($(this).val()); 
			getDistrict(0); 
			getWard(0); 
			$("input[name=regionIso]").val($(this).find("option:selected").attr("data-iso")); 
			$("input[name=idRegion]").val($(this).val()); 
			$("input[name=idSubRegion]").val(0); 
			$("input[name=idDistrict]").val(0); 
		});
		$(".addSelectSubRegion").on("change",".selectSubRegion",function() {
			getDistrict($(this).val()); 
			getWard(0);
			$("input[name=idSubRegion]").val($(this).val()); 
			$("input[name=idDistrict]").val(0); 
		});
		$(".addSelectDistrict").on("change",".selectDistrict",function() {
			getWard($(this).val()); 
			$("input[name=idDistrict]").val($(this).val()); 
			$("input[name=idWard]").val(0); 
		});
		$(".addSelectWard").on("change",".selectWard",function() {
			$("input[name=idWard]").val($(this).val()); 
		});
		function getRegions(){
			$(".addSelectRegion").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải quốc gia, vui lòng chờ...</small></div>"); 
			$.ajax({
				url: "'.route("regions.json.list",$channel["domain"]->domain).'",
				type: "GET",
				dataType: "json",
				success: function (result) {
					$(".addSelectRegion .loading").empty(); 
					if(result.success==true){
						getSubregion($("input[name=idRegion]").val()); 
						$(".addSelectRegion").append("<div class=\"input-group\"><span class=\"input-group-addon\"><i class=\"flag flag-"+$("input[name=regionIso]").val()+"\"></i></span><select class=\"selectRegion\" data-placeholder=\"Chọn quốc gia...\" name=\"channelRegion\" required>"
						+"<option value=\"\"></option></select></div><label class=\"error\" for=\"channelRegion\"></label>"); 
						$.each(result.region, function(i, item) {
							if(item.id==$("input[name=idRegion]").val()){
								$(".addSelectRegion .selectRegion").append("<option value="+item.id+" data-icon=\"flag-"+item.iso.toLowerCase()+"\"  data-iso="+item.iso.toLowerCase()+" selected>"+item.country+"</option>");
							}else{
								$(".addSelectRegion .selectRegion").append("<option value="+item.id+"  data-icon=\"flag-"+item.iso.toLowerCase()+"\"  data-iso="+item.iso.toLowerCase()+" >"+item.country+"</option>");
							}
						}); 
						function format(icon) {
							var originalOption = icon.element;
							return "<i class=\"flag " + $(originalOption).data("icon") + "\"></i> " + icon.text;
						}
						jQuery(".addSelectRegion .selectRegion").select2({
							width: "100%",
							formatResult: format
						});
					}else{
						
					}
				}
			});
		} 
		function getSubregion(idRegion){
			$(".addSelectSubRegion").empty(); 
			$(".addSelectSubRegion").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải thành phố, vui lòng chờ...</small></div>"); 
			var formData = new FormData();
			formData.append("idRegion", idRegion); 
			$.ajax({
				url: "'.route("subregion.json.list.post",$channel["domain"]->domain).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				data:formData,
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) {
					$(".addSelectSubRegion .loading").empty(); 
					$(".addSelectRegion .input-group-addon").html("<i class=\"flag flag-"+$("input[name=regionIso]").val()+"\"></i>"); 
					if(result.success==true){
						getDistrict($("input[name=idSubRegion]").val()); 
						$(".addSelectSubRegion").append("<select class=\"selectSubRegion\" data-placeholder=\"Chọn thành phố...\" name=\"channelSubRegion\">"
						+"<option value=\"\"></option></select><label class=\"error\" for=\"channelSubRegion\"></label>"); 
						$.each(result.subregion, function(i, item) {
							if(item.id==$("input[name=idSubRegion]").val()){
								$(".addSelectSubRegion .selectSubRegion").append("<option value="+item.id+" selected>"+item.subregions_name+"</option>");
							}else{
								$(".addSelectSubRegion .selectSubRegion").append("<option value="+item.id+">"+item.subregions_name+"</option>");
							}
						}); 
						function format(icon) {
							var originalOption = icon.element;
							return "<i class=\"fa fa-map-marker\"></i> " + icon.text;
						}
						jQuery(".addSelectSubRegion .selectSubRegion").select2({
							width: "100%",
							formatResult: format
						});
					}else{
						$(".addSelectSubRegion").empty(); 
					}
				}
			});
		}
		function getDistrict(idSubRegion){
			$(".addSelectDistrict").empty(); 
			$(".addSelectDistrict").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải quận huyện, vui lòng chờ...</small></div>"); 
			var formData = new FormData();
			formData.append("idSubRegion", idSubRegion); 
			$.ajax({
				url: "'.route("district.json.list.post",$channel["domain"]->domain).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				data:formData,
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) {
					$(".addSelectDistrict .loading").empty(); 
					if(result.success==true){
						getWard($("input[name=idDistrict]").val()); 
						$(".addSelectDistrict").append("<select class=\"selectDistrict\" data-placeholder=\"Chọn quận huyện...\" name=\"channelDistrict\">"
							+"<option value=\"\"></option></select><label class=\"error\" for=\"channelDistrict\"></label>"); 
						$.each(result.district, function(i, item) {
							if(item.id=='.$defaultDistrictId.'){
								$(".addSelectDistrict .selectDistrict").append("<option value="+item.id+" selected>"+item.district_name+"</option>");
							}else{
								$(".addSelectDistrict .selectDistrict").append("<option value="+item.id+">"+item.district_name+"</option>");
							}
						}); 
						function format(icon) {
							var originalOption = icon.element;
							return "<i class=\"fa fa-map-marker\"></i> " + icon.text;
						}
						jQuery(".addSelectDistrict .selectDistrict").select2({
							width: "100%",
							formatResult: format
						});
					}else{
						$(".addSelectDistrict").empty(); 
					}
				}
			});
		}
		function getWard(idDistrict){
			$(".addSelectWard").empty(); 
			$(".addSelectWard").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải phường xã, vui lòng chờ...</small></div>"); 
			var formData = new FormData();
			formData.append("idDistrict", idDistrict); 
			$.ajax({
				url: "'.route("ward.json.list.post",$channel["domain"]->domain).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				data:formData,
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) {
					//console.log(result); 
					$(".addSelectWard .loading").empty(); 
					if(result.success==true){
						$(".addSelectWard").append("<select class=\"selectWard\" data-placeholder=\"Chọn phường xã...\" name=\"channelWard\">"
							+"<option value=\"\"></option></select><label class=\"error\" for=\"channelWard\"></label>"); 
						$.each(result.ward, function(i, item) {
							if(item.id=='.$defaultWardId.'){
								$(".addSelectWard .selectWard").append("<option value="+item.id+" selected>"+item.ward_name+"</option>");
							}else{
								$(".addSelectWard .selectWard").append("<option value="+item.id+">"+item.ward_name+"</option>");
							}
						}); 
						function format(icon) {
							var originalOption = icon.element;
							return "<i class=\"fa fa-map-marker\"></i> " + icon.text;
						}
						jQuery(".addSelectWard .selectWard").select2({
							width: "100%",
							formatResult: format
						});
					}else{
						$(".addSelectWard").empty(); 
					}
				}
			});
		}
	', $dependencies);
?>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('onload',' 
		var docHeight = jQuery(document).height();
		if(docHeight > jQuery(".mainpanel").height())
		jQuery(".mainpanel").height(docHeight);
	', $dependencies);
?>