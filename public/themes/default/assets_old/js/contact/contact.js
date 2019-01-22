var rootUrl=$('meta[name=root]').attr('content'); 
$('#btnSendContact').click(function(){
	$('#contactForm .message').empty(); 
	var formData = new FormData();
	formData.append("name", $('#contactForm input[name=name]').val()); 
	formData.append("email", $('#contactForm input[name=email]').val()); 
	formData.append("phone", $('#contactForm input[name=phone]').val()); 
	formData.append("title", $('#contactForm input[name=title]').val()); 
	formData.append("content", $('#contactForm textarea[name=content]').val()); 
	$.ajax({
		url: rootUrl+"/contact",
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
				if(result.messageType=='validation'){
					$('#contactForm .message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>'); 
					var res = jQuery.parseJSON(JSON.stringify(result.message)); 
					var name;
					jQuery.each(res, function(i, val) {
						if(i=='name'){
							name='Tên';
						}else if(i=='email'){
							name='Email';
						}else if(i=='phone'){
							name='Số điện thoại';
						}else if(i=='title'){
							name='Tiêu đề';
						}else if(i=='content'){
							name='Nội dung';
						}else{
							name='';
						}
						$('#alertError').append('<li>'+val+' <b>'+name+'</b> </li>'); 
						$('#contactForm .message').animate({
						scrollTop: $('#contactForm .message').offset().top},
						'slow');
					});
				}else{
					
				}
			}else{
				$('#myModal .modal-title').empty(); 
				$('#myModal .modal-body').empty(); 
				$('#myModal .modal-footer').empty(); 
				$('#myModal .modal-title').html('<i class="glyphicon glyphicon-envelope"></i> Yêu cầu liên hệ'); 
				$('#myModal .modal-body').append(''
					+'<div class="alert alert-success">'
					  +'<strong>Cám ơn!</strong> Bạn đã gửi 1 yêu cầu liên hệ đến chúng tôi, chúng tôi sẽ phản hồi lại bạn trong thời gian sớm nhất. '
					+'</div>'
				+''); 
				$('#myModal').modal('show'); 
				$('#myModal').delay(30000).fadeOut('slow');
				   setTimeout(function() {
						$("#myModal").modal('hide'); 
						location.reload(); 
				}, 7000);
			}
		},
		error: function(result) {
		}
	});
});