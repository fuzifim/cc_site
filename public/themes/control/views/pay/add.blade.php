<?
	$channel['theme']->setTitle('Nạp tiền');
	$channel['theme']->setKeywords('Nạp tiền, nạp tiền vào tài khoản');
	$channel['theme']->setDescription('Nạp tiền vào tài khoản bằng Internet Banking hoặc Visa'); 
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
	{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('title') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel" style="position:relative; ">
		<div id="preloaderInBox" style="display:none;">
			<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
		</div>
		<?
			$getPayOrder=\App\Model\Pay_order::where('user_id','=',Auth::user()->id)->where('status','=','pending')->get(); 
		?>
		@if(count($getPayOrder)>0)
			Bạn có giao dịch nạp tiền chưa thành công. 
			<ul class="list-group">
				@foreach($getPayOrder as $payOrder)
				<li class="list-group-item">Nạp <strong>{{Site::price($payOrder->money)}} <sup>đ</sup></strong> bằng {{$payOrder->bank_code}} vào lúc: {{$payOrder->created_at}} <button type="button" class="btn btn-xs btn-primary rePayment" data-money="{{$payOrder->money}}" data-bankcode="{{$payOrder->bank_code}}" data-paymethod="{{$payOrder->payment_method}}" data-id="{{$payOrder->id}}">Thanh toán</button> <button type="button" class="btn btn-xs btn-danger payOrderDelete" data-id="{{$payOrder->id}}">Xóa</button></li>
				@endforeach
			</ul>
		@endif
		<div class="panel panel-primary">
			<div class="panel-body">
				<h3 class="text-success"><small>Số dư tài khoản của bạn:</small> <strong>{{Site::price($channel['financeUserTotal'])}}<sup>đ</sup></strong></h3> 
				<div class="form-group">
                    <input type="number" name="sizePriceNumber" id="inputPrice" class="form-control"placeholder="Nhập số tiền cần nạp" required >
					<code id="changePrice"></code>
                  </div>
			</div><!-- panel-body -->
		</div><!-- panel-default -->
		<div class="panel-group panel-group-dark listBank" id="accordion">
			<div class="panel panel-default mb5">
				<div class="panel-heading">
					<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><i class="glyphicon glyphicon-credit-card"></i> Bằng Internet Banking <i class="glyphicon glyphicon-chevron-down"></i></a>
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
			<div class="panel panel-default mb5">
				<div class="panel-heading">
					<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="collapsed"><i class="glyphicon glyphicon-credit-card"></i> Bằng thẻ Ngân hàng nội địa <i class="glyphicon glyphicon-chevron-down"></i></a>
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
			<div class="panel panel-default mb5">
				<div class="panel-heading">
					<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="collapsed"><i class="glyphicon glyphicon-credit-card"></i> Bằng Visa/ Master Card <i class="glyphicon glyphicon-chevron-down"></i></a>
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
			<div class="panel panel-default mb5">
				<div class="panel-heading">
					<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse4" class="collapsed"><i class="fa fa-bank"></i> Bằng chuyển khoản <i class="glyphicon glyphicon-chevron-down"></i></a>
					</h4>
				</div>
				<div id="collapse4" class="panel-collapse collapse">
					<div class="panel-body">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							Ghi số điện thoại ở nội dung thanh toán khi chuyển khoản! 
						</div>
						<div class="col-sm-6">
							<p><b>Thông tin tài khoản ngân hàng</b></p>
							<p>Tên tài khoản: Nguyễn Hùng Thanh</p>
							<p>Số tài khoản: 0071000672017</p>
							<p>Ngân hàng: Vietcombank, Hồ Chí Minh</p>
						</div>
						<div class="col-sm-6">
							<label class="btn btn-default"><img style="background:#fff; padding:5px; " src="{{asset("assets/img/bank/bank-vietcombank.png")}}" alt="" class="img-responsive bankCheck"><input type="radio" name="bankcode" payment_method="SEND_PAYMENT" value="VCB" class="hidden" autocomplete="off"></label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer text-right">
			<p class="text-danger">Số tiền sẽ được nạp ngay sau khi thanh toán! </p> 
			<button type="button" class="btn btn-lg btn-warning" id="btnPayment"><i class="fa fa-check"></i> Thanh toán</button>
		</div>
	<?
		$dependencies = array(); 
		$channel['theme']->asset()->writeScript('custom','
			
			$("#inputPrice").keyup(function () {
				if($(this).val().length>0){
					$("#changePrice").html(parseInt($(this).val()).toLocaleString()+" <sup>đ</sup>");
				}else{
					$("#changePrice").empty(); 
				}
			});
			$(".listBank").on("click",".bankCheck",function() {
				$(".bankCheck").not(this).parent().removeClass("btn-primary").addClass("btn-default")
					.siblings("input").prop("checked",false)
					.siblings(".bankCheck").css("opacity","0.5");
				$(this).parent().addClass("btn-primary").removeClass("btn-default")
					.siblings("input").prop("checked",true)
					.siblings(".bankCheck").css("opacity","1");
			});
			$(".payOrderDelete").click(function() {
				var formData = new FormData();
				formData.append("orderId", $(this).attr("data-id")); 
				$(".contentpanel #preloaderInBox").css("display", "block"); 
				$.ajax({
					url: "'.route("pay.order.pending.delete",$channel["domainPrimary"]).'",
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					type: "post",
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					dataType:"json",
					success:function(result){
						//console.log(result); 
						if(result.success==true){
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-success",
								sticky: false,
								time: ""
							}); 
							location.reload();
						}else{
							$(".contentpanel #preloaderInBox").css("display", "none"); 
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-danger",
								sticky: false,
								time: ""
							});
						}
					},
					error: function(result) {
					}
				});
			}); 
			$(".rePayment").click(function() {
				var formData = new FormData();
				formData.append("orderId", $(this).attr("data-id")); 
				formData.append("money", $(this).attr("data-money")); 
				formData.append("bankcode", $(this).attr("data-bankcode")); 
				formData.append("payment_method", $(this).attr("data-paymethod")); 
				formData.append("cancel_url", "'.Request::url().'"); 
				formData.append("payment_type", "rePayment"); 
				$(".contentpanel #preloaderInBox").css("display", "block"); 
				$.ajax({
					url: "'.route("pay.add.money",$channel["domainPrimary"]).'",
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					type: "post",
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					dataType:"json",
					success:function(result){
						//console.log(result); 
						if(result.success==true){
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-success",
								sticky: false,
								time: ""
							}); 
							window.location.href = result.checkout_url;
						}else{
							$(".contentpanel #preloaderInBox").css("display", "none"); 
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-danger",
								sticky: false,
								time: ""
							});
						}
					},
					error: function(result) {
					}
				});
			}); 
			$("#btnPayment").click(function() {
				var bankcode = $( ".listBank input[name=bankcode]:checked" ).val(); 
				var payment_method = $( ".listBank input[name=bankcode]:checked" ).attr("payment_method"); 
				var bankcodeCheck="";
				var payment_methodCheck=""; 
				if(typeof(bankcode) != "undefined" && bankcode !== null) {
					bankcodeCheck=bankcode; 
				}
				if(typeof(payment_method) != "undefined" && payment_method !== null) {
					payment_methodCheck=payment_method; 
				}
				var formData = new FormData();
				formData.append("money", $("input[name=sizePriceNumber]").val()); 
				formData.append("bankcode", bankcodeCheck); 
				formData.append("payment_method", payment_methodCheck); 
				formData.append("cancel_url", "'.Request::url().'"); 
				formData.append("payment_type", "addNew"); 
				$(".contentpanel #preloaderInBox").css("display", "block"); 
				$.ajax({
					url: "'.route("pay.add.money",$channel["domainPrimary"]).'",
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					type: "post",
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					dataType:"json",
					success:function(result){
						//console.log(result); 
						if(result.success==true){
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-success",
								sticky: false,
								time: ""
							}); 
							window.location.href = result.checkout_url;
						}else{
							$(".contentpanel #preloaderInBox").css("display", "none"); 
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-danger",
								sticky: false,
								time: ""
							});
						}
					},
					error: function(result) {
					}
				});
			});
		', $dependencies);
	?>

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('onload',' 
	', $dependencies);
?>