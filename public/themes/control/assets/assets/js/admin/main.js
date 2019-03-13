jQuery(document).ready(function($) {
	
	$(window).load(function(){
			$("#post_leftmenu").load("/adminwp/ajax/post_ajax_left");
			$("#cate_leftmenu").load("/adminwp/ajax/cate_ajax_left");
			//$("body").load("/adminwp/ajax/process_route");		
		});

});
function show_kenh(id){
	$('.kenh_sert_view_'+id).addClass('hide');
	$('.show_kenh_eadmin_'+id).removeClass('hide');
}
function Load_Content($params,$page,$limit)
{
	$=jQuery;
	$('#body_inc').show();	
	var sendInfo = {
           'Params': $params,
           'Page': $page,
           'Limit': $limit,
           '_token': $('input[name=_token]').val()
       };
	$.ajax({
	  url: "/adminwp/ajax/process_postcontent",
	  type: "post",
	  dataType: "html",
	  data: sendInfo,
	  success: function (data) {
	       $("#body_inc").html(data);
	  }
	});
}
function Load_Content_Napas($params,$page,$limit)
{
    $=jQuery;
    $('#body_inc').show();
    var sendInfo = {
        'Params': $params,
        'Page': $page,
        'Limit': $limit,
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        url: "/adminwp/ajax/process_bill_napas",
        type: "post",
        dataType: "html",
        data: sendInfo,
        success: function (data) {
            $("#body_inc").html(data);
        }
    });
}
function Load_Content_Search($text_search="",$params,$page,$limit)
{
	$=jQuery;
	$('#body_inc').show();	
	var sendInfo = {
           'Params': $params,
           'Page': $page,
           'Limit': $limit,
           'Search':$text_search,
           '_token': $('input[name=_token]').val()
       };
	$.ajax({
	  url: "/adminwp/ajax/process_postcontent",
	  type: "post",
	  dataType: "html",
	  data: sendInfo,
	  success: function (data) {
	       $("#body_inc").html(data);
	  }
	});
}
function Load_Content_Search_Napas($text_search="",$params,$page,$limit)
{
    $=jQuery;
    $('#body_inc').show();
    var sendInfo = {
        'Params': $params,
        'Page': $page,
        'Limit': $limit,
        'Search':$text_search,
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        url: "/adminwp/ajax/process_bill_napas",
        type: "post",
        dataType: "html",
        data: sendInfo,
        success: function (data) {
            $("#body_inc").html(data);
        }
    });
}

function Load_Content_Customers($id)
{
	$=jQuery;
	$('#modal_load').show();	
	var sendInfo = {
           'id': $id,
           '_token': $('input[name=_token]').val()
       };
	$.ajax({
	  url: "/adminwp/load/customers_details",
	  type: "post",
	  dataType: "html",
	  data: sendInfo,
	  success: function (data) {
	       $("#modal_load").html(data);
	       $('body').addClass('modal-open');
	  }
	});
}	
function CloseModal(id){
	$('#'+id).slideToggle( "slow", function() {
		 $('body').removeClass('modal-open');
	});
}