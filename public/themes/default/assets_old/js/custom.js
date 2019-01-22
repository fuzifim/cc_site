$(window).scroll(function() {
	if($(window).scrollTop() + $(window).height() >= $('.group-section-content').height()) {
		var curentPage = parseInt($("#curentPage").val());
		var urlPage=$("#urlPage").val(); 
		var lastPage=parseInt($("#lastPage").val());
		var page_url_int=curentPage+1;
	   if(page_url_int<=lastPage){
			if(page_url_int==lastPage){
				$('#load_item_page').hide();
			}
			$(".curentPage").val(page_url_int);
			var page_url=urlPage+"?page="+page_url_int;
		    load_more(page_url);
	   }
	}
});
function load_more(page_url,page_url_int){
	//$("#loading-page").hide();
	$.ajax({
		type: "GET",
		url: page_url,
		dataType: "html",
		contentType: "text/html",
		beforeSend: function() {
			$("#loading-page").fadeIn("slow");
		},
		success: function(data) {

			$(data).find(".listItem").ready(function() {
				var content_ajax = $(data).find(".listItem").html();
				$(".listItem").append(content_ajax);
				$("#loading-page").fadeOut("slow");
				
			});
		}
	});
}
jQuery(document).ready(function($){
	$("#load_item_page").delegate(".click-more","click", function() {
		var curentPage = parseInt($("#curentPage").val());
		var urlPage=$("#urlPage").val(); 
		var lastPage=parseInt($("#lastPage").val());
		var page_url_int=curentPage+1;
		$("#loading-page").hide();
		if(page_url_int<=lastPage){
			if(page_url_int==lastPage){
				$('#load_item_page').hide();
			}
			$(".curentPage").val(page_url_int);
			var page_url=urlPage+"?page="+page_url_int;
			load_more(page_url,page_url_int); 
	   } 

	});
	var rootUrl=$('meta[name=root]').attr('content'); 
	getSelect(); 
	$('footer').on("click","#viewSelect",function() {
		showSelect(); 
	}); 
	function getSelect(){
		$('footer .showInfoSelect').empty(); 
		$.ajax({
			url: rootUrl+"/select/get",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'GET',
			cache: false,
			dataType:'json',
			success:function(resultGet){
				if(resultGet.success==true){
					if(Object.keys(resultGet.data).length>0){
						$('footer').append('<div class="showInfoSelect">'
							+'<div class="alert alert-success alert-dismissable">'
								+'<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>'
								+'<div class="contentInfoSelect"></div>'
							+'</div>'
						+'</div>'); 
						$('footer .showInfoSelect .contentInfoSelect').append('<div class="container"><div class="row"><i class="glyphicon glyphicon-check"></i> Bạn có <strong>'+Object.keys(resultGet.data).length+'</strong> mục đã chọn. <button type="button" class="btn btn-xs btn-primary" id="viewSelect"><i class="glyphicon glyphicon-hand-right"></i> xem</button></div></div>'); 
						Object.keys(resultGet.data).forEach(function(key) {
							console.log(resultGet.data[key]); 
							//$('footer .showInfoSelect .contentInfoSelect').append('<li>'+resultGet.data[key].name+'</li>'); 

						});
					}
				}
			},
			error: function(resultGet) {
			}
		});
	}
	function showSelect(){
		//$('#myModal').modal('hide'); 
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$.ajax({
			url: rootUrl+"/select/get",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'GET',
			cache: false,
			dataType:'json',
			success:function(resultGet){
				if(resultGet.success==true){
					if(Object.keys(resultGet.data).length>0){
						$('#myModal .modal-title').html('<i class="glyphicon glyphicon-check"></i> Bạn có <strong>'+Object.keys(resultGet.data).length+'</strong> mục đã chọn.'); 
						$('#myModal .modal-body').append(''
							+'<div class="row">'
								+'<div class="col-lg-6 col-md-6 sol-sm-12 col-xs-12">'
									+'<div class="form-group listItem"></div>'
								+'</div>'
								+'<div class="col-lg-6 col-md-6 sol-sm-12 col-xs-12">'
									+'<div class="form-group">'
										+'<div class="priceSelect"></div>'
									+'</div>'
									+'<div class="form-group">'
										+'<div class="box-comment" contentEditable="true"  placeholder="Nội dung yêu cầu..."></div>'
									+'</div>'
									+'<small class="text-info"><i>Khi bạn gửi yêu cầu, chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất với những mục bạn đã chọn. </i></small>'
								+'</div>'
							+'</div>'
						+''); 
						var i=0; 
						var priceSelect=0; 
						Object.keys(resultGet.data).forEach(function(key) {
							//console.log(resultGet.data[key]); 
							i++; 
							priceSelect+=parseInt(resultGet.data[key].price); 
							$('#myModal .modal-body .listItem').append('<li class="list-group-item"><a href="#" data-dismiss="alert" aria-label="close" class="close deleteSelect" data-id="'+resultGet.data[key].id+'">×</a><strong>'+i+'.</strong> <a href="'+resultGet.data[key].attributes.link+'"><img src="'+resultGet.data[key].attributes.image+'" style="height:20px;"> '+resultGet.data[key].name+'</a> <small><i>('+parseInt(resultGet.data[key].price).toLocaleString()+'<sup>'+resultGet.data[key].attributes.currency+'</sup>)</i></small></li>'); 
							$('#myModal .modal-body .priceSelect').html('<strong>Tổng số tiền: '+priceSelect.toLocaleString()+'<sup>'+resultGet.data[key].attributes.currency+'</sup></strong>'); 

						});
					} 
					$('#myModal .modal-footer').append('<div class="text-right"><button type="button" class="btn btn-success" id="sendSelect"><i class="glyphicon glyphicon-envelope"></i> Gửi yêu cầu</button></div>'); 
					$('#myModal').modal('show'); 
				}else{
					$('#myModal').modal('hide'); 
				}
			},
			error: function(resultGet) {
			}
		});
	}
	$('#myModal .modal-footer').on("click","#sendSelect",function() {
		$('#loading').css('visibility', 'visible'); 
		//$('#myModal').modal('hide'); 
		var cartMessage=$('#myModal .modal-body .box-comment').text(); 
		var formData = new FormData();
		formData.append("cartMessage", cartMessage); 
		$.ajax({
			url: rootUrl+"/select/send",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				$('#loading').css('visibility', 'hidden');
				$('#myModal .modal-title').empty(); 
				$('#myModal .modal-body').empty(); 
				$('#myModal .modal-footer').empty(); 
				$('#myModal .modal-body').append(''
					+'<div class="form-group">'
						+'<div class="text-center">Cảm ơn bạn đã gửi yêu cầu đến chúng tôi. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất. </div>'
					+'</div>'
				+''); 
				$('#myModal').delay(30000).fadeOut('slow');
				   setTimeout(function() {
						$("#myModal").modal('hide'); 
						showSelect(); 
						getSelect(); 
				}, 7000);
			},
			error: function(result) {
			}
		}); 
	});
	$('#myModal .modal-body').on("click",".deleteSelect",function() {
		$('#loading').css('visibility', 'visible'); 
		$('#myModal').modal('hide'); 
		var idSelect=$(this).attr('data-id'); 
		var formData = new FormData();
		formData.append("idSelect", idSelect); 
		$.ajax({
			url: rootUrl+"/select/delete",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				$('#loading').css('visibility', 'hidden');
				showSelect(); 
				getSelect(); 
			},
			error: function(result) {
			}
		}); 
	});
	$('.postGallery').on("click",".imageShow",function() {
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		if($(this).attr('url-lg')){
			var urlImage=$(this).attr('url-lg'); 
		}else{
			var urlImage=$(this).attr('src'); 
		}
		$('#myModal .modal-body').append('<div class="text-center"><img class="img-responsive" src="'+urlImage+'"></div>');
		$('#myModal').modal('show');
	});
	$('.section-content').on("click",".itemSelect",function() {
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		var rootUrl=$('meta[name=root]').attr('content'); 
		var itemId=$(this).attr('data-id'); 
		var formData = new FormData();
		formData.append("itemId", itemId); 
		$.ajax({
			url: rootUrl+"/select/add",
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
					if(result.error=='login'){
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
							+'<a href="'+$('meta[name=root]').attr('content')+'/forgotpassword"><i class="glyphicon glyphicon-lock"></i> Quên mật khẩu</a> | <a href="'+$('meta[name=root]').attr('content')+'/register"><i class="glyphicon glyphicon-ok-sign"></i> Đăng ký</a>'
							+'</div>'); 
						$('#myModal .modal-footer').append('<button type="button" id="btnLogin" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Đăng nhập</button>');
						$('#myModal').modal('show');
					}
					
				}else if(result.success==true){
					var rootUrl=$('meta[name=root]').attr('content'); 
					$('footer .showInfoSelect').empty(); 
					$.ajax({
						url: rootUrl+"/select/get",
						headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
						type: 'GET',
						cache: false,
						dataType:'json',
						success:function(resultGet){
							getSelect(); 
						},
						error: function(resultGet) {
						}
					});
				}
			},
			error: function(result) {
			}
		});
	}); 
	$('.section-content').on("click",".likeUp",function() {
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		var rootUrl=$('meta[name=root]').attr('content'); 
		var postId=parseInt($(this).attr('data-id')); 
		var dataLike=parseInt($(this).find('.countLike_'+postId).text()); 
		var formData = new FormData();
		formData.append("postId", postId); 
		formData.append("likeType", 'like'); 
		formData.append("likeTable", 'posts'); 
		$.ajax({
			url: rootUrl+"/likes/add",
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
					if(result.error=='login'){
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
							+'<a href="'+rootUrl+'/forgotpassword"><i class="glyphicon glyphicon-lock"></i> Quên mật khẩu</a> | <a href="'+rootUrl+'/register"><i class="glyphicon glyphicon-ok-sign"></i> Đăng ký</a>'
							+'</div>'); 
						$('#myModal .modal-footer').append('<button type="button" id="btnLogin" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Đăng nhập</button>');
						$('#myModal').modal('show');
					}
					
				}else if(result.success==true){
					switch (result.action) {
						case "add":
							$(".countLike_"+postId).text(dataLike+1);
							$(".likeUp_"+postId).addClass('btn-success');
							break;
						case "delete":
							 $(".countLike_"+postId).text(dataLike-1); 
							 $(".likeUp_"+postId).removeClass('btn-success');
							break;
					}
				}
			},
			error: function(result) {
			}
		});
	});
	$('.section-content').on("click",".likeDown",function() {
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		var rootUrl=$('meta[name=root]').attr('content'); 
		var postId=parseInt($(this).attr('data-id')); 
		var dataLike=parseInt($(this).find('.countLikeDown_'+postId).text()); 
		var formData = new FormData();
		formData.append("postId", postId); 
		formData.append("likeType", 'unlike'); 
		formData.append("likeTable", 'posts'); 
		$.ajax({
			url: rootUrl+"/likes/add",
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
					if(result.error=='login'){
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
							+'<a href="'+rootUrl+'/forgotpassword"><i class="glyphicon glyphicon-lock"></i> Quên mật khẩu</a> | <a href="'+rootUrl+'/register"><i class="glyphicon glyphicon-ok-sign"></i> Đăng ký</a>'
							+'</div>'); 
						$('#myModal .modal-footer').append('<button type="button" id="btnLogin" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Đăng nhập</button>');
						$('#myModal').modal('show');
					}
					
				}else if(result.success==true){
					switch (result.action) {
                    case "add":
                        $(".countLikeDown_"+postId).text(dataLike+1);
						$(".likeDown_"+postId).addClass('btn-danger');
                        break;
                    case "delete":
                         $(".countLikeDown_"+postId).text(dataLike-1); 
						 $(".likeDown_"+postId).removeClass('btn-danger');
                        break;
                }
				}
			},
			error: function(result) {
			}
		});
	});
	$('.section-content').on("click",".btnShare",function() {
		var title=$(this).attr('data-title'); 
		var image=$(this).attr('data-image'); 
		var url=$(this).attr('data-url'); 
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		$('#myModal .modal-title').text('Chia sẻ'); 
		$('#myModal .modal-body').append('<div class="form-group">'+title+'</div>'
			+'<div class="row">'
				+'<div class="col-xs-6 col-sm-4 col-lg-3 text-center"><div class="form-group"><a class="btn btn-primary btn-block customer share" rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u='+url+'&amp;t='+title+'" id="fb-share" target="_blank"><i class="fa fa-facebook"></i> Facebook</a></div></div>'
				+'<div class="col-xs-6 col-sm-4 col-lg-3 text-center"><div class="form-group"><a class="btn btn-primary btn-block customer share" rel="nofollow" href="https://twitter.com/share?url='+url+'&amp;text='+title+'&amp;via=[via]&amp;hashtags=[hashtags]" target="_blank"><i class="fa fa-twitter"></i> Twitter</a></div></div>'
				+'<div class="col-xs-6 col-sm-4 col-lg-3 text-center"><div class="form-group"><a class="btn btn-primary btn-block customer share" rel="nofollow" href="https://plus.google.com/share?url='+url+'" target="_blank"><i class="fa fa-google-plus"></i> Google+</a></div></div>'
				+'<div class="col-xs-6 col-sm-4 col-lg-3 text-center"><div class="form-group"><a class="btn btn-primary btn-block customer share" rel="nofollow" href="https://pinterest.com/pin/create/bookmarklet/?media='+image+'&amp;url='+url+'&amp;is_video=[is_video]&amp;description='+title+'" target="_blank"><i class="fa fa-pinterest"></i> Pinterest</a></div></div>'
			+'</div>');	
		$('#myModal').modal('show');
	});
	$('#myModal').on("click","#btnLogin",function() {
		$('#myModal .message').empty(); 
		var Email=$('#myModal input[name=email]').val(); 
		var Password=$('#myModal input[name=password]').val();  
		var rootUrl=$('meta[name=root]').attr('content'); 
		var formData = new FormData();
		formData.append("email", Email); 
		formData.append("password", Password); 
		$.ajax({
			url: rootUrl+"/login",
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
					$('#myModal .message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				}else if(result.success==true){
					$('#myModal .message').append('<div class="alert alert-success alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					$('#myModal').delay(1000).fadeOut('slow');
					   setTimeout(function() {
						   $("#myModal").modal('hide');
					}, 1500);
					location.reload(); 
				}
			},
			error: function(result) {
			}
		});
	});
});
;(function($){
$.fn.customerPopup = function (e, intWidth, intHeight, blnResize) {
    
    // Prevent default anchor event
    e.preventDefault();
    
    // Set values for window
    intWidth = intWidth || '500';
    intHeight = intHeight || '400';
    strResize = (blnResize ? 'yes' : 'no');

    // Set title and open popup with focus on it
    var strTitle = ((typeof this.attr('title') !== 'undefined') ? this.attr('title') : 'Social Share'),
        strParam = 'width=' + intWidth + ',height=' + intHeight + ',resizable=' + strResize,            
        objWindow = window.open(this.attr('href'), strTitle, strParam).focus();
  }
  
  /* ================================================== */
  
  $(document).ready(function ($) {
	  $('#myModal').on("click",".customer.share",function(e) {
		  $(this).customerPopup(e);
	  });
  });
    
}(jQuery));
$(document).ready(function(){
  $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
    event.preventDefault(); 
    event.stopPropagation(); 
    $(this).parent().siblings().removeClass('open');
    $(this).parent().toggleClass('open');
  });
});