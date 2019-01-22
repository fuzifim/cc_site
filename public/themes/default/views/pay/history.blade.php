<?
	$channel['theme']->setTitle('Lịch sử thanh toán');
	$channel['theme']->setKeywords('Lịch sử thanh toán');
	$channel['theme']->setDescription('Lịch sử thanh toán các đơn hàng của bạn. '); 
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.sparkline', 'js/jquery.sparkline.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('retina', 'js/retina.min.js', array('core-script'))!!}
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
		<div class="row row-pad-5">
			<div class="col-md-6">
				<h3 class="text-success"><small>Số dư tài khoản của bạn:</small> <strong>{{Site::price($channel['financeUserTotal'])}}<sup>đ</sup></strong></h3> 
			</div>
			<div class="col-md-6 text-right">
				<a href="{{route('pay.add',$channel['domainPrimary'])}}" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-credit-card"></i> Nạp tiền vào tài khoản</a>
			</div>
		</div>
		@if(count(Auth::user()->joinOrder)>0)
		<div class="panel panel-default">
			<div class="panel-heading"><div class="panel-title"><i class="glyphicon glyphicon-shopping-cart"></i> Đơn hàng của bạn</div></div>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
					  <tr>
						<th><b>Loại</b></th>
						<th><b>Tên</b></th>
						<th><b>Số lượng</b></th>
						<th><b>Số tiền</b></th>
						<th><b>Ngày tạo</b></th>
						<th><b>Tình trạng</b></th>
						<th><b>Tùy chọn</b></th>
					  </tr>
					</thead>
					<tbody>
					@foreach(Auth::user()->joinOrder as $joinOrder)
						<?
							$cartConvert=json_decode($joinOrder->order->cart); 
						?>
						@foreach($cartConvert as $key=>$cart)
							@if($key=="channelAdd")
							<tr>
								<td>Tạo website</td>
								<td>{!!$cart->name!!}</td>
								<td>{!!$cart->quantity!!}</td>
								<td>{{Site::price($cart->price*$cart->quantity)}}</span><sup>VND</sup></td>
								<td>{{$joinOrder->order->created_at}}</td>
								<td>@if($joinOrder->order->status=='active')<span class="text-success">Đã thanh toán</span>@else <span class="text-danger">Chưa thanh toán</span> @endif</td>
								<td class="table-action">
									@if($joinOrder->order->status!='active')<a href="#" data-id="{{$joinOrder->order->id}}" class="orderDelete"><i class="fa fa-trash-o"></i> xóa</a>@endif
								</td>
							</tr>
							@else 
								@if(!empty($cart->attributes->type))
									@if($cart->attributes->type=='channelAdd')
										<tr>
											<td>Tạo website</td>
											<td>{!!$cart->name!!}</td>
											<td>{!!$cart->quantity!!}</td>
											<td>{{Site::price($cart->price*$cart->quantity)}}</span><sup>VND</sup></td>
											<td>{{$joinOrder->order->created_at}}</td>
											<td>@if($joinOrder->order->status=='active')<span class="text-success">Đã thanh toán</span>@else <span class="text-danger">Chưa thanh toán</span> @endif</td>
											<td class="table-action">
												@if($joinOrder->order->status!='active')<a href="#" data-id="{{$joinOrder->order->id}}" class="orderDelete"><i class="fa fa-trash-o"></i> xóa</a>@endif
											</td>
										</tr>
									@elseif($cart->attributes->type=='channelUpgrade')
										<tr>
											<td>Nâng cấp website</td>
											<td>{!!$cart->name!!}</td>
											<td>{!!$cart->quantity!!}</td>
											<td>{{Site::price($cart->price*$cart->quantity)}}</span><sup>VND</sup></td>
											<td>{{$joinOrder->order->created_at}}</td>
											<td>@if($joinOrder->order->status=='active')<span class="text-success">Đã thanh toán</span>@else <span class="text-danger">Chưa thanh toán</span> @endif</td>
											<td class="table-action">
												@if($joinOrder->order->status!='active')<a href="#" data-id="{{$joinOrder->order->id}}" class="orderDelete"><i class="fa fa-trash-o"></i> xóa</a>@endif
											</td>
										</tr>
									@elseif($cart->attributes->type=='channelReOrder')
										<tr>
											<td>Gia hạn website</td>
											<td>{!!$cart->name!!}</td>
											<td>{!!$cart->quantity!!}</td>
											<td>{{Site::price($cart->price*$cart->quantity)}}</span><sup>VND</sup></td>
											<td>{{$joinOrder->order->created_at}}</td>
											<td>@if($joinOrder->order->status=='active')<span class="text-success">Đã thanh toán</span>@else <span class="text-danger">Chưa thanh toán</span> @endif</td>
											<td class="table-action">
												@if($joinOrder->order->status!='active')<a href="#" data-id="{{$joinOrder->order->id}}" class="orderDelete"><i class="fa fa-trash-o"></i> xóa</a>@endif
											</td>
										</tr>
									@elseif($cart->attributes->type=='cloudReOrder')
										<tr>
											<td>Gia hạn Cloud</td>
											<td>{!!$cart->name!!}</td>
											<td>{!!$cart->quantity!!}</td>
											<td>{{Site::price($cart->price*$cart->quantity)}}</span><sup>VND</sup></td>
											<td>{{$joinOrder->order->created_at}}</td>
											<td>@if($joinOrder->order->status=='active')<span class="text-success">Đã thanh toán</span>@else <span class="text-danger">Chưa thanh toán</span> @endif</td>
											<td class="table-action">
												@if($joinOrder->order->status!='active')<a href="#" data-id="{{$joinOrder->order->id}}" class="orderDelete"><i class="fa fa-trash-o"></i> xóa</a>@endif
											</td>
										</tr>
									@elseif($cart->attributes->type=='hostingReOrder')
										<tr>
											<td>Gia hạn Hosting</td>
											<td>{!!$cart->name!!}</td>
											<td>{!!$cart->quantity!!}</td>
											<td>{{Site::price($cart->price*$cart->quantity)}}</span><sup>VND</sup></td>
											<td>{{$joinOrder->order->created_at}}</td>
											<td>@if($joinOrder->order->status=='active')<span class="text-success">Đã thanh toán</span>@else <span class="text-danger">Chưa thanh toán</span> @endif</td>
											<td class="table-action">
												@if($joinOrder->order->status!='active')<a href="#" data-id="{{$joinOrder->order->id}}" class="orderDelete"><i class="fa fa-trash-o"></i> xóa</a>@endif
											</td>
										</tr>
									@elseif($cart->attributes->type=='hostingAdd')
										<tr>
											<td>Đăng ký Hosting</td>
											<td>
												{!!$cart->name!!}
												<p><small><a class="badge badge-primary" href="{{route('channel.hosting.list',$channel['domainPrimary'])}}">Danh sách hosting</a></small></p>
											</td>
											<td>{!!$cart->quantity!!}</td>
											<td>{{Site::price($cart->price*$cart->quantity)}}</span><sup>VND</sup></td>
											<td>{{$joinOrder->order->created_at}}</td>
											<td>@if($joinOrder->order->status=='active')<span class="text-success">Đã thanh toán</span>@else <span class="text-danger">Chưa thanh toán</span> @endif</td>
											<td class="table-action">
												@if($joinOrder->order->status!='active')<a href="#" data-id="{{$joinOrder->order->id}}" class="orderDelete"><i class="fa fa-trash-o"></i> xóa</a>@endif
											</td>
										</tr>
									@elseif($cart->attributes->type=='domainAddCart')
										<tr>
											<td>Tên miền</td>
											<td>{!!$cart->name!!}</td>
											<td>{!!$cart->quantity!!}</td>
											<td>{{Site::price($cart->price*$cart->quantity)}}</span><sup>VND</sup></td>
											<td>{{$joinOrder->order->created_at}}</td>
											<td>@if($joinOrder->order->status=='active')<span class="text-success">Đã thanh toán</span>@else <span class="text-danger">Chưa thanh toán</span> @endif</td>
											<td class="table-action">
												@if($joinOrder->order->status!='active')<a href="#" data-id="{{$joinOrder->order->id}}" class="orderDelete"><i class="fa fa-trash-o"></i> xóa</a>@endif
											</td>
										</tr>
										@elseif($cart->attributes->type=='domainAdd')
										<tr>
											<td>Tên miền</td>
											<td>{!!$cart->name!!}</td>
											<td>{!!$cart->quantity!!}</td>
											<td>{{Site::price($cart->price*$cart->quantity)}}</span><sup>VND</sup></td>
											<td>{{$joinOrder->order->created_at}}</td>
											<td>@if($joinOrder->order->status=='active')<span class="text-success">Đã thanh toán</span>@else <span class="text-danger">Chưa thanh toán</span> @endif</td>
											<td class="table-action">
												@if($joinOrder->order->status!='active')<a href="#" data-id="{{$joinOrder->order->id}}" class="orderDelete"><i class="fa fa-trash-o"></i> xóa</a>@endif
											</td>
										</tr>
									@endif
								@endif
							@endif
						@endforeach
					@endforeach
					</tbody>
				</table>
				<div class="mb5"></div>
			</div>
		</div>
		@endif
		@if(count(Auth::user()->joinHistory)>0)
		<div class="panel panel-default">
			<div class="panel-heading"><div class="panel-title"><i class="glyphicon glyphicon-time"></i> Lịch sử giao dịch</div></div>
			<ul class="list-group">
				@foreach(Auth::user()->joinHistory as $joinHistory)
					@if($joinHistory->history->type=='domainAdd')
						<li class="list-group-item">
							<p>Vào lúc: <strong>{!!$joinHistory->history->created_at!!}</strong></p>
							<p>Đăng ký tên miền</p> 
							<p>Đơn hàng số: {!!$joinHistory->history->order_id!!}</p> 
							<p>Số tiền: <strong>{!!Site::price($joinHistory->history->price)!!}</strong></p>
						</li>
					@elseif($joinHistory->history->type=='channelAdd')
						<li class="list-group-item">
							<p>Vào lúc: <strong>{!!$joinHistory->history->created_at!!}</strong></p>
							<p>Tạo website</p> 
							<p>Đơn hàng số: {!!$joinHistory->history->order_id!!}</p> 
							<p>Số tiền: <strong>{!!Site::price($joinHistory->history->price)!!}</strong></p>
						</li>
					@elseif($joinHistory->history->type=='paymentAdd')
						<li class="list-group-item">
							<p>Vào lúc: <strong>{!!$joinHistory->history->created_at!!}</strong></p>
							<p>Nạp tiền vào tài khoản</p> 
							<p>Đơn hàng số: {!!$joinHistory->history->order_id!!}</p> 
							<p>Số tiền: <strong>{!!Site::price($joinHistory->history->price)!!}</strong></p>
						</li>
					@endif
				@endforeach
			</ul>
		</div>
		@else 
			<div class="alert alert-warning">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<strong>Thông báo!</strong> Bạn chưa có lịch sử thanh toán nào.
			</div>
		@endif
	</div>

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		$(".orderDelete").click(function() {
			var orderId = $(this).attr("data-id"); 
			var formData = new FormData();
			formData.append("orderId", orderId); 
			$.ajax({
				url: "'.route("pay.order.delete",$channel["domain"]->domain).'",
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
	', $dependencies);
?>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('onload',' 
	', $dependencies);
?>