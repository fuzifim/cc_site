<?
	$channel['theme']->setTitle('Cài đặt giao diện');
?>
@include('themes.admin.inc.header')
<link type="text/css" href="{{asset('assets/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet"/>
<script src="{{asset('assets/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js')}}"></script>
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				@include('themes.admin.partial.menuManageService')
			</div>
			<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="message"></div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group">
									<button type="button" tabindex="500" class="btn btn-xs btn-primary btn-file" style="position: relative; overflow: hidden; margin-bottom:5px; "><i class="glyphicon glyphicon-camera"></i>&nbsp;  <span>Chọn hình nền</span><input id="changeImageBackground" name="" accept="image/*" type="file" class=""></button> 1280x720
									<div class="imgBackground">
										@if(!empty($channel['info']->channelAttributeBackground->media->media_url))
											<img class="img-responsive" id="imgBackground" src="{{$channel['info']->channelAttributeBackground->media->media_url}}">
										@else
											<div class="noneBackground">Chưa cập nhật hình nền</div>
										@endif
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group">
									<label class="control-label" for="phone">Màu nền Menu</label>
									<div id="channelMenu" class="input-group">
										<input type="text" value="@if(!empty($channel['color']->channelMenu)){{$channel['color']->channelMenu}} @else #f8f8f8 @endif" name="channelColor[]" data-type="channelMenu" class="form-control" />
										<span class="input-group-addon"><i></i></span>
									</div>
									<script>
										$(function() {
											$('#channelMenu').colorpicker();
										});
									</script>
								</div>
								<div class="form-group">
									<label class="control-label" for="phone">Màu chữ Menu</label>
									<div id="channelMenuText" class="input-group">
										<input type="text" value="@if(!empty($channel['color']->channelMenuText)){{$channel['color']->channelMenuText}} @else #777 @endif" name="channelColor[]" data-type="channelMenuText" class="form-control" />
										<span class="input-group-addon"><i></i></span>
									</div>
									<script>
										$(function() {
											$('#channelMenuText').colorpicker();
										});
									</script>
								</div>
								<div class="form-group">
									<label class="control-label" for="phone">Màu nền Tiêu đề</label>
									<div id="channelTitle" class="input-group">
										<input type="text" value="@if(!empty($channel['color']->channelTitle)){{$channel['color']->channelTitle}} @else #d82731 @endif" name="channelColor[]" data-type="channelTitle" class="form-control" />
										<span class="input-group-addon"><i></i></span>
									</div>
									<script>
										$(function() {
											$('#channelTitle').colorpicker();
										});
									</script>
								</div>
								<div class="form-group">
									<label class="control-label" for="phone">Màu chữ Tiêu đề</label>
									<div id="channelTitleText" class="input-group">
										<input type="text" value="@if(!empty($channel['color']->channelTitleText)){{$channel['color']->channelTitleText}} @else #fff @endif" name="channelColor[]" data-type="channelTitleText" class="form-control" />
										<span class="input-group-addon"><i></i></span>
									</div>
									<script>
										$(function() {
											$('#channelTitleText').colorpicker();
										});
									</script>
								</div>
								<div class="form-group">
									<label class="control-label" for="phone">Màu nền Chân trang</label>
									<div id="channelFooter" class="input-group">
										<input type="text" value="@if(!empty($channel['color']->channelFooter)){{$channel['color']->channelFooter}} @else #333333 @endif" name="channelColor[]" data-type="channelFooter" class="form-control" />
										<span class="input-group-addon"><i></i></span>
									</div>
									<script>
										$(function() {
											$('#channelFooter').colorpicker();
										});
									</script>
								</div>
								<div class="form-group">
									<label class="control-label" for="phone">Màu chữ Chân trang</label>
									<div id="channelFooterText" class="input-group">
										<input type="text" value="@if(!empty($channel['color']->channelFooterText)){{$channel['color']->channelFooterText}} @else #fff @endif" name="channelColor[]" data-type="channelFooterText" class="form-control" />
										<span class="input-group-addon"><i></i></span>
									</div>
									<script>
										$(function() {
											$('#channelFooterText').colorpicker();
										});
									</script>
								</div>
								<div class="form-group text-right">
									<button type="button" class="btn btn-default" id="btnResetChannelColor"><i class="glyphicon glyphicon-refresh"></i> Trở về mặc định</button>
									<button type="button" class="btn btn-primary" id="btnSaveChannelTheme"><i class="glyphicon glyphicon-ok"></i> Lưu</button>
								</div>
							</div>
						</div>
					</div>
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
	$('#btnSaveChannelTheme').click(function(){
		$('#loading').css('visibility', 'visible'); 
		$(".message").empty(); 
		var dataColorJson={};
		$.each($("input[name='channelColor[]']"), function(i,item){ 
			dataColorJson[$(this).attr('data-type')] = item.value; 
		});
		var dataColor=JSON.stringify(dataColorJson); 
		//console.log(dataColor); 
		var formData = new FormData();
		formData.append("dataColor", dataColor); 
		$.ajax({
			url: "{{route('channel.attribute.color',$channel['domain']->domain)}}",
			type: 'post', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				//console.log(result); 
				$('#loading').css('visibility', 'hidden');
				$('.message').append('<div class="alert alert-success alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				$(".message").fadeTo(2000, 500).slideUp(500, function(){
					$(".message").slideUp(500);
				});
			},
			error: function(result) {
			}
		});
	});
	$('#btnResetChannelColor').click(function(){
		$.ajax({
			url: "{{route('channel.attribute.color.reset',$channel['domain']->domain)}}",
			type: 'post', 
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			success:function(result){
				$('#loading').css('visibility', 'hidden');
				$('.message').append('<div class="alert alert-success alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				$(".message").fadeTo(2000, 500).slideUp(500, function(){
					$(".message").slideUp(500);
				});
				location.reload();
			},
			error: function(result) {
			}
		});
	});
	$('#changeImageBackground').bind("change", function(){
		$('#loading').css('visibility', 'visible');
		var files = $("#changeImageBackground").prop("files")[0];  
		$(".message" ).append( '<div class="progress" id="progress-upload"><div class="progress-bar" role="progressbar"></div></div>' );
		var formData = new FormData();
		formData.append("file", files); 
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
				//console.log(result); 
				var formDataChannel = new FormData();
				formDataChannel.append("mediaId", result.id); 
				$.ajax({
					url: "{{route('channel.attribute.media.background',$channel['domain']->domain)}}",
					type: 'post', 
					headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
					cache: false,
					contentType: false,
					processData: false,
					data: formDataChannel,
					dataType:'json',
					success:function(resultMedia){
						$('#loading').css('visibility', 'hidden');
						//$("#imgBackground").attr('src', result.url);
						$('.imgBackground').empty(); 
						$('.imgBackground').append('<img class="img-responsive" src="'+result.url+'">'); 
					},
					error: function(resultMedia) {
					}
				});
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
@include('themes.admin.inc.footer')