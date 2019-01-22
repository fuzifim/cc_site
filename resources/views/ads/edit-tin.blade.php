@extends('inc.master')
@section('seo')
<?php
	$data_seo = array(
		'title' => 'Sửa tin nhanh -'.config('app.seo_title'),
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
<div id="wrapper">
    <div id="sidebar-wrapper" class="menu_home clear margin_top_10">
        @include('partials.user_sidebar_new')
    </div><!--sidebar-wrapper-->
    <div id="page-content-wrapper" class="page_content_primary clear">
        <div class="container-fluid entry_container xyz">
            <div class="row no-gutter mrb-5 country_option">
                <div class="col-lg-12">
                    <div class="breadcrumbs_inc clear">
                        {!! Breadcrumbs::render('postads') !!}
                    </div>
                </div>
            </div>
            <div class="row no-gutter">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div id="post-form-container" class="col-sm-12 col-md-12 col-lg-12">
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

                                <form id="post-item-form" action="{{ route('front.ads.updatefast',$ads->id)}}" method="post" accept-charset="utf-8" role="form" class="form-horizontal">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" name="ads_status" value="{{ $ads->ads_status }}" />
                                    <input type="hidden" name="ads_id" value="{{ $ads->id }}"/>
									
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label" for="item-title">Tiêu đề tin <span class="text-danger">(*)</span></label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input id="item-title" name="ads_title" value="{{ $ads->ads_title }}" type="text" class="form-control" placeholder="Nhập tiêu đề tin">
                                        </div>
                                    </div>

                                    <div class="form-group mrbt-5">
                                        <label class="col-md-3 col-xs-12 control-label" for="ads-description">Mô tả</label>
										<div class="col-sm-9 col-xs-12">
                                            <textarea name="ads_description" id="ads_description" rows="10" class="form-control mce" placeholder="Nhập nội dung mô tả hoặc giới thiệu">
                                              {{ htmlspecialchars_decode($ads->ads_description) }}
                                            </textarea>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-3 col-xs-12 control-label" for="">Ảnh/ Video <span class="text-danger">(*)</span></label>
                                        <div class="col-sm-9 col-xs-12">
                                            @include('include.upload-form')
                                        </div>
                                    </div>
									<!--Khuyến mãi-->
									<div class="form-group mrbt-5">
                                        <div class="col-sm-9 col-sm-offset-3 col-xs-12 col-xs-offset-0">
                                            <div class="alert alert-warning alert_km clear">
												<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
												<label class="clear" for="km">
												<div class="pull-left title-km">Khuyến mãi</div> <div class="pull-left" ><input id="km" name="km" type="checkbox" @if(strlen($ads->ads_promotion)>0) checked @endif autocomplete="off" value="1"/></div>
												</label>
											</div>
                                        </div>
                                    </div>
									
									<!--Show content Khuyen Mai-->
									<div class="form-group mrbt-5 km-input-field" @if(strlen($ads->ads_promotion)>0) style="display:block;" @else style="display:none;" @endif>
                                        <label class="col-md-3 col-xs-12 control-label" for="ads-description">Nội dung khuyến mãi</label>
										<div class="col-sm-9 col-xs-12">
                                            <textarea name="ads_promotion" id="ads_promotion" rows="5" class="form-control mce" placeholder="Nhập nội dung khuyến mãi">
                                            {{ htmlspecialchars_decode($ads->ads_promotion) }}
                                            </textarea>
                                        </div>
                                    </div>

                                    
                                     <script type="text/javascript">
                                        //jquery
										
										$('textarea').keypress(function(event) {
										  if (event.which == 13) {
											event.preventDefault();
											  var s = $(this).val();
											  $(this).val(s+"\n");
										  }
										});
                                        $('textarea#ads_description').html($('textarea#ads_description').html().trim());

                                        //without jquery
                                        document.getElementById('ads_description').innerHTML = document.getElementById('ads_description').innerHTML.trim();
										
										
                                         $(document).ready(function() {
                                             
											$("#km").click(function () {
												if ($(this).is(":checked")) {
													$(".km-input-field").slideDown("slow");
													
												} else {
													$(".km-input-field").hide();
													$("#ads_promotion").val("");
												}
											});
											 
											
                                         });
                                     </script>
									@if(!empty($template_webs) && count($template_webs)>0)
									<div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label" for="template-cate">Đăng tin cho<span class="text-danger">(*)</span></label>
                                        <div class="col-sm-9 col-xs-12">
                                           <select ng-model="[]" id="template_setting_select" name="id_template" class="form-control">
												@foreach($template_webs as $template_web)
													<option value="{{$template_web->id}}" @if($template_web->id==$ads->template_setting_id)  selected="selected" @endif>{{$template_web->domain}}</option>
												@endforeach
                                            </select>
                                        </div>
                                    </div>
									@endif
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label" for="item-price">Loại Tin<span class="text-danger">(*)</span></label>
                                        <div class="col-sm-9 col-xs-12">
                                            <select id="category_option_select" class="form-control cat_manage_s" name="category_option" autocomplet="off">
                                                {!!WebService::OptionCategoryGennerate($ads->category_option_ads_id)!!}
                                            </select>
                                        </div>
                                    </div>
                                    <div id="upload_youtube"  class="container_product clear" @if($ads->category_option_ads_id !=5)style="display:none;" @endif>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 col_title_upload control-label" for="item-price">Chọn Video<span class="text-danger">(*)</span></label>
                                        <div class="col-sm-9 col-xs-12">
                                            <div class="row_upload">
                                                <div class="col-lg-12 col-md-12 col-xs-12">
                                                    <div id="text_input_file" class="file_upload">
                                                        <input id="link_upload_youtube" type="text" value="{{$ads->link_youtube}}" name="link_upload_youtube" placeholder="Nhập link Youtube hoặc upload file">
                                                    </div>
                                                    <div id="videos_upload">
                                                        <a id="file_upload_videos" name="file_upload_videos" href="javascript:void(0)" data-toggle="modal" data-target="#upload_youtubeModal" data-backdrop="true"><i class="fa fa-upload" aria-hidden="true"></i> Upload File</a>
                                                    </div>
                                                 </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "alert alert-danger alert_video"><i>Bạn có thể nhập link youtube hoặc upload một file videos hệ thống tự động upload tới youtube tự động lấy link</i><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
                                </div><!--upload_youtube-->
                                   

                                 <div id="category_option_product_seleted" class="container_product clear" @if($ads->category_option_ads_id !=2)style="display:none;" @endif>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 control-label" for="item-price">Giá bán <span class="text-danger">(*)</span></label>
                                        <div class="col-sm-5 col-xs-12">
                                            @if (isset($ads) && $ads->ads_price != 0)
                                            <input value="{{ $ads->ads_price }}" name="ads_price" type="text" class="form-control" id="ads-price"
                                             data-placement="bottom" title=""
                                            placeholder="000" >
                                            @else
                                            <input value="" name="ads_price" type="text" class="form-control" id="ads-price"
                                             data-placement="bottom" title=""
                                            placeholder="000">
                                            @endif
                                             <p id="price-split" class="text-muted"></p>
                                         </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 control-label" for="item-price">Giá gốc </label>
                                        <div class="col-sm-5 col-xs-12">
                                            @if (isset($ads) && $ads->discount != 0)
                                            <input value="{{ $ads->discount }}" name="discount" type="text" class="form-control" id="discount"
                                             data-placement="bottom" title=""
                                            placeholder="000" >
                                            @else
                                            <input value="" name="discount" type="text" class="form-control" id="discount"
                                             data-placement="bottom" title=""
                                            placeholder="000" >
                                            @endif
                                             <p id="price-discount" class="text-muted"></p>
                                        </div>
                                    </div>

                                 </div>

								<div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label" for="item-title">Tag bài đăng</label>
                                        <div class="col-sm-9 col-xs-12 container_post_tag">
                                            <input ng-model="[]" autocomplete="on" id="item_tag_products" name="tag_products" value="" type="text" class="form-control" placeholder="Nhập tag đăng tin">
											<button id="add_tag_products" type="button" class="btn btn-success">Thêm Tag</button>
											
											<div id="tag_alert_error" class="alert alert-danger" style="display:none;">Bạn phải nhập vào tag  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
                                        </div>
                                    </div>   
									
									
									<div class="form-group tag_container_value_set" @if(!empty($tags) && count($tags)>0) style="display:block;" @else  style="display:none;" @endif>
                                        <label class="col-md-3 col-xs-12 control-label" for="item-title"></label>
                                        <div class="col-sm-9 col-xs-12">
                                            <div id="tag_post" class="alert alert-success">
												@if(!empty($tags) && count($tags)>0)
													@foreach($tags as $tag)
														<span class="tag_item" onclick="delete_tag('{{$tag->tag_slug}}',{{$ads->id}})" data="{{$tag->tag_slug}}"> <i class="fa fa-tag"></i> {{$tag->tag_name}}</span>
													@endforeach
												@endif
											</div>
                                        </div>
                                    </div>   
									

									<div class="clear pd-10"> </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-md-9 col-ms-9 col-xs-12 center_mobile">
                                             <button class="btn btn-primary" type="submit" name="send">Lưu
                                                <i class="fa fa-send"></i>
                                              </button>
                                              <a class="btn btn-default" href="{{ route('user.ads.manager',Auth::user()->id) }}">
                                                <i class="fa fa-mail-reply"></i>
                                              Bỏ qua</a>
                                        </div>
                                    </div>

                                </form>
                                <script>
                                    $('button[type="submit"]','#post-item-form').on('click',function(e){
                                        e.preventDefault();
                                        if( $('#dropbox').find('.preview').length <= 0
                                            || !$('input[name="ads_thumbnail[]"]').is(":checked") )
                                        {
                                            $('label#upload-msg').text('Bạn chưa upload ảnh hoặc chưa chọn ảnh đại diện');
                                            return;
                                        }
                                        $(this).submit();
                                        e.preventDefault();

                                    });

                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!--CKFinder && CKEditor-->
<script type="text/javascript" src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
<!--CKFinder && CKEditor-->
<script type="text/javascript">
	jQuery(function($){
		CKEDITOR.env.isCompatible = true;
		CKEDITOR.replace('ads_description',{
                  width: '100%',
                  resize_maxWidth: '100%',
                  resize_minWidth: '100%',
				  height:'200'
                 }
		);
		CKEDITOR.instances['ads_description'];
		
		CKEDITOR.replace('ads_promotion',{
                  width: '100%',
                  resize_maxWidth: '100%',
                  resize_minWidth: '100%',
				  height:'80'
                 }
		);
		CKEDITOR.instances['ads_promotion'];
		$('#videos_upload').on('click', '#file_upload_videos', function(e) {
			var check_box=0;
			var data_description=CKEDITOR.instances['ads_description'].getData();
			var data_title=$('#post-item-form input[name="ads_title"]').val();
			$('#description_youtube').val(data_description);
			$('input[name="title_youtube"]').val(data_title);
			var $src="img/no-image.png";
			$('.upload_image_post .preview').each(function() {
				if($(this).find('input[type=radio]').prop('checked')) { 
					$src=$(this).find('img.img-thumbnail').attr('src');
					$('#thumb_youtube_view_show').attr("src", $src);
					$('#images_youtube').val($src);
					check_box=1;
				}
			});
			if(data_description.length!= 0 && data_title!= 0 && check_box==1)
			{
				return true;
			}
			else
			{
				alert('Bạn phải nhập đầy đử dữ liệu trước khi upload Youtube');
				e.preventDefault();
				e.stopPropagation(); // Stop bubbling up
				return false;
				
			}
		});
			
    });
	
</script>

@endsection
