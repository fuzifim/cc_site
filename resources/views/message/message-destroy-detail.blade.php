<div id="message-detail" style="">
<?php
	$i=0;
	$message_title = '';
	$message_group = '';
	$to_member = '';
?>

	@if($message->from_member == Auth::user()->id)

	<?php $user = WebService::getUserByID($message->from_member) ?>

	<div id="msg-{{ $message->id }}" class="media bg-success clearfix">
		<div class="media-left">
	        <a href="#">
	          <img class="media-object" data-src="holder.js/64x64" alt="64x64" src="{{ $user->avata }}" data-holder-rendered="true" style="width: 64px; height: 64px;">
	        </a>
	  	</div>
		<div class="media-body" style="width:100%">
			
			{{ $message->message_body }}
			<p class="text-muted">{{ date('H:s:i d-m-Y',strtotime($message->created_at)) }}</p>
		</div>
		
	</div>
	@else
	<?php $user = WebService::getUserByID($message->to_member) ?>
	<div id="msg-{{ $message->id }}" class="media bg-info clearfix">
		<div class="media-body" style="width:100%">
			
			{{ $message->message_body }}
			<p class="text-muted">{{ date('H:s:i d-m-Y',strtotime($message->created_at)) }}</p>
		</div>
		<div class="media-right">
	        <a href="#">
	          <img class="media-object" data-src="holder.js/64x64" alt="64x64" src="{{ $user->avata }}" data-holder-rendered="true" style="width: 64px; height: 64px;">
	        </a>
	  	</div>
	  	
	</div>  	
	@endif
	
</div>

