@extends('inc.master')
@section('seo')
<?php 
    $data_seo = array(
        'title' => 'Tin nhắn |'.config('app.seo_title'),
        'keywords' => '',
        'description' => '',
        'og_title' => '',
        'og_description' => '',
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
                            <li class="dropdown active" itemprop="itemListElement"><a data-toggle="dropdown" itemprop="item" href=""><span class="glyphicon glyphicon-cog"></span> Bảng điều khiển</a> <span class="caret"></span>
								@include('partials.menu_dropdown_dashboard')
							</li>
							<li itemprop="itemListElement">Tin nhắn</li>
						</ol>
                    </div>
                </div>
            </div>
			<div class="row no-gutter">
					<div id="user-change-pass" class="col-sm-12 col-md-12 col-lg-12 message_container_show_all">
						<div class="clear content_messge">	
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
							<div  class="clear entry_content">
								<p class="clearfix"></p>
								<div class="row">
									<div class="col-md-12">
										<div class="btn-group">
											<div class="btn btn-default"><input type="checkbox" style="z-indmargin: 0; vertical-align: middle;" class="checkall"/></div>
											<button type="button" data-toggle="dropdown" class="button_cs_v btn btn-default dropdown-toggle"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
											<ul role="menu" class="dropdown-menu">
												<li><input id="s-message" name="keywords" type="text" class="form-control" placeholder="tìm tin nhắn..."></li>
												<li><a href="javascript:void(0)" onclick="Message.status({message_status:'inbox', page: 1})" >All</a></li>
												<li><a href="javascript:void(0)" onclick="Message.status({filter: 'read', message_status:'inbox', page: 1})">Đã xem</a></li>
												<li><a href="javascript:void(0)" onclick="Message.status({filter: 'unread', message_status:'inbox', page: 1})">Chưa xem</a></li>
											</ul>
										</div>

										<button type="button" data-toggle="tooltip" title="Làm mới" onclick="Message.status({message_status:'inbox', page: 1})"  class="btn btn-default mls mrs"><span class="fa fa-refresh"></span></button>
										<button id="destroy-message" type="button" style="display:none" class="btn btn-default">
											<i class="fa fa-trash-o"></i> Xóa
										</button>
									</div>
								</div> 
								<p class= "clear"></p>
								<div class="clear">
									 <ul class="nav nav-tabs">
										<li class="active"><a href="javascript:void(0)" onclick="Message.status({message_status:'inbox', page: 1})" data-toggle="tab"><span class="fa fa-inbox"></span>&nbsp;
											Thư đến</a></li>
										<li>
											<a href="javascript:void(0)" onclick="Message.status({message_status:'outbox', page: 1})" class="outbox-msg btn btn-default">
											<i class="fa fa-plane fa-fw mrs"></i>Thư đi</a>
										</li>
										<li>
											<a href="javascript:void(0)" onclick="Message.status({message_status:'destroy', page: 1})" class="destroy-msg btn btn-default">
											<i class="fa fa-trash-o"></i> Đã xóa</a>
										</li>
									  
									</ul>
									<div class="tab-content">
										<div id="home-message" class="tab-pane fade in active">
											
										</div>
									</div>			
								</div>
								
								@include('message.message-send')
							</div><!-- end # register-form-container -->
						   
						</div><!-- end post-ads -->
					</div>	
			</div>	
		</div>
	</div>	
<script>
	var Message = {

        /* 
            status: inbox, outbox, destroy
        */
        status : function(data){
            return jQuery.ajax({
                method: 'GET',
                url : '/ajax/message/status',
                data: data,    
                beforeSend:function(){
                    jQuery('#home-message').html(loadling);
                },
                success: function(data){
                    jQuery('#home-message').html(data);
                }
            });
        },

        changestatus : function(data){
            return jQuery.ajax({
                method: 'GET',
                url : '/ajax/message/changeStatus',
                data: data,    
                success: function(data){
                    
                }
            });
        },

		detail: function(mesage_group,page){
			return jQuery.ajax({
            	method: 'GET',
            	url : '/ajax/message/getMessagesDetail?page='+page+'&mesage_group='+mesage_group,
            	beforeSend:function(){
            		jQuery('#home-message').html(loadling);
            	},
            	success: function(data){
            		jQuery('#home-message').html(data);
            	}
            });
		},


        detailMesage: function(mesage_id){
            return jQuery.ajax({
                method: 'GET',
                url : '/ajax/message/getMessagesDetailByID?message_id='+mesage_id,
                beforeSend:function(){
                    jQuery('#home-message').html(loadling);
                },
                success: function(data){
                    jQuery('#home-message').html(data);
                }
            });
        },

		destroy: function(data){
			return jQuery.ajax({
            	method: 'GET',
            	url : '/ajax/message/destroy',
            	data: data,
            	beforeSend:function(){
            		jQuery('#home-message').html(loadling);
            	},
            	success: function(d){
                    if(d.r == 'success'){
                        var arrMsg = data.message_id.split(',');
                        jQuery.each(arrMsg,function(index,value){
                            if(value != ''){
                                jQuery('#home-message').find('div#msg-'+value).remove();
                            }
                        });
                        
                    }
            		
            	}
            });
		},
        destroyItem: function(message_id,del_state){
       
            return jQuery.ajax({
                method: 'GET',
                url : '/ajax/message/destroy',
                data: {message_id: message_id,del_state:del_state },
               
                success: function(data){
                    jQuery('div#msg-'+message_id).remove();
                }
            });
        },

        search: function(data){
       
            return jQuery.ajax({
                method: 'GET',
                url : '/ajax/message/search',
                data: data,
                beforeSend:function(){
                    jQuery('#home-message').html(loadling);
                },
                success: function(data){
                    jQuery('#home-message').html(data);
                }
            });
        }
        
	};
	var loadling = '<img class="center-block" src="/img/loading.gif"/>';
	jQuery(document).ready(function(){
		Message.status({message_status:'inbox', page: 1});

        //search 

        jQuery( "input#s-message" ).keypress(function( event ) {
            if ( event.which == 13 ) {
                event.preventDefault();
                var s = jQuery( "input#s-message" ).val();
                Message.search({ keywords: s });
            }
        });

        // Destroy only item
        jQuery(document).on('click', '.destroy-item', function (e) {
            e.preventDefault();
            var message_id = jQuery(this).data('msgid');
            var del_state = jQuery(this).data('delstate');
            Message.destroyItem(message_id,del_state);

        });   


        //Get message detail
        jQuery(document).on('click', 'a.get-detail-msg', function (e) {
            e.preventDefault();
            var mesage_group = jQuery(this).data('msg-group');
            var msg_status = jQuery(this).data('status');
            var msg_id = jQuery(this).data('msg-id');
            Message.detail(mesage_group,1);
            if(msg_status == 'unread'){
                Message.changestatus({message_id:msg_id,message_status:'read' });
            }

        });

        

        // Del message when chose as a list
         jQuery(document).on('click', 'button#destroy-message', function (e) {
            e.preventDefault();
            
            var del_state = jQuery('input#del-state').val();
            var data = '';
            jQuery('input:checkbox[name="message_id[]"]').each(function () {
                data += ','+(this.checked ? jQuery(this).val() : "");
            });
           
            Message.destroy({message_id: data,del_state:del_state});
            return;

        });   
        // Pagination
		jQuery(document).on('click', '#inbox-message .pagination a', function (e) {
            var page = jQuery(this).attr('href').split('page=')[1];
            Message.status({message_status:'inbox',page: page});
            e.preventDefault();
        });

        jQuery(document).on('click', '#inbox-message-filter .pagination a', function (e) {
            var page = jQuery(this).attr('href').split('page=')[1];
            var filter = jQuery(this).attr('href').split('filter=')[1].split('&')[0];
            Message.status({message_status:'inbox',filter:filter,page: page});
            e.preventDefault();
        });

        jQuery(document).on('click', '#outbox-message .pagination a', function (e) {
            var page = jQuery(this).attr('href').split('page=')[1];
            Message.status({message_status:'outbox',page: page});
            e.preventDefault();
        });

        jQuery(document).on('click', '#destroy-message .pagination a', function (e) {
            var page = jQuery(this).attr('href').split('page=')[1];
            Message.status({message_status:'destroy',page: page});
            e.preventDefault();
        });
        // End Pagination


        jQuery(document).on('click', '.checkall, input[name="message_id[]"]', function () {
            var list_msg = jQuery('input[name="message_id[]"]');
            if(this.checked) { // check select status
                if(list_msg.length > 0){
                    jQuery('button#destroy-message').show();
                }
                list_msg.each(function() { //loop through each checkbox
                    this.checked = true;             
                });

            }else{
                jQuery('button#destroy-message').hide();
                list_msg.each(function() { //loop through each checkbox
                    this.checked = false;                      
                });         
            }
        });
            
        
	});

</script>
@endsection