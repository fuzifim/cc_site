@extends('inc.master')
@section('seo')
<?php 
	$data_seo = array(
		'title' => ' Lịch sử thanh toán',
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
<!-- Modal -->
<div id="Withdrawal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">
					@if($priceTotal>0)
						Số dư tài khoản của bạn là: <strong><?php echo Site::formatMoney($priceTotal,true) ?><?php echo config('app.vpc_Currency'); ?></strong>
					@endif
				</h4>
			</div>
			<div class="modal-body">
				<div class="add_message"></div>
				<div class="form-group">
					<label class="control-label" for="price">Nhập số tiền cần rút:</label> 
					 <div class="input-group"> 
						<span class="input-group-addon">$</span>
						<input type="number" name="price" value="" min="0" @if($priceTotal>0) max="{{$priceTotal}}"  placeholder="Tối đa là: {{$priceTotal}}"  @else max="0" placeholder="Số dư của bạn không đủ để rút" @endif step="1" class="form-control currency" id="c2" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="takeout" data-id="0"><i class="glyphicon glyphicon-save"></i> Rút</button>
			</div>
		</div>
	</div>
</div>

    <div id="page-content-wrapper" class="page_content_primary clear">
        <div class="container-fluid entry_container xyz">
            <div class="row no-gutter mrb-5 country_option">
                <div class="col-lg-12">
                    <div class="breadcrumbs_inc clear">
                        <ol class="breadcrumb">
                            <li class="dropdown active"><a data-toggle="dropdown" itemprop="item" href=""><span class="glyphicon glyphicon-cog"></span> Bảng điều khiển</a> <span class="caret"></span>
								@include('partials.menu_dropdown_dashboard')
							</li>
                            <li class="active">Thanh toán</li>
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
                    <div id="post-fanpage" class="clear dasboard_container_details">
						@if($priceTotal>0)
							<div class="panel panel-default">
								<div class="panel-body">
									Số dư tài khoản của bạn là: <strong><?php echo Site::formatMoney($priceTotal,true) ?><?php echo config('app.vpc_Currency'); ?></strong> <button class="btn btn-xs btn-success"  data-toggle="modal" data-target="#Withdrawal"><i class="glyphicon glyphicon-transfer"></i> Rút tiền</button>
								</div>
							</div>
						@endif
						@if(count($service_payment)>0)
						<div class="panel panel-primary">
							<div class="panel-heading">
								Danh sách dịch vụ
							</div>
							<div class="panel-body" style="padding:0px; ">
								<div id="no-more-tables">
									<table class="col-md-12 table-bordered table-striped table-condensed cf">
										<thead class="cf">
											<tr>
												<th>Dịch vụ</th>
												<th>Ngày đăng ký</th>
												<th>Ngày hết hạn</th>
												<th>Tình trạng</th>
												<th>Tùy chọn</th>
											</tr>
										</thead>
										<tbody>
											@foreach($service_payment as $template)
												@if($template->date_end < \Carbon\Carbon::now()->format('Y-m-d H:i:s'))
													<tr class="text-danger">
														<td data-title="Dịch vụ">Kênh {{$template->domain}}</td>
														<td data-title="Ngày đăng ký"><?php echo date("d/m/Y", strtotime($template->created_at)); ?></td>
														<td data-title="Ngày hết hạn" >@if($template->date_end>0)<?php echo date("d/m/Y", strtotime($template->date_end)); ?>@else Chưa thanh toán @endif</td>
														<td data-title="Tình trạng" class="text-danger">Đã hết hạn</td>
														<td data-title="Tùy chọn">
															<a class="btn btn-xs btn-primary" href="{{route('user.templateType.setting',array($template->id,'general'))}}"><i class="glyphicon glyphicon-cog"></i> Cài đặt</a>
															<a class="btn btn-xs btn-danger" data-href="{{$template->id}}" href="{{route('front.ads.thanhtoan',$template->id)}}"><i class=" fa fa-lock"></i> Thanh toán</a> 
														</td>
													</tr>
												@else
													<tr class="text-success">
														<td data-title="Dịch vụ">Kênh <a href="//{{$template->domain}}">{{$template->domain}}</a></td>
														<td data-title="Ngày đăng ký"><?php echo date("d/m/Y", strtotime($template->created_at)); ?></td>
														<td data-title="Ngày hết hạn" ><?php echo date("d/m/Y", strtotime($template->date_end)); ?></td>
														<td data-title="Tình trạng" class="text-success">Đang sử dụng</td>
														<td data-title="Tùy chọn">
															<a class="btn btn-xs btn-primary" href="{{route('user.templateType.setting',array($template->id,'general'))}}"><i class="glyphicon glyphicon-cog"></i> Cài đặt</a> 
														</td>
													</tr>
												@endif
											@endforeach
										</tbody>
									</table>
								 </div> 
							</div>
						</div>
						@else
							<div class="panel panel-default">
								<div class="panel-body">
									Bạn chưa có giao dịch nào! 
								</div>
							</div>
						@endif
						@if (is_null($list_payes) || count($list_payes)==0)
						@else
						   <div class="panel panel-info">
								<div class="panel-heading">
									Lịch sử thanh toán
								</div>
								<div class="panel-body" style="padding:0px; ">
									<div id="no-more-tables">
										<table class="col-md-12 table-bordered table-striped table-condensed cf">
											<thead class="cf">
												<tr>
													<th class="numeric">Ngày</th>
													<th>Nội dung</th>
													<th class="numeric">Số tiền</th>
													<th class="numeric">Mã thanh toán</th> 
													<th>Trạng thái</th>
												</tr>
											</thead>
											<tbody>
												@foreach($list_payes as $list_pay)
													@if($list_pay->status=='received')
														<tr>
															<td data-title="Ngày" class="numeric"><?php echo date("d/m/Y", strtotime($list_pay->created_at)); ?></td>
															<td data-title="Nội dung">Thanh toán kênh <a href="//{{$list_pay->domain}}" target="_blank" class="text-primary">{{$list_pay->domain}}</a></td>
															<td data-title="Số tiền" class="numeric text-success"><?php echo Site::formatMoney($list_pay->amount,true) ?><span><?php echo config('app.vpc_Currency'); ?></span></td>
															<td data-title="Mã thanh toán" class="numeric">@if($list_pay->pay_hash >0){{$list_pay->pay_hash}}@else{{$list_pay->id}}@endif</td> 
															<td data-title="Trạng thái">Hoàn tất</td>
														</tr>
													@endif
													@if($list_pay->status=='sending')
														<tr>
															<td data-title="Ngày" class="numeric"><?php echo date("d/m/Y", strtotime($list_pay->created_at)); ?></td>
															<td data-title="Nội dung">Thanh toán kênh <a href="//{{$list_pay->domain}}" target="_blank" class="text-primary">{{$list_pay->domain}}</a></td>
															<td data-title="Số tiền" class="numeric text-success"><?php echo Site::formatMoney($list_pay->amount,true) ?><span><?php echo config('app.vpc_Currency'); ?></span></td>
															<td data-title="Mã thanh toán" class="numeric">@if($list_pay->pay_hash >0){{$list_pay->pay_hash}}@else{{$list_pay->id}}@endif</td>
															<td data-title="Trạng thái">Đang xử lý</td>
														</tr>
													@endif
												@endforeach
											</tbody>
										</table>
									 </div>  
								</div><!--panel-body-->
							</div><!--panel panel-primary-->
						@endif 
                    </div><!--post-fanpage-->
                </div>
            </div>
        </div>
    </div>
@endsection