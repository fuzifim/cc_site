@extends('inc.master')
@section('seo')
<?php 
	$data_seo = array(
		'title' => 'Bảng điều khiển',
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
    <div id="page-content-wrapper" class="">
		<div class="breadcrumbs_inc clear">
			<ol class="breadcrumb">
				<li><a href="{{url('/')}}"><i class="glyphicon glyphicon-home"></i> Trang chủ</a></li>
				<li class="dropdown active"><a data-toggle="dropdown" itemprop="item" href=""><span class="glyphicon glyphicon-cog"></span> Bảng điều khiển</a> <span class="caret"></span>
					@include('partials.menu_dropdown_dashboard')
				</li>
			</ol>
		</div>
        <div class="container">
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
						@foreach($templates as $template)
							@if($template->date_end < \Carbon\Carbon::now()->format('Y-m-d H:i:s'))
								<div class="alert alert-danger">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<strong>Thông báo!</strong>
									Kênh <a href="//{{$template->domain}}">{{$template->domain}}</a> đã hết hạn do chưa thanh toán. Vui lòng thanh toán để tiếp tục sử dụng! 
									<p>
										<a  class="btn btn-xs btn-danger" data-href="{{$template->id}}" href="{{route('front.ads.thanhtoan',$template->id)}}"><i class=" fa fa-lock"></i> Thanh toán</a>
										<a class="btn btn-xs btn-primary" href="{{route('user.templateType.setting',array($template->id,'general'))}}"><i class="glyphicon glyphicon-cog"></i> Cài đặt</a>
									<p>
								</div>
							@endif
						@endforeach
					</div>
                </div>
            </div>
        </div>
    </div>
@endsection