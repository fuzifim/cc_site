@extends('inc.master')
@section('seo')
<?php 
	$data_seo = array(
		'title' => 'Mã giảm giá',
		'keywords' => 'mã giảm giá',
		'description' => 'Tạo mã giảm giá | Dành cho đại lý',
		'og_title' => 'Mã giảm giá',
		'og_description' => 'Tạo mã giảm giá | Dành cho đại lý',
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
<div id="modalVoucher" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tạo mã giảm giá</h4>
      </div>
      <div class="modal-body">
		<div class="add_message"></div>
        <div class="form-group">
			<label class="control-label" for="voucher_code">Mã giảm giá:</label>
			<input class="form-control" type="text" name="voucher_code" id="voucher_code" value="" disabled>
		</div>
		<div class="form-group">
			<label class="control-label" for="discount">Chiết khấu</label>
			<?
				$discounts=array(
					'10'=>'10%', 
					'20'=>'20%', 
					'30'=>'30%', 
					'40'=>'40%', 
					'50'=>'50%', 
				); 
			?>
			<select class="form-control" id="discount" name="discount">
				@foreach ($discounts as $key => $discount)
					<option value="{{$key}}" id="discount_{{$key}}">{{$discount}}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<div class="input-group stylish-input-group">
				<input type="text" class="form-control" name="member" id="member"  placeholder="Thành viên..." > 
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-search"></span> 
				</span>
			</div>
		</div>
		<div class="list-group" id="append-member"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveVoucher" data-id="0"><i class="glyphicon glyphicon-ok"></i> Lưu</button>
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
                            <li class="dropdown" itemprop="itemListElement">
                                 <a href="{{route('home')}}" itemprop="item"><span itemprop="name"><i class="glyphicon glyphicon-home"></i> <span class="hidden-xs">Trang chủ</span></span></a>
                            </li>
                            <li class="dropdown active" itemprop="itemListElement"><a data-toggle="dropdown" itemprop="item" href=""><span class="glyphicon glyphicon-cog"></span> Bảng điều khiển</a> <span class="caret"></span>
								@include('partials.menu_dropdown_dashboard')
							</li>
							<li itemprop="itemListElement">Mã giảm giá</li>
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
					<script type="text/javascript">
						jQuery(document).ready(function($){ 
							$('#post-fanpage').delegate('#create_voucher_user', 'click', function() { 
								$('#append-member').empty(); 
								$(".add_message" ).empty(); 
								$("#saveVoucher").attr('data-id','0');
								jQuery.ajax({
									url: "/create-voucher/",
									type: "GET",
									dataType: "json",
									success: function (data) {
										console.log(data);
										jQuery("#voucher_code").val(data.voucher); 
									}
								});
								
							}); 
							$('#post-fanpage').delegate('#view_voucher', 'click', function() {
								var idVoucher = $(this).attr('data-id'); 
								$('#append-member').empty(); 
								$(".add_message" ).empty(); 
								$("#saveVoucher").attr('data-id',idVoucher);
								jQuery.ajax({
									url: "/view-voucher/"+idVoucher,
									type: "GET",
									dataType: "json",
									success: function (result) {
										console.log(result);
										jQuery("#voucher_code").val(result.voucher); 
										jQuery('#discount option[value='+result.discount+']').attr('selected','selected'); 
										
										jQuery.each( result.users, function( key, value ) {
										  $('#append-member').append('<div class="list-group-item">'+ 
											'<button class="badge delete-member" id="delete-member" data-id="'+value.id+'"><i class="glyphicon glyphicon-remove" style="color:#fff;"></i></button>'+
											'<a href="'+url_root+'/user/'+value.id+'"><img class="avata-chanel" src="'+value.avata+'"> '+value.name+'</a>'+
											'<input type="hidden" name="memberId[]" value="'+value.id+'"></div>');	
										});
										
									}
								});
								
							});
							if($('#member').length>0){ 
								var url_root=$('#url_root').val();
								$('#member').autocomplete({
									serviceUrl: url_root+'/autocomplete/search_member',
									type:'GET',
									paramName:'txt',
									dataType:'json',
									minChars:2,
									onSearchComplete: function(){
										$(this).css('background', 'transparent');
									},
									//lookup: currencies,
									onSelect: function (suggestions) {
										$('#append-member').append('<div class="list-group-item">'+ 
										'<button class="badge delete-member" id="delete-member" data-id="'+suggestions.data+'"><i class="glyphicon glyphicon-remove" style="color:#fff;"></i></button>'+
										'<a href="'+url_root+'/user/'+suggestions.data+'"><img class="avata-chanel" src="'+suggestions.avata+'"> '+suggestions.value+'</a>'+
										'<input type="hidden" name="memberId[]" value="'+suggestions.data+'"></div>');	
										
									} 
								});
							}
							
						});
						
						$(document).on('click', '.delete-member', function() {
							$(this).parent().remove();
						});
						$(document).on('click', '#saveVoucher', function() {
							$(".add_message" ).empty(); 
							var id = $(this).attr('data-id'); 
							var formData = new FormData(); 
							var voucherCode=$('input[name=voucher_code]').val(); 
							var discount=$( "#discount option:selected" ).val();
							var member = $('input[name="memberId[]"]').map(function(){ 
								return this.value; 
							}).get();
							formData.append("voucherCode", voucherCode); 
							formData.append("discount", discount); 
							for(var i = 0;i<member.length;i++){
								formData.append("member[]", member[i]);
							}
							$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')} });
							$.ajax({
								url: "/save-voucher/"+id,
								type: 'post', 
								cache: false,
								contentType: false,
								processData: false,
								data: formData,
								dataType:'json',
								success: function (result) {
									if(result.success==true){
										 $('#modalVoucher').modal('toggle'); 
										 location.reload();
									}else{
										$(".add_message" ).append( '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.msg+'</div>');
									}
									console.log(result);
								}
							});
						});
						function destroy_code_review(id){
							jQuery.ajax({
									url: "/delete-voucher/"+id,
									type: "GET",
									dataType: 'json',
									beforeSend: function(){
										jQuery("#overlay_load").fadeIn("slow");
										jQuery(".lighbox_show").fadeIn("slow");
									},
									success: function (data) {
										if(data.msg == 'success'){
											jQuery("table.table_voucher").find('.tr_item'+id).remove();
											jQuery("#overlay_load").fadeOut("slow");
											jQuery(".lighbox_show").fadeOut("slow");
										}else{
											alert('Lỗi xóa dữ liệu!');
										}
									}
							});
						}
                    </script>
                    <div id="post-fanpage" class="clear voucher_container_details">
                        <div class="panel panel-primary">
                            <div class="panel-heading clear">
                                <span class="glyphicon glyphicon-star"></span> <b>Mã giảm giá</b>
								<button class="btn btn-xs btn-success pull-right" id="create_voucher_user" data-id="0"  data-toggle="modal" data-target="#modalVoucher"><span class="glyphicon glyphicon-pencil"></span> <b>Tạo mã</b></button>
                            </div>
                            <div class="panel-body panel-table" style="padding:0px; ">
								
								<div id="create_voucher_show" class="clear create_voucher_show"></div>
								<div id="no-more-tables" class="clear list_voucher_table">
									<table class="table_voucher col-md-12 table-bordered table-striped table-condensed cf">
										  <thead class="cf">
											<tr>
												<th>Mã giảm giá</th>
												<th>Ngày tạo</th>
												<th>Thành viên</th>
												<th>Chiết khấu</th>
												<th align="center">Tùy chọn</th>
											</tr> 
										  </thead>
										  @if(!empty($vouchers) && count($vouchers)>0)
											 @foreach($vouchers as $voucher)
												<? $members=WebService::getMemberJoinVoucher($voucher->id); ?>
											  <tr class="tr_item{{$voucher->id}}">
												<td data-title="Mã giảm giá"><input class="form-control" type="text" value="@if(!empty($voucher->string_voucher)){{$voucher->string_voucher}}@endif"></td>
												<td data-title="Ngày tạo">@if(!empty($voucher->date_create)) {{Site::Date($voucher->date_create)}} @endif</td>
												<td data-title="Thành viên">{{count($members)}} thành viên</td>
												<td data-title="Chiết khấu">{{$voucher->discount}}%</td>
												<td data-title="Tùy chọn">
													<button class="btn btn-xs btn-primary" id="view_voucher" data-id="{{$voucher->id}}" data-toggle="modal" data-target="#modalVoucher"><i class="glyphicon glyphicon-edit"></i> Xem</button>
													<button id="destroy_code" onClick="destroy_code_review({{$voucher->id}})" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> xóa</button> 
												</td>
											  </tr>
											  @endforeach
											@endif
										</table>
										@include('include.pagination', ['paginator' => $vouchers])
								</div><!--list_voucher_table-->
							</div><!--panel-body-->
                            <div class="panel-footer">
                                <div class="row">
                                    <div class="col-xs-12 col-md-12 col-lg-12 text-left">
                                        Tổng: <span class="text-danger">{{count($vouchers)}}</span> mã. 
                                    </div>
                                </div>
                            </div>
                        </div><!--panel-primary-->
                    </div><!--post-fanpage-->
                </div>
            </div>
        </div>
    </div>
@endsection