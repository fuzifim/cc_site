<?
	$theme->setTitle('Đăng bài');
?>
@include('themes.admin.inc.header')
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				@include('themes.admin.partial.menuManageService')
			</div>
			<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
				<div class="insertForm"></div>
				<div class="add_message_summernote"></div>
				<div class="form-group">
					<input id="item-title" name="postTitle" style="font-size:18px;" value="{{$post->posts_title}}" type="text" class="form-control title-post-edit" placeholder="Nhập tiêu đề bài đăng">
					<small>
						<span style="color:#666; ">{!!WebService::time_request($post->posts_updated_at)!!}</span> - <b>Trên:</b> <a href="{{route('channel.home',$channel['domain']->domain)}}">{{$channel['info']->channel_name}}</a> 
						<div class="addCategoryContent">
							@if(count($post->postsJoinCategory)>0)
								@foreach($post->postsJoinCategory as $cat)
									<a class="btn btn-xs categoryItem" data-id="{{$cat->getCategory->id}}" data-name="{{$cat->getCategory->category_name}}" data-href="{{route('channel.slug',array($channel['domain']->domain,$cat->getCategory->getSlug->slug_value))}}"><i class="glyphicon glyphicon-folder-open"></i> {{$cat->getCategory->category_name}}</a> 
								@endforeach
							@endif
						</div>
						<div class="attributePrice">@if(!empty($post->price->posts_attribute_value))<strong>Giá: </strong><span class="text-danger">{!!Site::price($post->price->posts_attribute_value)!!}</span> {{$channel['info']->channelJoinRegion->region->currency_code}} <button type="button" class="btn btn-xs btn-danger" id="delPrice"><i class="glyphicon glyphicon-remove"></i></button>@endif</div>
					</small>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<button type="button" tabindex="500" class="list-group-item list-group-item-info btn-file" style="position: relative; overflow:hidden; "><i class="glyphicon glyphicon-camera"></i>&nbsp;  <span>Thêm File/ Ảnh/ Video</span><input id="postMedia" name="input4[]" type="file" multiple="" class=""></button>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<button type="button" class="list-group-item list-group-item-info" id="addCategory"><i class="glyphicon glyphicon-list"></i> Chọn danh mục</button>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<button type="button" class="list-group-item list-group-item-info" id="addAttribute"><i class="glyphicon glyphicon-list-alt"></i> Thêm thuộc tính</button>
						</div>
					</div><!--
					<div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
						<div class="form-group">
							<a href="" class="list-group-item"><i class="glyphicon glyphicon-tags"></i> Thêm tag</a>
						</div>
					</div>-->
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<button type="button" class="list-group-item list-group-item-info" id="addOptions"><i class="glyphicon glyphicon-check"></i> Tùy chọn</button>
						</div>
					</div>
				</div>
				<div class="form-group">
					<textarea name="postContent" id="summernote" class="form-control">{{ htmlspecialchars_decode($post->posts_description) }}</textarea>
				</div>
				<div class="fileMedia">
					<div class="insert-media-files row">
						
					</div>
					<div class="insert-media-gallery row">
						
					</div>
				</div>
				<div class="form-group text-right">
					<a class="btn btn-danger postDelete" href="#" style="margin-right:20px; "><i class="glyphicon glyphicon-trash"></i> Xóa</a> 
					<button class="btn btn-primary" type="button" name="send" id="savePost"><i class="glyphicon glyphicon-ok"></i> Lưu</button> 
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
	$('.insert-media-gallery').on("click","img",function() {
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$('#myModal .modal-body').append('<img class="img-responsive" src="'+$(this).attr('url-lg')+'">');
		$('#myModal').modal('show');
	});
</script>
<script>
	$('.postDelete').click(function(){
		if(confirm('Bạn có chắc muốn xóa?')){
			var formData = new FormData();
			formData.append("postId", {{$post->id}}); 
			$.ajax({
				url: "{{route('channel.post.delete',$channel['domain']->domain)}}",
				headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
				type: 'post',
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				dataType:'json',
				success:function(result){
					console.log(result); 
					$(".add_message_summernote" ).append( '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					$('html, body').animate({scrollTop: $(".add_message_summernote").offset().top}, 'slow'); 
					setTimeout(function(){
						window.location.href="{{route('channel.trash.list',$channel['domain']->domain)}}"; 
					},1000);
				},
				error: function(result) {
				}
			});
		}
	}); 
	$('#addOptions').click(function(){
		getOptions(); 
	}); 
	function getOptions(){
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		var formData = new FormData();
		formData.append("postId", {{$post->id}}); 
		$.ajax({
			url: "{{route('channel.post.attribute.options.get',$channel['domain']->domain)}}",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				$('#myModal .modal-title').text(result.message); 
				$('#myModal .modal-body').append('<div class="message"></div>'); 
				var checkOnCategory=''; 
				var viewFullScreen=''; 
				if(result.success==true && result.options.hideOnCategory=='checked'){
					checkOnCategory='checked'; 
				}
				if(result.success==true && result.options.viewFullScreen=='checked'){
					viewFullScreen='checked'; 
				}
				$('#myModal .modal-body').append(''
					+'<div class="form-group">'
						+'<div class="checkbox">'
							+'<label><input type="checkbox" name="options[]" data-type="hideOnCategory" value="" '+checkOnCategory+'>Ẩn hiển thị trên các danh mục</label>'
						+'</div>'
					+'</div>'
					+'<div class="form-group">'
						+'<div class="checkbox">'
							+'<label><input type="checkbox" name="options[]" data-type="viewFullScreen" value="" '+viewFullScreen+'>Xem toàn màn hình</label>'
						+'</div>'
					+'</div>'
				+''); 
				$('#myModal .modal-footer').append('<div class="text-right"><button type="button" class="btn btn-primary" id="btnSaveOptions"><i class="glyphicon glyphicon-ok"></i> Lưu</button></div>'); 
				$('#myModal').modal('show'); 
			},
			error: function(result) {
			}
		});
	}
	getPostAttributeGallery(); 
	function getPostAttributeGallery(){
		$.ajax({
			url: "{{route('channel.post.attribute.gallery',array($channel['domain']->domain,$post->id))}}",
			type: "GET",
			dataType: "json",
			success: function (result) {
				//console.log(result); 
				$('.insert-media-gallery').empty(); 
				$('.insert-media-files').empty(); 
				$("#postMedia").val(''); 
				$.each(result.gallery, function(i, item) {
					if(item.media_type.match('image.*')){
						$('.insert-media-gallery').append('<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group" style="position:relative; max-height:150px; min-height:150px; overflow:hidden; "><button type="button" class="btn btn-xs btn-danger delMedia"  data-id="'+item.id+'" style="position:absolute; right:5px; top:5px;"><i class="glyphicon glyphicon-trash"></i> Xóa</button><img class="img-reponsive img-thumbnail" style="width:100%; " src="'+item.media_url_xs+'" url-lg="'+item.media_url+'"></div></div>');
					}else if(item.media_type.match('video.*')){
						$('.insert-media-gallery').append('<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group" style="position:relative; max-height:150px; min-height:150px; overflow:hidden; "><button type="button" class="btn btn-xs btn-danger delMedia"  data-id="'+item.id+'" style="position:absolute; right:5px; top:5px; z-index:1; "><i class="glyphicon glyphicon-trash"></i> Xóa</button>'
						+'<video style="width:100%; height:auto; " controls>'
						  +'<source src="'+item.media_url+'" type="video/mp4">'
						  +'<source src="'+item.media_url+'" type="video/ogg">'
							+'Your browser does not support the video tag.'
						+'</video>'
						+'</div></div>');
					}else if(item.media_storage=='files'){
						$('.insert-media-files').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
							+'<div class="form-group">'
								+'<li class="list-group-item">'
									+'<a href="'+item.media_url+'"><i class="glyphicon glyphicon-download-alt"></i> '+item.media_name+'</a>'
									+'<button type="button" class="pull-right btn btn-xs btn-danger delMedia"  data-id="'+item.id+'" style="position:absolute; right:5px; top:5px;"><i class="glyphicon glyphicon-trash"></i> Xóa</button>'
								+'</li>'
							+'</div>'
						+'</div>');
					}
				}); 
			}
		});
	}
	$('#myModal').on("click","#btnSaveOptions",function() {
		var dataJson={};
		$.each($('input[name="options[]"]:checked'), function(i,item){ 
			dataJson[$(this).attr('data-type')] = 'checked'; 
		});
		var dataOptions=JSON.stringify(dataJson); 
		var formData = new FormData(); 
		formData.append("options", dataOptions); 
		formData.append("postId", {{$post->id}}); 
		$.ajax({
			url: "{{route('channel.post.attribute.options.post',$channel['domain']->domain)}}",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				getOptions(); 
			},
			error: function(result) {
			}
		});
	});
	$('.addCategoryContent').on("click",".categoryItem",function() {
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		var categoryName=$(this).attr('data-name'); 
		var categoryUrl=$(this).attr('data-href');
		var	categoryId=$(this).attr('data-id'); 
		$('#myModal .modal-title').text(categoryName); 
		$('#myModal .modal-body').append('<div class="text-center"><div class="contentModal text-center"><a class="btn btn-sm btn-success" href="'+categoryUrl+'"><i class="glyphicon glyphicon-eye-open"></i> Xem</a> <button type="button" class="btn btn-sm btn-danger btnDelCategoryPost" data-id="'+categoryId+'"><i class="glyphicon glyphicon-trash"></i> Xóa</button></div></div>'); 
		$('#myModal').modal('show'); 
	});
	
	$( "#addCategory" ).click(function() {
		$('#loading').css('visibility', 'visible'); 
		$('.message').empty(); 
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$.ajax({
			url: "{{route('post.join.category.list',$channel['domain']->domain)}}",
			type: "GET",
			dataType: "json",
			success: function (result) {
				$('#myModal .modal-title').text(result.message); 
				$('#myModal .modal-body').append('<div class="message"></div>'); 
				$.each(result.category, function(i, item) {
					$('#myModal .modal-body').append('<div class="list-group-item"><span class=""><botton type="button" class="btn btn-xs btn-success btnSelectCategory" data-id="'+item.id+'" data-name="'+item.category_name+'"><i class="glyphicon glyphicon-ok"></i> Chọn</button></span> <strong>'+item.category_name+'</strong> <small>'+item.category_description+'</small> </div>');
				}); 
				$('#myModal .modal-footer').append('<div class="form-group"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button></div>'); 
				$('#loading').css('visibility', 'hidden'); 
				$('#myModal').modal('show'); 
			}
		});
	});
	$('#myModal').on("click",".btnSelectCategory",function() {
		var categoryId=$(this).attr('data-id'); 
		var categoryName=$(this).attr('data-name'); 
		$('#myModal .message').empty(); 
		var formData = new FormData();
		formData.append("categoryId", categoryId); 
		formData.append("postId", {{$post->id}}); 
		$.ajax({
			url: "{{route('post.join.category.add',$channel['domain']->domain)}}",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				if(result.success==false){
					$('#myModal .message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				}else if(result.success==true){
					$('.addCategoryContent').empty(); 
					$.each(result.category, function(i, item) {
						$('.addCategoryContent').append('<a class="btn btn-xs categoryItem" data-id="'+item.id+'" data-name="'+item.category_name+'" data-href=""><i class="glyphicon glyphicon-folder-open"></i> '+item.category_name+'</a> ');
					}); 
					$('#loading').css('visibility', 'hidden'); 
					$('#myModal').modal('hide'); 
				}
			},
			error: function(result) {
			}
		});
	});
	$('#myModal').on("click",".btnDelCategoryPost",function() {
		$(".add_message_summernote" ).empty(); 
		$('#modalPost').modal('hide');
		$('#loading').css('visibility', 'visible'); 
		var categoryId=$(this).attr('data-id'); 
		var formData = new FormData();
		formData.append("categoryId", categoryId); 
		formData.append("postId", {{$post->id}}); 
		$.ajax({
			url: "{{route('post.join.category.delete',$channel['domain']->domain)}}",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){ 
				$('.addCategoryContent').empty(); 
				$.each(result.category, function(i, item) {
					$('.addCategoryContent').append('<a class="btn btn-xs categoryItem" data-id="'+item.id+'" data-name="'+item.category_name+'" data-href=""><i class="glyphicon glyphicon-folder-open"></i> '+item.category_name+'</a> ');
				}); 
				$('#myModal').modal('hide'); 
				$('#loading').css('visibility', 'hidden'); 
			},
			error: function(result) {
			}
		});
	});
	$( "#addAttribute" ).click(function() {
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$('#myModal .modal-title').text('Thêm thuộc tính'); 
		$('#myModal .modal-body').append(''
		+'<div class="form-group">'
			+'<button class="btn btn-xs btn-primary" id="addAttributePrice"><i class="glyphicon glyphicon-plus"></i> Thêm giá</button>'
		+'</div>'); 
		$('#myModal .modal-footer').append('<div class="text-right"><button type="button" class="btn btn-primary" id="btnSaveAttribute"><i class="glyphicon glyphicon-ok"></i> Lưu</button></div>'); 
		$('#myModal').modal('show'); 
	}); 
	$('#myModal').on("click","#addAttributePrice",function() {
		var attributePrice = $('#myModal').find('#attributePrice'); 
		if(attributePrice.length <= 0){
			$('#myModal .modal-body').append('<div class="messagePrice"></div>'
			+'<div class="form-group" id="attributePrice">'
				+'<div class=""><code><i>Giá không khoảng cách, không ký tự. Vd: 500000 (=500.000{{$channel["info"]->channelJoinRegion->region->currency_code}})</i></code></div>'
				+'<div class="input-group">'
					+'<span class="input-group-addon">{{$channel["info"]->channelJoinRegion->region->currency_code}}</span>'
					+'<input type="number" name="attributePrice" class="form-control" value="" placeholder="Vd: 500000">'
					+'<span class="input-group-addon"><button type="button" class="btn btn-xs btn-danger" id="delElementPrice" style="padding:0px 5px; "><i class="glyphicon glyphicon-remove"></i></button></span>'
				+'</div>'
			+'</div>');
		}
		
	}); 
	$('#myModal').on("click","#delElementPrice",function() {
		$('#attributePrice').remove(); 
	});
	$('#myModal').on("click","#btnSaveAttribute",function() {
		$('#myModal .messagePrice').empty(); 
		var attributePrice=$('#myModal #attributePrice input[name=attributePrice]').val(); 
		var formData = new FormData();
		formData.append("attributePrice", attributePrice); 
		formData.append("postId", {{$post->id}}); 
		$.ajax({
			url: "{{route('channel.post.attribute.add',$channel['domain']->domain)}}",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){ 
				console.log(result); 
				if(result.success==false){
					$('#myModal .messagePrice').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				}else if(result.success==true){
					$('#myModal .messagePrice').append('<div class="alert alert-success alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					$('.attributePrice').append('<strong>Giá: </strong><span class="text-danger">'+result.price+'</span> <button type="button" class="btn btn-xs btn-danger" id="delPrice"><i class="glyphicon glyphicon-remove"></i></button>'); 
					$('#myModal').modal('hide'); 
				}
			},
			error: function(result) {
			}
		});
	});
	$('.attributePrice').on("click","#delPrice",function() {
		var formData = new FormData();
		formData.append("postId", {{$post->id}}); 
		$.ajax({
			url: "{{route('channel.post.attribute.delete.price',$channel['domain']->domain)}}",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){ 
				if(result.success==true){
					$('.attributePrice').empty(); 
				}
			},
			error: function(result) {
			}
		});
	});
	$( "#savePost" ).click(function() {
		$('#loading').css('visibility', 'visible'); 
		$(".add_message_summernote" ).empty(); 
		var formData = new FormData();
		formData.append("channelId", {{$channel['info']->id}}); 
		formData.append("postId", {{$post->id}}); 
		formData.append("postTitle", $('input[name=postTitle]').val()); 
		formData.append("slugValue", $('input[name=slugValue]').val()); 
		formData.append("postContent", $('textarea[name=postContent]').val()); 
		$.ajax({
			url: "{{route('channel.post.save',$channel['domain']->domain)}}",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				console.log(result); 
				$('#loading').css('visibility', 'hidden'); 
				if(result.success==true){
					$(".add_message_summernote" ).append( '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>');
					$('html, body').animate({scrollTop: $(".insertForm").offset().top}, 'slow');
					$(".add_message_summernote").fadeTo(2000, 500).slideUp(500, function(){
						$(".add_message_summernote").slideUp(500);
					});
				}else if(result.success==false){
					$(".add_message_summernote" ).append( '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					$('html, body').animate({scrollTop: $(".add_message_summernote").offset().top}, 'slow');
				}
			},
			error: function(result) {
			}
		});
	});
	$('#postMedia').bind("change", function(){
		$('#loading').css('visibility', 'visible'); 
		$(".add_message_summernote" ).empty(); 
		var files = $("#postMedia").prop("files");  
		var totalFile=files.length; 
		var postTitle=$('input[name=postTitle]').val(); 
		var mediaId= [];
		if(totalFile>0){
			if(postTitle.length) {
				$("#loading" ).append( '<div class="progress" id="progress-upload"><div class="progress-bar" role="progressbar"></div></div>' );
				for(var i=0;i<totalFile;i++)
				{
					var formData = new FormData();
					formData.append("file", files[i]); 
					formData.append("postTitle", postTitle); 
					formData.append("postId", {{$post->id}}); 
					formData.append("postType", "post"); 
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
							$('#loading').css('visibility', 'hidden');
							if(result.success==true){
								var formDataPost = new FormData();
								formDataPost.append("postId", {{$post->id}}); 
								formDataPost.append("postAttributeType", 'gallery'); 
								formDataPost.append("postAttributeValue", result.id); 
								$.ajax({
									url: "{{route('channel.post.attribute.gallery.insert',$channel['domain']->domain)}}",
									type: 'post', 
									headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
									cache: false,
									contentType: false,
									processData: false,
									data: formDataPost,
									dataType:'json',
									success:function(resultMedia){
										getPostAttributeGallery(); 
										$(".add_message_summernote" ).empty(); 
										$(".add_message_summernote" ).append( '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+resultMedia.message+'</div>');
										$(".add_message_summernote").fadeTo(2000, 500).slideUp(500, function(){
											$(".add_message_summernote").slideUp(500);
										});
									},
									error: function(resultMedia) {
									}
								});
							}else if(result.success==false){
								$(".add_message_summernote" ).append( '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.msg+'</div>'); 
								$('html,body').animate({
								scrollTop: $('.add_message_summernote').offset().top},
								'slow');
							}
						},
						error: function(result) {
						}
					}); 
				}
			}else{
				$('#loading').css('visibility', 'hidden');
				$(".add_message_summernote" ).append( '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Vui lòng nhập tiêu đề bài viết trước khi tải Ảnh hay Video</div>'); 
				$('html,body').animate({
				scrollTop: $('.add_message_summernote').offset().top},
				'slow');
			}
		}
    });
	$('.fileMedia').on("click",".delMedia",function() {
		if(confirm('Bạn có chắc muốn xóa?')){
			$(".add_message_summernote" ).empty(); 
			var mediaId= $(this).attr('data-id'); 
			var formData = new FormData();
			formData.append("mediaId", mediaId); 
			formData.append("postId", {{$post->id}}); 
			formData.append("postAttributeType", 'gallery'); 
			$('#loading').css('visibility', 'visible');
			$.ajax({ 
				url: "{{route('post.attribute.media.delete',$channel['domain']->domain)}}",
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
					getPostAttributeGallery(); 
					$(".add_message_summernote" ).append( '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>');
					$(".add_message_summernote").fadeTo(2000, 500).slideUp(500, function(){
						$(".add_message_summernote").slideUp(500);
					});
				},
				error: function(result) {
				}
			}); 
		}
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
				$('#loading').css('visibility', 'hidden');
				$(".add_message_summernote" ).append('<button class="btn btn-success btn-xs" id="progress-after" style="margin-bottom:5px; "><i class="fa fa-spinner fa-spin"></i> Đang xử lý vui lòng chờ trong giây lát! </button>'); 
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
			popover: {
				image: [
					['custom', ['imageAttributes']],
					['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
					['float', ['floatLeft', 'floatRight', 'floatNone']],
					['remove', ['removeMedia']]
				],
			},
			lang: "vi-VN", 
			imageAttributes:{
				imageDialogLayout:'default', // default|horizontal
				icon:'<i class="note-icon-pencil"/>',
				removeEmpty:false // true = remove attributes | false = leave empty if present
			},
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
				}
			}
	});
	function uploadImage(file,editor,welEditable) {
		$(".add_message_summernote" ).empty(); 
		var postTitle=$('input[name=postTitle]').val(); 
		var postContent=$('textarea[name=postContent]').val(); 
		if(postTitle.length) {
			var formData = new FormData(); 
			formData.append("file", file); 
			formData.append("postId", {{$post->id}}); 
			formData.append("postTitle", postTitle); 
			formData.append("postContent", postContent); 
			$.ajax({
				url: "{{route('channel.upload.file',$channel['domain']->domain)}}",
				headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')}, 
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
						if(result.MimeType.match('image.*')){
							$('#summernote').summernote('editor.insertImage', result.url_thumb, function ($image) {
							 $image.addClass("imageShow"); 
							  $image.attr('url-lg', result.url_thumb);
							});
						}else if(result.MimeType.match('video.*')){
							var node = $(''
							+'<video style="width:100%; height:auto; " controls>'
							  +'<source src="'+result.url+'" type="video/mp4">'
								+'Your browser does not support the video tag.'
							+'</video>'); 
							$('#summernote').summernote('editor.insertNode', node[0]); 
						}else if(result.media_storage=='files'){
							$('.insert-media-files').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
								+'<div class="form-group">'
									+'<li class="list-group-item">'
										+'<a href="'+result.url+'"><i class="glyphicon glyphicon-download-alt"></i> '+result.filename+'</a>'
										+'<button type="button" class="pull-right btn btn-xs btn-danger delMedia"  data-id="'+result.id+'" style="position:absolute; right:5px; top:5px;"><i class="glyphicon glyphicon-trash"></i> Xóa</button>'
									+'</li>'
								+'</div>'
							+'</div>');
						}
						$('#progress-after').hide(); 
						$(".add_message_summernote" ).append( '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.msg+'</div>');
						$(".add_message_summernote").fadeTo(2000, 500).slideUp(500, function(){
							$(".add_message_summernote").slideUp(500);
						});
					}else{
						$('#progress-after').hide(); 
						$(".add_message_summernote" ).append( '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.msg+'</div>'); 
						$('html, body').animate({scrollTop: $(".add_message_summernote").offset().top}, 'slow');
					}
				},
				error: function(result) {
					console.log(result);
					$('#progress-after').hide(); 
					$(".add_message_summernote" ).append( '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.msg+'</div>');
				}
			});
		}else{
			$(".add_message_summernote" ).append( '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Vui lòng nhập tiêu đề bài viết trước khi tải Ảnh hay Video</div>'); 
			$('html, body').animate({scrollTop: $(".add_message_summernote").offset().top}, 'slow');
		}
	}
	function progressHandlingFunction(e){
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
				$(".add_message_summernote" ).append('<button class="btn btn-success btn-xs" id="progress-after" style="margin-bottom:5px; "><i class="fa fa-spinner fa-spin"></i> Đang xử lý vui lòng chờ trong giây lát! </button>'); 
			}
		}
	}
});
</script>
@include('themes.admin.inc.footer')