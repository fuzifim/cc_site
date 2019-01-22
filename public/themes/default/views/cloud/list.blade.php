<?
	$channel['theme']->setTitle('Quản lý Cloud');
	$channel['theme']->setKeywords('Quản lý cloud của tôi');
	$channel['theme']->setDescription('Quản lý danh sách cloud server '); 
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
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('title') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel" style="position:relative; ">
		<div class="form-group">
			<a href="{{route('pages.cloud',$channel['domainPrimary'])}}" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-plus"></i> Thêm Cloud Server mới</a>
		</div>
		@if(count($getCloud)>0)
		<ul class="list-group">
			@foreach($getCloud as $cloud)
				<?
					if($cloud->date_end>=\Carbon\Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')){
						$listItem='list-group-item-success'; 
						$channelStatus='<span class="label label-success">Đang hoạt động</span>'; 
						$option=''; 
					}else if($cloud->date_end<\Carbon\Carbon::now()->format('Y-m-d H:i:s')){
						$listItem='list-group-item-danger'; 
						$channelStatus='<span class="label label-danger">Hết hạn</span>'; 
						$option='<button type="button" class="btn btn-xs btn-success reOrder" data-id="'.$cloud->id.'"><i class="glyphicon glyphicon-repeat"></i> Gia hạn</button>';
					}else if($cloud->date_end<\Carbon\Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')){
						$listItem='list-group-item-warning'; 
						$channelStatus='<span class="label label-warning">Sắp hết hạn</span>'; 
						$option='<button type="button" class="btn btn-xs btn-success reOrder" data-id="'.$cloud->id.'"><i class="glyphicon glyphicon-repeat"></i> Gia hạn</button>'; 
					}else{
						$listItem=''; 
						$channelStatus=''; 
						$option=''; 
					}
				?>
				<li class="list-group-item {{$listItem}}">
					<a href="#" class="close dropdown dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i><span class="hidden-xs"> Quản lý</span>
					</a>
					<ul class="dropdown-menu" style="padding:0px;right:0px;left:inherit;">
						<a class="list-group-item" href="#"><i class="glyphicon glyphicon-trash"></i> Xóa</a> 
					</ul>
					<strong>{{$cloud->name}} - {!!$cloud->serviceAttribute->name!!}</strong>
					<br><small>Ngày đăng ký: <i class="glyphicon glyphicon-time"></i> {{Site::date($cloud->created_at)}}</small>
					<br><small class="text-danger">Ngày hết hạn: <i class="glyphicon glyphicon-time"></i> {{Site::date($cloud->date_end)}}</small>
					<br><strong>{!!Site::price($cloud->serviceAttribute->price_re_order)!!}</span></strong><sup>đ</sup>/ tháng</span> 
					<div class="form-group">
						{!!$channelStatus!!} {!!$option!!} 
					</div>
				</li>
			@endforeach
		</ul>
		{!!$getCloud->render()!!}
		@else 
			<div class="alert alert-warning">
				<strong>Thông báo!</strong> Bạn chưa có đăng ký Cloud Server Nào. <a href="{{route('pages.cloud',$channel['domainPrimary'])}}">Thêm Cloud Server</a> vào danh sách! 
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
		$(".reOrder").click(function () {
			var formData = new FormData();
			formData.append("cloudId", $(this).attr("data-id")); 
			formData.append("cartType", "cloudReOrder"); 
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