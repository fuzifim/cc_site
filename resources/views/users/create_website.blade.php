@extends('inc.master')
@section('seo')
<?php
	$data_seo = array(
		'title' => 'Tạo kênh Cung Cấp | '.config('app.seo_title'),
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
                        {!! Breadcrumbs::render('user.createweb') !!}
                    </div>
                </div>
            </div>
            <div class="row no-gutter">
                <div class="col-lg-8 col-md-8 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="clear error_create_view">
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
                            </div><!--error_create_view-->
                            <form class="" action="{{ route('user.updatecreateweb') }}" method="post">
                                 <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="">Địa chỉ kênh <span class="text-danger">(*)</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon" style="font-weight:bold;">http://</span>
                                                <input required="required" class="form-control" placeholder="Nhập địa chỉ kênh của bạn" name="tem_domain" id="tem_domain" value="{{old('tem_domain')}}" type="text">
                                                <div class = "input-group-btn">
                                                  <button type = "button" class = "btn btn-default" id="showDomainChose" tabindex = "-1" style="font-weight:bold; color:#de1a42; ">
                                                     .dakenh.net
                                                  </button>
                                               </div><!-- /btn-group -->
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label" for="tem_name">Tên kênh <span class="text-danger">(*)</span></label>
                                            <input required="required" class="form-control" placeholder="Nhập tên kênh của bạn" name="tem_name" id="tem_name" value="{{old('tem_name')}}" type="text" maxlength="50">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="tem_description">Giới thiệu kênh</label>
                                            <textarea required="required" class="form-control" placeholder="Nhập nội dung giới thiệu kênh của bạn" name="tem_description" id="tem_description">{{old('tem_description')}}</textarea>
                                        </div>

										<div class="form-group mutiselected_group clear">
												<label class="control-label" for="tem_name">Lĩnh vực hoạt động: <span class="text-danger">(*)</span></label>
												<div class="select_muti_template_cate clear">
												   <select ng-model="[]" id="template_cate_id_add" name="template_cate_id[]" class="form-control"  multiple="multiple">
														@if (count($user_categories) > 0)
														   {!! WebService::showMenuCategory($user_categories,0,0,"") !!}
														@endif;
													</select>
												</div>
										</div>
										
											<script type="text/javascript">
												jQuery(document).ready(function($) {
													 $('#template_cate_id_add').multiselect({
														enableClickableOptGroups: true,
														enableCollapsibleOptGroups: true,
														enableFiltering: true,
														includeSelectAllOption: true,
														maxHeight: 200
													 })
												 }); 
											 </script>							
										
                                        <div class="form-group">
                                            <label class="control-label" for="tem_description">Chọn Quốc gia <span class="text-danger">(*)</span></label>
                                            <select name="region_select" id="regoin_profile" class="form-control">
													<option value="0">Lựa chọn</option>
													{!!WebService::getRegionByCode()!!}
                                            </select>
                                        </div><!--form-group-->

                                        <div class="form-group">
                                             <label class="control-label" for="tem_description">Chọn Thành Phố <span class="text-danger">(*)</span></label>
                                             <select name="subregion_select" id="subregoin_profile" class="form-control">
                                                  <option value="">Lựa chọn</option>
												  {!!WebService::getSubregionbyID()!!}
                                             </select>
                                        </div><!--form-group-->

                                    </div>
                                    <!--<div class="col-md-4">
                                        <div class="well">
                                            <h4>Chọn thời gian sử dụng</h4>
                                            <div class="funkyradio">
                                                <div class="funkyradio-success">
                                                    <input type="radio" name="package" value="1" id="radio1" checked/>
                                                    <label for="radio1">600.000đ/ 1 năm</label>
                                                </div>
                                                <div class="funkyradio-success">
                                                    <input type="radio" name="package" value="2" id="radio2"/>
                                                    <label for="radio2">1.200.000đ/ 2 năm</label>
                                                </div>
                                                <div class="funkyradio-success">
                                                    <input type="radio" name="package" value="3" id="radio3" />
                                                    <label for="radio3">1.800.000đ/ 3 năm</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-primary" name="btnSave"><i class="glyphicon glyphicon-send"></i> Tiếp theo</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <h3>Đa kênh là gì</h3> 
                            <p style="text-align: justify;">Là kênh quảng cáo thông tin trực tuyến được đăng ký tại DaKenh.Net, bạn có thể đăng tải bất kỳ thông tin, hình ảnh, video, hay nội dung giới thiệu về doanh nghiệp, sản phẩm, dịch vụ nhằm phổ biến thông tin đến mọi người biết đến thông qua kênh của bạn.</p>
                            <p style="text-align: justify;">Người xem có thể tìm kiếm thông tin mà bạn đã đăng tải dễ dàng thông qua kênh của bạn và trên các công cụ tìm kiếm hay mạng xã hội như Google, Faccebook, Youtube...</p>
                            <hr>
                            <div class="box-price-chanel">
                                <div class="price-channel">880.000đ/ 1 năm</div>
                                <p style="text-align:center; ">Cho mỗi kênh quảng cáo.  Đăng ký và thanh toán trực tuyến</p>
                            </div>
                            <hr>
                            <ul>
                                <li>Có địa chỉ tên miền riêng cho kênh của bạn</li>
                                <li>Không giới hạn nội dung đăng tải</li>
                                <li>Nội dung được hiển thị ngay trên DaKenh.Net và trên các trang mạng xã hội. </li>  
                                <li>Video đươc hiển thị trên DaKenh.Net, Youtube, Facebook...</li>
                                <li>Quản lý và xem dễ dàng bất kỳ đâu thông qua điện thoại hoặc máy tính. </li> 
                                <li>Tích hợp các công cụ chia sẻ lên các kênh mạng xã hội như Facebook, Google+, Twitter, Linkedin...</li>
                                <li>Thống kê lượt xem, thích, bình luận trên các nội dung mà bạn đăng tải</li>
								<li>Và còn nhiều hơn nữa... </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--MutiSelect-->
<script src="{{asset('js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
<link type="text/css" href="{{asset('css/bootstrap-multiselect.css')}}" rel="stylesheet"/>
<!--End MutiSelect-->
@endsection