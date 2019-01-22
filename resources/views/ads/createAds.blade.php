@extends('inc.master')
@section('seo')
<?php
	$data_seo = array(
		'title' => 'Đăng bài',
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
                        <ol class="breadcrumb">
                            <li class="dropdown" itemprop="itemListElement">
                                 <a href="{{route('home')}}" itemprop="item"><span itemprop="name"><i class="glyphicon glyphicon-home"></i> <span class="hidden-xs">Trang chủ</span></span></a>
                            </li>
                            <li class="dropdown active" itemprop="itemListElement"><a data-toggle="dropdown" itemprop="item" href=""><span class="glyphicon glyphicon-cog"></span> <span itemprop="name">Bảng điều khiển</span></a> <span class="caret"></span>
								@include('partials.menu_dropdown_dashboard')
							</li>
							<li itemprop="itemListElement"><span itemprop="name">Đăng bài</span></li>
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
                    <div class="panel">
                        <div class="panel-body">
                            <div id="post-form-container" class="col-sm-12 col-md-12 col-lg-12">
                                <form id="post-item-form" action="{{ route('front.ads.editfast',$ads->id) }}" method="post" accept-charset="utf-8" role="form" class="">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" name="ads_status" value="{{ $ads->ads_status }}" />
                                    <input type="hidden" name="ads_id" value="{{ $ads->id }}"/>
									<input type="hidden" name="thumbnail" value="{{ $ads->ads_thumbnail }}"/>
									<div class="insert-media"></div>
									<div class="row">
										<div class="col-md-8 col-xs-12">
											<div class="add_message_summernote"></div>
											<div class="form-group">
												<input id="item-title" name="ads_title" value="{{ $ads->ads_title }}" type="text" class="form-control title-post-edit" placeholder="Nhập tiêu đề bài đăng">
											</div>
											<div class="form-group mrbt-5">
												<textarea name="ads_description" id="summernote" rows="10" class="form-control summernote">{{ htmlspecialchars_decode($ads->ads_description) }}</textarea>
											</div>
										</div>
										<div class="col-md-4 col-xs-12">
											<div class="form-group">
												<div id="myCarousel" class="carousel slide" data-ride="carousel">
													  <!-- Wrapper for slides -->
													<div class="addMediaToContent carousel-inner">
														<? $i=0; ?>
														@if(count($adsMedia)>0)
															@foreach($adsMedia as $media)
																<? $i++; ?>
																@if ($media->file_type == "image/jpeg" || $media->file_type == "image/jpg" || $media->file_type == "image/png" || $media->file_type == "image/gif")
																	<div class="item @if($i==1) active @endif col-xs-12 col-sm-12 col-md-12"><div class="mediaGallery"><img class="img-responsive" src="{{$media->file_url}}"></div> <div class="content">{{$media->file_content}}</div></div>
																@endif
																@if ($media->file_type == "video/x-flv" || $media->file_type == "video/mp4" || $media->file_type == "application/x-mpegURL" || $media->file_type == "video/MP2T" || $media->file_type == "video/3gpp" || $media->file_type == "video/quicktime" || $media->file_type == "video/x-quicktime" || $media->file_type == "image/mov" || $media->file_type == "video/avi" || $media->file_type == "video/x-msvideo" || $media->file_type == "video/x-ms-wmv")
																	<div class="item @if($i==1) active @endif col-xs-12 col-sm-12 col-md-12"><div class="embed-responsive embed-responsive-16by9 mediaGallery"><iframe class="embed-responsive-item" src="{{$media->file_url}}" frameborder="0" allowfullscreen></iframe></div> <div class="content">{{$media->file_content}}</div></div>
																@endif
															@endforeach
														@endif 
														<span class="countMedia">{{count($adsMedia)}}</span>
													</div>
													@if(count($adsMedia)>0)
													  <!-- Left and right controls -->
													  <a class="left carousel-control carousel_control_left" href="#myCarousel" data-slide="prev">
														<span class="glyphicon glyphicon-chevron-left"></span>
														<span class="sr-only">Previous</span>
													  </a>
													  <a class="right carousel-control carousel_control_right" href="#myCarousel" data-slide="next">
														<span class="glyphicon glyphicon-chevron-right"></span>
														<span class="sr-only">Next</span>
													  </a>
													@endif
												</div>
											</div>
											<div class="form-group">
												<ul class="list-group">
													@if(count($adsMedia)>0)
													<a href="" class="list-group-item" id="btnShowMedia"  data-toggle="modal" data-target="#addMedia"><i class="glyphicon glyphicon-camera"></i> Thêm ảnh/ Video</a>
													@else
													<div tabindex="500" class="list-group-item btn-file" style="position: relative; "><i class="glyphicon glyphicon-camera"></i>&nbsp;  <span>Thêm ảnh/ Video</span><input id="mediaFile" name="input4[]" type="file" multiple="" class=""></div>
													@endif
													<a href="" class="list-group-item"><i class="glyphicon glyphicon-plus-sign"></i> Chọn kênh đăng</a>
													<a href="" class="list-group-item"><i class="glyphicon glyphicon-check"></i> Chọn thể loại</a>
													<a href="" class="list-group-item"><i class="glyphicon glyphicon-tags"></i> Thêm tag bài viết</a>
												</ul>
											</div>
											@if(!empty($template_webs) && count($template_webs)>1)
											<div class="form-group">
												<label class="control-label" for="template-cate">Đăng lên<span class="text-danger">(*)</span></label>
											   <select  id="template_setting_select" name="id_template" class="form-control">
														<option value="0">Chọn kênh đăng</option>  
													@foreach($template_webs as $template_web)
														<option value="{{$template_web->id}}">{{$template_web->domain}}</option>
													@endforeach
												</select>
											</div>
											@endif
											<div class="form-group">
												<label class="control-label" for="item-price">Thể loại<span class="text-danger">(*)</span></label>
												<select id="category_option_select" class="form-control cat_manage_s" name="category_option" autocomplet="off">
													{!!WebService::OptionCategoryGennerate(0)!!}
												</select>
											</div>
											 <div id="category_option_product_seleted" class="container_product clear" @if(isset($ads) && $ads->category_option_ads_id !=2)style="display:none;" @endif>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<div class="form-group">
														<label class="control-label" for="item-price">Giá bán <span class="text-danger">(*)</span></label>
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
												<div class="col-md-6 col-sm-6 col-xs-12">
													<div class="form-group">
														<label class="control-label" for="item-price">Giá gốc </label>
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
												<label class="control-label" for="item-title">Tag bài đăng</label>
												<div class="input-group container_post_tag">
													<input autocomplete="on" id="item_tag_products" name="tag_products" value="" type="text" class="form-control" placeholder="Nhập tag đăng tin">
													<div class="input-group-btn">
														<button id="add_tag_products" type="button" class="btn btn-success">Thêm Tag</button>
													</div>
												</div>
											</div>
											<div class="form-group tag_container_value_set" @if(!empty($tags) && count($tags)>0) style="display:block;" @else  style="display:none;" @endif>
												<div id="tag_alert_error" class="alert alert-danger" style="display:none;">Bạn phải nhập vào tag  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
												<div id="tag_post" class="list-group">
													@if(!empty($tags) && count($tags)>0)
														@foreach($tags as $tag)
															<div class="list-group-item"><i class="fa fa-tag"></i> {{$tag->tag_name}} <span class="btn btn-xs btn-danger" onclick="delete_tag('{{$tag->tag_slug}}',{{$ads->id}})" data="{{$tag->tag_slug}}"><i class="glyphicon glyphicon-trash"></i></span></div>
														@endforeach
													@endif
												</div>
											</div>
											<div class="form-group">
												<a class="btn btn-default" href="{{ redirect()->back()->getTargetUrl() }}"><i class="glyphicon glyphicon-menu-left"></i> Trở lại</a> 
												<button class="btn btn-primary" type="submit" name="send"><i class="glyphicon glyphicon-ok"></i> Lưu</button> 
												<a class="btn btn-danger" href="{{ route('front.ads.delete',array(0,$ads->id)) }}"><i class="glyphicon glyphicon-trash"></i> Xóa</a> 
											</div>
										</div>
									</div>

                                </form>
                                <script>
                                    $('button[type="submit"]','#post-item-form').on('click',function(e){
                                        e.preventDefault();
                                        /*
										if( $('#dropbox').find('.preview').length <= 0
                                            || !$('input[name="ads_thumbnail[]"]').is(":checked") )
                                        {
                                            $('label#upload-msg').text('Bạn chưa upload ảnh hoặc chưa chọn ảnh đại diện');
                                            return;
                                        }
										*/
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
<div id="addMedia" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Thêm ảnh/ Video</h4>
      </div>
      <div class="modal-body">
		<div class="addMediaMessage"></div> 
		<div class="addMediaContent"></div>
      </div>
      <div class="modal-footer">
        <div tabindex="500" class="btn btn-primary btn-file" style="position: relative; "><i class="glyphicon glyphicon-plus"></i>&nbsp;  <span>Thêm…</span><input id="mediaFile" name="input4[]" type="file" multiple="" class=""></div>
        <button type="button" class="btn btn-success" id="btnAddMedia"><i class="glyphicon glyphicon-ok"></i> Lưu</button> 
		<button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>
      </div>
    </div>

  </div>
</div>
<script>
	$( "#btnShowMedia" ).click(function() {
		$('#addMedia .addMediaContent').empty(); 
		$('#btnAddMedia').prop('disabled', false);
		var id=$('input[name=ads_id]').val(); 
		$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')} }); 
		$.ajax({
			url: '/get_media/ads/'+id,
			type: 'GET',
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			success:function(result){
				$.each(result.medias, function(i, item) {
					var fileType = item.file_type.split('/')[0]; 
					if(fileType=='image'){
						$('.addMediaContent').append('<div class="result-add-media"><div class="form-group"><img class="img-responsive item-media" data-type="image" data-id="'+item.id+'" src="'+item.file_url+'"></div><div class="form-group"><div id="contentBoxMedia" contentEditable="true" placeholder="Mô tả nội dung này...">'+item.file_content+'</div></div></div>'); 
					}else if(fileType=='video'){
						$('.addMediaContent').append('<div class="result-add-media"><div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item item-media" data-type="video" data-id="'+item.id+'" src="'+item.file_url+'" frameborder="0" allowfullscreen></iframe></div><div class="form-group"><div id="contentBoxMedia" contentEditable="true" placeholder="Mô tả nội dung này...">'+item.file_content+'</div></div></div>'); 
					}
				});
			},
			error: function(result) {
			}
		});
	});
	$( "#btnAddMedia" ).click(function() {
		$( ".result-add-media" ).each(function() {
			var type=$( this ).find('.item-media').attr('data-type'); 
			var id=$('input[name=ads_id]').val(); 
			var media=$( this ).find('.item-media').attr('src'); 
			var mediaId=$( this ).find('.item-media').attr('data-id'); 
			var content=$( this ).find('#contentBoxMedia').html(); 
			var formData = new FormData();
			formData.append("id", id); 
			formData.append("type", type); 
			formData.append("table", 'ads'); 
			formData.append("media", media); 
			formData.append("mediaId", mediaId); 
			formData.append("content", content); 
			$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')} }); 
			$.ajax({
				url: '/update_media',
				type: 'post',
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				dataType:'json',
				success:function(result){
				},
				error: function(result) {
				}
			});
			$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')} }); 
			$.ajax({
				url: '/media_join',
				type: 'post',
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				dataType:'json',
				success:function(result){
					if(type==='image'){
						$('.addMediaToContent').append('<div class="item col-xs-12 col-sm-12 col-md-12"><div class="mediaGallery"><img class="img-responsive" src="'+media+'"></div> <div class="content">'+content+'</div></div>'); 
					}else if(type==='video'){
						$('.addMediaToContent').append('<div class="item col-xs-12 col-sm-12 col-md-12"><div class="embed-responsive embed-responsive-16by9 mediaGallery"><iframe class="embed-responsive-item" src="'+media+'" frameborder="0" allowfullscreen></iframe></div> <div class="content">'+content+'</div></div>'); 
					}
				},
				error: function(result) {
				}
			});
		});
		$('#addMedia').modal('hide'); 
		$('#addMedia .addMediaMessage').empty(); 
		$('#btnAddMedia').prop('disabled', true);
	});
	$('#mediaFile').bind("change", function(){ 
		$('#addMedia .addMediaMessage').empty(); 
		$('#btnAddMedia').prop('disabled', true);
		var files = $("#mediaFile").prop("files");  
		var totalFile=files.length; 
		if(totalFile>0){
			$('#addMedia').modal('show');
			for(var i=0;i<totalFile;i++)
			{
				$(".addMediaMessage" ).append( '<div class="progress" id="progress-upload"><div class="progress-bar" role="progressbar"></div></div>' );
				var titlePost=$('input[name=ads_title]').val(); 
				var formData = new FormData();
				formData.append("title", titlePost); 
				formData.append("file", files[i]); 
				$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')} }); 
				$.ajax({
					url: '/file/uploadnote',
					type: 'post',
					xhr: function() {
						var myXhr = $.ajaxSettings.xhr();
						if (myXhr.upload) myXhr.upload.addEventListener('progress',progressHandlingFunctionAddMedia, false);
						return myXhr;
					},
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					dataType:'json',
					success:function(result){
						if(result.success==true){
							if(result.MimeType=='image'){
								$('.addMediaContent').append('<div class="result-add-media"><div class="form-group"><img class="img-responsive item-media" data-type="image" data-id="'+result.media.id+'" src="'+result.url+'"></div><div class="form-group"><div id="contentBoxMedia" contentEditable="true" placeholder="Mô tả nội dung này..."></div></div></div>'); 
							}else if(result.MimeType=='video_youtube'){
								$('.addMediaContent').append('<div class="result-add-media"><div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item item-media" data-type="video" data-id="'+result.media.id+'" src="'+result.url+'" frameborder="0" allowfullscreen></iframe></div><div class="form-group"><div id="contentBoxMedia" contentEditable="true" placeholder="Mô tả nội dung này..."></div></div></div>'); 
							}
							$('#progress-after').hide(); 
							$(".addMediaMessage" ).append( '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.msg+'</div>');
							$(".addMediaMessage").fadeTo(2000, 500).slideUp(500, function(){
								$(".addMediaMessage").slideUp(500);
							});   
							$('#btnAddMedia').prop('disabled', false);
						}else{
							$('#progress-after').hide(); 
							$(".addMediaMessage" ).append( '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.msg+'</div>');
							$(".addMediaMessage").fadeTo(2000, 500).slideUp(500, function(){
								$(".addMediaMessage").slideUp(500);
							}); 
						}
					},
					//,
					error: function(result) {
					}
				});
			} 
		}else{
			$('#addMedia').modal('hide');
		}
		
	});  
	function progressHandlingFunctionAddMedia(e){
		var progress_bar_id         = '#progress-upload';
		var percent = 0;
		var position = e.loaded || e.position;
		var total = e.total; 
		if(e.lengthComputable){
			percent = Math.ceil(position / total * 100);
			//$('progress').show(); 
			//$('progress').attr({value:e.loaded, max:e.total});
			$(progress_bar_id +" .progress-bar").css("width", + percent +"%"); 
			$(progress_bar_id +" .progress-bar").text(percent +"%"); 
			// reset progress on complete
			if (e.loaded == e.total) {
				//$('progress').attr('value','0.0'); 
				$(progress_bar_id).hide(); 
				$(".addMediaMessage" ).append('<button class="btn btn-success btn-xs" id="progress-after" style="margin-bottom:5px; "><i class="fa fa-spinner fa-spin"></i> Đang xử lý vui lòng chờ trong giây lát! </button>'); 
			}
		}
	}
</script>
<link href="{{asset('js/summernote/dist/summernote.css')}}" rel="stylesheet">
<script src="{{asset('js/summernote/dist/summernote.js')}}"></script>
<script src="{{asset('js/summernote/lang/summernote-vi-VN.js')}}"></script>
<script>
$(document).ready(function() { 
	$('.summernote').summernote(
		{
			lang: "vi-VN", 
			placeholder: 'Bạn đang viết gì? ', 
			dialogsInBody: true, 
			focus: true,
			minHeight: 150,   //set editable area's height 
			enterHtml: '<br>',
			//height:250,
			//minHeight:null,
			//maxHeight:null,
			@if(Agent::isMobile())
			toolbar: [
				// [groupName, [list of button]]
				['style', ['bold', 'italic']],
				['insert', ['picture','video', 'link', 'hr']],
				['view', ['codeview']],
			],
			@else 
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'italic', 'underline', 'clear']],
				['fontname', ['fontname']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['height', ['height']],
				['table', ['table']],
				['insert', ['link', 'picture', 'video', 'hr']],
				['view', ['fullscreen', 'codeview']],
				['help', ['help']]
			], 	
			@endif	
			codemirror: { // codemirror options
				theme: 'monokai'
			}, 
			callbacks: {
				onImageUpload: function (files){
						uploadImage(files[0]);
				},
				onEnter: function(){
					$('.summernote').summernote('insertNode', document.createElement("br")); 
				}
			}
	});
	function uploadImage(file,editor,welEditable) {
		var formData = new FormData(); 
		var ads_id=$('input[name=ads_id]').val(); 
		var titlePost=$('input[name=ads_title]').val(); 
		var description=$('textarea[name=ads_description]').val();
		formData.append("file", file); 
		formData.append("title", titlePost); 
		formData.append("description", description); 
		$(".add_message_summernote" ).html('');
		$(".add_message_summernote" ).append( '<div class="progress" id="progress-upload"><div class="progress-bar" role="progressbar"></div></div>' );
		$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')} });
		$.ajax({
			url: '/file/uploadnote',
			type: 'post',
			xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) myXhr.upload.addEventListener('progress',progressHandlingFunction, false);
                return myXhr;
            },
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				console.log(result); 
				if(result.success==true){
					if(result.MimeType=='image'){
						$('.summernote').summernote('editor.insertImage', result.url, function ($image) {
						  $image.css('width', '100%');
						  $image.addClass("img-responsive");
						});
					}else if(result.MimeType=='video_youtube'){
						var node = $('<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="'+result.url+'" frameborder="0" allowfullscreen></iframe></div>')
						$('.summernote').summernote('editor.insertNode', node[0]); 
						$(".insert-media" ).append( '<input type="hidden" value="'+result.url+'" name="link_upload_youtube">'); 
						$(".insert-media" ).append( '<input type="hidden" value="'+result.id_video+'" name="id_video">');
					}
					$('#progress-after').hide(); 
					$(".add_message_summernote" ).append( '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.msg+'</div>');
				}else{
					$('#progress-after').hide(); 
					$(".add_message_summernote" ).append( '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.msg+'</div>');
				}
			},
			//,
			error: function(result) {
				console.log(result);
				$('#progress-after').hide(); 
				$(".add_message_summernote" ).append( '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.msg+'</div>');
			}
		});
	}
	function progressHandlingFunction(e){
		var progress_bar_id         = '#progress-upload';
		var percent = 0;
		var position = e.loaded || e.position;
		var total = e.total; 
		if(e.lengthComputable){
			percent = Math.ceil(position / total * 100);
			//$('progress').show(); 
			//$('progress').attr({value:e.loaded, max:e.total});
			$(progress_bar_id +" .progress-bar").css("width", + percent +"%"); 
			$(progress_bar_id +" .progress-bar").text(percent +"%"); 
			// reset progress on complete
			if (e.loaded == e.total) {
				//$('progress').attr('value','0.0'); 
				$(progress_bar_id).hide(); 
				$(".add_message_summernote" ).append('<button class="btn btn-success btn-xs" id="progress-after" style="margin-bottom:5px; "><i class="fa fa-spinner fa-spin"></i> Đang xử lý vui lòng chờ trong giây lát! </button>'); 
			}
		}
	}
});
</script>

@endsection
