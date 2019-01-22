@extends('inc.master')
@section('seo')
<?php 
	$data_seo = array(
		'title' => 'Danh sách thành viên',
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
<div id="wrapper">
    <div id="sidebar-wrapper" class="menu_home margin_top_10 clear">
        @include('partials.user_sidebar_new')
    </div><!--sidebar-wrapper-->
    <div id="page-content-wrapper" class="page_content_primary clear">
        <div class="container-fluid entry_container xyz">
            <div class="row no-gutter mrb-5 country_option">
                <div class="col-lg-12">
                    <div class="breadcrumbs_inc clear">
                        <ol class="breadcrumb">
                            <li class="dropdown active"><a data-toggle="dropdown" itemprop="item" href=""><span class="glyphicon glyphicon-cog"></span> Bảng điều khiển</a> <span class="caret"></span>
								@include('partials.menu_dropdown_dashboard')
							</li>
                            <li class="active">Danh sách thành viên</li>
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
						<div class="panel panel-info">
							<div class="panel-heading">
								Danh sách thành viên
							</div>
							<div class="panel-body" style="padding:0px; ">
								<div id="no-more-tables">
									<table class="col-md-12 table-bordered table-striped table-condensed cf">
										<thead class="cf">
											<tr>
												<th>ID</th>
												<th>Tên</th>
												<th>Email</th>
												<th>Điện thoại</th>
												<th>Địa chỉ</th> 
												<th>Trạng thái</th>
											</tr>
										</thead>
										<tbody>
											@foreach($users as $user)
												<tr>
													<td data-title="ID">{{$user->id}}</td>
													<td data-title="Tên">{{$user->name}}</td>
													<td data-title="Email">{{$user->email}}</td>
													<td data-title="Điện thoại">{{$user->user_phone}}</td>
													<td data-title="Địa chỉ">{{$user->shop_address}}</td> 
													<td data-title="Trạng thái">{{$user->user_status}}</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								 </div>  
							</div><!--panel-body-->
							<div class="panel-footer">
								@include('include.pagination', ['paginator' => $users])
							</div>
						</div><!--panel panel-primary-->
					</div><!--post-fanpage-->
                </div>
            </div>
        </div>
    </div>
</div>	
 
@endsection