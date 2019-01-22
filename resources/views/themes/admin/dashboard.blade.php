<?
	$theme->setTitle('Bảng điều khiển');
?>
@include('themes.admin.inc.header')
<div class="section">
	<div class="container">
		@if(Session::has('message_success'))
			<div class="alert alert-success alert-dismissible fade in" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
				{{ Session::get('message_success') }}
			</div>
		@elseif(Session::has('message_danger'))
			<div class="alert alert-danger alert-dismissible fade in" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
				{{ Session::get('message_danger') }}
			</div>
		@endif
		<div class="message"></div>
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				@include('themes.admin.partial.menuManageService')
			</div>
			<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-lg-6 text-center">
						<div class="form-group">
							<a class="btn btn-success btn-block" rel="nofollow" href="{{route('channel.post.add',$channel['domain']->domain)}}"><span><i class="glyphicon glyphicon-edit"></i> Đăng bài</span></a>
						</div>
					</div>
					<div class="col-xs-6 col-sm-6 col-lg-6 text-center">
						<div class="form-group">
							<a class="btn btn-primary btn-block" rel="nofollow" href="{{route('channel.admin.setting',$channel['domain']->domain)}}"><span><i class="glyphicon glyphicon-cog"></i> Cài đặt chung</span></a>
						</div>
					</div>
					<div class="col-xs-6 col-sm-6 col-lg-6 text-center">
						<div class="form-group">
							<a class="btn btn-primary btn-block" rel="nofollow" href="{{route('channel.admin.contact',$channel['domain']->domain)}}"><span><i class="glyphicon glyphicon-envelope"></i> Cài đặt liên hệ</span></a>
						</div>
					</div>
					<div class="col-xs-6 col-sm-6 col-lg-6 text-center">
						<div class="form-group">
							<a class="btn btn-primary btn-block" rel="nofollow" href="{{route('channel.admin.theme',$channel['domain']->domain)}}"><span><i class="glyphicon glyphicon-picture"></i> Cài đặt giao diện</span></a>
						</div>
					</div>
				</div>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title"><i class="glyphicon glyphicon-info-sign"></i> Thông tin</h4>
					</div>
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
							@if(!empty($channel['info']->channelService->name))
								@if($channel['info']->service_attribute_id==1)
									<div class="panel-heading">
										<h4><i class="glyphicon glyphicon-briefcase"></i> {{$channel['info']->channel_name}}</h4>
										<p>Địa chỉ: <a href="//{{$channel['domain']->domain}}">http://{{$channel['domain']->domain}}</a></p>
										<p><i class="glyphicon glyphicon-time"></i> Ngày đăng ký: {!!Site::Date($channel['info']->channel_created_at)!!}</p> 
										<p><strong>Đang sử dụng: {{$channel['info']->channelService->name}}</strong>  
											<button type="button" class="btn btn-xs btn-primary" id="btnChannelUpgrade"><i class="glyphicon glyphicon-open"></i> Nâng cấp</button>
											@role(['admin','manage'])
												<button type="button" class="btn btn-xs btn-default channelEdit"><i class="glyphicon glyphicon-edit"></i> Sửa</button>
											@endrole
										</p>
									</div>
								@else
									<div class="panel-heading">
										<h4><i class="glyphicon glyphicon-briefcase"></i> {{$channel['info']->channel_name}}</h4>
										<p>Địa chỉ: <a href="//{{$channel['domain']->domain}}">http://{{$channel['domain']->domain}}</a></p>
										<p><i class="glyphicon glyphicon-time"></i> Ngày đăng ký: {!!Site::Date($channel['info']->channel_created_at)!!}</p> 
										<p class="text-danger"><i class="glyphicon glyphicon-time"></i> Hạn sử dụng đến: {!!Site::Date($channel['info']->channel_date_end)!!}</p>
										<p><strong><span><i class="glyphicon glyphicon-usd"></i> {!!Site::price($channel['info']->channelService->price_re_order)!!}</span></strong><sup>{{$channel['info']->channelJoinRegion->region->currency_code}}</sup>/ {{$channel['info']->channelService->order_unit_month}} {{$channel['info']->channelService->per}}</p>
										<p><strong>Đang sử dụng: {{$channel['info']->channelService->name}}</strong></p> 
										@if($channel['info']->channel_date_end < \Carbon\Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')) 
											<button type="button" class="btn btn-xs btn-primary" id="btnChannelReOrder"><i class="glyphicon glyphicon-repeat"></i> Gia hạn</button>
										@endif
										@role(['admin','manage'])
											<button type="button" class="btn btn-xs btn-default channelEdit"><i class="glyphicon glyphicon-edit"></i> Sửa</button>
										@endrole
									</div>
								@endif
							@endif
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="panel-heading">
								<h4 class="panel-title"><i class="glyphicon glyphicon-stats"></i> Thống kê lượt xem</h4>
							</div>
							<div class="panel-body">
								<div class="text-center"><strong class="text-primary" style="font-size:36px;">{!!Site::price($channel['info']->channel_view)!!}</strong></div>
							</div>
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
	$('.channelEdit').click(function(){
		getInfoChannel(); 
	}); 
	function getInfoChannel(){
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$('#myModal .modal-title').html('<i class="glyphicon glyphicon-briefcase"></i> Thông tin kênh'); 
		$('#myModal .modal-body').append(''
			+'<div class="form-group">'
				+'<label for="date_begin" class="control-label">Ngày đăng ký</label>'
				+'<div class="input-group">'
					+'<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-calendar"></i></span>'
					+'<input placeholder="{!!\Carbon\Carbon::parse($channel['info']->channel_created_at)->format('d-m-Y')!!}" id="date_begin" name="date_begin" value="{!!\Carbon\Carbon::parse($channel['info']->channel_created_at)->format('d-m-Y')!!}" type="text" data-date-format="dd-mm-yyyy" class="form-control datepicker">'
				+'</div>'
			+'</div>'
			+'<div class="form-group">'
				+'<label for="date_end" class="control-label">Ngày Hết hạn</label>'
				+'<div class="input-group">'
					+'<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-calendar"></i></span>'
					+'<input placeholder="{!!\Carbon\Carbon::parse($channel['info']->channel_date_end)->format('d-m-Y')!!}" id="date_end" name="date_end" value="{!!\Carbon\Carbon::parse($channel['info']->channel_date_end)->format('d-m-Y')!!}"" type="text" data-date-format="dd-mm-yyyy" class="form-control datepicker">'
				+'</div>'
			+'</div>'
			+'<div class="form-group">'
				+'<label for="channelPackge" class="control-label">Gói tùy chọn</label>'
				+'<div class="input-group">'
					+'<div class="checkbox text-primary">'
						+'<label><input type="radio" name="channelPackge" value="{{$channel['info']->service_attribute_id}}" checked disabled> Đang sử dụng</label>'
					+'</div>'
					+'<div class="checkbox text-primary">'
						+'<label><input type="radio" name="channelPackge" value="3" > Cao cấp</label>'
					+'</div>'
					+'<div class="checkbox text-primary">'
						+'<label><input type="radio" name="channelPackge" value="1" > Miễn phí</label>'
					+'</div>'
				+'</div>'
			+'</div>'
		+''); 
		$('#myModal .modal-footer').append('<div class="text-right"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button> <button type="button" class="btn btn-primary" id="btnSaveChangeChannel"><i class="glyphicon glyphicon-ok"></i> Lưu</button></div>'); 
		$('#myModal').modal('show'); 
	}
	$('#myModal').on("click","#btnSaveChangeChannel",function() {
		var formData = new FormData();
		formData.append("date_begin", $('#myModal input[name=date_begin]').val()); 
		formData.append("date_end", $('#myModal input[name=date_end]').val()); 
		formData.append("channelPackge", $('#myModal input[name=channelPackge]:checked').val()); 
		$.ajax({
			url: "{{route('channel.update.id',$channel['domain']->domain)}}",
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
	$('#btnChannelUpgrade').click(function(){
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$('#myModal .modal-title').html('<i class="glyphicon glyphicon-open"></i> Nâng cấp gói'); 
		$('#myModal .modal-body').append(''
			+'<div class="list-group">'
				+'<li class="list-group-item list-group-item-info"><i class="glyphicon glyphicon-ok"></i> Không quảng cáo</li>'
				+'<li class="list-group-item list-group-item-info"><i class="glyphicon glyphicon-ok"></i> Tích hợp tên miền riêng</li>'
				+'<li class="list-group-item list-group-item-info"><i class="glyphicon glyphicon-ok"></i> Hổ trợ 24/7</li>'
			+'</div>'
		+''); 
		$('#myModal .modal-footer').append('<div class="text-right"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button> <button type="button" class="btn btn-primary" id="btnOrder"><i class="glyphicon glyphicon-ok"></i> Nâng cấp</button></div>'); 
		$('#myModal').modal('show'); 
	}); 
	$('#btnChannelReOrder').click(function(){
		$('.message').empty(); 
		var formData = new FormData();
		formData.append("cartType", 'channel'); 
		formData.append("cartName", {{$channel['info']->id}}); 
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
				if(result.success==true){
					$('#myModal').modal('hide'); 
					window.location.href="{{route('cart.show',$channel['domain']->domain)}}"; 
				}else if(result.success==false){
					$('.message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					$('#myModal').modal('hide'); 
				}
            }
        });
	}); 
	$('#myModal').on("click","#btnOrder",function() {
		$('.message').empty(); 
		var formData = new FormData();
		formData.append("cartType", 'channel'); 
		formData.append("cartName", {{$channel['info']->id}}); 
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
				if(result.success==true){
					$('#myModal').modal('hide'); 
					window.location.href="{{route('cart.show',$channel['domain']->domain)}}"; 
				}else if(result.success==false){
					$('.message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					$('#myModal').modal('hide'); 
				}
            }
        });
	});
	$('#delOrder').click(function(){
		var orderId=$(this).attr('data-id'); 
		var formData = new FormData();
		formData.append("orderId", orderId); 
        $.ajax({
            url: "{{route('channel.cart.order.delete',$channel['domain']->domain)}}",
            type: "POST",
            cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
            success: function (result) {
				$('.message').append('<div class="alert alert-success alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				$(".message").fadeTo(2000, 500).slideUp(500, function(){
					$(".message").slideUp(500);
					location.reload(); 
				});
            }
        });
	}); 
	$('#paymentNL').click(function(){
		$('#loading').css('visibility', 'visible');
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$('#myModal .modal-title').text('Thông tin thanh toán'); 
		var orderId=$(this).attr('data-order-id'); 
		var amount=$(this).attr('data-amount'); 
		var unit=$(this).attr('data-order-unit'); 
		var orderName=$(this).attr('data-order-name'); 
		$('#myModal .modal-body').append('<input type="hidden" name="orderId" value="'+orderId+'">'
			+'<div class="messagePayment"></div>'
			+'<div class="form-group">'
				+'<p><strong><i class="glyphicon glyphicon-check"></i> Thanh toán:</strong> '+orderName+'</p>'
				+'<p><strong><i class="glyphicon glyphicon-user"></i> Người thanh toán:</strong> {{Auth::user()->name}}</p>'
				+'<p><strong><i class="glyphicon glyphicon-envelope"></i> Email:</strong> {{Auth::user()->joinEmail[0]->email->email_address}}</p>'
				@if(!empty(Auth::user()->joinPhone[0]->phone->phone_number)) +'<p><strong><i class="glyphicon glyphicon-envelope"></i> Điện thoại:</strong> {{Auth::user()->joinPhone[0]->phone->phone_number}}</p>' @else +'<p class="text-danger"><i class="glyphicon glyphicon-earphone"></i> Vui lòng cập nhật số điện thoại để thanh toán <a href="{{route("channel.profile.info",$channel["domain"]->domain)}}"><i class="glyphicon glyphicon-edit"></i> Cập nhật</a></p>' @endif
				+'<p><strong><i class="glyphicon glyphicon-map-marker"></i> Địa chỉ:</strong> {{$channel["info"]->channelJoinSubRegion->subregion->subregions_name}}, {{$channel["info"]->joinAddress[0]->address->joinRegion->region->country}}</p>'
			+'</div>'
			+'<div class="form-group boxPrice">'
				+'<p class="text-center">Số tiền thanh toán</p>'
				+'<span class="priceTotal"><span class="amountNumber" data-amount="'+amount+'">'+(amount/1000).toFixed(3) +'</span><sup>{{$channel["info"]->joinAddress[0]->address->joinRegion->region->currency_code}}</sup> <select name="quanlity" class="quanlity" data-amount="'+amount+'" style="color:#333; font-size:20px; "><option value="1">'+unit+' tháng</option><option value="2">'+unit*2+' tháng</option><option value="4">'+unit*4+' tháng</option><option value="6">'+unit*6+' tháng</option><option value="8">'+unit*8+' tháng</option></select></span>'
			+'</div>'
			+'<div class="form-group">'
				+'<label class="control-label" for="channelName">Nhập mã khuyến mãi</label>'
				+'<div class="input-group">'
					+'<span class="input-group-addon hidden-xs"><i class="glyphicon glyphicon-piggy-bank"></i></span>'
					+'<input class="form-control" name="voucherCode" placeholder="Nhập mã giảm giá" >'
					+'<span class="input-group-addon"><button type="button" class="btn btn-xs btn-primary" style="padding:0px 5px; " id="btnCheckVoucher"><i class="glyphicon glyphicon-refresh"></i> Áp dụng</button></span>'
				+'</div>'
			+'</div>'
			+'<div class="form-group">'
				+'<div class="message-voucher"></div>'
			+'</div>'
			+'<div class="panel-group" id="accordion">'
				+'<div class="panel panel-primary">'
					+'<div class="panel-heading">'
						+'<h4 class="panel-title">'
						+'<a data-toggle="collapse" data-parent="#accordion" href="#collapse1" class="openCollapse"><i class="glyphicon glyphicon-credit-card"></i> Bằng Internet Banking <i class="glyphicon glyphicon-chevron-down"></i></a>'
						+'</h4>'
					+'</div>'
					+'<div id="collapse1" class="panel-collapse collapse in">'
						+'<div class="panel-body">'
							+'<div class="row">'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-vietcombank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="IB_ONLINE" value="VCB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-donga.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="IB_ONLINE" value="DAB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-bidv.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="IB_ONLINE" value="BIDV" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-techcombank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="IB_ONLINE" value="TCB" class="hidden" autocomplete="off"></label>'
								+'</div>'
							+'</div>'
						+'</div>'
					+'</div>'
				+'</div>'
				+'<div class="panel panel-info">'
					+'<div class="panel-heading">'
						+'<h4 class="panel-title">'
						+'<a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="openCollapse"><i class="glyphicon glyphicon-credit-card"></i> Bằng thẻ Ngân hàng nội địa <i class="glyphicon glyphicon-chevron-down"></i></a>'
						+'</h4>'
					+'</div>'
					+'<div id="collapse2" class="panel-collapse collapse">'
						+'<div class="panel-body">'
							+'<div class="row">'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-vietcombank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="VCB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-donga.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="DAB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-bidv.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="BIDV" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-techcombank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="TCB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-mb.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="MB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-vib.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="VIB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-viettinbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="ICB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-eximbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="EXB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-acb.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="ACB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-hdbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="HDB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-maritime.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="MSB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-ncb.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="NVB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-vieta.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="VAB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-vpbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="VPB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-sacombank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="SCB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-pgbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="PGB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-gpbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="GPB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-agribank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="AGB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-saigonbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="SGB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-bacabank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="BAB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-tpbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="TPB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-namabank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="NAB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-shb.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="SHB" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-oceanbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="OJB" class="hidden" autocomplete="off"></label>'
								+'</div>'
							+'</div>'
						+'</div>'
					+'</div>'
				+'</div>'
				+'<div class="panel panel-info">'
					+'<div class="panel-heading">'
						+'<h4 class="panel-title">'
						+'<a data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="openCollapse"><i class="glyphicon glyphicon-credit-card"></i> Bằng Visa/ Master Card <i class="glyphicon glyphicon-chevron-down"></i></a>'
						+'</h4>'
					+'</div>'
					+'<div id="collapse3" class="panel-collapse collapse">'
						+'<div class="panel-body">'
							+'<div class="row">'
								+'<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/visa.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="VISA" value="VISA" class="hidden" autocomplete="off"></label>'
								+'</div>'
								+'<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group text-center">'
									+'<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/mastercard.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="VISA" value="MASTER" class="hidden" autocomplete="off"></label>'
								+'</div>'
							+'</div>'
						+'</div>'
					+'</div>'
				+'</div>'
			+'</div>'
		+''); 
		$('#myModal').on("change",".quanlity",function() {
			var quanlity=$(this).val(); 
			var amount=$(this).attr('data-amount'); 
			var totalAmount=parseInt(amount*quanlity); 
			$( "#myModal .amountNumber").text(totalAmount.toLocaleString()); 
			$( "#myModal .amountNumber").attr("data-amount", totalAmount);
		});
		$('#myModal .modal-footer').append('<div class="text-right"><button type="button" class="btn btn-primary" id="btnPayment"><i class="glyphicon glyphicon-ok"></i> Thanh toán</button></div>'); 
		$('#myModal').modal('show'); 
		$('#loading').css('visibility', 'hidden');
	}); 
	$('#myModal').on("click","#btnPayment",function() {
		$('#myModal .messagePayment').empty(); 
		var orderId=$( "#myModal input[name=orderId]" ).val();
		var bankcode = $( "#myModal input[name=bankcode]:checked" ).val(); 
		var payment_method = $( "#myModal input[name=bankcode]:checked" ).attr('payment_method'); 
		var voucherCode=$('#myModal input[name=voucherCode]').val(); 
		var quanlity=$('#myModal select[name=quanlity]').val(); 
		if(typeof(quanlity) != "undefined" && quanlity !== null) {
			quanlityCheck=quanlity; 
		}else{
			quanlityCheck='';
		}
		if(typeof(bankcode) != "undefined" && bankcode !== null) {
			bankcodeCheck=bankcode; 
		}else{
			bankcodeCheck='';
		}
		if(typeof(payment_method) != "undefined" && payment_method !== null) {
			payment_methodCheck=payment_method; 
		}else{
			payment_methodCheck='';
		}
		var formData = new FormData();
		formData.append("orderId", orderId); 
		formData.append("bankcode", bankcodeCheck); 
		formData.append("payment_method", payment_methodCheck); 
		formData.append("cancel_url", "{{Request::url()}}"); 
		formData.append("voucherCode", voucherCode); 
		formData.append("quanlity", quanlityCheck); 
		$.ajax({
			url: "{{route('channel.payment.request',$channel['domain']->domain)}}",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				$('#loading').css('visibility', 'hidden');
				if(result.success==true){
					window.location.href = result.checkout_url;
				}else if(result.success==false){
					$('#myModal .messagePayment').append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					 $('#myModal').animate({
					scrollTop: $('#myModal .messagePayment').offset().top},
					'slow');
				}
			},
			error: function(result) {
			}
		});
	});
	$('#myModal').on("click","#btnCheckVoucher",function() {
		$('#myModal .message-voucher').empty(); 
		var voucherCode= $('#myModal input[name=voucherCode]').val(); 
		var formData = new FormData();
		formData.append("voucherCode", voucherCode); 
		$.ajax({
			url: "{{route('voucher.check',$channel['domain']->domain)}}",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				//console.log(result); 
				if(result.success==true){
					$('#myModal .message-voucher').append('<div class="alert alert-success" id="alertMessage"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
						+'<div class="form-group">' 
							+'<p><strong>Tìm thấy '+result.voucher_about+'</strong></p>'
							+'<p><strong><i class="glyphicon glyphicon-piggy-bank"></i> Giảm:</strong> '+result.discount+'%</p>'
							+'<p><strong><i class="glyphicon glyphicon-time"></i> Hạn sử dụng:</strong> '+result.voucher_date_end+'</p>'
							+'<p><strong><i class="glyphicon glyphicon-user"></i> Người hổ trợ:</strong> '+result.author+'</p>'
							+'<p><strong><i class="glyphicon glyphicon-envelope"></i> Email:</strong> '+result.author_email+'</p>'
							+'<p><strong><i class="glyphicon glyphicon-earphone"></i> Điện thoại:</strong> '+result.author_phone+'</p>'
						+'</div>'
					+'</div>');
					$('#myModal input[name=voucherCode], #myModal #btnCheckVoucher, #myModal select[name=quanlity]').disable(true);
					var amountChange=$( "#myModal .amountNumber").attr('data-amount'); 
					var newPrice=amountChange*(result.discount/100); 
					$( "#myModal .amountNumber").empty(); 
					$( "#myModal .amountNumber").html(''
					+'<span class="">'
						+'<span class="oldPrice">Giá cũ: '+amountChange.toLocaleString()+'</span>'
						+'<span class="newPrice"><span class="" style="font-size:16px; font-weight:normal; ">Giảm còn:</span> '+newPrice.toLocaleString()+'</span>'
					+'</span>'); 
				}else if(result.success==false){
					$('#myModal .message-voucher').append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				}
			},
			error: function(result) {
			}
		});
	});
	jQuery.fn.extend({
		disable: function(state) {
			return this.each(function() {
				this.disabled = state;
			});
		}
	});
	$('#myModal').on("click",".bankCheck",function() {
		//$(this).parent().toggleClass("btn-primary"); 
		$('.bankCheck').not(this).parent().removeClass('btn-primary')
    		.siblings('input').prop('checked',false)
            .siblings('.bankCheck').css('opacity','0.5');
    	$(this).parent().addClass('btn-primary')
            .siblings('input').prop('checked',true)
    		.siblings('.bankCheck').css('opacity','1');
	});
</script>
@include('themes.admin.inc.footer')