<?
	$channel['theme']->setTitle('Thanh toán');
?>
@include('themes.admin.inc.header') 
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">Thông tin thanh toán</div>
					</div>
					<div class="panel-body">
						<p><strong><i class="glyphicon glyphicon-user"></i> Người thanh toán:</strong> {{Auth::user()->name}}</p>
						<p><strong><i class="glyphicon glyphicon-envelope"></i> Email:</strong> {{Auth::user()->emailJoin->email->email_address}}</p>
						@if(!empty(Auth::user()->phoneJoin->phone->phone_number)) <p><strong><i class="glyphicon glyphicon-envelope"></i> Điện thoại:</strong> {{Auth::user()->phoneJoin->phone->phone_number}}</p> @else <p class="text-danger"><i class="glyphicon glyphicon-earphone"></i> Vui lòng cập nhật số điện thoại để thanh toán <a href="{{route('channel.profile.info',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-edit"></i> Cập nhật</a></p> @endif
						<p><strong><i class="glyphicon glyphicon-map-marker"></i> Địa chỉ:</strong> {{$channel['info']->channelJoinSubRegion->subregion->subregions_name}}, {{$channel['info']->channelJoinRegion->region->country}}</p>
					</div>
				</div>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
				<div class="panel panel-primary">
					<div class="message"></div>
					@if(!empty($cartOrder->id) && count($cartOrder->attribute)>0)
						<ul class="list-group listCart">
							<? $price=0;?>
							@foreach($cartOrder->attribute as $cart)
								<?
									$attribute=json_decode($cart->attribute_value); 
								?>
								@if($cart->attribute_type=='channel' || $cart->attribute_type=='domain' || $cart->attribute_type=='domainReOrder' || $cart->attribute_type=='hostingReOrder' || $cart->attribute_type=='cloudReOrder' || $cart->attribute_type=='mail_serverReOrder')
									<li class="list-group-item list-group-item-info cart_type_{{$cart->attribute_type}}">
										<a href="#" class="close btnDeleteCart" data-id="{{$cart->id}}" data-dismiss="alert" aria-label="close">&times;</a>
										<div class="form-group">
											<h4 class="list-group-item-heading">{{$attribute->name}}</h4>
										</div>
										<div class="itemCart">
											<i class="glyphicon glyphicon-usd"></i> Số tiền: <strong><span class="amountNumber" data-amount-change-order="{{$attribute->price_order}}" data-amount-change-re-order="{{$attribute->price_re_order}}" data-amount-change-total-old="{{$attribute->price_order+$attribute->price_re_order}}" data-amount-change-total="{{$attribute->price_order+$attribute->price_re_order}}" data-discount="0">{{Site::price($attribute->price_order+$attribute->price_re_order)}}</span><sup>{{$channel['info']->channelJoinRegion->region->currency_code}}</sup></strong>/ 
											<select name="quanlity[]" data-id="{{$cart->id}}" class="quanlity" data-amount-order="{{$attribute->price_order}}" data-amount-re-order="{{$attribute->price_re_order}}" data-amount-total-old="{{$attribute->price_order+$attribute->price_re_order}}" data-amount-total="{{$attribute->price_order+$attribute->price_re_order}}" data-discount="0">
												<option value="1">{{$attribute->unit}} {{$attribute->per}}</option>
												<option value="2">{{$attribute->unit*2}} {{$attribute->per}}</option>
												<option value="4">{{$attribute->unit*4}} {{$attribute->per}}</option>
												<option value="6">{{$attribute->unit*6}} {{$attribute->per}}</option>
												<option value="8">{{$attribute->unit*8}} {{$attribute->per}}</option>
											</select>
										</div>
									</li>
									<? $price+=$attribute->price_order+$attribute->price_re_order; ?>
								@elseif($cart->attribute_type=='voucher')
									<li class="list-group-item list-group-item-success">
										<a href="#" class="close btnDeleteCart" data-id="{{$cart->id}}" data-dismiss="alert" aria-label="close">&times;</a>
										<strong><i class="glyphicon glyphicon-gift"></i> Mã giảm giá</strong> <code>{{$attribute->voucher_code}}</code>
										<p>{{$attribute->description}}</p>
										<p><strong><i class="glyphicon glyphicon-piggy-bank"></i> Giảm:</strong> {{$attribute->discount}}%</p>
										<p><strong><i class="glyphicon glyphicon-time"></i> Hạn sử dụng:</strong> {!!Site::Date($attribute->date_end)!!}</p>
										<p><strong><i class="glyphicon glyphicon-user"></i> Hỗ trợ:</strong> {{$attribute->author}}</p>
										<p><strong><i class="glyphicon glyphicon-envelope"></i> Email:</strong> {{$attribute->author_email}}</p>
										<p><strong><i class="glyphicon glyphicon-earphone"></i> Điện thoại:</strong> {{$attribute->author_phone}}</p>
									</li>
								@endif
							@endforeach
						</ul>
						<div class="boxPrice text-center"><p class="">Tổng tiền thanh toán</p><span class="priceTotal"><span class="amountTotalNumber" data-amount="{{$price}}">{{Site::price($price)}}</span><sup>{{$channel['info']->channelJoinRegion->region->currency_code}}</sup></span></div>
						@foreach($cartOrder->attribute as $voucher)
							@if($voucher->attribute_type=='voucher')
								<?
									$attribute=json_decode($voucher->attribute_value); 
								?>
								<script>
									var amountChannel=parseInt($('.cart_type_{{$attribute->type}}').find('.amountNumber').attr('data-amount-change-total')); 
									var newPrice=amountChannel*({{$attribute->discount}}/100); 
									$('.cart_type_channel').find('.amountNumber').attr("data-amount-change-total", newPrice); 
									$('.cart_type_channel').find('.amountNumber').attr("data-discount", {{$attribute->discount}}); 
									$('.cart_type_channel').find('.quanlity').attr("data-discount", {{$attribute->discount}}); 
									var oldAmount=parseInt($('.amountTotalNumber').attr('data-amount')); 
									var totalAmount=0; 
									$.each($(".amountNumber"), function(i,item){ 
										totalAmount+= parseInt($(this).attr('data-amount-change-total'));  
									});
									$('.amountTotalNumber').empty(); 
									$( ".amountTotalNumber").html(''
										+'<span class="oldPrice">Giá cũ: <span class="amountTotalOld">'+oldAmount.toLocaleString()+'</span></span>'
										+'<span class="newPrice"><span class="" style="font-size:16px; font-weight:normal; ">Giảm còn:</span> <span class="amountTotalNew">'+totalAmount.toLocaleString()+'</span></span>'
									+''); 
									$('.amountTotalNumber').attr("data-amount",totalAmount); 
									$('.cart_type_channel').find('.amountNumber').empty(); 
									$( ".cart_type_channel .amountNumber").html(''
										+'<span class="oldPrice">Giá cũ: <span class="textOldPrice">'+amountChannel.toLocaleString()+'</span></span>'
										+'<span class="newPrice"><span class="" style="font-size:16px; font-weight:normal; ">Giảm còn:</span> <span class="textPrice">'+newPrice.toLocaleString()+'</span></span>'
									+''); 
								</script>
							@endif
						@endforeach
						<div class="panel-body">
							<div class="form-group">
								<label class="control-label" for="channelName">Nhập mã khuyến mãi</label>
								<div class="input-group">
									<span class="input-group-addon hidden-xs"><i class="glyphicon glyphicon-piggy-bank"></i></span>
									<input class="form-control" name="voucherCode" placeholder="Mã thẻ khuyến mãi" >
									<span class="input-group-addon"><button type="button" class="btn btn-xs btn-primary" style="padding:0px 5px; " id="btnCheckVoucher"><i class="glyphicon glyphicon-refresh"></i> Áp dụng</button></span>
								</div>
							</div>
							<div class="form-group">
								<div class="message-voucher"></div>
							</div>
						</div>
						<div class="panel-group listBank" id="accordion">
							<div class="panel panel-primary" style="border-radius:0; border:none; ">
								<div class="panel-heading" style="border-radius:0;">
									<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion" href="#collapse1" class="openCollapse"><i class="glyphicon glyphicon-credit-card"></i> Bằng Internet Banking <i class="glyphicon glyphicon-chevron-down"></i></a>
									</h4>
								</div>
								<div id="collapse1" class="panel-collapse collapse in">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-vietcombank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="IB_ONLINE" value="VCB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-donga.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="IB_ONLINE" value="DAB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-bidv.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="IB_ONLINE" value="BIDV" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-techcombank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="IB_ONLINE" value="TCB" class="hidden" autocomplete="off"></label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-info" style="border-radius:0; border:none; ">
								<div class="panel-heading" style="border-radius:0;">
									<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="openCollapse"><i class="glyphicon glyphicon-credit-card"></i> Bằng thẻ Ngân hàng nội địa <i class="glyphicon glyphicon-chevron-down"></i></a>
									</h4>
								</div>
								<div id="collapse2" class="panel-collapse collapse">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-vietcombank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="VCB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-donga.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="DAB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-bidv.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="BIDV" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-techcombank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="TCB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-mb.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="MB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-vib.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="VIB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-viettinbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="ICB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-eximbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="EXB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-acb.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="ACB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-hdbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="HDB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-maritime.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="MSB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-ncb.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="NVB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-vieta.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="VAB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-vpbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="VPB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-sacombank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="SCB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-pgbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="PGB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-gpbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="GPB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-agribank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="AGB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-saigonbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="SGB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-bacabank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="BAB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-tpbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="TPB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-namabank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="NAB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-shb.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="SHB" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-oceanbank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="ATM_ONLINE" value="OJB" class="hidden" autocomplete="off"></label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-info" style="border-radius:0; border:none; ">
								<div class="panel-heading" style="border-radius:0;">
									<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="openCollapse"><i class="glyphicon glyphicon-credit-card"></i> Bằng Visa/ Master Card <i class="glyphicon glyphicon-chevron-down"></i></a>
									</h4>
								</div>
								<div id="collapse3" class="panel-collapse collapse">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/visa.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="VISA" value="VISA" class="hidden" autocomplete="off"></label>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group text-center">
												<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/mastercard.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="VISA" value="MASTER" class="hidden" autocomplete="off"></label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="panel-footer">
							<div class="text-right"><button type="button" class="btn btn-primary" id="btnPayment"><i class="glyphicon glyphicon-ok"></i> Thanh toán</button></div>
						</div>
					@else
						<div class="panel-body">
							<div class="alert alert-warning">
								Bạn không có đơn hàng nào cần thanh toán! 
							</div>
						</div>
					@endif
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
	jQuery.fn.extend({
		disable: function(state) {
			return this.each(function() {
				this.disabled = state;
			});
		}
	});
	$('.listBank').on("click",".bankCheck",function() {
		$('.bankCheck').not(this).parent().removeClass('btn-primary')
    		.siblings('input').prop('checked',false)
            .siblings('.bankCheck').css('opacity','0.5');
    	$(this).parent().addClass('btn-primary')
            .siblings('input').prop('checked',true)
    		.siblings('.bankCheck').css('opacity','1');
	});
	$('#btnPayment').click(function() {
		$('.message').empty(); 
		$('#loading').css('visibility', 'visible');
		var bankcode = $( ".listBank input[name=bankcode]:checked" ).val(); 
		var payment_method = $( ".listBank input[name=bankcode]:checked" ).attr('payment_method'); 
		var dataQuanlityJson={};
		$.each($("select[name='quanlity[]']"), function(i,item){ 
			dataQuanlityJson[$(this).attr('data-id')] = item.value; 
		});
		var dataQuanlity=JSON.stringify(dataQuanlityJson); 
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
		formData.append("orderId", {{$cartOrder->id}}); 
		formData.append("bankcode", bankcodeCheck); 
		formData.append("payment_method", payment_methodCheck); 
		formData.append("cancel_url", "{{Request::url()}}"); 
		formData.append("quanlity", dataQuanlity); 
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
					$('.message').append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					$('html, body').animate({scrollTop: $(".message").offset().top}, 'slow');
				}
			},
			error: function(result) {
			}
		});
	});
	$( ".quanlity" ).change(function() {
		var quanlity=$(this).val(); 
		var amount=parseInt($(this).attr('data-amount-order')); 
		var amountReOrder=parseInt($(this).attr('data-amount-re-order')); 
		var discount=parseInt($(this).attr('data-discount')); 
		var amountQuanlityNodiscount=amount+(amountReOrder*quanlity); 
		var amountQuanlity=(amount+(amountReOrder*quanlity))-((amount+(amountReOrder*quanlity))*(discount/100)); 
		$(this).parent('.itemCart').find('.amountNumber').attr("data-amount-change-total-old", amountQuanlityNodiscount); 
		$(this).parent('.itemCart').find('.amountNumber').attr("data-amount-change-total", amountQuanlity); 
		var totalAmount=0; 
		var totalAmountDiscount=0; 
		$.each($(".amountNumber"), function(i,item){ 
			totalAmount+= parseInt($(this).attr('data-amount-change-total'));  
			totalAmountDiscount+= parseInt($(this).attr('data-amount-change-total'))+(parseInt($(this).attr('data-amount-change-total-old'))*(parseInt($(this).attr('data-discount'))/100));  
		});
		if ($(this).parent('.itemCart').find('.textPrice').length) {
			$(this).parent('.itemCart').find('.textOldPrice').text(amountQuanlityNodiscount.toLocaleString()); 
			$(this).parent('.itemCart').find('.textPrice').text(amountQuanlity.toLocaleString()); 
		}else{
			$(this).parent('.itemCart').find('.amountNumber').text(amountQuanlity.toLocaleString()); 
		}
		if($('.amountTotalNumber').find('.amountTotalNew').length){
			$('.amountTotalNumber').find('.amountTotalOld').text(totalAmountDiscount.toLocaleString()); 
			$('.amountTotalNumber').find('.amountTotalNew').text(totalAmount.toLocaleString()); 
			$('.amountTotalNumber').attr("data-amount",totalAmount); 
		}else{
			$('.amountTotalNumber').text(totalAmount.toLocaleString()); 
			$('.amountTotalNumber').attr("data-amount",totalAmount); 
		}
	});
	$('#btnCheckVoucher').click(function() {
		$('.message-voucher').empty(); 
		var voucherCode= $('input[name=voucherCode]').val(); 
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
				if(result.success==true){
					location.reload(); 
				}else if(result.success==false){
					$('.message-voucher').append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				}
			},
			error: function(result) {
			}
		});
	});
	$('.listCart').on("click",".btnDeleteCart",function() {
		var formData = new FormData();
		formData.append("attributeId", $(this).attr('data-id')); 
		$.ajax({
			url: "{{route('cart.attribute.delete',$channel['domain']->domain)}}",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				if(result.success==true){
					location.reload(); 
				}
			},
			error: function(result) {
			}
		});
	});
</script>
@include('themes.admin.inc.footer')