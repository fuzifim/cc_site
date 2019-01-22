<div class="list-group mail-box">
	<input id="del-state" type="hidden" value="to_del">
	@foreach ($message as $m)
		<div class="list-group-item">
			<div class="@if($m->message_status == 'unread') unread @endif">
	   			<input name="message_id[]" type="checkbox" style="" value="{{ $m->id }}">
	   			@if( $m->from_member == Auth::user()->id )
					<?php $user = WebService::getUserByID($m->from_member) ?>

		   			<span style="min-width: 120px; display: inline-block;" class="name">
						<small>To: </small>{{ $user->name }}
		   			</span>
	   			@else
	   				<?php $user = WebService::getUserByID($m->to_member) ?>
					<span style="min-width: 120px; display: inline-block;" class="name">
						<small>From: </small>{{ $user->name }}
		   			</span>
	   			@endif
				<a  href="javascript:void(0)" onclick="Message.detailMesage({{ $m->id }})">
					<span>{{ $m->message_title }}</span>&nbsp; - &nbsp;
				</a>
	   			<span class="text-muted">
	   				{{ $m->message_body }}
	   			</span>
	   			<span class="time-badge">{{ $m->created_at }}</span>
	   			
			</div>
		</div>
	@endforeach	
</div>


<p class="clearfix"></p>
<nav id="destroy-message" class="pull-right">{!! $message->render() !!}</nav>
