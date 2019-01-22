<div class="list-group mail-box">
	<input id="del-state" type="hidden" value="to_del">
	@if( count($message) > 0 )
	@foreach ($message as $m)
		<div id="msg-{{ $m->id }}" class="list-group-item">
			<div class="@if($m->message_status == 'unread') unread @endif">
	   			<input name="message_id[]" type="checkbox" style="" value="{{ $m->id }}">
				<a class="get-detail-msg" href="javascript:void(0)" data-msg-id = "{{ $m->id }}" data-msg-group ="{{ $m->message_group }}" data-status = "{{ $m->message_status }}">
					<span>{{ $m->message_title }}</span>
				</a>
	   			<span class="text-muted">
	   				&nbsp; - &nbsp; {{ $m->message_body }}
	   			</span>
	   			<small class="time-badge">{{ $m->created_at }}</small>
				<small>- Bởi {{ $m->name }}</small>
	   			
			</div>
		</div>
	@endforeach	
	@endif
</div>
<p class="clearfix"></p>
@if(isset($filter))
	<nav id="inbox-message-filter" class="pull-right">{!! $message->appends(['filter' => $filter])->render() !!}</nav>
@else	
	<nav id="inbox-message" class="pull-right">{!! $message->render() !!}</nav>
@endif



