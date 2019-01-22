@extends('inc.master')
@section('seo')
<?php 
  $data_seo = array(
    'title' => 'Thanh toán đơn hàng',
    'keywords' => '',
    'description' => '',
    'og_title' => '',
    'og_description' => '',
    'og_url' => Request::url(),
    'og_sitename' => config('app.appname'),
    'og_img' => '',
    'current_url' => Request::url()
  );
  $seo = WebService::getSEO($data_seo); 
?>
@include('partials.seo')
@endsection
@section('content')

    <div id="page-content-wrapper" class="page_content_primary clear">
        <div class="container-fluid entry_container xyz">
            <div class="row no-gutter mrb-5 country_option">
                <div class="col-lg-12">
                    <div class="breadcrumbs_inc clear">
                        <ol class="breadcrumb">
							<li><a href="{{url('/')}}"><i class="glyphicon glyphicon-home"></i> <span class="hidden-xs">Trang chủ</span></a></li>
                            <li><a href="{{route('user.payment')}}">Thanh toán</a></li>
                            <li class="active">Thanh toán đơn hàng</li>
						</ol>
                    </div>
                </div>
            </div>
            <div class="row no-gutter">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    @include('partials.message')
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Thông báo!</strong> Có lỗi xảy ra.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div id="post-fanpage-thanhtoan" class="">
						<form method="post" id="frm_vps" class="" name="frm_search" ng-controller="" action="" role="vpc_process">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<input type="hidden" name="template_id" value="{{$template_setting->id}}"/>
						<input type="hidden" id="promoto_pay" name="voucher_check" value="0"/>
                        <div class="col-lg-8 col-xs-12 col-md-6">
                          <!--Phuong thuc thanh toan-->
                          <div class="panel panel-default">
							<div class="panel-heading">
								Thanh toán trực tuyến
							</div>
                            <div id="tabs-container" class="payment_method_select panel-body">
                              <div class="tab-content clear">
                                  <div class="tab-pane active" id="atm">
										<p class="note">Để thực hiện việc thanh toán, bắt buộc thẻ ATM của bạn đã có đăng ký sử dụng dịch vụ Internet Banking tại các Ngân hàng.</p> 
										<p>{{config('app.appname')}} hỗ trợ thanh toán trực tuyến thông qua 27 ngân hàng tại Việt Nam. </p>
										<p><img class="img-responsive" src="http://img.cungcap.net/media/1471752917_thanh/1489995932-ecom-kbjpg.jpg"></p>
                                  </div><!--atm-->

                              </div><!--tab-content-->  
                            </div><!--tab-container-->  
                          </div><!--payment_form-->
                        </div>

                        <div class="col-lg-4 col-xs-12 col-md-6">
                          <!--User-->
                            <div class="ch-box panel panel-default">
								<div class="panel-heading">
									Thông tin thanh toán
								</div> 
								<div class="panel-body">
									<span class="active_website">Kênh: <a href="//{{$template_setting->domain}}"><i class="glyphicon glyphicon-link"></i> {{$template_setting->domain}}</a></span>
									<div class="row no-gutter">
										<div class="col-md-4">
											<select class="form-control" name="number" id="number">
											  <option value="1">1 năm</option>
											  <option value="2">2 năm</option>
											  <option value="3">3 năm</option>
											  <option value="5">5 năm</option>
											</select>
										</div>
										<div class="col-md-8">
											<div class="form-group">
												<div class="add-coupon add-coupon-inputs form-group">
													<div id="form_set_voucher" class="input-group">
														<input id="coupon" class="form-control voucher_input_field" type="text" autocapitalize="off" autocorrect="off" placeholder="Nhập giảm giá" name="couponcode"/>
														<span class="input-group-btn">
															<span id="voucher_tbl_ok" class="btn btn-success">Áp dụng</span>
														</span>
													</div>
												</div>
												<div class="error-promo-code"></div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<strong class="total-label">Thành tiền</strong><span class="vat-minicart">(Tổng số tiền thanh toán)</span>
										<strong class="total-price"><span id="price_total_pay"><?php echo Site::formatMoney(config('app.vpc_Amount'),true) ?></span><?php echo config('app.vpc_Currency'); ?></strong>
									</div>
									<div class="pull-right">
										<button id="placeYourOrderBtn" class="btn btn-primary" type="button" style="opacity: 1; cursor: pointer;">
											<span class="submit_btn_icon fa-lock fa"></span>
											<span id="placeYourOrderButtonText">Thanh toán</span>
										</button>
										<a href="#" class="btn btn-default" role="button"><i class="glyphicon glyphicon-question-sign"></i> Hướng dẫn thanh toán</a>
									</div>
								</div>  
                            </div>
                        </div>        
					</form>	
                    </div><!--post-fanpage-thanhtoan-->
                </div>
            </div>
        </div>
    </div>
<?
	include(app_path().'/includes/alepay/Lib/Alepay.php');
	$orderCode='123456';
	$amount=10000;
	$currency='VND';
	$orderDescription='Kênh '.$template_setting->domain;
	$totalItem=2;
	$checkoutType=1;
	$returnUrl='http://dakenh.net/atm';
	$cancelUrl=Request::url();
	$buyerName='Nguyễn Hùng Thanh';
	$buyerEmail='thanh@crviet.com';
	$buyerPhone='0903706288';
	$buyerAddress='420 Nơ Trang Long';
	$buyerCity='Hồ Chí Minh';
	$buyerCountry='Việt Nam';
	$paymentHours='12';
	
	$params=array(
		'orderCode'=>$orderCode,
		'amount'=>$amount,
		'currency'=>$currency,
		'orderDescription'=>$orderDescription,
		'totalItem'=>$totalItem,
		'checkoutType'=>$checkoutType,
		'returnUrl'=>$returnUrl,
		'cancelUrl'=>$cancelUrl,
		'buyerName'=>$buyerName,
		'buyerEmail'=>$buyerEmail,
		'buyerPhone'=>$buyerPhone,
		'buyerAddress'=>$buyerAddress,
		'buyerCity'=>$buyerCity,
		'buyerCountry'=>$buyerCountry,
		'paymentHours'=>$paymentHours
	);
	$alepay = new \Alepay(array(
		"apiKey" => config('app.payment_token'),
		"encryptKey" => config('app.payment_enscript'),
		"checksumKey" => config('app.payment_checksum'),
		"callbackUrl" => 'http://dakenh.net/payment'
	));
	$alepayUtils = new \AlepayUtils();
	$dataEncrypt = $alepayUtils->encryptData(json_encode($params), config('app.payment_enscript'));
	$checksum = md5($dataEncrypt . config('app.payment_checksum'));
	$items = array(
		'token' => config('app.payment_token'),
		'data' => $dataEncrypt ,
		'checksum' => $checksum
	);
	$data_string = json_encode($items); 
?>
<!-- Modal -->
<div id="openPayment"  style="display: none" role="dialog">
  <div class="embed-responsive embed-responsive-16by9"><iframe style="position:fixed; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;" id="getFarmPayment" class="embed-responsive-item" src="" frameborder="0" allowfullscreen></iframe></div>
</div>
<script>
	$( "#placeYourOrderBtn" ).click(function() {
		$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')} }); 
		$.ajax({
			url: 'http://test.alepay.vn/checkout/v1/request-order',
			type: 'POST',
			cache: false,
			contentType: 'application/json',
			processData: false,
			dataType:'json',
			data: JSON.stringify(<? echo $data_string;?>),
			success:function(result){
				console.log(result); 
				if(result.errorCode == '000'){
					var formData = new FormData();
					formData.append("data", result.data); 
					formData.append("publicKey", '<? echo config('app.payment_enscript'); ?>'); 
					$.ajax({
						url: "{{route('user.payment.request')}}",
						type: 'POST',
						cache: false,
						contentType: false,
						processData: false,
						dataType:'json', 
						data:formData,
						success:function(resultRequest){
							//var dataResult=jQuery.parseJSON(resultRequest); 
							console.log(resultRequest); 
							$('#openPayment').show();
							$('#getFarmPayment').attr('src',resultRequest.checkoutUrl);
						},
						error: function(resultRequest) {
						}
					});
				} 
			},
			error: function(result) {
			}
		});
	});
 jQuery(document).ready(function($){
    // scroll body to 0px on click
	/*Voucher load check*/
    $("#form_set_voucher").delegate("#voucher_tbl_ok","click", function() {
		$( ".error-promo-code" ).empty(); 
        var voucherCode = $('#coupon').val();
		var numberSelect=$('#number').val(); 
		var productId={{$template_setting->id}}; 
        var url_root=$('#url_root').val();
		var page_url=url_root+'/ajax/voucher/'+voucherCode;
        if($('#coupon').val().length>0){
			$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')} });
            $.ajax({
                type: "GET",
                url: page_url, 
				data: {voucherCode:voucherCode, numberSelect:numberSelect, productId:productId}, 
                dataType: "json",
                contentType: false,
                beforeSend: function() {
                    $("#overlay_load").fadeIn("slow");
                    $(".lighbox_show").fadeIn("slow");
                },
                success: function(data) {
                    $("#overlay_load").fadeOut("slow");
                    $(".lighbox_show").fadeOut("slow");
                    if(data.msg == 'success'){
                        $('#price_total_pay').empty().text(data.voucher_total);
                        var content_voucher='<p class="clear"><i class="glyphicon glyphicon-qrcode"></i> CODE: <span class="text-danger">'+data.code+'</span></p>'+
                                '<p class="text-success">Giảm giá: <i class="glyphicon glyphicon-gift"></i> <strong class="text-warning">'+data.promo+'</strong> tổng tiền thanh toán</p>';
                        $('#form_set_voucher').empty().html(content_voucher);
                        $('#promoto_pay').val(data.code);
                    }
					else if(data.msg == 'error'){ 
						$( ".error-promo-code" ).append( '<div class="alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Mã giảm giá không hợp lệ</div>' );
					}
                }
            });
        }else{
            alert('Bạn phải nhập mã giảm giá!');
        }

    });
    /*Voucher Load Check*/
});/*End Jquery Ready*/
 </script>
 <script src="{{asset('js/nganluong.apps.mcflow.js')}}"></script>
@endsection