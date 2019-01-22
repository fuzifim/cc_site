<?
	$getChannelRole=\App\Model\Channel_role::where('user_id','=',Auth::user()->id)->where('parent_id','!=',$channel['info']->id)->orderBy('updated_at','desc')->take(5)->get(); 
?>
@if(count($getChannelRole)>0)
	<li class="dropdown-header">Kênh bạn đang quản lý</li>
	@foreach($getChannelRole as $channelRole)
		<?
			$getRole=\App\Role::findOrFail($channelRole->role_id); 
			$role_permissions = $getRole->perms()->get();
		?>
		@foreach($role_permissions as $permission)
			@if($permission->name=='manager_channel')
				<?
					$getChannelInfo=\App\Model\Channel::find($channelRole->parent_id); 
				?>
				<li><a href="{{route('channel.home',$getChannelInfo->domainJoinPrimary->domain->domain)}}"><i class="glyphicon glyphicon-globe"></i> {{$getChannelInfo->channel_name}}</a></li>
			@endif
		@endforeach
	@endforeach
	<li class="divider"></li>
@endif