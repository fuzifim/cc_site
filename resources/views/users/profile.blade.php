@extends('inc.master')
@section('seo')
<?php 
	$data_seo = array(
		'title' => 'Cập nhật hồ sơ',
		'keywords' => config('app.keywords_default'),
		'description' => config('app.description_default'),
		'og_title' => '',
		'og_description' => config('app.description_default'),
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
                        <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                            <li class="dropdown hidden-xs" itemprop="itemListElement">
                                 <a href="{{route('home')}}" itemprop="item"><span itemprop="name"><i class="glyphicon glyphicon-home"></i> <span class="hidden-xs">Trang chủ</span></span></a>
                            </li>
							<li class="dropdown active" itemprop="itemListElement"><a data-toggle="dropdown" itemprop="item" href=""><span class="glyphicon glyphicon-cog"></span> Bảng điều khiển</a> <span class="caret"></span>
								@include('partials.menu_dropdown_dashboard')
							</li>
                            <li class="dropdown" itemprop="itemListElement">Hồ sơ</li>
						</ol>
                    </div>
                </div>
            </div>
            <div class="row no-gutter">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    
                    <div class="panel">
                        <div class="panel-body">
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
						
                           <form id="update-profile" action="{{ route('user.post.profile',$user->id) }}" method="post" accept-charset="utf-8" role="form" class="">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
								
								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label class="control-label" for="name">Ảnh Thành Viên <span class="text-danger">(*)</span></label>
											<div class="user_profile_content">
												<div id="crop-avatar">
														<div id="user-avata" class="avatar-view">
																@if (Auth::user()->avata != "")
																	{!! HTML::image( Auth::user()->avata,'',array('class'=>'img-responsive img-thumbnail','id'=>'user-avatar')) !!}
																@else
																	 {!! HTML::image('img/avata.png')!!}
																@endif
																<span class="avatar-change"><i class="fa fa-image"></i> Đổi ảnh</span>
														</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-10">
										<div class="form-group">
											<label class="control-label" for="name">Tên thành viên<span class="text-danger">(*)</span></label>
											<div class="input-group">
												<input readonly="" placeholder="" id="name" name="name" value="{{ $user->name }}" type="text" class="form-control">
												<input type="hidden" value="{{ $user->name }}" name="old_name" id="old-name">
												<div class="input-group-btn">
													<button id="accept-name" name="accept_name" type="button" onclick="$('#name').removeAttr('readonly')" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Sửa tên</button>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label" for="gender">Giới tính</label>
												<select name="gender" id="gender" class="form-control">
													
													<option @if( $user->gender == "Nam") selected @endif value="Nam">Nam</option>
													<option value="Nữ" @if( $user->gender == "Nữ") selected @endif>Nữ</option>
													<option  value="Khác" @if( $user->gender == "Khác") selected @endif >Khác</option>
												</select>
										</div>

										<div class="form-group">
											<label class="control-label" for="birthday">Ngày sinh</label>
											<input placeholder="" id="birthday"  data-language='en' name="birthday" value="{{ date('d-m-Y',strtotime($user->birthday)) }}" type="text" class="datepicker-here form-control">
										</div>
										<div class="form-group">
											<label class="control-label" for="email">Email<span class="text-danger">(*)</span></label>
											<input placeholder="" id="email" name="email" value="{{$user->email}}" type="text" class="form-control">
										</div>

										<div class="form-group">
											<label class="control-label" for="phone">Số điện thoại</label>
											<input placeholder="" data-toggle="tooltip" data-placement="right" title="Ví dụ: 0902181852" id="phone" name="user_phone" value="{{ $user->user_phone }}" type="text" class="form-control">
										 </div>
										
										<div class="form-group">
											<label class="control-label" for="phone">Nơi sống</label>
											<input placeholder="" id="shop-address" name="shop_address" value="{{ $user->shop_address }}" type="text" class="form-control">
										</div>
									</div>
								</div>
                                <!--<div class="form-group">
                                    <label class="col-sm-3 control-label" for="shop-banner">Banner/website <span class="text-danger">(*)</span></label>
                                    <div class="col-sm-9">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="fa fa-upload"></i>
                                            <span>Chọn hình(Kích thước 1030x315)</span>
                                            <!-- The file input field used as target for the file upload widget -->
                                            <!--<input id="fileupload" type="file" name="file">
                                        </span>
                                    
                                        <div id="dropbox" class="">
                                            @if ($user->shop_banner != '')
                                            <div class="col-md-12 preview">
                                                <img class="img-responsive img-thumbnail" src="{{ $user->shop_banner }}" />
                                                <div class="item-action">
                                                    <a href="javascript:void(0)" onclick="removebanner()" class="pull-right">
                                                    <i class="fa fa-trash-o"></i></a>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <label for="" class="text-danger" id="upload-msg"></label>
                                        <div id="progress" class="progress">
                                            <div class="progress-bar progress-bar-success"></div>
                                        </div>
                                        
                                    </div>
                                    
                                </div>-->
                            <!--
                              <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p class="control-left-p">Địa chỉ</p>
                                                <input type="text" name="diachi" class="form-control">
                                            </div>
                                            <div class="col-sm-4">
                                                <p class="control-left-p">Phường/Xã</p>
                                                <input type="text" name="phuong" class="form-control">
                                            </div>
                                            <div class="col-sm-4">
                                                 <p class="control-left-p">Quận/Huyện</p>
                                                 <input type="text" name="quan" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                               </div>

                              <div class="form-group">
                                  <div class="col-sm-12">
                                     <div class="row">
                                         <div class="col-sm-4">
                                               <p class="control-left-p">Quốc Gia</p>
                                               <select id="regoin_profile" class="form-control">
                                                    <option value="0">Lựa chọn</option>
                                                    {!! WebService::tree_option_data($data_region,"country") !!}
                                               </select>
                                         </div>
                                         <div class="col-sm-4">
                                              <p class="control-left-p">Tỉnh/Thành Phố</p>
                                              <select id="subregoin_profile" class="form-control">
                                                     <option value="">Lựa chọn</option>
                                              </select>
                                         </div>
                                     </div>
                                  </div>
                              </div>
                               
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="shop-address">Địa chỉ shop</label>
                                    <div class="col-sm-9">
                                         <div class="row">
                                            <div class="col-sm-9">
                                                <input data-tooltip="Nhập địa chỉ của bạn" id="shop-address" type="text" name="shop_address" value="{{ $user->shop_address }}" class="form-control ">
                                            </div>
                                            <div class="col-sm-3">
                                                
                                                <input type="button" class="btn btn-default" value="Tìm địa chỉ" onclick="codeAddress()">
                                            </div>
                                         </div>
                                        
                                            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBi9KZruuoyo3xx6w30iy2IV2qk-JpmlD4"></script>
                                            <p class="examp_address"><span>Ví dụ: 75 Giải Phóng, Phường Đồng Tâm, Quận Hai Bà Trưng, Hà Nội</span></p>
                                            <p class="examp_long">
                                                Tọa độ: <span class="text-alert">Di chuyển mũi tên trong bản đồ đến đúng địa điểm</span>
                                                <input type="hidden" id="lat" name="shop_lat"  value="{{ $user->shop_lat }}" >
                                                <input type="hidden" id="lng" name="shop_long"  value="{{ $user->shop_long }}" >
                                            </p>
                                            <div id="map-canvas" style="height:300px;"></div>
                        
                                    </div>
                                </div>
            -->
                                
                                   
                                <!-- end panel -->
                            
                                <div class="form-group">
                                    <button class="btn btn-primary pull-right" type="submit" name="send">Lưu 
										<i class="fa fa-send"></i>
									</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<link rel="stylesheet" href="{{ url('/datepicker/datepicker.css') }}">
<script src="{{ url('/datepicker/datepicker.js') }}"></script>
<script src="{{ url('/datepicker/date_picker_en.js') }}"></script>

<link rel="stylesheet" href="{{ url('lib/jQuery-File-Upload/styles.css') }}">
<script src="{{ url('lib/jQuery-File-Upload/js/vendor/jquery.ui.widget.js') }}"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="{{ url('lib/jQuery-File-Upload/js/jquery.iframe-transport.js') }} "></script>
<!-- The basic File Upload plugin -->
<script src="{{ url('lib/jQuery-File-Upload/js/jquery.fileupload.js') }}"></script>
<script>
	
$(function () {
    'use strict';
    // Change this to the location of your server-side upload handler:
    $('#fileupload').fileupload({
        url: '/file/uploadbanner',
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        //singleFileUploads: false,
        //limitMultiFileUploads: 5,
        dropZone: '#dropbox',
        formData: { 
            '_token': $('meta[name=_token]').attr('content')
        },
        add: function(e, data){
            $('.message','#dropbox').remove();
            data.submit();
        },

        done: function (e, data) {
            console.log(data);
            $('label#upload-msg').text('');
            if(!data.result.success){
                $('label#upload-msg').text(data.result.msg);
                return;
            }else{
                var preview = $(template);
                $('img', preview)
                    .attr('src',data.result.file_url)
                    .addClass('img-responsive img-thumbnail');
                $('a',preview).attr('onclick','removebanner()');   
                $('#dropbox').html(preview);
                $('label#upload-msg').text(data.result.msg);
            }
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});

function removebanner(){
	$.ajax({
		url:'/file/removebanner',
		dataType :'json',
		success: function(data){
			if(data.success){
				$('#dropbox').html('');
				$('label#upload-msg').text(data.msg);
			}
		}
	});

};

var template = '<div class="col-md-12 preview">'+
                    '<img />'+
                    '<div class="item-action">'+
                        '<a href="javascript:void(0)" class="pull-right"><i class="fa fa-trash-o"></a>'+
                    '</div>'+
                '</div>';
</script>

<script>
	// maps
    var geocoder;
    var map;
    var currentMarker;
    function initMap() {
        geocoder = new google.maps.Geocoder();
        var mapOptions = {
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: new google.maps.LatLng(<?php echo ($user->shop_lat != '') ? $user->shop_lat.','.$user->shop_long : '21.0025609,105.841135' ?>)
        };

        map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);

        currentMarker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(<?php echo ($user->shop_lat != '') ? $user->shop_lat.','.$user->shop_long : '21.0025609,105.841135' ?>),
            //icon: {
            //  path: google.maps.SymbolPath.CIRCLE,
            //  scale: 10
            //},
            draggable: true,
        });
         google.maps.event.addListener(currentMarker, 'drag', function(event) {
            document.getElementById('lat').value = event.latLng.lat();
            document.getElementById('lng').value = event.latLng.lng();
        });

        google.maps.event.addListener(currentMarker, 'dragend', function(event) {
            document.getElementById('lat').value = event.latLng.lat();
            document.getElementById('lng').value = event.latLng.lng();
        });
    } //end init

    function codeAddress() {
        var address = document.getElementById('shop-address').value;
        currentMarker.setMap(null);
        //markerNewPosition.setMap(null);
        geocoder.geocode({
            'address': address
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
            	console.log(results);
                document.getElementById('lat').value = results[0].geometry.location.lat();
                document.getElementById('lng').value = results[0].geometry.location.lng();

                //console.log(JSON.stringify(results));
                map.setCenter(results[0].geometry.location);
                currentMarker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    //icon: {
                    //  path: google.maps.SymbolPath.CIRCLE,
                    //  scale: 10
                    //},
                    draggable: true,
                });

                google.maps.event.addListener(currentMarker, 'drag', function(event) {
                    document.getElementById('lat').value = event.latLng.lat();
                    document.getElementById('lng').value = event.latLng.lng();
                });

                google.maps.event.addListener(currentMarker, 'dragend', function(event) {
                    document.getElementById('lat').value = event.latLng.lat();
                    document.getElementById('lng').value = event.latLng.lng();
                });
            } else {
                alert('Có lỗi xảy ra không thể xác định địa điểm !');
            }
        });
    }

     google.maps.event.addDomListener(window, 'load', initMap);
</script>

@endsection