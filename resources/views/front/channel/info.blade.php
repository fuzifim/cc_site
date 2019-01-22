@extends('inc.master')
@section('seo')
<?php
	$data_seo = array(
		'title' => 'Thông tin kênh đã tạo',
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
			<div class="col-lg-8 col-md-8 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<strong>Thông tin mẫu</strong>
					</div>
					<div class="panel-body">
						<div class="message"></div> 
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-4">
								<img class="img-responsive img-thumbnail" src="{{$channel->media_url}}">
							</div>
							<div class="col-xs-12 col-sm-6 col-md-8" style="text-align: justify; padding:0px 20px; ">
								{!!htmlspecialchars_decode($channel->temp_content)!!}
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-xs-12">
				<div class="panel panel-primary">
					<div class="panel-heading"><strong>Thông tin kênh của bạn</strong></div>
					<div class="panel-body">
						<ul class="list-group">
							<li class="list-group-item"><strong>Địa chỉ kênh:</strong> <a href="//{{$channel->domain}}">{{$channel->domain}}</a></li>
							<li class="list-group-item"><strong>Tên kênh:</strong> {{$channel->channel_name}}</li>
							<li class="list-group-item"><strong>Khu vực:</strong> {{$channel->subregions_name}}</li>
							<li class="list-group-item"><strong>Ngày đăng ký:</strong> {!!Site::Date($channel->channel_created_at)!!}</li>
							<li class="list-group-item"><strong>Tình trạng:</strong> @if($channel->channel_status=='billing')Chưa thanh toán @elseif($channel->channel_status=='active') Đang sử dụng @endif</li>
							@if($channel->channel_status=='active')
								<li class="list-group-item"><strong>Hạn sử dụng:</strong> {!!Site::Date($channel->channel_date_end)!!}</li>
							@endif
						</ul>
						@if($channel->channel_status=='billing')
						<div class="box-price-chanel">
							<div class="price-channel">880.000đ/ 6 tháng</div>
							<p style="text-align:center; ">Đăng ký sử dụng thử miễn phí 30 ngày</p>
						</div>
						@endif
					</div>
					<div class="panel-footer text-right">
						@if($channel->channel_status=='billing')
						<a class="btn btn-default" href="{{route('front.channel.add')}}"><i class="glyphicon glyphicon-ok"></i> Sử dụng thử</a>
						<button class="btn btn-primary" type="button" data-id="{{$channel->theme_id}}" id="addTheme"><i class="glyphicon glyphicon-ok"></i> Thanh toán</button>
						@elseif($channel->channel_status=='active')
							<a class="btn btn-default" href="#"><i class="glyphicon glyphicon-cog"></i> Cài đặt</a>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
<script>
	$( "#addTheme").click(function() {
		$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')} }); 
		var formData = new FormData();
		formData.append("themeId",$( this ).attr('data-id')); 
		$.ajax({
			url: "{{route('front.theme.join')}}",
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			success:function(result){
				if(result.success==true){
					console.log(result);
				}
			},
			error: function(result) {
			}
		});
	});
</script>
@endsection