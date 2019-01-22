@extends('inc.master')
@section('seo')
<?php 
	$data_seo = array(
		'title' => 'Cài đặt Kênh',
		'keywords' => config('app.keywords_default'),
		'description' => config('app.description_default'),
		'og_title' => 'Cài đặt Kênh',
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
<div id="wrapper" class="manage_post_ads">
    <div id="sidebar-wrapper" class="menu_home clear margin_top_10">
        @include('partials.user_sidebar_new')
    </div><!--sidebar-wrapper-->
    <div id="page-content-wrapper" class="page_content_primary clear ">
        <div class="container-fluid entry_container xyz">
            <div class="row no-gutter mrb-5 country_option">
                <div class="col-lg-12">
                    <div class="breadcrumbs_inc clear">
						<ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                            <li class="dropdown" itemprop="itemListElement">
                                 <a data-toggle="dropdown" href="" itemprop="item"><span itemprop="name"><i class="glyphicon glyphicon-home"></i> Trang chủ</span></a>
                            </li>
                            <li class="dropdown" itemprop="itemListElement">
								<a data-toggle="dropdown" href="" itemprop="item"><span itemprop="name"><i class="glyphicon glyphicon-cog"></i> Cài đặt thông tin kênh</span></a>
								<span class="caret"></span>
								<ul class="dropdown-menu">
									<li><a href="">
										<i class="glyphicon glyphicon-picture"></i> Hình ảnh</a>
									</li>
									<li><a href="">
										<i class="glyphicon glyphicon-map-marker"></i> Thông tin liên hệ</a>
									</li>
									<li><a href="">
										<i class="glyphicon glyphicon-share"></i> Mạng xã hội</a>
									</li>
								</ul>
                            </li>
						</ol>
                    </div>
                </div>
            </div>
            <div class="theme_setting_user clear" >
                <div class="row no-gutter">
                    <div id="post-fanpage" class="col-lg-12 col-md-12 col-xs-12">
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
                            <div id="post-form-container" class="col-sm-12 col-md-12 col-lg-12 template_setting_website">
                              <form id="post-about-form" action="{{ route('user.template.setting',$temp_set->user_id)}}" method="post" accept-charset="utf-8" role="form" class="form-horizontal theme_setting_form" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                     <input type="hidden" name="id_template" value="{{$id_template}}" >
                                    <div class="clear">
                                          <!-- Nav tabs -->
                                            <ul id="config" class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#site-config" aria-controls="site-config" role="tab" data-toggle="tab">Cài đặt kênh</a></li>
                                                <li role="presentation"><a href="#design" aria-controls="design" role="tab" data-toggle="tab">Hình ảnh</a></li>
                                                <li role="presentation"><a href="#address_template" aria-controls="address" role="tab" data-toggle="tab">Thông tin liên hệ</a></li>
                                                <li role="presentation"><a href="#seo" aria-controls="seo" role="tab" data-toggle="tab">Mạng xã hội</a></li>

                                            </ul>
                                      <!-- Tab panes -->
                                      <div class="tab-content">
                                        <p></p>

                                        <div role="tabpanel" class="tab-pane fade in" id="address_template">
                                         <div class="alert alert-danger f13">
                                           <strong>Thông báo !</strong> Vui lòng nhập đầy đủ thông tin để phục vụ cho việc tìm kiếm tốt hơn. 
                                         </div>

                                         <!--Content -->

                                        <div class="website_info_company clear" style="display: block;">
                                           <div class="group_container_contact_view clear">
                                                  @include('include.company_info')
                                           </div><!--group_container_contact_view-->
                                        </div>
                                         <!--End content-->

                                         </div><!--address_template-->
                                        <div role="tabpanel" class="tab-pane fade in active" id="site-config">
                                            
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="text-area">Tên kênh: <span class="text-danger">(*)</span> </label>
                                                <div class="col-sm-9">
                                                       <input type="text" placeholder="Tên kênh website" data-toggle="tooltip" data-placement="top" title="Ví dụ:Chuyên cung cấp các thiết bị điện tử" class="form-control" name="blog_title" value="@if($temp_set->title == '')Blog @else{!!$temp_set->title!!}@endif"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="phone">Địa chỉ kênh: </label>
                                                    <div class="col-sm-9">
                                                        <a class="pd_5_website" href="{{$template_setting->domain}}"><i class="menu-icon fa fa-globe"></i> {{$template_setting->domain}}</a>
                                                    </div>
                                            </div>
                                            <div class="form-group mutiselected_group">
												<label class="col-md-3 control-label" for="ads-cate">Lĩnh vực hoạt động: <span class="text-danger">(*)</span></label>
												<div class="col-sm-9 select_muti_template_cate">
												   <select ng-model="[]" id="template_cate_id_add" name="template_cate_id[]" class="form-control"  multiple="multiple">
														@if (count($user_categories) > 0)
														   {!! WebService::showMenuCategory($user_categories,$template_join_category,0,"") !!}
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
                                                <label class="col-sm-3 control-label" for="phone">Giới thiệu kênh:</label>
                                                <div class="col-sm-9">
                                                    <textarea name="description"  s="20" rows="5" class="form-control">{{$temp_set->description}}</textarea>
                                                </div>
                                             </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="phone">Từ khóa: </label>
                                                <div class="col-sm-9">
                                                    <input name="keywords"  s="20" rows="5" class="form-control" value="{{$temp_set->keyword}}">
                                                </div>
                                             </div>
                                        </div><!--- end site config -->
                                        <div role="tabpanel" class="tab-pane fade" id="design">
                                            <div class="form-group">
                                                <div id="crop-logo-web">
                                                    <label class="col-sm-3 control-label" for="item-title">Ảnh đại diện: </label>
                                                    <div class="avatar-view-image col-sm-8 col-md-8 col-lg-8" id="user-logo" data-original-title="" title="">
                                                        @if($temp_set->logo !='')
                                                            <img id="user-logo-image" class="avanta_defaul img-responsive img-thumbnail" src="{{ asset($temp_set->logo) }}" alt="logo-website"  height="150" width="150"/>
                                                        @else
                                                            <img height="150" width="150" alt="logo-website" id="user-logo-image" class="avanta_defaul img-responsive img-thumbnail" src="http://placehold.it/300x300&amp;text=LOGO-WEBSITE">
                                                        @endif
                                                        <span class="avatar-change"><i class="fa fa-image"></i> Chọn ảnh</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                   <label class="col-sm-3 control-label" for="">Ảnh bìa <span class="text-danger">(*)</span></label>
                                                   <div class="col-sm-9">
                                                      @include('include.upload-template-setting')
                                                  </div>
                                            </div>

                                            <div class="hiden">

                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="text-area">Màu nền (Background): </label>
                                                    <div class="col-sm-9">
                                                        <div class="bfh-colorpicker" data-name="bg" data-color="@if($temp_set->bg == '')#fff @else{{$temp_set->bg}}@endif" data-close="false">
                                                       </div>
                                                       <p class="text-muted">đối với giao diện mặc định giá trị này không thể thay đổi</p>
                            
                                                   </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="text-area">Màu liên kết (link): </label>
                                                    <div class="col-sm-9">
                                                        <div class="bfh-colorpicker" data-name="link" data-color="@if($temp_set->link == '')#235c79 @else{{$temp_set->link}}@endif" data-close="true">
                                                        </div>
                                                   </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="text-area">Màu nền Menu : </label>
                                                    <div class="col-sm-9">
                                                        <div class="bfh-colorpicker" data-name="nav" data-color="@if ($temp_set->nav == '')#0094da @else{{$temp_set->nav}}@endif" data-close="true">
                                                        </div>
                                                   </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="text-area">Màu chữ trên Menu : </label>
                                                    <div class="col-sm-9">
                                                        <div class="bfh-colorpicker" data-name="link_menu" data-color="@if ($temp_set->link_menu == '')#fff @else{{$temp_set->link_menu}}@endif" data-close="false">
                                                        </div>
                                                   </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="text-area">Màu nền Menu hover : </label>
                                                    <div class="col-sm-9">
                                                        <div class="bfh-colorpicker" data-name="menu_hover" data-color="@if($temp_set->menu_hover == '')#d9edf7 @else{{$temp_set->menu_hover}}@endif" data-close="false">
                                                        </div>
                                                         <p class="text-muted"> Màu nền menu khi rê chuột qua</p>
                                                   </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="text-area">Màu chữ Menu hover : </label>
                                                    <div class="col-sm-9">
                                                        <div class="bfh-colorpicker" data-name="text_menu" data-color="@if ($temp_set->text_menu == '')#235c79 @else{{$temp_set->text_menu}}@endif" data-close="false">
                                                        </div>
                                                         <p class="text-muted"> Màu chữ menu khi rê chuột qua</p>
                                                   </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="text-area">Màu tiêu đề categories : </label>
                                                    <div class="col-sm-9">
                                                        <div class="bfh-colorpicker" data-name="cate" data-color="@if ($temp_set->cate == '')#333333 @else{{$temp_set->cate}}@endif" data-close="false">
                                                        </div>
                                                   </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="text-area">Màu nền tiêu đề cột phải : </label>
                                                    <div class="col-sm-9">
                                                        <div class="bfh-colorpicker" data-name="panel" data-color="@if ($temp_set->panel == '')#d9edf7 @else{{$temp_set->panel}}@endif" data-close="false">
                                                        </div>
                                                   </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="text-area">Màu tiêu đề cột phải : </label>
                                                    <div class="col-sm-9">
                                                        <div class="bfh-colorpicker" data-name="text_panel" data-color="@if ($temp_set->text_panel == '')#31708f @else{{$temp_set->text_panel}}@endif" data-close="false">
                                                        </div>
                                                   </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="text-area">Màu nền footer : </label>
                                                    <div class="col-sm-9">
                                                        <div class="bfh-colorpicker" data-name="footer" data-color="@if ($temp_set->footer == '')#007ab3 @else{{$temp_set->footer}}@endif" data-close="false">
                                                        </div>
                                                        
                                                   </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="text-area">Màu menu footer : </label>
                                                    <div class="col-sm-9">
                                                        <div class="bfh-colorpicker" data-name="footer_menu" data-color="@if ($temp_set->footer_menu == '')#fff @else{{$temp_set->footer_menu}}@endif" data-close="false">
                                                        </div>
                                                         
                                                   </div>
                                                </div>
                                            </div>
                                        </div><!--- end site design -->
                                        <div role="tabpanel" class="tab-pane fade" id="seo">
                                           <div class="form-group">
                                                <label class="col-sm-3 control-label" for="phone">Địa chỉ facebook</label>
                                                <div class="col-sm-9">
                                                    <input placeholder="Tài khoản facebook" data-toggle="tooltip" data-placement="top" title="Ví dụ: https://www.facebook.com/abc" id="phone" name="user_face" value="{{ $temp_set->user_face }}" type="text" class="form-control">
                                                <p class="text-danger"><small> Thông tin tài khoản facebook của bạn là nội dung được in đậm: http://facebook.com/<strong>abcxyz</strong></small></p>
                                        
                                                </div>
                                             </div>
                                           <div class="form-group">
                                                <label class="col-sm-3 control-label" for="fanpage">Địa chỉ fanpage</label>
                                                <div class="col-sm-9">
                                                     <input type="text" placeholder="Địa chỉ fanpage Facebook" data-toggle="tooltip" data-placement="top" title="Ví dụ: https://www.facebook.com/cungcap.net" class="form-control" name="fanpage" value="{{$temp_set->fanpage}}"/>
                                                    
                                                 </div>
                                            </div>
                                           <div class="form-group">
                                                <label class="col-sm-3 control-label" for="phone">Tài khoản skype</label>
                                                <div class="col-sm-5">
                                                    <input placeholder="Tài khoản skype" data-toggle="tooltip" data-placement="top" title="Ví dụ: abcxyz" id="phone" name="user_skype" value="{{ $temp_set->skype }}" type="text" class="form-control">
                                               
                                        
                                                </div>
                                             </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="text-area">Mã Zalo QR</label>
                                                <div class="col-sm-9">
                                                    <textarea id="summernote" name="qr" s="30"  class="form-control">{!! stripslashes($temp_set->qr_zalo) !!}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="phone">Thông tin thanh toán</label>
                                                <div class="col-sm-9">
                                                        <textarea id="pay_info" name="pay_info" s="20" rows="5" class="form-control">{!! stripslashes($temp_set->order_info) !!}</textarea>
                                                <p class="text-danger"><small>Nhập thông tin thanh toán dành cho các khách hàng mua hàng qua hình thức chuyển khoản.</small></p>
                                        
                                                </div>
                                            </div>
                                             
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="phone">Google Analytics ID</label>
                                                <div class="col-sm-9">
                                                    <input placeholder="" data-toggle="tooltip" data-placement="right" title="Ví dụ: UA-66953833-1" id="ga" name="ga" value="{{ $temp_set->ga }}" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12 col-ms-12 ">
                                            <button id="send" class="btn btn-primary pull-right" type="submit" name="send">Lưu <i class="glyphicon glyphicon-ok"></i>
                                                
                                            </button>
                                        </div>
                                    </div>
                              </form>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>


	<script src="{{ asset('/js/bootstrap-formhelpers.js') }}"></script>
	<script src="{{ asset('/js/tab.js') }}"></script>
	<link type="text/css" href="{{asset('css/bootstrap-formhelpers.min.css')}}" rel="stylesheet"/>
	<script>
	 $(document).ready(function() {
		$('#config a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		})
		
		 $('#shop-domain').focusout(function(){
		         $.ajax({
		                type: 'POST',
		                url: '/ajax/checkdomain',
		                data: {
		                    domain :  $(this).val(),
		                },
		                dataType:'json',
		               success:function(data){
			               	if(data.success == false){
			                  $('#post-about-form').find('label#result').text(data.msg);
			                  $("#send").attr("disabled","disabled");
			               	}else{
			               	$('#post-about-form').find('label#result').empty();
			               	 $("#send").removeAttr("disabled");
			               	}
		                }
		
		            });
		           return false;
		    });
	 })
	</script>

<!--CKFinder && CKEditor-->
<script type="text/javascript" src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
<!--CKFinder && CKEditor-->
<script type="text/javascript">
	jQuery(function($){
		CKEDITOR.replace('pay_info',{
                  width: '100%',
                  resize_maxWidth: '100%',
                  resize_minWidth: '100%',
				  height:'150'
                 }
		);
		CKEDITOR.instances['pay_info'];
		CKEDITOR.replace('summernote',{
                          width: '100%',
                          resize_maxWidth: '100%',
                          resize_minWidth: '100%',
        				  height:'100'
                         }
        		);
        CKEDITOR.instances['summernote'];

        
    });
</script>


<script type="text/javascript">
	// maps
    var geocoder;
    var map;
    var currentMarker;
    function initMap() {
        geocoder = new google.maps.Geocoder();
        var mapOptions = {
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: new google.maps.LatLng(<?php echo ($temp_set->lat_shop != '') ? $temp_set->lat_shop.','.$temp_set->long_shop : '21.0025609,105.841135' ?>)
        };

        map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);

        currentMarker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(<?php echo ($temp_set->lat_shop != '') ? $temp_set->lat_shop.','.$temp_set->long_shop : '21.0025609,105.841135' ?>),
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
        var address = document.getElementById('shop-address-full').value;
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

<!--MutiSelect-->
<script src="{{asset('js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
<link type="text/css" href="{{asset('css/bootstrap-multiselect.css')}}" rel="stylesheet"/>
<!--End MutiSelect-->
@endsection