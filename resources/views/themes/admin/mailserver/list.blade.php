<?
	$channel['theme']->setTitle('Quản lý Email Server');
?>
@include('themes.admin.inc.header')
<link rel="stylesheet" href="{{asset('assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
<script src="{{asset('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/checkbox.css')}}">
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				@include('themes.admin.partial.menuManageService')
			</div>
			<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
				<div class="row">
					<div class="panel panel-primary">
						<div class="panel-heading"><h3 class="panel-title"><i class="glyphicon glyphicon-hdd"></i> Danh sách đang sử dụng</h3></div>
						<div class="panel-body">
							@role(['admin','manage'])
								<div class="form-group">
									<button type="button" class="btn btn-xs btn-default addNewmail_serverOutsite"><i class="glyphicon glyphicon-plus"></i> Thêm mới Email Server bên ngoài</button>
								</div>
							@endrole
							@if(count($mail_serverJoin)>0)
								<ul class="list-group listmail_server">
									@foreach($mail_serverJoin as $mail_server)
									<li class="list-group-item @if($mail_server->mail_server->date_end < \Carbon\Carbon::now()->format('Y-m-d H:i:s')) list-group-item-danger @elseif($mail_server->mail_server->date_end < \Carbon\Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')) list-group-item-warning @elseif($mail_server->mail_server->date_end > \Carbon\Carbon::now()->format('Y-m-d H:i:s')) list-group-item-success @endif dropdown">
									@if($mail_server->mail_server->date_end < \Carbon\Carbon::now()->format('Y-m-d H:i:s')) <span class="pull-right"><i class="glyphicon glyphicon-ok"></i> Đã hết hạn</span>@elseif($mail_server->mail_server->date_end < \Carbon\Carbon::now()->addMonth(1)->format('Y-m-d H:i:s'))<span class="pull-right"><i class="glyphicon glyphicon-ok"></i> Sắp hết hạn</span> @elseif($mail_server->mail_server->date_end > \Carbon\Carbon::now()->format('Y-m-d H:i:s')) <span class="pull-right"><i class="glyphicon glyphicon-ok"></i> Đang sử dụng</span> @endif
										<h4 class="list-group-item-heading"><small>{!!$mail_server->mail_server->serviceAttribute->name!!}</small> {{$mail_server->mail_server->name}}</h4>
										<div class="list-group-item-text">
											<p><span class=""><i class="glyphicon glyphicon-time"></i> Ngày đăng ký: {!!Site::Date($mail_server->mail_server->date_begin)!!}</span></p>
											<p><span class="text-danger"><i class="glyphicon glyphicon-time"></i> Ngày hết hạn: {!!Site::Date($mail_server->mail_server->date_end)!!}</span></p>
											<p>
												<button type="button" class="btn btn-xs btn-primary reOrder" mail_server-id="{{$mail_server->mail_server->id}}"><i class="glyphicon glyphicon-repeat"></i> Gia hạn</button> 
												<button type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-cog"></i> Cài đặt</button>
												<button type="button" class="btn btn-xs btn-danger deletemail_server" data-id="{{$mail_server->mail_server->id}}"><i class="glyphicon glyphicon-remove"></i> Xóa</button>
												@role(['admin','manage'])
													<button type="button" class="btn btn-xs btn-default mail_serverEdit" data-id="{{$mail_server->mail_server->id}}"><i class="glyphicon glyphicon-edit"></i> Sửa</button>
												@endrole
											</p>
										</div>
									</li>
									@endforeach
								</ul>
							@else
								<div class="alert alert-warning">
									Bạn chưa có Email Server nào được đăng ký!
								</div>
							@endif
						</div>
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
	$('.mail_serverEdit').click(function(){
		var mail_serverId=$(this).attr('data-id'); 
		getInfomail_server(mail_serverId); 
	}); 
	function getInfomail_server(mail_serverId){
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		var formData = new FormData();
		formData.append("mail_serverId", mail_serverId); 
		$.ajax({
			url: "{{route('mail_server.get.id',$channel['domain']->domain)}}",
			type: "POST",
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			success: function (result) {
				console.log(result); 
				if(result.success==true){
					$('#myModal .modal-title').html('<i class="glyphicon glyphicon-globe"></i> '+result.getMail_server.name); 
					$('#myModal .modal-body').append(''
						+'<div class="form-group">'
							+'<label for="date_begin" class="control-label">Ngày đăng ký</label>'
							+'<div class="input-group">'
								+'<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-calendar"></i></span>'
								+'<input placeholder="'+result.date_begin+'" id="date_begin" name="date_begin" value="'+result.date_begin+'" type="text" data-date-format="dd-mm-yyyy" class="form-control datepicker">'
							+'</div>'
						+'</div>'
						+'<div class="form-group">'
							+'<label for="date_end" class="control-label">Ngày Hết hạn</label>'
							+'<div class="input-group">'
								+'<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-calendar"></i></span>'
								+'<input placeholder="'+result.date_end+'" id="date_end" name="date_end" value="'+result.date_end+'" type="text" data-date-format="dd-mm-yyyy" class="form-control datepicker">'
							+'</div>'
						+'</div>'
						+'<div class="form-group">'
							+'<label for="mail_serverStatus" class="control-label">Tình trạng</label>'
							+'<div class="input-group">'
								+'<div class="checkbox text-primary">'
									+'<label><input type="radio" name="mail_serverStatus" value="'+result.getMail_server.status+'" checked disabled> '+result.getMail_server.status+'</label>'
								+'</div>'
								+'<div class="checkbox text-primary">'
									+'<label><input type="radio" name="mail_serverStatus" value="active" > Active</label>'
								+'</div>'
								+'<div class="checkbox text-primary">'
									+'<label><input type="radio" name="mail_serverStatus" value="delete" > Delete</label>'
								+'</div>'
							+'</div>'
						+'</div>'
					+''); 
					$('#myModal .modal-footer').append('<div class="form-group"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button> <button type="button" class="btn btn-primary" id="saveChangemail_serverInfo" data-id="'+result.getMail_server.id+'"><i class="glyphicon glyphicon-ok"></i> Lưu</button></div>'); 
					$('#myModal .datepicker').datepicker();
					$('#myModal').modal('show'); 
				}
			}
		});
	}
	$('#myModal').on("click","#saveChangemail_serverInfo",function() {
		var formData = new FormData();
		formData.append("mail_serverId", $(this).attr('data-id')); 
		formData.append("date_begin", $('#myModal input[name=date_begin]').val()); 
		formData.append("date_end", $('#myModal input[name=date_end]').val()); 
		formData.append("mail_serverStatus", $('#myModal input[name=mail_serverStatus]:checked').val()); 
		$.ajax({
			url: "{{route('mail_server.save.id',$channel['domain']->domain)}}",
			type: "POST",
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			success: function (result) {
				console.log(result); 
				location.reload();
			}
		});
	}); 
	$('.deletemail_server').click(function(){
		if(confirm('Bạn có chắc muốn xóa?')){
			var formData = new FormData();
			formData.append("mail_serverId", $(this).attr('data-id')); 
			$.ajax({
				url: "{{route('mail_server.remove',$channel['domain']->domain)}}",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:'json',
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
				success: function (result) {
					console.log(result); 
					if(result.success==true){
						location.reload();
					} 
				}
			});
		}
	}); 
	$('.reOrder').click(function(){
		var formData = new FormData();
		formData.append("cartType", 'mail_serverReOrder'); 
		formData.append("cartName", $(this).attr('mail_server-id')); 
		$.ajax({
			url: "{{route('cart.add',$channel['domain']->domain)}}",
			type: "POST",
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			success: function (result) {
				console.log(result); 
				if(result.success==true){
					window.location.href="{{route('cart.show',$channel['domain']->domain)}}"; 
				}else{
					$('.domainMessage').append('<div class="form-group"><div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div></div>'); 
				}
			}
		});
	}); 
	$('.addNewmail_serverOutsite').click(function(){
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$('#myModal .modal-title').html('Thêm mới Email Server bên ngoài'); 
		$('#myModal .modal-body').append(''
			+'<div class="form-group">'
				+'<label for="domainAddress" class="control-label">Địa chỉ tên miền</label>'
				+'<div class="input-group">'
					+'<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-globe"></i></span>'
					+'<input placeholder="Nhập địa chỉ tên miền" id="domainAddress" name="domainAddress" value="" type="text" class="form-control">'
				+'</div>'
			+'</div>'
			+'<div class="form-group">'
				+'<label for="servicemail_server" class="control-label">Chọn dịch vụ Email Server</label>'
				+'<select class="form-control" id="servicemail_server" name="servicemail_server">'
					<?
						$services=\App\Model\Services::where('services_type','=','mail_server')->where('services_status','=','active')->get(); 
					?>
						+'<option value="0">--Chọn dịch vụ--</option>'
					@foreach($services as $service)
						+'<option class="" value="{!!$service->id!!}">{!!$service->services_name!!}</option>'
					@endforeach
				+'</select>'
			+'</div>'
			+'<div class="insertServiceAttribute"></div>'
			+'<div class="form-group">'
				+'<label for="date_begin" class="control-label">Ngày đăng ký</label>'
				+'<div class="input-group">'
					+'<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-calendar"></i></span>'
					+'<input placeholder="dd-mm-yyyy" id="date_begin" name="date_begin" value="" type="text" data-date-format="dd-mm-yyyy" class="form-control datepicker">'
				+'</div>'
			+'</div>'
			+'<div class="form-group">'
				+'<label for="date_end" class="control-label">Ngày Hết hạn</label>'
				+'<div class="input-group">'
					+'<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-calendar"></i></span>'
					+'<input placeholder="dd-mm-yyyy" id="date_end" name="date_end" value="" type="text" data-date-format="dd-mm-yyyy" class="form-control datepicker">'
				+'</div>'
			+'</div>'
		+''); 
		$('#myModal .modal-footer').append('<div class="form-group"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button> <button type="button" class="btn btn-primary" id="saveNewmail_serverOutsite" ><i class="glyphicon glyphicon-ok"></i> Lưu</button></div>'); 
		$('#myModal .datepicker').datepicker();
		$('#myModal').modal('show'); 
	}); 
	$('#myModal').on('click',"#saveNewmail_serverOutsite", function() {
		var formData = new FormData();
		formData.append("mail_serverName", $('#myModal input[name=domainAddress]').val()); 
		formData.append("serviceAttributeId", $('#myModal input[name=serviceAttribute]:checked').val()); 
		formData.append("date_begin", $('#myModal input[name=date_begin]').val()); 
		formData.append("date_end", $('#myModal input[name=date_end]').val()); 
		$.ajax({
			url: "{{route('mail_server.add',$channel['domain']->domain)}}",
			type: "POST",
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			success: function (result) {
				console.log(result); 
				if(result.success==true){
					location.reload();
				} 
			}
		});
	});
	$('#myModal').on('change',"#servicemail_server", function() {
		$('#myModal .insertServiceAttribute').empty(); 
		var formData = new FormData();
		formData.append("serviceId", $(this).val()); 
		$.ajax({
			url: "{{route('service.get.attribute.id',$channel['domain']->domain)}}",
			type: "POST",
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			success: function (result) {
				console.log(result); 
				if(result.success==true){
					$.each(result.serviceAttributeAll, function(i, item) {
						$('#myModal .insertServiceAttribute').append(''
							+'<div class="checkbox checkbox-success">'
								+'<input type="checkbox" class="filled-in" name="serviceAttribute" value="'+item.id+'" id="'+item.id+'"/>'
								+'<label for="'+item.id+'"> <strong>'+item.name+'</strong> <small class="text-danger">('+(parseInt(item.price_re_order)+parseInt(item.price_order)).toLocaleString()+'<sup>{{$channel['info']->channelJoinRegion->region->currency_code}}</sup>/'+item.order_unit_month+' '+item.per+')</small></label>'
							+'</div>'
						+'');
					});
				} 
			}
		});
	}); 
</script>
@include('themes.admin.inc.footer')