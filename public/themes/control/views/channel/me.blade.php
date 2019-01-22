<?
	$channel['theme']->setTitle('Website của tôi');
	$channel['theme']->setKeywords('Website của tôi');
	$channel['theme']->setDescription('Danh sách website của tôi. '); 
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.sparkline', 'js/jquery.sparkline.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('retina', 'js/retina.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('title') !!}</h1>
	</div>
	<div class="contentpanel" style="position:relative; ">
		<?
			$getChannelRole=\App\Model\Channel_role::where('user_id','=',Auth::user()->id)->where('parent_id','!=',$channel['info']->id)->orderBy('updated_at','desc')->take(5)->get(); 
		?>
		@if(count($getChannelRole)>0)
		<ul class="list-group">
			@foreach($getChannelRole as $channelRole)
				<?
					$getRole=\App\Role::findOrFail($channelRole->role_id); 
					$role_permissions = $getRole->perms()->get();
				?>
				@foreach($role_permissions as $permission)
					@if($permission->name=='manager_channel')
						<?
							$getChannelInfo=\App\Model\Channel::find($channelRole->parent_id); 
							if($getChannelInfo->domainJoinPrimary->domain->domain_primary!='default'){
								if(count($getChannelInfo->domainAll)>0){
									foreach($getChannelInfo->domainAll as $domain){
										if($domain->domain->domain_primary=='default'){
											$domainPrimary=$domain->domain->domain; 
										}
									}
								}else{
									$domainPrimary=$getChannelInfo->domainJoinPrimary->domain->domain; 
								}
							}else{
								$domainPrimary=$getChannelInfo->domainJoinPrimary->domain->domain; 
							}
						?>
						<li class="list-group-item">
							<a href="#" class="close dropdown dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i><span class="hidden-xs"> Quản lý</span>
							</a>
							<ul class="dropdown-menu" style="padding:0px;right:0px;left:inherit;">
								<a class="list-group-item" href="{{route('channel.home',$domainPrimary)}}"><i class="glyphicon glyphicon-globe"></i> Xem website</a> 
								<a class="list-group-item" href="#"><i class="glyphicon glyphicon-trash"></i> Xóa</a> 
							</ul>
							<strong><a href="{{route('channel.home',$domainPrimary)}}">{{$getChannelInfo->channel_name}}</a></strong>
							<br><small><a href="{{route('channel.home',$domainPrimary)}}"><i class="glyphicon glyphicon-globe"></i> {{$domainPrimary}}</a></small>
							<br><small>Ngày đăng ký: <i class="glyphicon glyphicon-time"></i> {{Site::date($getChannelInfo->channel_created_at)}}</small>
							@if($getChannelInfo->channelService->id!=1)<br><small class="text-danger">Ngày hết hạn: <i class="glyphicon glyphicon-time"></i> {{Site::date($getChannelInfo->channel_date_end)}}</small>@endif
							<br><strong>{{$getChannelInfo->channelService->name}} <span>{!!Site::price($getChannelInfo->channelService->price_re_order)!!}</span></strong><sup>đ</sup>/ năm</span></strong>
							@if($getChannelInfo->channel_status!='test')<br><small><strong>Tình trạng: <span class="label label-success">Đang hoạt động</span>@endif</strong> <a class="label label-primary" href="{{route('channel.home',$domainPrimary)}}"><i class="glyphicon glyphicon-hand-right"></i> Xem website</a></small>
						</li>
					@endif
				@endforeach
			@endforeach
		</ul>
		@endif
	</div>

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		
	', $dependencies);
?>