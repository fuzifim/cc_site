<?
	$channel['theme']->setTitle('Cài đặt chung');
?>
@include('themes.admin.inc.header')
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				@include('themes.admin.partial.menuManageService')
			</div>
			<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
						<button type="button" tabindex="500" class="btn btn-xs btn-primary btn-file" style="position: relative; overflow: hidden; margin-bottom:5px; "><i class="glyphicon glyphicon-camera"></i>&nbsp;  <span>Thay đổi Logo</span><input id="mediaFileLogo" name="" type="file" class=""></button>300x300
						<div class="form-group text-center">
							<img class="img-responsive img-thumbnail" id="logoChannel" style="width:100%; " src="@if(!empty($channel['info']->channelAttributeLogo->media->media_url_small)){{$channel['info']->channelAttributeLogo->media->media_url_small}} @else {{asset('assets/img/logo-default.jpg')}} @endif"> 
						</div>
					</div>
					<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
						<button type="button" tabindex="500" class="btn btn-xs btn-primary btn-file" style="position: relative; overflow: hidden; margin-bottom:5px; "><i class="glyphicon glyphicon-camera"></i>&nbsp;  <span>Thêm ảnh bìa</span><input id="changeImageCove" name="" type="file" multiple="" class=""></button> 1170x350
						<div class="form-group">
							<div id="myCarousel" class="carousel slide" data-ride="carousel">
								<div class="carousel-inner" style="max-height:188px; overflow:hidden; position:relative;">
									
								</div>
								<a class="left carousel-control carousel_control_left" href="#myCarousel" data-slide="prev">
									<span class="glyphicon glyphicon-chevron-left"></span>
									<span class="sr-only">Previous</span>
								</a>
								<a class="right carousel-control carousel_control_right" href="#myCarousel" data-slide="next">
									<span class="glyphicon glyphicon-chevron-right"></span>
									<span class="sr-only">Next</span>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="message"></div>
				<div class="form-group">
					<label class="control-label" for="text-area">Tên kênh: <span class="text-danger">(*)</span> </label>
					<input id="item-channelName" name="channel_name" value="{{$channel['info']->channel_name}}" type="text" class="form-control" placeholder="Nhập tên kênh">
				</div>
				
				<div class="form-group">
					<label class="control-label" for="phone">Mô tả:</label>
					<textarea name="channel_about" id="summernote" class="form-control">{{ htmlspecialchars_decode($channel['info']->channel_description) }}</textarea>
				</div>
				<div class="form-group mutiselected_group">
					<label class="control-label" for="ads-cate">Lĩnh vực hoạt động: <span class="text-danger">(*)</span></label>
					<div class="select_muti_channel_fields">
					   <select ng-model="[]" id="channel_fields" name="Field_id[]" class="form-control"  multiple="multiple">
							{!!htmlspecialchars_decode($channel_fields)!!}
						</select>
					</div>
				</div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						 $('#channel_fields').multiselect({
							enableClickableOptGroups: true,
							enableCollapsibleOptGroups: true,
							enableFiltering: true,
							includeSelectAllOption: true,
							maxHeight: 300
						 })
					 }); 
				</script>
				<div class="panel panel-primary">
					<div class="panel-heading"><div class="panel-title"><i class="glyphicon glyphicon-search"></i> SEO</div></div>
					<div class="panel-body">
						<div class="form-group">
							<label class="control-label" for="text-area">Meta Title: </label>
							<input id="metaTitle" name="metaTitle" value="@if(!empty($getSeo->metaTitle)){!!$getSeo->metaTitle!!}@endif" type="text" class="form-control" placeholder="Nhập thẻ tiêu đề">
						</div>
						<div class="form-group">
							<label class="control-label" for="text-area">Meta description: </label>
							<textarea name="metaDescription" class="form-control" maxlength="250" placeholder="Nhập thẻ mô tả...">@if(!empty($getSeo->metaDescription)){!!$getSeo->metaDescription!!}@endif</textarea>
						</div>
					</div>
				</div>
				<div class="form-group text-right">
					<button id="send" class="btn btn-primary" type="button" name="send"><i class="glyphicon glyphicon-ok"></i> Lưu </button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="loading">
	<ul class="bokeh">
		<li></li>
		<li></li>
		<li></li>
	</ul>
</div>
<script>
	$('#send').click(function(){
		$('#loading').css('visibility', 'visible'); 
		$('.message').empty(); 
		var dataField={};
		$.each($("#channel_fields option:selected"), function(i,item){ 
			dataField[i] = item.value; 
		});
		var Fields=JSON.stringify(dataField); 
		var formData = new FormData();
		formData.append("channelName", $('input[name=channel_name]').val()); 
		formData.append("channelDescription", $('textarea[name=channel_about]').val()); 
		formData.append("channelField", Fields); 
		formData.append("metaTitle", $('input[name=metaTitle]').val()); 
		formData.append("metaDescription", $('textarea[name=metaDescription]').val()); 
		$.ajax({
			url: "{{route('channel.admin.setting.update',$channel['domain']->domain)}}",
			type: 'POST', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				$('#loading').css('visibility', 'hidden');
				console.log(result); 
				if(result.success==true){
					$('.message').append('<div class="alert alert-success alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					$("#alertError").alert();
					$("#alertError").fadeTo(2000, 500).slideUp(500, function(){
						$("#alertError").slideUp(500); 
						//location.reload(); 
					});
				}else{
					$('.message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				}
			},
			error: function(result) {
			}
		});
	}); 
	getChannelAttributeImageCover(); 
	function getChannelAttributeImageCover(){
		$("#changeImageCove").val(''); 
		$.ajax({
			url: "{{route('channel.attribute.image.cover',array($channel['domain']->domain,$channel['info']->id))}}",
			type: "GET",
			dataType: "json",
			success: function (result) {
				$('.carousel-inner').empty(); 
				if(result.gallery.length){
					$.each(result.gallery, function(i, item) {
						if(i==0){
							$('.carousel-inner').append('<div class="item active" style="position:relative; "><button class="btn btn-xs btn-danger delBannerChannel"  data-id="'+item.id+'" style="position:absolute; right:5px; top:5px;"><i class="glyphicon glyphicon-trash"></i> Xóa</button><img class="img-reponsive" style="width:100%; " src="'+item.media_url_small+'"></div>');
						}else{
							$('.carousel-inner').append('<div class="item" style="position:relative; "><button class="btn btn-xs btn-danger delBannerChannel"  data-id="'+item.id+'" style="position:absolute; right:5px; top:5px;"><i class="glyphicon glyphicon-trash"></i> Xóa</button><img class="img-reponsive" style="width:100%; " src="'+item.media_url_small+'"></div>');
						}
					})
				}else{
					$('.carousel-inner').append('<div class="item active">'
						+'<img class="img-responsive" style="width:100%; " src="{{asset('assets/img/banner-default.jpg')}}" alt="">'
					+'</div>');
				}
			}
		});
	}
	$('#mediaFileLogo').bind("change", function(){
		$('#loading').css('visibility', 'visible');
		var files = $("#mediaFileLogo").prop("files")[0];  
		$(".addMediaMessage" ).append( '<div class="progress" id="progress-upload"><div class="progress-bar" role="progressbar"></div></div>' );
		var formData = new FormData();
		formData.append("file", files); 
		formData.append("postType", "logo"); 
		$.ajax({
			url: "{{route('channel.upload.file',$channel['domain']->domain)}}",
			type: 'post', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
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
				console.log(result); 
				var formDataChannel = new FormData();
				formDataChannel.append("mediaId", result.id); 
				$.ajax({
					url: "{{route('channel.attribute.media.logo',$channel['domain']->domain)}}",
					type: 'post', 
					headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
					cache: false,
					contentType: false,
					processData: false,
					data: formDataChannel,
					dataType:'json',
					success:function(resultMedia){
						$('#loading').css('visibility', 'hidden');
						$("#logoChannel").attr('src', result.url);
					},
					error: function(resultMedia) {
					}
				});
			},
			error: function(result) {
			}
		});
		
	});
	$('#changeImageCove').bind("change", function(){
		var files = $("#changeImageCove").prop("files");  
		var totalFile=files.length; 
		var mediaId= [];
		if(totalFile>0){
			$('#loading').css('visibility', 'visible');
			for(var i=0;i<totalFile;i++)
			{
				$(".addMediaMessage" ).append( '<div class="progress" id="progress-upload"><div class="progress-bar" role="progressbar"></div></div>' );
				var formData = new FormData();
				formData.append("file", files[i]); 
				formData.append("postType", "banner"); 
				$.ajax({
					url: "{{route('channel.upload.file',$channel['domain']->domain)}}",
					headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
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
						var formDataChannel = new FormData();
						formDataChannel.append("channelAttributeType", 'banner'); 
						formDataChannel.append("channelAttributeValue", result.id); 
						$.ajax({
							url: "{{route('channel.attribute.media.add',$channel['domain']->domain)}}",
							type: 'post', 
							headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
							cache: false,
							contentType: false,
							processData: false,
							data: formDataChannel,
							dataType:'json',
							success:function(resultMedia){
								$('#loading').css('visibility', 'hidden');
								getChannelAttributeImageCover(); 
							},
							error: function(resultMedia) {
							}
						});
					},
					error: function(result) {
					}
				});
			}
		}
    });
	$('.carousel-inner').on("click",".delBannerChannel",function() {
		var mediaId= $(this).attr('data-id'); 
		var formData = new FormData();
		formData.append("mediaId", mediaId); 
		formData.append("channelAttributeType", 'banner'); 
		$('#loading').css('visibility', 'visible');
		$.ajax({
			url: "{{route('channel.attribute.media.delete',$channel['domain']->domain)}}",
			type: 'post', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				console.log(result); 
				$('#loading').css('visibility', 'hidden');
				if(result.success==true){
					getChannelAttributeImageCover(); 
				}
			},
			error: function(result) {
			}
		}); 
	});
	function progressHandlingFunctionAddMedia(e){
		var progress_bar_id         = '#progress-upload';
		var percent = 0;
		var position = e.loaded || e.position;
		var total = e.total; 
		if(e.lengthComputable){
			percent = Math.ceil(position / total * 100);
			$(progress_bar_id +" .progress-bar").css("width", + percent +"%"); 
			$(progress_bar_id +" .progress-bar").text(percent +"%"); 
			if (e.loaded == e.total) {
				$(progress_bar_id).hide(); 
				$(".addMediaMessage" ).append('<button class="btn btn-success btn-xs" id="progress-after" style="margin-bottom:5px; "><i class="fa fa-spinner fa-spin"></i> Đang xử lý vui lòng chờ trong giây lát! </button>'); 
			}
		}
	}
</script>
<link href="{{asset('assets/js/summernote/dist/summernote.css')}}" rel="stylesheet">
<script src="{{asset('assets/js/summernote/dist/summernote.js')}}"></script>
<script src="{{asset('assets/js/summernote/lang/summernote-vi-VN.js')}}"></script>
<script src="{{asset('assets/js/summernote/dist/summernote-image-attributes.js')}}"></script>
<script>
$(document).ready(function() { 
	$('#summernote').summernote(
		{
			
			displayFields:{
				imageBasic:true,  // show/hide Title, Source, Alt fields
				imageExtra:false, // show/hide Alt, Class, Style, Role fields
				linkBasic:true,   // show/hide URL and Target fields for link
				linkExtra:false   // show/hide Class, Rel, Role fields for link
			},
			placeholder: 'Bạn đang viết gì? ', 
			dialogsInBody: true, 
			focus: true,
			minHeight: 150,   //set editable area's height 
			enterHtml: '<br>',
			//height:250,
			//minHeight:null,
			//maxHeight:null,
			toolbar: [
				// [groupName, [list of button]]
				['style', ['bold', 'italic', 'underline', 'clear']],
			], 	
			codemirror: { // codemirror options
				theme: 'monokai'
			}
	});
});
</script>
{!!Theme::asset()->container('footer')->add('bootstrap-multiselect', 'assets/js/bootstrap-multiselect.js')!!}
@include('themes.admin.inc.footer')