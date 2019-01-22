<div class="list-group mail-box">
	<input id="del-state" type="hidden" value="from_del">
	@foreach ($message as $m)
		<div id="msg-{{ $m->id }}" class="list-group-item">
			<div class="">
	   			<input name="message_id[]" type="checkbox" style="" value="{{ $m->id }}">
					<a  href="javascript:void(0)" onclick="Message.detail({{ $m->message_group }},1)" >
						<span>{{ $m->message_title }}</span>&nbsp; - &nbsp;
					</a>
		   			<span class="text-muted">
		   				{{ $m->message_body }}
		   			</span>
		   			<small class="time-badge">{{ $m->created_at }}</small>
					<small>- Bởi {{ $m->name }}</small>
			</div>
		</div>
	@endforeach	
</div>
<p class="clearfix"></p>
<nav id="outbox-message" class="pull-right">{!! $message->render() !!}</nav>
