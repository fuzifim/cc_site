@extends('inc.master')
@section('seo')
<?php
	$data_seo = array(
		'title' => 'Tạo kênh',
		'keywords' => '',
		'description' => '',
		'og_type' => '',
		'og_site_name' => '',
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
	<div class="container-fluid xyz">
		<div class="row no-gutter">
			<div class="col-lg-9 col-md-9 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<strong>Thông tin kênh</strong>
					</div>
					<div class="panel-body">
						<div class="message"></div>
						<form class="" action="{{ route('front.channel.save') }}" method="post">
							 <input type="hidden" name="_token" value="{{ csrf_token() }}" >
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label" for="">Địa chỉ kênh <span class="text-danger">(*)</span></label>
										<div class="input-group">
											<input required="required" class="form-control" placeholder="Nhập địa chỉ kênh của bạn" name="tem_domain" id="tem_domain" value="@if(isset($getCart)){{$getCart->attributes->channelDomain}}@endif" type="text">
											<div class = "input-group-btn">
											  <button type = "button" class = "btn btn-default" id="showDomainChose" tabindex = "-1" style="font-weight:bold; color:#de1a42; ">
												 .{{config('app.url')}}
											  </button>
										   </div><!-- /btn-group -->
										</div>
									</div>

									<div class="form-group">
										<label class="control-label" for="tem_name">Tên kênh <span class="text-danger">(*)</span></label>
										<input required="required" class="form-control" placeholder="Nhập tên kênh của bạn" name="tem_name" id="tem_name" value="@if(isset($getCart)){{$getCart->attributes->channelName}}@endif" type="text" maxlength="50">
									</div>
									<div class="form-group">
										<label class="control-label" for="tem_description">Giới thiệu kênh</label>
										<div id="textChannelAbout" style="font-size:14px; " contentEditable="true" placeholder="Mô tả kênh của bạn...">@if(isset($getCart)){{$getCart->attributes->channelAbout}}@endif</div>
										<input id="inputChannelAbout" name="tem_description" value="@if(isset($getCart)){{$getCart->attributes->channelAbout}}@endif" type="hidden" class="form-control title-post-edit" placeholder="Mô tả kênh của bạn">
										<script>
											$('#textChannelAbout').keyup(function () {
												 $('#inputChannelAbout').val($(this).text());
											 });
										</script>
									</div>

									<div class="form-group mutiselected_group clear">
											<label class="control-label" for="tem_name">Lĩnh vực hoạt động: <span class="text-danger">(*)</span></label>
											<div class="select_muti_template_cate clear">
											   <select ng-model="[]" id="fields_id" name="fields_id[]" class="form-control"  multiple="multiple">
													@if (count($Fields) > 0)
													   {!! WebService::showMenuFields($Fields,0,0,"") !!}
													@endif;
												</select>
											</div>
									</div>
									
										<script type="text/javascript">
											jQuery(document).ready(function($) {
												 $('#fields_id').multiselect({
													enableClickableOptGroups: true,
													enableCollapsibleOptGroups: true,
													enableFiltering: true,
													includeSelectAllOption: true,
													maxHeight: 200
												 })
											 }); 
										 </script>							
									
									<div class="form-group">
										<label class="control-label" for="regoin_profile">Chọn Quốc gia <span class="text-danger">(*)</span></label>
										<select name="region_select" id="regoin_profile" class="form-control">
												<option value="0">Lựa chọn</option>
										</select>
									</div><!--form-group-->

									<div class="form-group">
										 <label class="control-label" for="subregoin_profile">Chọn Thành Phố <span class="text-danger">(*)</span></label>
										 <select name="subregion_select" id="subregoin_profile" class="form-control">
											  <option value="">Lựa chọn</option>
										 </select>
									</div><!--form-group-->

								</div>
							</div>
						</form>
						<div class="box-price-chanel">
							<div class="price-channel">880.000đ/ 6 tháng</div>
							<p style="text-align:center; ">Cho mỗi kênh quảng cáo.  Đăng ký và thanh toán trực tuyến</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong>Giao diện đã chọn</strong></div>
					<div class="panel-body">
						<div class="form-group text-center">
							<img class="img-responsive img-thumbnail" src="{{$theme->media_url}}">
						</div>
					</div>
					<div class="panel-footer text-right">
						<button type="button" id="channel-add" class="btn btn-primary" name="btnSave"><i class="glyphicon glyphicon-send"></i> Tiếp theo</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
<!--MutiSelect-->
<script src="{{asset('js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
<link type="text/css" href="{{asset('css/bootstrap-multiselect.css')}}" rel="stylesheet"/>
<!--End MutiSelect-->

<script>
	$( "#channel-add").click(function() {
		$('.message').empty(); 
		$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')} }); 
		var formData = new FormData();
		formData.append("channelTheme", {{$theme->theme_id}}); 
		formData.append("channelDomain", $('input[name=tem_domain]').val()); 
		formData.append("channelName", $('input[name=tem_name]').val()); 
		formData.append("channelAbout", $('input[name=tem_description]').val()); 
		formData.append("channelFields", $( "select[name='fields_id[]']" ).val()); 
		formData.append("channelRegion", $('select[name=region_select]').val()); 
		formData.append("channelSubRegion", $('select[name=subregion_select]').val()); 
		$.ajax({
			url: "{{route('front.channel.save')}}",
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			success:function(result){
				console.log(result); 
				if(result.success==true){
					window.location.href=result.url_redirect;
				}else if(result.success==false){
					if(result.messageType=='validation'){
						$('.message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>'); 
						var res = jQuery.parseJSON(JSON.stringify(result.message)); 
						var name;
						jQuery.each(res, function(i, val) {
								if(i=='channelDomain'){
									name='Địa chỉ kênh:';
								}else if(i=='channelName'){
									name='Tên kênh';
								}else if(i=='channelAbout'){
									name='Mô tả kênh kênh:';
								}else if(i=='channelSubRegion'){
									name='Chọn thành phố:';
								}else{
									name='';
								}
								$('#alertError').append('<li><b>'+name+'</b> '+val+'</li>');
						});
					}
					else if(result.messageType=='errorDomain'){
						$('.message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					}
				}
			},
			error: function(result) {
			}
		});
	});
	$('select#regoin_profile').change(function () {
        var country=$(this).val();
        $.ajax({
            url: "{{route('home')}}/regions_ajax/"+country,
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('select#subregoin_profile').removeAttr('disabled');
                $("select#subregoin_profile").html(data);
            }
        });
    });
    $('select#subregoin_profile').change(function () {
        var country=$(this).val();
        $.ajax({
            url: "{{route('home')}}/subregions_ajax/"+country,
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('select#district_profile').removeAttr('disabled');
                $("select#district_profile").html(data);
            }
        });
    });
</script>
@endsection