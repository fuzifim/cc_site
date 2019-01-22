<?
	$channel['theme']->setTitle('Thanh toán');
	$channel['theme']->setKeywords('Thanh toán');
	$channel['theme']->setDescription('Thanh toán đơn hàng của bạn '); 
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
<div class="mainpanel">
	{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel" style="position:relative; ">
		<div id="preloaderInBox" style="display:none;">
			<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
		</div>
		<div class="row row-pad-5">
			<div class="col-md-6">
				<h3 class="text-success"><small>Số dư tài khoản của bạn:</small> <strong>{{Site::price($channel['financeUserTotal'])}}<sup>đ</sup></strong></h3> 
			</div>
			<div class="col-md-6 text-right">
				<a href="{{route('pay.add',$channel['domainPrimary'])}}" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-credit-card"></i> Nạp tiền vào tài khoản</a>
			</div>
		</div>
		@if(count($listCart)>0)
		<div class="panel panel-primary">
			<div class="panel-body panel-body-nopadding">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
						  <tr>
							<th><b>Loại</b></th>
							<th><b>Tên</b></th>
							<th><b>Số lượng</b></th>
							<th><b>Số tiền</b></th>
							<th><b>Tùy chọn</b></th>
						  </tr>
						</thead>
						<tbody>
						@foreach($listCart as $key=>$cart)
							<?
								$cartAttribute=json_decode($cart->attributes); 
							?>
							@if($cartAttribute->type=='channelAdd')
							<tr>
								<td><b>Tạo website</b></td>
								<td><strong>{!!$cart->name!!}</strong>
									<p><code>http://{!!$cart->attributes->domain!!}.{!!$cart->attributes->domainLtd!!}</code></p>
								</td>
								<td>
									<select class="form-control input-sm mb15" name="updateQuanlity" data-id="{!!$cart->id!!}">
									  <option value="1" @if($cart->quantity==1) selected @endif >1 {!!$cart->attributes->per!!}</option>
									  <option value="3" @if($cart->quantity==3) selected @endif >3 {!!$cart->attributes->per!!}</option>
									  <option value="6" @if($cart->quantity==6) selected @endif >6 {!!$cart->attributes->per!!}</option>
									  <option value="12" @if($cart->quantity==12) selected @endif >12 {!!$cart->attributes->per!!}</option>
									  <option value="24" @if($cart->quantity==24) selected @endif >24 {!!$cart->attributes->per!!}</option>
									</select>
								</td>
								<td>{{Site::price($cart->getPriceSum())}}</span><sup>VND</sup></td>
								<td class="table-action">
								  <a href="#" class="removeCart" data-id="{!!$cart->id!!}"><span class="label label-danger"><i class="fa fa-trash-o"></i> xóa</span></a> 
								</td>
							</tr>
							@elseif($cartAttribute->type=='channelUpgrade')
							<tr>
								<td><b>Nâng cấp {!!$cartAttribute->channel_service_name!!}</b></td>
								<td>{!!$cart->name!!}</td>
								<td>
									<select class="form-control input-sm mb15" name="updateQuanlity" data-id="{!!$cart->id!!}">
									  <option value="1" @if($cart->quantity==1) selected @endif >1 {!!$cart->attributes->per!!}</option>
									  <option value="3" @if($cart->quantity==3) selected @endif >3 {!!$cart->attributes->per!!}</option>
									  <option value="6" @if($cart->quantity==6) selected @endif >6 {!!$cart->attributes->per!!}</option>
									  <option value="12" @if($cart->quantity==12) selected @endif >12 {!!$cart->attributes->per!!}</option>
									  <option value="24" @if($cart->quantity==24) selected @endif >24 {!!$cart->attributes->per!!}</option>
									</select>
								</td>
								<td>{{Site::price($cart->getPriceSum())}}</span><sup>VND</sup></td>
								<td class="table-action">
								  <a href="#" class="removeCart" data-id="{!!$cart->id!!}"><span class="label label-danger"><i class="fa fa-trash-o"></i> xóa</span></a> 
								</td>
							</tr>
							@elseif($cartAttribute->type=='channelReOrder')
							<tr>
								<td><b>Gia hạn website</b></td>
								<td>{!!$cart->name!!}</td>
								<td>
									<select class="form-control input-sm mb15" name="updateQuanlity" data-id="{!!$cart->id!!}">
									  <option value="1" @if($cart->quantity==1) selected @endif >1 {!!$cart->attributes->per!!}</option>
									  <option value="3" @if($cart->quantity==3) selected @endif >3 {!!$cart->attributes->per!!}</option>
									  <option value="6" @if($cart->quantity==6) selected @endif >6 {!!$cart->attributes->per!!}</option>
									  <option value="12" @if($cart->quantity==12) selected @endif >12 {!!$cart->attributes->per!!}</option>
									  <option value="24" @if($cart->quantity==24) selected @endif >24 {!!$cart->attributes->per!!}</option>
									</select>
								</td>
								<td>{{Site::price($cart->getPriceSum())}}</span><sup>VND</sup></td>
								<td class="table-action">
								  <a href="#" class="removeCart" data-id="{!!$cart->id!!}"><span class="label label-danger"><i class="fa fa-trash-o"></i> xóa</span></a> 
								</td>
							</tr>
							@elseif($cartAttribute->type=='cloudReOrder')
							<tr>
								<td><b>Gia hạn Cloud</b></td>
								<td>{!!$cart->name!!} - {{$cart->attributes->service_attribute_name}}</td>
								<td>
									<select class="form-control input-sm mb15" name="updateQuanlity" data-id="{!!$cart->id!!}">
									  <option value="1" @if($cart->quantity==1) selected @endif >1 {!!$cart->attributes->per!!}</option>
									  <option value="3" @if($cart->quantity==3) selected @endif >3 {!!$cart->attributes->per!!}</option>
									  <option value="6" @if($cart->quantity==6) selected @endif >6 {!!$cart->attributes->per!!}</option>
									  <option value="12" @if($cart->quantity==12) selected @endif >12 {!!$cart->attributes->per!!}</option>
									  <option value="24" @if($cart->quantity==24) selected @endif >24 {!!$cart->attributes->per!!}</option>
									</select>
								</td>
								<td>{{Site::price($cart->getPriceSum())}}</span><sup>VND</sup></td>
								<td class="table-action">
								  <a href="#" class="removeCart" data-id="{!!$cart->id!!}"><span class="label label-danger"><i class="fa fa-trash-o"></i> xóa</span></a> 
								</td>
							</tr>
							@elseif($cartAttribute->type=='hostingReOrder')
							<tr>
								<td><b>Gia hạn Hosting</b></td>
								<td>{!!$cart->name!!} - {{$cart->attributes->service_attribute_name}}</td>
								<td>
									<select class="form-control input-sm mb15" name="updateQuanlity" data-id="{!!$cart->id!!}">
									  <option value="1" @if($cart->quantity==1) selected @endif >1 {!!$cart->attributes->per!!}</option>
									  <option value="3" @if($cart->quantity==3) selected @endif >3 {!!$cart->attributes->per!!}</option>
									  <option value="6" @if($cart->quantity==6) selected @endif >6 {!!$cart->attributes->per!!}</option>
									  <option value="12" @if($cart->quantity==12) selected @endif >12 {!!$cart->attributes->per!!}</option>
									  <option value="24" @if($cart->quantity==24) selected @endif >24 {!!$cart->attributes->per!!}</option>
									</select>
								</td>
								<td>{{Site::price($cart->getPriceSum())}}</span><sup>VND</sup></td>
								<td class="table-action">
								  <a href="#" class="removeCart" data-id="{!!$cart->id!!}"><span class="label label-danger"><i class="fa fa-trash-o"></i> xóa</span></a> 
								</td>
							</tr>
							@elseif($cartAttribute->type=='hostingAdd')
							<tr>
								<td><b>Đăng ký hosting</b></td>
								<td>{!!$cart->name!!}</td>
								<td>
									<select class="form-control input-sm mb15" name="updateQuanlity" data-id="{!!$cart->id!!}">
									  <option value="1" @if($cart->quantity==1) selected @endif >1 {!!$cart->attributes->per!!}</option>
									  <option value="3" @if($cart->quantity==3) selected @endif >3 {!!$cart->attributes->per!!}</option>
									  <option value="6" @if($cart->quantity==6) selected @endif >6 {!!$cart->attributes->per!!}</option>
									  <option value="12" @if($cart->quantity==12) selected @endif >12 {!!$cart->attributes->per!!}</option>
									  <option value="24" @if($cart->quantity==24) selected @endif >24 {!!$cart->attributes->per!!}</option>
									</select>
								</td>
								<td>{{Site::price($cart->getPriceSum())}}</span><sup>VND</sup></td>
								<td class="table-action">
								  <a href="#" class="removeCart" data-id="{!!$cart->id!!}"><span class="label label-danger"><i class="fa fa-trash-o"></i> xóa</span></a> 
								</td>
							</tr>
							@elseif($cartAttribute->type=='emailAdd')
							<tr>
								<td><b>Đăng ký Email Server</b></td>
								<td>{!!$cart->name!!}</td>
								<td>
									<select class="form-control input-sm mb15" name="updateQuanlity" data-id="{!!$cart->id!!}">
									  <option value="1" @if($cart->quantity==1) selected @endif >1 {!!$cart->attributes->per!!}</option>
									  <option value="3" @if($cart->quantity==3) selected @endif >3 {!!$cart->attributes->per!!}</option>
									  <option value="6" @if($cart->quantity==6) selected @endif >6 {!!$cart->attributes->per!!}</option>
									  <option value="12" @if($cart->quantity==12) selected @endif >12 {!!$cart->attributes->per!!}</option>
									  <option value="24" @if($cart->quantity==24) selected @endif >24 {!!$cart->attributes->per!!}</option>
									</select>
								</td>
								<td>{{Site::price($cart->getPriceSum())}}</span><sup>VND</sup></td>
								<td class="table-action">
								  <a href="#" class="removeCart" data-id="{!!$cart->id!!}"><span class="label label-danger"><i class="fa fa-trash-o"></i> xóa</span></a> 
								</td>
							</tr>
							@elseif($cartAttribute->type=='cloudAdd')
							<tr>
								<td><b>Đăng ký cloud</b></td>
								<td>{!!$cart->name!!}</td>
								<td>
									<select class="form-control input-sm mb15" name="updateQuanlity" data-id="{!!$cart->id!!}">
									  <option value="1" @if($cart->quantity==1) selected @endif >1 {!!$cart->attributes->per!!}</option>
									  <option value="3" @if($cart->quantity==3) selected @endif >3 {!!$cart->attributes->per!!}</option>
									  <option value="6" @if($cart->quantity==6) selected @endif >6 {!!$cart->attributes->per!!}</option>
									  <option value="12" @if($cart->quantity==12) selected @endif >12 {!!$cart->attributes->per!!}</option>
									  <option value="24" @if($cart->quantity==24) selected @endif >24 {!!$cart->attributes->per!!}</option>
									</select>
								</td>
								<td>{{Site::price($cart->getPriceSum())}}</span><sup>VND</sup></td>
								<td class="table-action">
								  <a href="#" class="removeCart" data-id="{!!$cart->id!!}"><span class="label label-danger"><i class="fa fa-trash-o"></i> xóa</span></a> 
								</td>
							</tr>
							@elseif($cartAttribute->type=='domainAdd')
							<tr>
								<td><b>{!!$cartAttribute->service_name!!}</b></td>
								<td>{!!$cart->name!!}</td>
								<td>{!!$cart->quantity!!} năm</td>
								<td>{{Site::price($cart->getPriceSum())}}</span><sup>VND</sup></td>
								<td class="table-action">
								  <a href="#" class="removeCart" data-id="{!!$cart->id!!}"><span class="label label-danger"><i class="fa fa-trash-o"></i> xóa</span></a> 
								</td>
							</tr>
							@elseif($cartAttribute->type=='domainAddCart')
							<tr>
								<td><b>{!!$cartAttribute->service_name!!}</b></td>
								<td>{!!$cart->name!!}</td>
								<td>
									<select class="form-control input-sm mb15" name="updateQuanlity" data-id="{!!$cart->id!!}">
									  <option value="1" @if($cart->quantity==1) selected @endif >1 {!!$cart->attributes->per!!}</option>
									  <option value="2" @if($cart->quantity==2) selected @endif >2 {!!$cart->attributes->per!!}</option>
									  <option value="3" @if($cart->quantity==3) selected @endif >3 {!!$cart->attributes->per!!}</option>
									  <option value="5" @if($cart->quantity==5) selected @endif >5 {!!$cart->attributes->per!!}</option>
									</select>
								</td>
								<td>{{Site::price($cart->getPriceSum())}}</span><sup>VND</sup></td>
								<td class="table-action">
								  <a href="#" class="removeCart" data-id="{!!$cart->id!!}"><span class="label label-danger"><i class="fa fa-trash-o"></i> xóa</span></a> 
								</td>
							</tr>
							@endif
						@endforeach
						</tbody>
					</table>
				</div>
				<table class="table table-total">
					<tbody>
						<tr>
							<td><strong>Tổng tiền:</strong></td>
							<td><b>{{Site::price($totalPrice)}}<sup>VND</sup><b></td>
						</tr>
					</tbody>
				</table>
			</div><!-- panel-body -->
		</div><!-- panel-default -->
		<div class="panel-footer text-right">
			<p class="text-danger">Dịch vụ của bạn sẽ được kích hoạt ngay sau khi thanh toán! <a href="tel:@if(!empty($channel['info']->phoneJoin->phone->phone_number)){{$channel['info']->phoneJoin->phone->phone_number}}@endif"><i class="glyphicon glyphicon-earphone"></i> Trợ giúp?</a></p> 
			<button type="button" class="btn btn-lg btn-warning" id="btnPayment"><i class="fa fa-check"></i> Thanh toán</button>
		</div>
	<?
		$dependencies = array(); 
		$channel['theme']->asset()->writeScript('custom','
			
			$("select[name=updateQuanlity]").change(function() {
				var cartId=$(this).attr("data-id"); 
				var quanlityChange=$(this).val(); 
				var formData = new FormData();
				formData.append("cartId", cartId); 
				formData.append("quanlityChange", quanlityChange); 
				$("#preloaderInBox").css("display", "block");  
				$.ajax({
					url: "'.route("pay.cart.quanlity",$channel["domainPrimary"]).'",
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					type: "post",
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					dataType:"json",
					success:function(result){
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
							$("#preloaderInBox").css("display", "none");  
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
				return false;
			}); 
			$(".removeCart").click(function() {
				var cartId=$(this).attr("data-id"); 
				var formData = new FormData();
				formData.append("cartId", cartId); 
				$("#preloaderInBox").css("display", "block");  
				$.ajax({
					url: "'.route("cart.remove",$channel["domainPrimary"]).'",
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					type: "post",
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					dataType:"json",
					success:function(result){
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
							$("#preloaderInBox").css("display", "none");  
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
				return false; 
			}); 
			$("#btnPayment").click(function() { 
				if(confirm("Bạn có chắc muốn thanh toán?")){
					var formData = new FormData();
					formData.append("cancel_url", "'.Request::url().'"); 
					$(".contentpanel #preloaderInBox").css("display", "block"); 
					$.ajax({
						url: "'.route("pay.cart.send",$channel["domain"]->domain).'",
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
								window.location.href = "'.route("pay.history",$channel["domainPrimary"]).'";
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
				}
			});
		', $dependencies);
	?>
	@else
		<div class="panel panel-default">
			<div class="panel-heading">
			  <div class="panel-btns">
				<a href="" class="panel-close">×</a>
				<a href="" class="minimize">−</a>
			  </div><!-- panel-btns -->
			  <h4 class="panel-title"><i class="fa fa-shopping-cart"></i> Đơn hàng</h4>
			</div><!-- panel-heading -->
			<div class="panel-body">
				<div class="alert alert-info">
					Bạn không có đơn hàng nào thanh toán!  
				</div>
			</div>
		</div>
	@endif

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('onload',' 
	', $dependencies);
?>