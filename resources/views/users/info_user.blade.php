@extends('inc.master')
@section('seo')
<?php $data_seo = array(
         'title' => 'Thành viên '.$user->name.config('app.title_seo'),
         'keywords' => 'Thông tin thành viên '.$user->name,
         'description' => 'Thông tin thành viên '.$user->name,
         'og_title' => 'Thông tin thành viên '.$user->name.config('app.title_seo'),
          'og_description' => 'Thông tin thành viên '.$user->name,
          'og_url' =>Request::url(),
          'og_img' => $user->avata,
           'current_url' =>Request::url()
        );
$seo = WebService::getSEO($data_seo);
?>
@include('partials.seo')
@endsection
@section('content')
           <div id="page-content-wrapper" class="page_content_primary clear">
                <div class="container-fluid entry_container xyz">
                      <div class="row no-gutter mrb-5 country_option_cs">
                          <div class="col-lg-12">
                              <div class="breadcrumbs_container pd-body clear">
                                  <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                                      <li itemprop="itemListElement">
                                          <a href="{{route('home')}}" itemprop="item"><i class="glyphicon glyphicon-home"></i> <span itemprop="name">Trang Chủ</span></a>
                                      </li>
                                       <li itemprop="itemListElement">
                                           <a href="{{Request::url()}}" itemprop="item"><span itemprop="name">{{$user->name}}</span></a>
                                       </li>
                                  </ol>
                              </div>
                          </div>
                      </div>
                      <div class="row no-gutter content_user_profile">
                         <div class="col-lg-12 col-md-12 col-xs-12 entry_content_user_profile">
                            <!--Begin entry_content_user_profile-->
                            <div class="entry_content clear">
                                <div class="panel panel-default">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-3 title">Ảnh đại diện</div>
											<div class="col-lg-9 logo_user_field value">
												@if ($user->avata != '')
												  {!! HTML::image($user->avata,'',array('class'=>'img-responsive img-thumbnail')) !!}
												@else
													{!! HTML::image('img/avata.png','',array('class'=>'img-responsive img-thumbnail')) !!}
												@endif
											</div>
										</div>
										<div class="row">
										   <div class="col-lg-3 title">Tên thành viên</div>
										   <div class="col-lg-9 value">{{ $user->name }}</div>
										</div>
										<div class="row">
											<div class="col-lg-3 title">Giới tính</div>
											<div class="col-lg-9 value">
												 @if(strlen($user->gender)>0)
													 {{ $user->gender }}
												 @else
													<div class="alert alert-danger">Chưa cập nhật</div>
												 @endif
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 title">Ngày sinh</div>
											<div class="col-lg-9 value">
												@if(strlen($user->birthday)>0)
												{{ date('d-m-Y',strtotime($user->birthday)) }}
												 @else
												  <div class="alert alert-danger">
														Chưa cập nhật
												  </div>
												  @endif
											 </div>
										</div>
										<div class="row">
											<div class="col-lg-3 title">Email</div>
											@if (Auth::check())
												<div class="col-lg-9 value">{{ $user->email }}</div>
											@else 
												<div class="col-lg-9 value">Email bị ẩn. <a href="{{route('user.login.page')}}">Đăng nhập</a> mới có thể xem được</div>
											@endif
										</div>
										<div class="row">
											<div class="col-lg-3 title">Số điện thoại</div>
											@if (Auth::check())
												<div class="col-lg-9 value">
													@if(strlen($user->user_phone)>0)
													{{ $user->user_phone }}
													 @else
														<div class="alert alert-danger">Chưa cập nhật</div>
													@endif
												</div>
											@else 
												<div class="col-lg-9 value">
													Số điện thoại bị ẩn. <a href="{{route('user.login.page')}}">Đăng nhập</a> mới có thể xem được
												</div>
											@endif
										</div>
										<div class="row">
											<div class="col-lg-3 title">Địa chỉ</div>
											<div class="col-lg-9 value">
												 @if(strlen($user->shop_address)>0)
													 {{ $user->shop_address }}
												 @else
													 <div class="alert alert-danger">Chưa cập nhật</div>
												 @endif
											</div>
										</div>
										<div class="row">
											   <div class="col-12 text-center">
													@if (Auth::check())
													   @if(Auth::user()->id != $user->id)
														 <div class="button_send_sms"><a onclick="$('#post-message').slideToggle()"><i class="fa fa-envelope"></i> Gửi tin nhắn</a></div>
													   @endif
													@else
														<div class="button_send_sms"><a href="#!login" data-toggle="modal" data-target="#myModal" data-backdrop="true"><i class="fa fa-envelope"></i> Gửi tin nhắn</a></div>
													@endif
											   </div>
										</div>
									</div>
								</div>
                                <div class="panel-footer"></div>
                             </div>
                            <!--End entry_content_user_profile-->
                         </div>
                      </div><!--content_user_profile-->
                </div><!--entry_container-->
           </div>
@endsection