<?
	$channel['theme']->setTitle('Thông tin tài khoản');
?>
@include('themes.admin.inc.header')
<link type="text/css" href="{{asset('assets/flags-img/16x16/sprite-flags-16x16.css')}}" rel="stylesheet"/>
	<div class="section">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					<button type="button" tabindex="500" class="btn btn-xs btn-primary btn-file" style="position: relative; overflow: hidden; margin-bottom:5px; "><i class="glyphicon glyphicon-camera"></i>&nbsp;  <span>Thay đổi ảnh đại diện</span><input id="changeAvata" name="" type="file" class=""></button> 300x300
					<div class="form-group text-center">
						<img class="img-responsive img-thumbnail" id="logoChannel" style="width:100%; " src="@if(!empty(Auth::user()->getAvata->media->media_url)){{Auth::user()->getAvata->media->media_url}} @else {{asset('assets/img/no-avata.png')}} @endif"> 
					</div>
					<div class="list-group">
					@if(Auth::user()->hasRole(['admin','manage','staff'])==false && $channel['info']->channel_parent_id==0)
						<button type="button" class="list-group-item active" id="joinStaff"><i class="glyphicon glyphicon-ok"></i> Tham gia phát triển</button>
					@endif
						<button class="list-group-item" type="button" id="changePassword"><i class="glyphicon glyphicon-lock"></i> Đổi mật khẩu</button>
					@if($channel['info']->id==137)
						<!--<button class="list-group-item" type="button" id="userNote"><i class="glyphicon glyphicon-list-alt"></i> Báo cáo</button>-->
					@endif
					@if($channel['info']->channel_parent_id==0)
						@role(['admin','manage','staff'])
							<button type="button" class="list-group-item" id="profileBank"><i class="glyphicon glyphicon-credit-card"></i> Tài khoản ngân hàng</button>
							<button type="button" class="list-group-item active" id="profileSales"><i class="glyphicon glyphicon-stats"></i> Thống kê đơn hàng</button> 
							<button type="button" class="list-group-item" id="profileCustomers"><i class="glyphicon glyphicon-briefcase"></i> Danh sách khách hàng</button>
							<button type="button" class="list-group-item" id="profileVoucher"><i class="glyphicon glyphicon-piggy-bank"></i> Mã khuyến mãi</button>
						@endrole
					@endif
					</div>
				</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="message"></div>
							@if($channel['info']->id==137)
								<?
									$showNote=false; 
									$getRoleChannel=\App\Model\Channel_role::where('parent_id','=',$channel['info']->id)->where('user_id','=',Auth::user()->id)->first(); 
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
									<input placeholder="Tên tài khoản" id="name" name="name" value="{{Auth::user()->name}}" type="text" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label for="phone" class="control-label">Số điện thoại</label>
								<div class="input-group">
									<span class="input-group-addon" id="basic-addon1"><i class="flag flag-16 flag-{{mb_strtolower($channel['info']->channelJoinRegion->region->iso)}}"></i></span>
									<input placeholder="Số điện thoại" id="phone" name="phone" value="@if(!empty(Auth::user()->phoneJoin->phone->phone_number)){{Auth::user()->phoneJoin->phone->phone_number}}@else{{$channel['info']->channelJoinRegion->region->phone_prefix}}@endif" type="text" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="control-label">Email</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
									<input placeholder="Địa chỉ email" id="email" name="email" value="{{Auth::user()->emailJoin->email->email_address}}" type="text" class="form-control" disabled>
								</div>
							</div>
							<div class="form-group">
								@if(!empty(Auth::user()->getRegion->region->country))
									<div class="fieldsAdd" id="changeRegion">
										<span id="flagIso"><i class="flag flag-16 flag-{{mb_strtolower(Auth::user()->getRegion->region->iso)}}"></i></span> <span id="channelRegionName">{{Auth::user()->getRegion->region->country}}</span> 
										<input type="hidden" id="region" name="channelRegion" value="{{Auth::user()->getRegion->region->id}}">
										<span class="glyphicon glyphicon-menu-down btnAdd"></span>
									</div>
								@else
									<div class="fieldsAdd" id="changeRegion">
										<span id="flagIso"><i class="flag flag-16 flag-{{mb_strtolower($channel['info']->channelJoinRegion->region->iso)}}"></i></span> <span id="channelRegionName">{{$channel['info']->channelJoinRegion->region->country}}</span> 
										<input type="hidden" id="region" name="channelRegion" value="{{$channel['info']->channelJoinRegion->region->id}}">
										<span class="glyphicon glyphicon-menu-down btnAdd"></span>
									</div>
								@endif 
								
							</div>
							<div class="form-group">
								@if(!empty(Auth::user()->getSubRegion->subRegion->subregions_name))
									<div class="fieldsAdd" id="changeSubregion">
										<span id="subRegionName"><i class="glyphicon glyphicon-map-marker"></i> {{Auth::user()->getSubRegion->subRegion->subregions_name}} </span>
										<input type="hidden" name="channelSubregion" id="subRegion" value="{{Auth::user()->getSubRegion->subRegion->id}}">
										<span class="glyphicon glyphicon-menu-down btnAdd"></span>
									</div>
								@else
									<div class="fieldsAdd" id="changeSubregion">
										<span id="subRegionName"><i class="glyphicon glyphicon-map-marker"></i> {{$channel['info']->channelJoinSubRegion->subregion->subregions_name}} </span>
										<input type="hidden" name="channelSubregion" id="subRegion" value="{{$channel['info']->channelJoinSubRegion->subregion->id}}">
										<span class="glyphicon glyphicon-menu-down btnAdd"></span>
									</div>
								@endif 
							</div>
						</div>
						<div class="panel-footer text-right">
							<button type="button" class="btn btn-primary" id="btnProfileSave"><i class="glyphicon glyphicon-ok"></i> Lưu</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<div id="loading">
	<ul class="bokeh">
		<li></li>
		<li></li>
		<li></li>
	</ul>
</div>
<script>
	@if($channel['info']->id==137)
		$('.reportWeek').click(function(){
			var reportType='note_week'; 
			getReportWeek(reportType); 
		}); 
		$('.reportMonth').click(function(){
			var reportType='note_month'; 
			getReportWeek(reportType); 
		}); 
		$('.reportYear').click(function(){
			var reportType='note_year'; 
			getReportWeek(reportType); 
		}); 
		function getReportWeek(reportType){
			$('#myModal .modal-title').empty(); 
			$('#myModal .modal-body').empty(); 
			$('#myModal .modal-footer').empty(); 
			var formData = new FormData();
			formData.append("reportType", reportType); 
			$.ajax({
				url: "{{route('channel.profile.note.get',$channel['domain']->domain)}}",
				type: 'POST', 
				headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				dataType:'json',
				success:function(result){
					console.log(result); 
					$('#myModal .modal-body').append('<div class="messageAlert"></div>'); 
					$('#myModal .modal-title').html('<button type="button" class="btn btn-success" id="addUserNote"><i class="glyphicon glyphicon-plus-sign"></i> Thêm loại báo cáo</button>');  
					if(result.success==false){
						$('#myModal .modal-body .messageAlert').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>');
					}else{
						$('#myModal .modal-body').append('<div class="alert alert-success alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><p><i class="glyphicon glyphicon-time"></i> Báo cáo này được cập nhật vào lúc: '+result.dataNote.updated_at+'</p></div>');
						var obj = JSON.parse(result.dataNote.value); 
						$.each(obj, function(i,data){
							var objItem = JSON.parse(data); 
							$('#myModal .modal-body').append(''
								+'<div class="alert alert-info">'
									+'<div class="row groupInput">'
										+'<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label for="name" class="control-label">'+objItem.inputNoteName+'</label><input placeholder="'+objItem.inputNoteName+'" id="inputNote" name="inputNote[]" data-type="inputNoteName" value="'+objItem.inputNoteName+'" type="text" class="form-control" disabled></div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><a href="#" class="close removeUserNote" data-dismiss="alert" aria-label="close">&times;</a><label for="name" class="control-label">Số liệu</label><input placeholder="'+objItem.inputNoteNumber+'" id="inputNote" name="inputNote[]" data-type="inputNoteNumber" value="'+objItem.inputNoteNumber+'" type="text" class="form-control"></div>'
									+'</div>'
								+'</div>'
							+''); 
						});
					}
					$('#myModal .modal-footer').append('<div class="text-right"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button> <button type="button" class="btn btn-primary" id="btnSaveUserNoteWeek" data-type="'+reportType+'"><i class="glyphicon glyphicon-ok"></i> Lưu</button></div>'); 
					$('#myModal').modal('show');
				},
				error: function(result) {
				}
			});
		}
	$('#myModal').on("click","#btnSaveUserNoteWeek",function(){
		var inputJsonGroup={}; 
		$.each($("#myModal .groupInput"), function(i,dataGroup){ 
			var inputJson={}; 
			$.each($(dataGroup).find("input[name='inputNote[]']"), function(y,item){ 
				inputJson[$(this).attr('data-type')] = item.value; 
			});
			var dataInput=JSON.stringify(inputJson); 
			inputJsonGroup[i]=dataInput; 
		});
		var dataInputGroup=JSON.stringify(inputJsonGroup); 
		var formData = new FormData();
		formData.append("reportType", $(this).attr('data-type')); 
		formData.append("dataInputGroup", dataInputGroup); 
		$.ajax({
			url: "{{route('channel.profile.note.save',$channel['domain']->domain)}}",
			type: 'POST', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				console.log(result); 
				getReportWeek($(this).attr('data-type')); 
			},
			error: function(result) {
			}
		});
	}); 
	@endif
	$('#userNote').click(function(){
		getUserNote(); 
	}); 
	function getUserNote(){
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$.ajax({
			url: "{{route('channel.profile.note',$channel['domain']->domain)}}",
			type: 'GET', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			success:function(result){
				console.log(result); 
				$('#myModal .modal-title').html('<button type="button" class="btn btn-success" id="addUserNote"><i class="glyphicon glyphicon-plus-sign"></i> Thêm mới loại báo cáo</button>');  
				$('#myModal .modal-body').append('<div class="messageAlert"></div>'); 
				if(result.success==false){
					$('#myModal .modal-body .messageAlert').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>');
				}else{
					
				}
				$('#myModal .modal-footer').append('<div class="text-right"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button> <button type="button" class="btn btn-primary" id="btnSaveUserNote"><i class="glyphicon glyphicon-ok"></i> Lưu</button></div>'); 
				$('#myModal').modal('show');
			},
			error: function(result) {
			}
		});
	}
	$('#myModal').on("click","#addUserNote",function() {
		$('#myModal .modal-body .messageAlert').empty(); 
		$('#myModal .modal-body').append(''
			+'<div class="alert alert-info">'
				+'<div class="row groupInput">'
					+'<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><label for="name" class="control-label">Loại báo cáo</label><input placeholder="Tên báo cáo" id="inputNote" name="inputNote[]" data-type="inputNoteName" value="" type="text" class="form-control"></div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><a href="#" class="close removeUserNote" data-dismiss="alert" aria-label="close">&times;</a><label for="name" class="control-label">Số liệu</label><input placeholder="Nhập số liệu báo cáo" id="inputNote" name="inputNote[]" data-type="inputNoteNumber" value="" type="text" class="form-control"></div>'
				+'</div>'
			+'</div>'
		+''); 
	}); 
	$('#myModal').on("click",".removeUserNote",function() {
		$(this).parent().parent().parent().remove(); 
	}); 
	$('#changePassword').click(function(){
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$('#myModal .modal-title').html('<i class="glyphicon glyphicon-lock"></i> Đổi mật khẩu');  
		$('#myModal .modal-body').append(''
			+'<div class="form-group">'
				+'<label for="phone" class="control-label">Mật khẩu cũ</label>'
				+'<div class="input-group">'
					+'<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>'
					+'<input placeholder="Mật khẩu cũ" id="password" name="password" value="" type="password" class="form-control">'
				+'</div>'
			+'</div>'
			+'<div class="form-group">'
				+'<label for="phone" class="control-label">Mật khẩu mới</label>'
				+'<div class="input-group">'
					+'<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>'
					+'<input placeholder="Mật khẩu mới" id="passwordNew" name="passwordNew" value="" type="password" class="form-control">'
				+'</div>'
			+'</div>'
		+''); 
		$('#myModal .modal-footer').append('<div class="text-right"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button> <button type="button" class="btn btn-primary" id="btnSavePassword"><i class="glyphicon glyphicon-ok"></i> Lưu</button></div>'); 
		$('#myModal').modal('show');
	}); 
	$('#myModal').on("click","#btnSavePassword",function() {
		var oldPass=$('#myModal input[name=password]').val(); 
		var newPass=$('#myModal input[name=passwordNew]').val(); 
		var formData = new FormData();
		formData.append("oldPass", oldPass); 
		formData.append("newPass", newPass); 
		$.ajax({
			url: "{{route('channel.profile.changepass',$channel['domain']->domain)}}",
			type: 'POST', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				console.log(result); 
				if(result.success==true){
					$('.message').append('<div class="alert alert-success alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					$('#myModal').modal('hide');
					$("#alertError").alert();
					$("#alertError").fadeTo(2000, 500).slideUp(500, function(){
						$("#alertError").slideUp(500); 
						location.reload(); 
					});
				}else{
					$('.message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					$('#myModal').modal('hide');
				}
			},
			error: function(result) {
			}
		});
	});
	$('#joinStaff').click(function(){
		$.ajax({
			url: "{{route('channel.profile.joinstaff',$channel['domain']->domain)}}",
			type: 'POST', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			success:function(result){
				console.log(result); 
				if(result.success==true){
					$('.message').append('<div class="alert alert-success alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
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
	$('#profileBank').click(function(){
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$('#myModal .modal-title').html('<i class="glyphicon glyphicon-credit-card"></i> Tài khoản ngân hàng');  
		$.ajax({
			url: "{{route('channel.profile.bank',$channel['domain']->domain)}}",
			type: 'GET', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			success:function(result){
				console.log(result); 
				$('#myModal .modal-body').append('<div class="messageBank"></div>'
				+'<div class="form-group">'
					+'<label for="bankName" class="control-label">Tên tài khoản</label>'
					+'<input class="form-control" type="text" id="bankName" name="bank[]" data-type="bankName" value="'+result.bank.bankName+'" placeholder="Tên tài khoản">'
				+'</div>'
				+'<div class="form-group">'
					+'<label for="bankNumber" class="control-label">Số tài khoản</label>'
					+'<input class="form-control" type="text" id="bankNumber" name="bank[]"  data-type="bankNumber" value="'+result.bank.bankNumber+'" placeholder="Số tài khoản">'
				+'</div>'
				+'<div class="form-group">'
					+'<label for="bankCompany" class="control-label">Ngân hàng</label>'
					+'<input class="form-control" type="text" id="bankCompany" name="bank[]"  data-type="bankCompany" value="'+result.bank.bankCompany+'" placeholder="Tên ngân hàng">'
				+'</div>'
				+'<div class="form-group">'
					+'<label for="bankRegion" class="control-label">Chi nhánh/ Khu vực</label>'
					+'<input class="form-control" type="text" id="bankRegion" name="bank[]"  data-type="bankRegion" value="'+result.bank.bankRegion+'" placeholder="Chi nhánh/ Khu vực">'
				+'</div>'
				+''); 
				$('#myModal .modal-footer').append('<div class="text-right"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button> <button type="button" class="btn btn-primary" id="btnSaveBank"><i class="glyphicon glyphicon-ok"></i> Lưu</button></div>'); 
				$('#myModal').modal('show');
			},
			error: function(result) {
			}
		});
	}); 
	$('#myModal').on("click","#btnSaveBank",function() {
		var bankJson={}; 
		$.each($("input[name='bank[]']"), function(i,item){ 
			bankJson[$(this).attr('data-type')] = item.value; 
		});
		var dataBank=JSON.stringify(bankJson); 
		var formData = new FormData();
		formData.append("dataBank", dataBank); 
		$.ajax({
			url: "{{route('channel.profile.bank.request',$channel['domain']->domain)}}",
			type: 'post', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				if(result.success==true){
					$('#myModal .messageBank').append('<div class="alert alert-success alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					$("#myModal .messageBank").fadeTo(2000, 500).slideUp(500, function(){
						$("#myModal .messageBank").slideUp(500);
					});
				}else{
					$('#myModal .messageBank').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				}
			},
			error: function(result) {
			}
		});
	});
	$('#profileVoucher').click(function(){
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$('#myModal .modal-title').html('<i class="glyphicon glyphicon-piggy-bank"></i> Mã khuyến mãi');  
		$.ajax({
			url: "{{route('channel.profile.voucher',$channel['domain']->domain)}}",
			type: 'GET', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			success:function(result){
				//console.log(result); 
				$('#myModal .modal-body').append(''
				+'<div class="form-group">'
					+'<div class="form-group text-center">Mã khuyến mãi</div>'
					+'<div class="text-center" style="font-size:42px;"><strong><code>'+result.voucherCode+'</code></strong></div>'
				+'</div>'); 
				$('#myModal .modal-footer').append('<div class="text-right"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button></div>'); 
				$('#myModal').modal('show');
			},
			error: function(result) {
			}
		});
	}); 
	$('#profileCustomers').click(function(){
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$('#myModal .modal-title').html('<i class="glyphicon glyphicon-briefcase"></i> Thống kê khách hàng');  
		$.ajax({
			url: "{{route('channel.profile.customers',$channel['domain']->domain)}}",
			type: 'GET', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			success:function(result){
				//console.log(result); 
				$('#myModal .modal-body').append('<div class="list-group contentData"></div>'); 
				$.each(result.customer, function(i, item) {
					$('.contentData').append('<div class="list-group-item"><span class="numberCount" style="font-size:20px; font-weight:bold; ">'+(i+1)+' - '+item.channelName+'</span>'
						+'<div class=""><i class="glyphicon glyphicon-map-marker"></i> '+item.channelCompany.company_address+'</div>'
						+'<div class=""><i class="glyphicon glyphicon-earphone"></i> '+item.channelPhone.phone_number+'</div>'
						+'<div class=""><i class="glyphicon glyphicon-envelope"></i> '+item.channelEmail.email_address+'</div>'
						+'<div class=""><a href="//'+item.channelDomain.domain+'"><i class="glyphicon glyphicon-globe"></i> '+item.channelDomain.domain+'</a></div>'
					+'</div>');
				})
				$('#myModal .modal-footer').append('<div class="text-right"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button></div>'); 
				$('#myModal').modal('show');
			},
			error: function(result) {
			}
		});
	}); 
	$('#profileSales').click(function(){
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$('#myModal .modal-title').html('<i class="glyphicon glyphicon-stats"></i> Thống kê đơn hàng');  
		$.ajax({
			url: "{{route('channel.profile.sales',$channel['domain']->domain)}}",
			type: 'GET', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			success:function(result){
				//console.log(result); 
				$('#myModal .modal-body').append(''
				+'<div class="form-group">'
					+'<div class="form-group text-center">Tổng số đơn hàng đăng ký thành công</div>'
					+'<div class="text-center" style="font-size:42px;"><strong>'+result.totalOrder+'</strong></div>'
				+'</div>'); 
				$('#myModal .modal-footer').append('<div class="text-right"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button></div>'); 
				$('#myModal').modal('show');
			},
			error: function(result) {
			}
		});
	}); 
	$('#btnProfileSave').click(function(){
		$(".message").empty(); 
		var name=$('input[name=name]').val(); 
		var phone=$('input[name=phone]').val(); 
		var email=$('input[name=email]').val(); 
		var region=$('input[name=channelRegion]').val();  
		var subRegion=$('input[name=channelSubregion]').val();  
		var formData = new FormData();
		formData.append("name", name); 
		formData.append("phone", phone); 
		formData.append("email", email); 
		formData.append("region", region); 
		formData.append("subRegion", subRegion); 
		$.ajax({
			url: "{{route('profile.save',$channel['domain']->domain)}}",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				//console.log(result); 
				if(result.success==false){
					if(result.errorType=='validation'){
						$('.message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>'); 
						var res = jQuery.parseJSON(JSON.stringify(result.message)); 
						var name;
						jQuery.each(res, function(i, val) {
							$('.message #alertError').append('<li>'+val+'</li>');
						});
					}else{
						$('.message').append('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					}
				}else if(result.success==true){
					$('.message').append('<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					$(".message").fadeTo(2000, 500).slideUp(500, function(){
						$(".message").slideUp(500);
					});
				}
				
			},
			error: function(result) {
			}
		});
	});
	$('#changeAvata').bind("change", function(){
		$('#loading').css('visibility', 'visible');
		var files = $("#changeAvata").prop("files")[0];  
		$(".addMediaMessage" ).append( '<div class="progress" id="progress-upload"><div class="progress-bar" role="progressbar"></div></div>' );
		var formData = new FormData();
		formData.append("file", files); 
		$.ajax({
			url: "{{route('channel.upload.file',$channel['domain']->domain)}}",
			type: 'post', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			xhr: function() {
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) myXhr.upload.addEventListener('progress',progressHandlingFunctionAddMedia, false);
				return myXhr;
			},
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				console.log(result); 
				var formDataChannel = new FormData();
				formDataChannel.append("mediaId", result.id); 
				$.ajax({
					url: "{{route('profile.logo.change',$channel['domain']->domain)}}",
					type: 'post', 
					headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
					cache: false,
					contentType: false,
					processData: false,
					data: formDataChannel,
					dataType:'json',
					success:function(resultMedia){
						$('#loading').css('visibility', 'hidden');
						$("#logoChannel").attr('src', result.url);
					},
					error: function(resultMedia) {
					}
				});
			},
			error: function(result) {
			}
		});
		
	});
	$('#changeRegion').click(function () {
		$('#loading').css('visibility', 'visible');
		$('.message').empty(); 
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
        $.ajax({
            url: "{{route('regions.json.list',$channel['domain']->domain)}}",
            type: "GET",
            dataType: "json",
            success: function (result) {
				$('#loading').css('visibility', 'hidden');
				$('#myModal .modal-title').text(result.message); 
				$('#myModal .modal-body').append('<div class="row addContentRegion"></div>'); 
				$.each(result.region, function(i, item) {
					$('#myModal .modal-body .addContentRegion').append('<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><div class="form-group"><button class="btn btn-xs checkRegion" style="white-space: inherit; background: none; text-align:left; " type="button" name="checkRegion" value="'+item.country+'" data-id="'+item.id+'" data-flagiso="'+item.iso.toLowerCase()+'" id="checkRegion"> <i class="flag flag-16 flag-'+item.iso.toLowerCase()+'"></i> '+item.country+'</button></div></div>');
				})
				$('#myModal').modal('show');
            }
        });
    });
	$('#changeSubregion').click(function () {
		$('#loading').css('visibility', 'visible');
		$('.message').empty(); 
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		var idRegion=$('#region').val(); 
		$.ajax({
            url: "{{route('channel.home',$channel['domain']->domain)}}/regions/json/subregion/list/"+idRegion,
            type: "GET",
            dataType: "json",
            success: function (result) {
				$('#loading').css('visibility', 'hidden');
				$('#myModal .modal-title').text(result.message); 
				$('#myModal .modal-body').append('<div class="row addContentRegion"></div>'); 
				$.each(result.subregion, function(i, item) {
					$('#myModal .modal-body .addContentRegion').append('<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><div class="form-group"><button class="btn btn-xs checkSubRegion" style="white-space: inherit; background: none; text-align:left; " type="button" name="checkSubRegion" value="'+item.subregions_name+'" data-id="'+item.id+'" id="checkSubRegion"><i class="glyphicon glyphicon-map-marker"></i> '+item.subregions_name+'</button></div></div>');
				})
				$('#myModal').modal('show');
            }
        });
	});
	$('#myModal').on("click",".checkRegion",function() {
		var regionName=$(this).val(); 
		var regionId=$(this).attr("data-id");
		var flagIso=$(this).attr("data-flagiso");
		$('#region').val(regionId); 
		$('#channelRegionName').text(regionName);
		$('#flagIso').html('<i class="flag flag-16 flag-'+flagIso+'"></i>');
		$('#myModal').modal('hide');
	});
	$('#myModal').on("click",".checkSubRegion",function() {
		var subRegionName=$(this).val(); 
		var subRegionId=$(this).attr("data-id");
		var flagIso=$(this).attr("data-flagiso");
		$('#subRegion').val(subRegionId); 
		$('#subRegionName').html('<i class="glyphicon glyphicon-map-marker"></i> '+subRegionName);
		$('#myModal').modal('hide');
	});
	function progressHandlingFunctionAddMedia(e){
		var progress_bar_id         = '#progress-upload';
		var percent = 0;
		var position = e.loaded || e.position;
		var total = e.total; 
		if(e.lengthComputable){
			percent = Math.ceil(position / total * 100);
			$(progress_bar_id +" .progress-bar").css("width", + percent +"%"); 
			$(progress_bar_id +" .progress-bar").text(percent +"%"); 
			if (e.loaded == e.total) {
				$(progress_bar_id).hide(); 
				$(".addMediaMessage" ).append('<button class="btn btn-success btn-xs" id="progress-after" style="margin-bottom:5px; "><i class="fa fa-spinner fa-spin"></i> Đang xử lý vui lòng chờ trong giây lát! </button>'); 
			}
		}
	}
</script>
@include('themes.admin.inc.footer')