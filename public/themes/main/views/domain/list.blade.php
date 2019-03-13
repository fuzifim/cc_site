<?
	$channel['theme']->setTitle('Quản lý Tên miền');
	$channel['theme']->setKeywords('Quản lý tên miền của tôi');
	$channel['theme']->setDescription('Quản lý danh sách tên miền của tôi ');
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
			<a href="{{route('pages.domain',$channel['domainPrimary'])}}" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-plus"></i> Thêm tên miền mới</a>
		</div>
		@if(count($getDomain)>0)
		<ul class="list-group">
			@foreach($getDomain as $domain)
				<?
					if($domain->date_end>=\Carbon\Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')){
						$listItem='list-group-item-success'; 
						$channelStatus='<span class="badge badge-success">Đang hoạt động</span>'; 
						$option=''; 
					}else if($domain->date_end<\Carbon\Carbon::now()->format('Y-m-d H:i:s')){
						$listItem='list-group-item-danger'; 
						$channelStatus='<span class="badge badge-danger">Hết hạn</span>'; 
						$option='<button type="button" class="badge badge-success reOrder" data-id="'.$domain->id.'"><i class="glyphicon glyphicon-repeat"></i> Gia hạn</button>';
					}else if($domain->date_end<\Carbon\Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')){
						$listItem='list-group-item-warning'; 
						$channelStatus='<span class="badge badge-warning">Sắp hết hạn</span>'; 
						$option='<button type="button" class="badge badge-success reOrder" data-id="'.$domain->id.'"><i class="glyphicon glyphicon-repeat"></i> Gia hạn</button>'; 
					}else{
						$listItem=''; 
						$channelStatus=''; 
						$option=''; 
					}
				?>
				<li class="list-group-item {{$listItem}}">
					<div class="dropdown pull-right">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i><span class="hidden-xs"> Quản lý</span></a>
						<ul class="dropdown-menu" style="padding:0px;right:0px;left:inherit;">
							<a class="list-group-item changeDns" data-id="{{$domain->id}}" href="#"><i class="glyphicon glyphicon-chevron-right"></i> Đổi DNS </a> 
							<a class="list-group-item deleteDomain" data-id="{{$domain->id}}" href="#"><i class="glyphicon glyphicon-trash"></i> Xóa</a> 
						</ul>
					</div>
					<strong>{!!$domain->domain!!}</strong>
					<br><small>Đăng ký: <i class="glyphicon glyphicon-time"></i> {{Site::date($domain->created_at)}}</small> <small class="text-danger">Hết hạn: <i class="glyphicon glyphicon-time"></i> {{Site::date($domain->date_end)}}</small>
					<br><strong>{!!Site::price($domain->serviceAttribute->price_re_order)!!}</span></strong><sup>đ</sup>/ {!!$domain->serviceAttribute->per!!}</span> 
					<div class="form-group">
						{!!$channelStatus!!} {!!$option!!} 
					</div>
				</li>
			@endforeach
		</ul>
		{!!$getDomain->render()!!}
		@else 
			<div class="alert alert-warning">
				<strong>Thông báo!</strong> Bạn chưa có đăng ký Tên miền Nào. <a href="{{route('pages.domain',$channel['domainPrimary'])}}">Thêm Tên miền mới</a> vào danh sách
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