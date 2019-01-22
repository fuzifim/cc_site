/*Load ajax load content*/
//var page_url_int = 1;
$(window).scroll(function() { //detect page scroll
	if($(window).scrollTop() + $(window).height() >= $('#group_content_page_generate').height()) { //if user scrolled from top to bottom of the page
		//page_url_int++; //page number increment
		//'_token': $('input[name=_token]').val(),
		var token_load_company = parseInt($(this).parent().find("#token_load_company").val());
		var click = parseInt($("#check_click_company").val());
		var total = parseInt($("#total_company").val());
		var url_curent=$("#url_request_current").val(); 
		var item_load=$(this).parent().find("#load_item_get").val();
		var lastPage=parseInt($("#lastPage").val());
		var page_url_int=click+1;
		
	   // alert(click+"__"+page_url_int+"__"+lastPage+"URL:"+page_url); 
	   if(page_url_int<=lastPage){
			if(page_url_int==lastPage){
				$('#load_item_page').hide();
			}
			$(".check_click_company").val(page_url_int);
			var page_url=url_curent+"/?page="+page_url_int;
		    load_more(page_url); //load content   
			console.log(page_url); 
	   }
	}
});
function load_more(page_url,page_url_int){
	$("#loading-page").hide();
	$.ajax({
		type: "GET",
		url: page_url,
		dataType: "html",
		contentType: "text/html",
		beforeSend: function() {
			$("#loading-page").fadeIn("slow");
		},
		success: function(data) {

			$(data).find("#group_content_page_generate").ready(function() {
				var content_ajax = $(data).find("#group_content_page_generate").html();
				$("#group_content_page_generate").append(content_ajax);

				$('.carousel .carousel-page-'+page_url_int+' .item').each(function(){
					var next = $(this).next();
					if (!next.length) {
						next = $(this).siblings(':first');
					}
					next.children(':first-child').clone().appendTo($(this));

					for (var i=0;i<2;i++) {
						next=next.next();
						if (!next.length) {
							next = $(this).siblings(':first');
						}

						next.children(':first-child').clone().appendTo($(this));
					}
				});
				$("#loading-page").fadeOut("slow");
			});
		}
	});
}
jQuery(document).ready(function($){
	$("#load_item_page").delegate(".click-more","click", function() {
        var token_load_company = $("#token_load_company").val();
		var click = parseInt($("#check_click_company").val()); 
		var total = parseInt($("#total_company").val());
		var url_curent=$("#url_request_current").val(); 
		var item_load=parseInt($("#load_item_get").val());
		var lastPage=parseInt($("#lastPage").val());
		var page_url_int=click+1;
		$("#loading-page").hide();
	   // alert(click+"__"+page_url_int+"__"+lastPage+"URL:"+page_url);
	    if(page_url_int<=lastPage){
			if(page_url_int==lastPage){
				$('#load_item_page').hide();
			}
			$(".check_click_company").val(page_url_int);
			var page_url=url_curent+"/?page="+page_url_int;
		    load_more(page_url,page_url_int); //load content   
			console.log(page_url); 
	   } 

    });
    // scroll body to 0px on click
  $('#txt_search').bind('input keyup keydown keypress', function(){    
        var $value_txt=encodeURL($(this).val());
        $("#frm_search").attr("action", "http://cungcap.net/search/"+$value_txt+"/?page=1");
        $("#frm_search").attr("method","get");
    });
    $("#frm_search #search_btn").click(function() {
       if($('#txt_search').val()==''){
           alert('Nhập từ khóa cần tìm! ');
           $('#txt_search').focus();
            return false;
       }else{
           $('#frm_search').submit();
       }
    });//endsearch
	
    $('#upload_file_yt').change(function(evt) {
        // alert($(this).val());
        $("#youtube_upload_text").val($(this).val());
        $("#youtube_upload_text").attr("value",$(this).val());
    });
	
	$("#addCoupon").delegate("#addCouponBtn","click", function() {
		$('#addCoupon').addClass('hide');
		$('#coupon-body').removeClass('hide');
	});
	
    $(window).on('load resize', function () {
        var vs_767= window.matchMedia("only screen and (max-width: 767px)");
        if(vs_767.matches)
        {
            //$('#wrapper #page-content-wrapper').removeAttr('style');
            $(".navbar-header").delegate("button.nav-bar-search","click", function() {
                //alert('click');
                $('.search_form_container_view').css({
                    'cssText': 'display: block !important;',
                    'width': '100%',
                    'position': 'absolute',
                    'padding-right':'66px',
                    'padding-left':'5px'
                });
                $("#txt_search").focus();
            });


        }
        else
        {
            $('.search_form_container_view').removeAttr("style");
            if($('#wrapper #sidebar-wrapper #menu').length>0 && $('#wrapper #page-content-wrapper').length>0){
                var heighr_sidebar_wrapper=$('#wrapper #sidebar-wrapper #menu').outerHeight();
                var height_footer=$('footer#colophon').outerHeight();
                var height_max_sidebar=heighr_sidebar_wrapper+height_footer;
                    $('#wrapper').css({
                        "minHeight":height_max_sidebar

                    });

            }
            if(($('footer#colophon').outerHeight()+$('header#masthead').outerHeight()+$('#content_main').outerHeight())<$(window).outerHeight()){
                 $('#content_main').css({
                        "minHeight":+$(window).outerHeight()-($('footer#colophon').outerHeight()+$('header#masthead').outerHeight())

                  });
                 $('#page-content-wrapper').css({
                        "minHeight":+$(window).outerHeight()-($('footer#colophon').outerHeight()+$('header#masthead').outerHeight())

                  });
            }else{
                $('#content_main').removeAttr('style');
            }


        }

    });
    $('a#scroll_to_top').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });
	
	
    //upload file
    $("form#upload-form").validate({
        errorElement: "div",
        errorPlacement: function(error, element) {
            element.after(error).addClass('validate invalid error');
            offset = element.offset();
            //error.addClass('text-danger');  // add a class to the wrapper
            error.addClass('text-danger').insertAfter($(element).parents('.input-group'));
        },
        rules: {
            upload_file_yt:{
                required: true
                //email : true,
            }
        },
        messages: {
            upload_file_yt:{
                required : "Bạn cần chọn file upload"
                //email: "Email chÆ°a Ä‘Ãºng Ä‘á»‹nh dáº¡ng",
            }
        },

        submitHandler: function(form) {
            var formData = new FormData(form);
            var token=$('#token_upload').val();
            var progress_bar_id         = '#progress-wrp';
            $('#progress_bar_group').show();
            $(progress_bar_id +" .progress-bar").css("width", "0%");
            $(progress_bar_id + " .status").text("0%");
            $('button#tbn_upload_youtube_bt').prop('disabled', true);
            //formData.append('_token', token);
            $.ajax({

                type: "POST",
                url: "/upload/youtube",
                data: formData ? formData : form.serialize(),
                mimeTypes:'multipart/form-data',
                cache: false,
                contentType : false,
                processData : false,
                'headers':{
                    'X-CSRF-TOKEN': token
                },
               xhr: function(){
                    //upload Progress
                    var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
                            var percent = 0;
                            var position = event.loaded || event.position;
                            var total = event.total;
                            if (event.lengthComputable) {
                                percent = Math.ceil(position / total * 100);
                            }
                            //update progressbar
                            $(progress_bar_id +" .progress-bar").css("width", + percent +"%");
                            $(progress_bar_id + " .status").text(percent +"%");
                            if(percent==100){
                                $('#progress-wrp').html('<img class="icon_load" alt="icon-load" src="http://cungcap.net/img/loading.gif"><p>File đang được upload lên Youtube..</p>');
                            }
                        }, true);
                    }
                    return xhr;
                },
                success: function(data)
                {
                     if(data.msg == 'success'){
                        //window.location.reload();
                       // window.location.assign(data.link);
                       $('#progress_bar_group').hide();
                       $('#upload_youtubeModal').modal('toggle');
                       var youtube_link_success="https://www.youtube.com/embed/"+data.link; 
                       $('#link_upload_youtube').val(youtube_link_success);
                     }else{
                        $('button#tbn_upload_youtube_bt').prop('disabled', false);
                        alert("Lỗi upload youtube. Mời bạn thử lại sau");
                        $('#progress_bar_group').hide();
                        return false;
                     }
                },
                error : function (xhr, textStatus, errorThrown) {
                    $('button#tbn_upload_youtube_bt').prop('disabled', false);
                }
            });

            return false;
        }
    });// end upload form
	
	
	
	//login
	 $("form#login-form").validate({
        errorElement: "div",
        errorPlacement: function(error, element) {
            element.after(error).addClass('validate invalid error');
            offset = element.offset();
            //error.addClass('text-danger');  // add a class to the wrapper
            error.addClass('text-danger').insertAfter($(element).parents('.input-group')); 
        },
        rules: {
            email:{
            	required: true
            	//email : true,
            },
            password: {
                required:true,
                minlength: 6
            },               
        },
        messages: {
            email:{
            	required : "Nhập Email đăng nhập"
            	//email: "Email chÆ°a Ä‘Ãºng Ä‘á»‹nh dáº¡ng",
            },
            password: {
                required: "Nhập mật khẩu",
                minlength:"Tối thiểu 6 kí tự"
            },
        },
        submitHandler: function(form) {
            var checked;
            if($('input[name=remember]').prop('checked') === true){
                 checked=1;
            }else{
                checked=0;
            }
            $.ajax({
                type: "POST",
                url: "/login",
                data: {
                   	 '_token': $('input[name=_token]').val(),
                	 "email" : $( form ).find( 'input[name=email]' ).val(),
                	 "password" : $( form ).find( 'input[name=password]' ).val(),
                     "remember" : checked
                },
                dataType: 'json',
                success: function(data)
                {
                   if(data.msg == 'success'){
                        //window.location.reload();
                        window.location.assign(data.link);
                        //$.cookie('user_cungcapnet', $( form ).find( 'input[name=email]' ).val());
                        Cookies.set('user_cungcapnet', $( form ).find( 'input[name=email]' ).val());
                   }else{
                        $( form ).find( '#msg-login' ).text(data.msg);
                   }
                   
                }
             });

                    return false;

        }
    });// end login form


     //form register
    $("form#register-form").validate({
        errorElement: "div",
        errorPlacement: function(error, element) {
                element.after(error);
                offset = element.offset();
                error.addClass('text-danger');  // add a class to the wrapper
            },
        rules: {
            name :{
                "required": true,
                'alpha':true
            },
            phone :{
                "required": true,
                 number : true
            },
            email:{
                "required": true,
                "email" : true,
                "validateExistEmail":true
            },
            password: {
                required:true,
                minlength: 6
            },    
            password_confirmation: {
                required:true,
                minlength: 6,
                equalTo: "form#register-form #password"
            }, 
            accept_term: {
                required:true
            }

        },
        messages: {
            name :{
                "required": "Nhập họ tên đầy đủ",
                'alpha': "Vui lòng nhập các kí tự a-zA-Z và 0-9 và không có khoảng trắng.."
            },
            phone :{
                "required": "Nhập số điện thoại",
                 number:"Số điện thoại là dạng số 09xxxxxxxx"
            },
            email:{
                required : "Nhập email đăng nhập",
                email: "Email chưa đúng định dạng",
                validateExistEmail :"Email chưa đúng định dạng"
            },
            password: {
                required: "Nhập mật khẩu",
                minlength:"Tối thiểu 6 kí tự"
            },
            password_confirmation: {
                required: "Nhập xác nhận mật khẩu",
                minlength:"Tối thiểu 6 kí tự",
                equalTo: "Mật khẩu xác nhận chưa khớp"
            },
             accept_term: {
               required: "Bạn phải chấp nhận điều khoản của chúng tôi.."
            }
            
        },
        submitHandler: function(form) {

           form.submit();

        }
    });// end login form

    $("form#post-item-form").validate({
        errorElement: "div",
        errorPlacement: function(error, element) {
            element.after(error);
            offset = element.offset();
            error.addClass('text-danger');  // add a class to the wrapper
        },

        submitHandler: function(form) {

            form.submit();

        }
    });// end login form

    $('select#category_option_select').change(function () {
        var optionSelected = $(this).find("option:selected").val();
        if(optionSelected==2){
            $('#category_option_product_seleted').slideDown();
        }else{
            $('#category_option_product_seleted').slideUp();

        }
        if(optionSelected==5){
            $('#upload_youtube').slideDown();
        }else{
            $('#upload_youtube').slideUp();
        }

    });

    var category_option_select_ready=$('select#category_option_select').find("option:selected").val();
    if(category_option_select_ready==2){
        $('#category_option_product_seleted').slideDown();
    }else{
        $('#category_option_product_seleted').slideUp();

    }
    if(category_option_select_ready==5){
        $('#upload_youtube').slideDown();
    }else{
        $('#upload_youtube').slideUp();
    }


    $('select#regoin_profile').change(function () {
        var country=$(this).val();
        $.ajax({
            url: "/regions_ajax/"+country,
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('select#subregoin_profile').removeAttr('disabled');
                $("select#subregoin_profile").html(data);
            }
        });
    });
    $('select#subregoin_profile').change(function () {
        var country=$(this).val();
        $.ajax({
            url: "/subregions_ajax/"+country,
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('select#district_profile').removeAttr('disabled');
                $("select#district_profile").html(data);
            }
        });
    });
    $('select#district_profile').change(function () {
        var country=$(this).val();
        $.ajax({
            url: "/districregions_ajax/"+country,
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('select#ward_profile').removeAttr('disabled');
                $("select#ward_profile").html(data);
            }
        });
    });
    $('#address_template #address_enter').bind('input keyup keydown keypress', function(){
        var address_ds=$('#address_enter').val();
        var ward_ds=$('#ward_profile option:selected').text();
        var district_ds=$('#district_profile option:selected').text();
        var subregoin_ds=$('#subregoin_profile option:selected').text();
        var region_ds=$('#regoin_profile option:selected').text();
        var shop_address_full=address_ds+', '+ward_ds+', '+district_ds+', '+subregoin_ds+', '+region_ds;
        $('#shop-address-full').val(shop_address_full);
        //codeAddress();
    });


    $('#content_main').waypoint(function( direction ){

            if( direction == 'up'){
                $('#scroll_to_top').addClass('stuck');
            }else {
                 $('#scroll_to_top').removeClass('stuck');
             }
    },{offset: 'bottom-in-view'});


    //form post message
    $("form#send-message").validate({
        errorElement: "div",
        errorPlacement: function(error, element) {
            element.after(error);
            offset = element.offset();
            error.addClass('text-danger');  // add a class to the wrapper
        },
        rules: {

            title:{
                "required": true,
                minlength: 5
            },
            message: {
                required:true,
                minlength: 5
            }

        },
        messages: {

            title:{
                required : "Nhập tiêu đề nội dung",
                minlength: "Tối thiểu 20 kí tự"
            },
            message: {
                required: "Nhập tin nhắn",
                minlength:"Tối thiểu 50 kí tự"
            }
        },
        submitHandler: function(form) {
            $.ajax({
                type: 'POST',
                url: '/ajax/send_message',
                data: {
                    '_token': $('input[name=_token]').val(),
                    to_member : $( form ).find( 'input[name=to_user]' ).val(),
                    message_title:  $( form ).find( 'input[name=title]' ).val(),
                    message_body:  $( form ).find( 'textarea[name=message]' ).val()
                },
                dataType:'json',
                success:function(data){

                    $(form)[0].reset();
                    $(form).find('label#result').text(data.msg);

                }

            });
            return false;
        }
    });// end post message




	/* Masony Container*/
    /*
    $( window ).load( function(){
        var $container = $('#container_inc_list');
        var columns = 3,
            setColumns = function () {
                columns = $(window).width() > 1000 ? 5 : $(window).width() > 767 ? 4 : $(window).width() > 640 ? 3 : $(window).width() > 320 ? 2 : 1;
            };
        setColumns();
        $( window ).resize( setColumns );
        $container.imagesLoaded(function () {
            $container.masonry({
                itemSelector: '.post-item',
                isAnimated: true,
                isResizable: true,
                gutterWidth: 0,
                columnWidth:  function( containerWidth ) { return containerWidth / columns; }
            }).resize();

        });
    });
    */
	/*Releated Masory*/
    /*
	$( window ).load( function(){
        var $container_related = $('#products_related_content_read');
		if($container_related.length>0){
			var $window_releated=$(window).width()*75/100;
			console.log("Window Relead:"+$window_releated);
			var columns = 4,
				setColumns = function () {
					columns = $(window).width() > 1000 ? 4 : $(window).width() > 767 ? 3 : $(window).width() > 640 ? 2 : $(window).width() > 320 ? 2 : 1;
				};
			setColumns();
			$container_related.resize( setColumns );
			$container_related.imagesLoaded(function () {
				$container_related.masonry({
					itemSelector: '.product_releated_item',
					isAnimated: true,
					isResizable: true,
					gutterWidth: 0,
					columnWidth:  function( containerWidth ) { return containerWidth / columns; }
				}).resize();

			});
		}
    });
    */
/*Auto Height home*/
    var height_primary=$("ul.main-nav li.menu_dm>ul.primary-menu").outerHeight();
    var height_main=$("#content_main").outerHeight();
    var height_content_check=height_main-height_primary;
    var height_footer=$('footer#colophon').outerHeight();
    $(".main-nav .menu_dm")
        .mouseenter(function() {
            if(height_content_check<0){
                $('#content_main').css({height:+(height_primary+10)});
            }
        })
        .mouseleave(function() {
            $('#content_main').removeAttr('style');
        });

    $("ol.breadcrumb li.dropdown")
        .mouseenter(function() {
            var height_drop_down=$(this).find("ul.dropdown-menu").outerHeight();

            if((height_main-height_drop_down)<0){
                $('#content_main').css({height:+(height_drop_down+height_footer+40)});
            }
        })
        .mouseleave(function() {
            $('#content_main').removeAttr('style');
        });
/*Auto Height home*/


    
    

    /*End load content*/
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    $("#menu-toggle-2").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled-2");
        $('#menu ul').hide();
    });
    initMenu();



    //form register
    $(".template_setting_website form#post-about-form").validate({
        errorElement: "div",
        errorPlacement: function(error, element) {
            element.after(error);
            offset = element.offset();
            error.addClass('text-danger');  // add a class to the wrapper
        },
        rules: {
            title_shop :{
                "required": true
            },
            phone_contact :{
                "required": true,
                 number : true
            },
            blog_title:{
                "required": true
            },
            region_select: {
                "required":true
            },
            subregion_select: {
                "required":true
            }

        },
        messages: {
            title_shop :{
                "required": "Nhập tên cửa hàng hoặc công ty"
            },
            phone_contact :{
                "required": "Nhập số điện thoại",
                 number:"Số điện thoại là dạng số 09xxxxxxxx"
            },
            blog_title:{
                "required": "Nhập Tiêu đề Website"
            },
            region_select: {
                "required": "Lựa chọn quốc gia"
            },
            subregion_select: {
                "required": "Lựa chọn thành phố"
            }
        },
        submitHandler: function(form) {

            form.submit();

        }
    });// end login form
  
  if($('#item_tag_products').length>0){ 
		var url_root=$('#url_root').val();
        $('#item_tag_products').autocomplete({
            serviceUrl: url_root+'/autocomplete/search_tag',
            type:'GET',
            paramName:'txt',
            dataType:'json',
            minChars:2,
            onSearchComplete: function(){
                $(this).css('background', 'transparent');
            },
            //lookup: currencies,
            onSelect: function (suggestion) {
                //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
            }
        });
   }
   if($('#frm_search #txt_search').length>0){ 
		var url_root=$('#url_root').val();
        $('#frm_search #txt_search').autocomplete({ 
            serviceUrl: url_root+'/autocomplete/search_header',
            type:'GET',
            paramName:'txt',
            dataType:'json',
            minChars:2,
            deferRequestBy:100,
            onSearchComplete: function(){
                $(this).css('background', 'transparent');
            },
            //lookup: currencies,
            onSelect: function (suggestion) {
                var $value_txt=encodeURL(suggestion.value); 
                var $value_data_string=suggestion.data.split('-')[0]; 
				var $dataId=suggestion.data.split('-')[1]; 
				//console.log(suggestion)
				//alert($value_data_string); 
                //var $value_data_string_split=$value_data_string.split('/');
                //var $value_data_user=$value_data_string_split[0];
                $("#frm_search").attr("action", url_root+"/search/"+$value_data_string+"/"+$dataId+"/?page=1");
                $("#frm_search").attr("method","get");
                $('#frm_search').submit();
                //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
            }
        });
   }
    if($('#taxcode_company_primary').length>0){ 
		var url_root=$('#url_root').val();
        $('#taxcode_company_primary').autocomplete({
            serviceUrl: url_root+'/autocomplete/search_taxcode',
            type:'GET',
            paramName:'taxcode',
            dataType:'json',
            minChars:4,
            onSearchComplete: function(){
                $(this).css('background', 'transparent');
                $('.autocomplete-suggestions').css({
                    'width':+$(this).outerWidth()
                })
                
            },
            //lookup: currencies,
            onSelect: function (suggestion) {
                //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
                //console.log(suggestion.data.tax_code);
               $(this).val(suggestion.data.tax_code);
			   $('#id_ps_auto').val(suggestion.data.custom_id);
               $('#name_ps_auto').val(suggestion.data.custom_company);
               $('#phone_ps_auto').val(suggestion.data.phone);
               $('#shop-address-full').val(suggestion.data.address);
               $('#address_enter').val('');  
              //codeAddress();
               console.log(suggestion.data);
               $('#regoin_profile option')
                    .removeAttr('selected')
                    .filter('[value="'+suggestion.data.country+'"]')
                    .prop("selected","selected");

                $('#subregoin_profile option')
                    .removeAttr('selected')
                    .filter('[value="'+suggestion.data.city+'"]')
                    .prop("selected","selected");   
                 //load ajax get quan huyen
                    $.ajax({
                        type: "GET",
                        url: url_root+"/ajax/autocomplete_load_quan/"+suggestion.data.city,
                        dataType: "html",
                        contentType: "text/html",
                        beforeSend: function() {
                           // $(".lighbox_show").show();
                           // $("#overlay_load").show();
                            $("#overlay_load").fadeIn("slow");
                            $(".lighbox_show").fadeIn("slow");
                        },
                        success: function(data) {
                            $("#overlay_load").fadeOut("slow");
                            $(".lighbox_show").fadeOut("slow");
                            $('#district_profile').html(data);

                            $('#district_profile option')
                                .removeAttr('selected')
                                .filter('[value="'+suggestion.data.district+'"]')
                                .prop("selected","selected");       
                            

                             /*Load phuong xa*/       
                              $.ajax({
                                    type: "GET",
                                    url: url_root+"/ajax/autocomplete_load_xa/"+suggestion.data.district,
                                    dataType: "html",
                                    contentType: "text/html",
                                    beforeSend: function() {
                                       // $(".lighbox_show").show();
                                       // $("#overlay_load").show();
                                        //$("#overlay_load").fadeIn("slow");
                                        //$(".lighbox_show").fadeIn("slow");
                                    },
                                    success: function(data) {
                                       // $("#overlay_load").fadeOut("slow");
                                       // $(".lighbox_show").fadeOut("slow");
                                        $('#ward_profile').html(data);

                                        $('#ward_profile option')
                                            .removeAttr('selected')
                                            .filter('[value="'+suggestion.data.ward+'"]')
                                            .prop("selected","selected");       
                                        
                                    }
                                });         
                              /*End load phuong xa*/  
                             
                        },
                        error: function(xhr, status, error) {
                                 $("#overlay_load").fadeOut("slow");
                                 $(".lighbox_show").fadeOut("slow");
                        }  
                         
                    });   

                 /*End set*/   
            }
        });
    }
    $(".container_post_tag").delegate("button#add_tag_products","click", function() {
		var text_add=$('input[name=tag_products]').val(); 
		var ads_id=$('input[name=ads_id]').val(); 
		var url_root=$('#url_root').val();
        if($.trim(text_add).length == 0){
            $('#tag_alert_error').show();
            return false;
        }
        else
        {
			$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')} }); 
			var formData = new FormData(); 
			formData.append("text_add", text_add); 
			formData.append("ads_id", ads_id); 
            $.ajax({
                type: 'post',
                url: url_root+'/add_tag/',
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
                dataType:'json',
                success:function(result){
					console.log(result); 
                    if(result.msg == 'success'){
                        $('#item_tag_products').val('');
						$.ajax({
                            type: 'post',
                            url: url_root+'/show_tag/',
                            data: {
                                '_token': $('input[name=_token]').val(),
                                ads_id: $('form#post-item-form').find('input[name=ads_id]' ).val()
                            },
                            dataType: 'html',
                            success: function (dataSet) {
                                $('.tag_container_value_set').show();
                                $('.tag_container_value_set #tag_post').html(dataSet);
                            }
                        });
                        //alert('ok');
                        
                    }
                }

            });
        }
    });


 
    /*
    $(".find_company_info").delegate("button#btn-search-code","click", function() {
        var input_taxcode=$('#tax-code-valid').val();
        if(input_taxcode !=''){
            $.ajax({
                url: "/ajax/load_taxcode",
                data: {
                    '_token': $('input[name=_token]').val(),
                    'tax-code':input_taxcode
                },
                type: "POST",
                dataType: 'html',
                beforeSend: function(){
                    $("#overlay_load").fadeIn("slow");
                    $(".lighbox_show").fadeIn("slow");
                },
                success: function(data) {
                    $('.alert_tax_code').hide();
                    $("#overlay_load").fadeOut("slow");
                    $(".lighbox_show").fadeOut("slow");
                    $('.group_container_contact_view').html(data);

                }
            });
        }
        else
        {
            $('.alert_tax_code').show();
            $('.alert_tax_code').html('<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Phải phải nhập mã số thuế');
            return false;
        }
    })
    */
    $(window).on("load resize",function(){

        /*Auto height every item container_region_promotion */
        $('.row_region_promotion .container_region_promotion').each(function() {
            var maxHeight_promotion = -1;
            $(this).find('.item-post-promotion').each(function() {
                if($(this).outerHeight() > maxHeight_promotion)
                    maxHeight_promotion = $(this).outerHeight();
            });
            $(this).find('.item-post-promotion').css({'height':+maxHeight_promotion});
        });


    });
});/*End Jquery Ready*/



function initMenu() {
    $('#menu ul').hide();
    $('#menu ul').children('.current').parent().show();
        getHeightWapper();
    //$('#menu ul:first').show();
    $('#menu li a').click(
        function() {
            $('#menu li').removeClass('active');
            var checkElement = $(this).next();
            $(this).parent().addClass('active');
            getHeightWapper();
            if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
                return false;
            }
            if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
                $('#menu ul:visible').slideUp('normal');
                checkElement.slideDown('normal');
                return false;
            }
        }
    );
}
function validateSelect(validateName,elementName){
    // Validate select
    jQuery.validator.addMethod(validateName, function(value) {
        //alert(id);
        var isSuccess = false;
        if(jQuery('select[name="'+elementName+'"]').val() != "-1"){
            isSuccess = true;
        }
        return isSuccess;
    }, "");
}

function commaSeparateNumber(val){
        while (/(\d+)(\d{3})/.test(val.toString())){
          val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
        }
        return val;
}
function getHeightWapper(){
    if($('#wrapper #sidebar-wrapper #menu').length>0 && $('#wrapper #page-content-wrapper').length>0){
        var heighr_sidebar_wrapper=$('#wrapper #sidebar-wrapper #menu').outerHeight();
        var height_footer=$('footer#colophon').outerHeight();
        var height_max_sidebar=heighr_sidebar_wrapper+height_footer;
        $('#wrapper').css({
            "minHeight":height_max_sidebar
        });

    }
}

function setLikeWebsite(id,action) {
    var url_request=$('.like_btn_'+id).find('.url_current_'+id).val();
    $.ajax({
        url: url_request+"/like/"+id+"/"+action,
        data: {
            '_token': $('input[name=_token]').val()
        },
        type: "POST",
        dataType: 'json',
        beforeSend: function(){

        },
        success: function(data) {
            if(data.msg == 'success') {
                var likes = parseInt($('.like_input_value_' + id).val());
                switch (action) {
                    case "like":
                        likes = likes + 1;
                        $('.like_btn_' + id + ' .portfolio_utube_suscribe_button').html('<a class="tag_unlike" onclick="setLikeWebsite(' + id + ',\'unlike\')" href="javascript:void(0)"><i class="glyphicon glyphicon-thumbs-up"></i>Đã Thích</a><span class="like_value like_value_' + id + '">' + likes + '</span>');
                        $('.like_input_value_' + id).val(likes);
                        break;
                    case "unlike":
                        likes = likes - 1;
                        $('.like_btn_' + id + ' .portfolio_utube_suscribe_button').html(' <a class="tag_like" onclick="setLikeWebsite(' + id + ',\'like\')" href="javascript:void(0)"><i class="glyphicon glyphicon-thumbs-up"></i>Thích</a><span class="like_value like_value_' + id + '">' + likes + '</span>');
                        $('.like_input_value_' + id).val(likes);
                        break;
                }
            }
            else if(data.msg == 'login')
            {
                alert('Bạn phải đăng nhập mới sử dụng chức năng này');
                window.location = data.link;
            }
            else{
                alert('Hệ thống ko hiểu bạn click cái gì');
                window.location = data.link;
            }
        }
    });
}
function setLikePost(id,action) {
    var url_request=$('.url_current_product_'+id).val();
    $.ajax({
        url: url_request+"/like_post/"+id+"/"+action,
        data: {
            '_token': $('input[name=_token]').val()
        },
        type: "POST",
        dataType: 'json',
        beforeSend: function(){

        },
        success: function(data) {
            if(data.msg == 'success') {
                var likes = parseInt($('.url_current_like_product_' + id).val());
                switch (action) {
                    case "like":
                        likes = likes + 1;
                        $('.like_post_set').html('<button type="button" class="btn btn-default" onclick="setLikePost('+id+',\'unlike\')"><i class="glyphicon glyphicon-thumbs-up"></i> '+likes+'</button>');
                        $('.url_current_like_product_' + id).val(likes);
                        break;
                    case "unlike":
                        likes = likes - 1;
                        $('.like_post_set').html('<button type="button" class="btn btn-default" onclick="setLikePost('+id+',\'like\')"><i class="glyphicon glyphicon-thumbs-up"></i> '+likes+'</button>');
                        $('.url_current_like_product_' + id).val(likes);
                        break;
                }
            }
            else if(data.msg == 'login')
            {
                alert('Bạn phải đăng nhập mới sử dụng chức năng này');
                window.location = data.link;
            }
            else{
                alert('Hệ thống ko hiểu bạn click cái gì');
                window.location = data.link;
            }
        }
    });
}
function setUnLikePost(id,action) {
    var url_request=$('.url_current_product_'+id).val();
    $.ajax({
        url: url_request+"/unlike_post/"+id+"/"+action,
        data: {
            '_token': $('input[name=_token]').val()
        },
        type: "POST",
        dataType: 'json',
        beforeSend: function(){

        },
        success: function(data) {
            if(data.msg == 'success') {
                var likes = parseInt($('.url_current_unlike_product_' + id).val());
                switch (action) {
                    case "like":
                        likes = likes + 1;
                        $('.unlike_post_set').html('<button type="button" class="btn btn-default" onclick="setUnLikePost('+id+',\'unlike\')"><i class="glyphicon glyphicon-thumbs-down"></i> '+likes+'</button>');
                        $('.url_current_unlike_product_' + id).val(likes);
                        break;
                    case "unlike":
                        likes = likes - 1;
                        $('.unlike_post_set').html('<button type="button" class="btn btn-default" onclick="setUnLikePost('+id+',\'like\')"><i class="glyphicon glyphicon-thumbs-down"></i> '+likes+'</button>');
                        $('.url_current_unlike_product_' + id).val(likes);
                        break;
                }
            }
            else if(data.msg == 'login')
            {
                alert('Bạn phải đăng nhập mới sử dụng chức năng này');
                window.location = data.link;
            }
            else{
                alert('Hệ thống ko hiểu bạn click cái gì');
                window.location = data.link;
            }
        }
    });
}
function notCareAds(id){
    var url_request=$('.like_btn_'+id).find('.url_current_'+id).val();
    $.ajax({
        url: url_request+"/notcare/"+id+"/",
        data: {
            '_token': $('input[name=_token]').val()
        },
        type: "POST",
        dataType: 'json',
        beforeSend: function(){
            $("#overlay_load").fadeIn("slow");
            $(".lighbox_show").fadeIn("slow");
        },
        success: function(data) {
            $("#overlay_load").fadeOut("slow");
            $(".lighbox_show").fadeOut("slow");
            if(data.msg == 'success') {
                $('#panel_change_'+id).hide();
                return true;
            }
            else if(data.msg == 'error_dup')
            {
                alert('Bạn không thể ẩn website của bạn');
                return false;
            }
            else{
                alert('Hệ thống ko hiểu bạn click cái gì');
                return false;
            }
        }
    });
}
function delete_tag(slug,id){
    if (confirm("Có phải bạn muốn xóa tag này?")) {
		var url_root=$('#url_root').val();
        $.ajax({
            url: url_root+"/delete_tag/",
            data: {
                '_token': $('input[name=_token]').val(),
                'slug':slug,
                'ads_id':id
            },
            type: "POST",
            dataType: 'json',
            beforeSend: function () {
                $("#overlay_load").fadeIn("slow");
                $(".lighbox_show").fadeIn("slow");
            },
            success: function (result) {
                if(result.msg == 'success') {
                    $("#overlay_load").fadeOut("slow");
                    $(".lighbox_show").fadeOut("slow");

                    $.ajax({
                        type: 'POST',
                        url: url_root+'/show_tag/',
                        data: {
                            '_token': $('input[name=_token]').val(),
                            ads_id: id
                        },
                        dataType: 'html',
                        success: function (dataSet) {
                            $('.tag_container_value_set').show();
                            $('.tag_container_value_set #tag_post').html(dataSet);
                        }
                    });

                }else{
                    alert('Lỗi xóa Tag');
                }
            }
        });
    }
}
function delete_website(id){
    var url_request=$('#url_root').val();
   // var confirm = confirm("Are you sure you want to delete?");
    if (confirm("Bạn có chắc muốn xóa")) {
		$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')} });
        $.ajax({
            url: url_request + "/website_delete/" + id + "/",
            data: {
                '_token': $('input[name=_token]').val()
            },
            type: "POST",
            dataType: 'json',
            beforeSend: function () {
                $("#overlay_load").fadeIn("slow");
                $(".lighbox_show").fadeIn("slow");
            },
            success: function (data) {
                $("#overlay_load").fadeOut("slow");
                $(".lighbox_show").fadeOut("slow");
                if (data.msg == 'success') {
                    $('#website_view_' + id).hide();
                    return true;
                }
                else if (data.msg == 'error') {
                    alert('Lỗi xóa website');
                    return false;
                }
                else {
                    alert('Lỗi xóa website');
                    return false;
                }
            }
        });
    }
}
$('#ads-price').on('keyup', function (event) {
    var number = commaSeparateNumber($('#ads-price').val());
    $('#price-split').text("Giá sản phẩm: "+number+" VNĐ");

})
$('#name').on('keyup', function (event) {
    $('p#subdomain').text("Link riêng của bạn: http://"+$('#name').val()+".cungcap.net");
})
$('#discount').on('keyup', function (event) {
    var number = commaSeparateNumber($('#discount').val());
    $('#price-discount').text("Giá giảm: "+number+" VNĐ");
})

function encodeURL(toEncode) {
    return encodeURIComponent(toEncode)
        .replace(/!/g, '%21')
        .replace(/'/g, '%27')
        .replace(/\(/g, '%28')
        .replace(/\)/g, '%29')
        .replace(/\*/g, '%2A');
}
