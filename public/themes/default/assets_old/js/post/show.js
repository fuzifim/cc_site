$('.box-comment').keyup(function () {
	$('#commentContent').val($(this).text());
});

$('#btnCommentClick').click(function(){
	$('html,body').animate({
	scrollTop: $('#panelComment').offset().top},
	'slow');
}); 
$('#panelComment').on("click",".addComment",function() {
	$('.messageComment').empty(); 
	$('#myModal .modal-title').empty(); 
	$('#myModal .modal-body').empty(); 
	$('#myModal .modal-footer').empty(); 
	var rootUrl=$('meta[name=root]').attr('content'); 
	var parentId=$(this).attr('parent-id'); 
	var formData = new FormData();
	formData.append("postId", $(this).attr('data-id')); 
	formData.append("parentId", parentId); 
	formData.append("table", 'posts'); 
	formData.append("commentContent", $('#panelComment input[name=commentContent]').val()); 
	$.ajax({
		url: rootUrl+"/comment/add",
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
				$('.messageComment').append('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				if(result.messageType=='login'){
					$('#myModal .modal-title').text('Đăng nhập'); 
					$('#myModal .modal-body').append('<div class="message"></div><div class="form-group"><div class="input-group">'
						+'<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>'
						+'<input placeholder="Email hoặc điện thoại" id="email" name="email" type="text" class="form-control valid" value="" aria-required="true" aria-invalid="false">'
						+'</div></div>'
						+'<div class="form-group">'
							+'<div class="input-group">'
								+'<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>'
								+'<input placeholder="Mật khẩu" id="password" type="password" name="password" class="form-control valid" aria-required="true" aria-invalid="false">'
							+'</div>'
						+'</div>'
						+'<div class="form-group">'
						+'<a href="'+rootUrl+'/lost-password"><i class="glyphicon glyphicon-lock"></i> Quên mật khẩu</a> | <a href="'+rootUrl+'/register"><i class="glyphicon glyphicon-ok-sign"></i> Đăng ký</a>'
						+'</div>'); 
					$('#myModal .modal-footer').append('<button type="button" id="btnLogin" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Đăng nhập</button>');
					$('#myModal').modal('show');
				}
			}else if(result.success==true){
				$('.messageComment').append('<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				location.reload(); 
			}
		},
		error: function(result) {
		}
	});
});