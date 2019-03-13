<?
	$channel['theme']->setTitle('Quản lý Hosting');
	$channel['theme']->setKeywords('Quản lý hosting của tôi');
	$channel['theme']->setDescription('Quản lý danh sách hosting ');
Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'));
Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('jquery.sparkline', 'js/jquery.sparkline.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('retina', 'js/retina.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'));
?>
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('title') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel" style="position:relative; ">
		<div id="preloaderInBox" style="display:none;">
			<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
		</div>
		<div class="form-group">
			<a href="{{route('pages.hosting',$channel['domainPrimary'])}}" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-plus"></i> Thêm Hosting mới</a>
		</div>
		@if(count($getHosting)>0)
		<ul class="list-group">
			@foreach($getHosting as $hosting)
				<?
					if($hosting->date_end>=\Carbon\Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')){
						$listItem='list-group-item-success'; 
						$channelStatus='<span class="badge badge-success">Đang hoạt động</span>'; 
						$option=''; 
					}else if($hosting->date_end<\Carbon\Carbon::now()->format('Y-m-d H:i:s')){
						$listItem='list-group-item-danger'; 
						$channelStatus='<span class="badge badge-danger">Hết hạn</span>'; 
						$option='<button type="button" class="badge badge-success reOrder" data-id="'.$hosting->id.'"><i class="glyphicon glyphicon-repeat"></i> Gia hạn</button>';
					}else if($hosting->date_end<\Carbon\Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')){
						$listItem='list-group-item-warning'; 
						$channelStatus='<span class="badge badge-warning">Sắp hết hạn</span>'; 
						$option='<button type="button" class="badge badge-success reOrder" data-id="'.$hosting->id.'"><i class="glyphicon glyphicon-repeat"></i> Gia hạn</button>'; 
					}else{
						$listItem=''; 
						$channelStatus=''; 
						$option=''; 
					}
					$hostingName=$hosting->name; 
					if($hosting->type=='inet'){
						$dataHosting=json_decode($hosting->content); 
						$hostingName=$dataHosting->domainName; 
						$linkLogin=$dataHosting->serverName; 
						$userName=$dataHosting->userName; 
						$password=$dataHosting->password; 
						$urlLogin=str_replace('2087', '2083', $dataHosting->serverName); 
						$option='
						<a class="badge badge-primary" target="_blank" href="'.$urlLogin.'">Đăng nhập</a>
						<p class="mt5"><span class=""><strong>Username:</strong> <span>'.$dataHosting->userName.'</span> - <strong>Password:</strong> <span>'.$dataHosting->password.'</span></span></p>
						';
					}else if($hosting->type=='host_cungcap'){
						$dataHosting=json_decode($hosting->content); 
						$hostingName='<a class="btn btn-xs btn-default" href="http://'.$dataHosting->domain.'" target="_blank"><i class="glyphicon glyphicon-globe"></i> '.$dataHosting->domain.'</a>';
						$userName=$dataHosting->userName; 
						$password=$dataHosting->password; 
						$urlLogin=$dataHosting->url_login; 
						$option='
						<a class="badge badge-primary" target="_blank" href="'.$urlLogin.'">Đăng nhập</a>
						<p class="mt5"><span class=""><strong>Username:</strong> <code>'.$dataHosting->userName.'</code> - <strong>Password:</strong> <code>'.$dataHosting->password.'</code></span></p>
						';
					}
				?>
				<li class="list-group-item {{$listItem}}">
					<div class="dropdown pull-right">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i><span class="hidden-xs"> Quản lý</span></a>
						<ul class="dropdown-menu" style="padding:0px;right:0px;left:inherit;">
							<a class="list-group-item" href="{{route('channel.hosting.change.domain',array($channel['domainPrimary'],$hosting->id))}}"><i class="glyphicon glyphicon-globe"></i> Đổi tên miền</a> 
							<a class="list-group-item deleteHosting" data-id="{{$hosting->id}}" href="#"><i class="glyphicon glyphicon-trash"></i> Xóa</a> 
						</ul>
					</div>
					<strong>{!!$hostingName!!}</strong>
					<br><small>Đăng ký: <i class="glyphicon glyphicon-time"></i> {{Site::date($hosting->created_at)}}</small> <small class="text-danger">Hết hạn: <i class="glyphicon glyphicon-time"></i> {{Site::date($hosting->date_end)}}</small>
					<br><strong>{!!Site::price($hosting->serviceAttribute->price_re_order)!!}</span></strong><sup>đ</sup>/ tháng</span> 
					<div class="form-group">
						{!!$channelStatus!!} {!!$option!!} 
					</div>
				</li>
			@endforeach
		</ul>
		{!!$getHosting->render()!!}
		@else 
			<div class="alert alert-warning">
				<strong>Thông báo!</strong> Bạn chưa có đăng ký Hosting Nào. <a href="{{route('pages.hosting',$channel['domainPrimary'])}}">Thêm Hosting</a> vào danh sách
			</div>
		@endif
	</div>

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		$(".toggle").on("toggle", function(e, active) {
		  if (active) {
			console.log("Toggle is now ON!");
		  } else {
			console.log("Toggle is now OFF!");
		  }
		});
		$(".deleteHosting").click(function () {
			if(confirm("Bạn có chắc muốn xóa?")){
				var formData = new FormData();
				formData.append("idHosting", $(this).attr("data-id")); 
				$("#preloaderInBox").css("display", "block"); 
				$.ajax({
					url: "'.route("channel.hosting.delete",$channel["domainPrimary"]).'",
					type: "POST",
					cache: false,
					contentType: false,
					processData: false,
					dataType:"json",
					data:formData,
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					success: function (result) {
						//console.log(result); 
						if(result.success==true){
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-success",
								sticky: false,
								time: ""
							});
							location.reload();
						}else{
							$("#preloaderInBox").css("display", "none"); 
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-danger",
								sticky: false,
								time: ""
							});
						}
					}
				});
			}
		});
		$(".reOrder").click(function () {
			var formData = new FormData();
			formData.append("hostingId", $(this).attr("data-id")); 
			formData.append("cartType", "hostingReOrder"); 
			$.ajax({
				url: "'.route("create.cart",$channel["domainPrimary"]).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				data:formData,
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) {
					//console.log(result); 
					if(result.success==true){
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						});
						window.location.href = "'.route("pay.cart",$channel["domainPrimary"]).'";
					}else{
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-danger",
							sticky: false,
							time: ""
						});
					}
				}
			});
		});
	', $dependencies);
?>