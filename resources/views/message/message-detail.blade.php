<div id="message-detail">
<?php
	$i=0;
	$message_title = '';
	$message_group = '';
	$to_member = '';
?>
@foreach ($message as $m)

	@if ($i == 0)
		<?php 
			$message_title = $m->message_title;
			$message_group = $m->message_group;
			$to_member = $m->from_member;
		?>
	@endif
	@if($m->from_member == Auth::user()->id)
	<?php $hidden = ($m->from_del != 0)? 'hidden' : '' ?>

	<div id="msg-{{ $m->id }}" class="media bg-success clearfix {{ $hidden }}">
		<div class="media-left">
	          <img class="media-object" data-src="holder.js/64x64" alt="64x64" src="{{ $m->avata }}" data-holder-rendered="true" style="width: 64px; height: 64px;">
	  	</div>
		<div class="media-body">
			
			{{ $m->message_body }}
			<p class="text-muted">{{ date('H:s:i d-m-Y',strtotime($m->created_at)) }}</p>
		</div>
		<div class="sperator"></div>
		<a href="#" title="Xóa" class="pull-right destroy-item" data-msgid="{{ $m->id }}" data-delstate="from_del">
				<i class="fa fa-trash-o"></i>
		</a>
	</div>
	@else
	<?php $hidden = ($m->to_del != 0)? 'hidden' : '' ?>
	<div id="msg-{{ $m->id }}" class="media bg-info clearfix {{ $hidden }}">
		<div class="media-body">
				<p class="lead">{{$m->message_title}}</p><hr>
			{{ $m->message_body }}
			<p class="text-muted">{{ date('H:s:i d-m-Y',strtotime($m->created_at)) }}</p>
		</div>
		<div class="media-right">
	          <img class="media-object" data-src="holder.js/64x64" alt="64x64" src="{{ $m->avata }}" data-holder-rendered="true" style="width: 64px; height: 64px;">
	  	</div>
	  	<a href="#" title="Xóa" class="pull-right destroy-item" data-msgid="{{ $m->id }}" data-delstate="to_del">
			<i class="fa fa-trash-o"></i>
		</a>
	</div>  	
	@endif
	
@endforeach
	<nav id="paginate-detail" class="pull-right">
		{!! $message->render() !!}
	</nav>
	<input type="hidden" value="">
</div>
@if (count($message) > 0)
<p class="clearfix"></p>
<form id="message-reply" action="" name="reply_form" >
	<input type="hidden" name="message_group" value="{{ $message_group }}">
	<input type="hidden" name="message_title" value="{{ $message_title }}">
	<input type="hidden" name="to_member" value="{{ $to_member }}">
	<div class="form-group">
		<textarea required name="message_body" id="" cols="" rows="5" placeholder="trả lời" class="form-control"></textarea>
		
	</div>
	<div class="form-group">
		<button class="btn btn-primary" ng-disabled="reply_form.$invalid">Gửi</button>
		<label for="" id="result"></label>
	</div>
</form>
@endif
<script>
	// form login page
    jQuery("form#message-reply").validate({
        errorElement: "div",
        errorPlacement: function(error, element) {
            element.after(error).addClass('validate invalid error');
            offset = element.offset();
            error.addClass('text-danger');  // add a class to the wrapper
        },
        rules: {
            message_body:{
                required: true,
                minlength: 30,
                maxlength: 5000
            }          
        },
        messages: {
            message_body:{
                required : "Nhập tin nhắn trả lời",
                minlength: "Tin nhắn tối thiểu 20 ký tự",
                maxlength: "Tin nhắn tối đa 5000 ký tự",
            },
        },
        submitHandler: function(form) {
        	var dataPost =  {
        	    '_token': $('input[name=_token]').val(),
                to_member : jQuery( form ).find( 'input[name=to_member]' ).val(),
                message_title:  jQuery( form ).find( 'input[name=message_title]' ).val(),
                message_group:  jQuery( form ).find( 'input[name=message_group]' ).val(),
                message_body:  jQuery( form ).find( 'textarea[name=message_body]' ).val(),
            };
        	jQuery.ajax({
                type: 'POST',
                url: '/ajax/send_message',
                data: dataPost,
                dataType:'json',
                success:function(data){
                	if(data.msg_code == 'success'){
                		jQuery(form)[0].reset();
                		jQuery(form).find('label#result').text(data.msg);
                		Message.detail(dataPost.message_group,1);
                	}
                   
                    
                }

            });
           return false;

           //form.submit();
        }
    });// end login form page
</script>