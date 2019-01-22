<?
	$channel['theme']->setTitle('Thống kê');
	$channel['theme']->setKeywords('Thống kê');
	$channel['theme']->setDescription(''); 
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.sparkline', 'js/jquery.sparkline.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel" style="position:relative; ">
		<div id="preloaderInBox" style="display:none;">
			<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
		</div>
		<div class="row-pad-5">
			<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"><i class="glyphicon glyphicon-briefcase"></i> {{$channel['info']->channel_name}}</h4>
					</div>
					<div class="panel-body">
					@if(!empty($channel['info']->channelService->name))
						<p>Tên miền: <a href="//{{$channel['domain']->domain}}">http://{{$channel['domain']->domain}}</a></p>
						<p><i class="glyphicon glyphicon-time"></i> Ngày đăng ký: {!!Site::Date($channel['info']->channel_created_at)!!}</p> 
						@if($channel['info']->service_attribute_id!=1)<p class="text-danger"><i class="glyphicon glyphicon-time"></i> Hạn sử dụng đến: {!!Site::Date($channel['info']->channel_date_end)!!}</p>@endif
						@if($channel['info']->service_attribute_id!=1)<p><strong><span><i class="glyphicon glyphicon-usd"></i> {!!Site::price($channel['info']->channelService->price_re_order)!!}</span></strong><sup>VND</sup>/ {{$channel['info']->channelService->order_unit_month}} {{$channel['info']->channelService->per}}</p>@endif
						<p><strong>Đang sử dụng: {{$channel['info']->channelService->name}}</strong></p> 
						@if($channel['info']->service_attribute_id!=1)
							@if($channel['info']->channel_date_end < \Carbon\Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')) 
								<button type="button" class="btn btn-xs btn-primary" id="btnChannelReOrder"><i class="glyphicon glyphicon-repeat"></i> Gia hạn</button>
							@endif
						@endif
						@role(['admin','manage'])
							<button type="button" class="btn btn-xs btn-default channelEdit"><i class="glyphicon glyphicon-edit"></i> Sửa</button>
						@endrole
					@endif
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"><i class="glyphicon glyphicon-stats"></i> Giới hạn</h4>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<span class="sublabel">Dung lượng ({{$channel['totalSize']}}/ {{$channel['limitSize']}} MB)</span>
							<div class="progress progress-sm">
								<div style="width: {{$channel['percenSize']}}%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="50" role="progressbar" class="progress-bar @if($channel['percenSize']>=100) progress-bar-danger @elseif($channel['percenSize']>=50) progress-bar-warning @else progress-bar-success @endif"></div>
							</div>
							<span class="sublabel">Số lượng bài ({{$channel['totalPosts']}}/ {{$channel['limitPosts']}} bài)</span>
							<div class="progress progress-sm">
								<div style="width: {{$channel['percenPosts']}}%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="50" role="progressbar" class="progress-bar @if($channel['percenPosts']>=100) progress-bar-danger @elseif($channel['percenPosts']>=50) progress-bar-warning  @else progress-bar-success  @endif"></div>
							</div>
							<a class="btn label label-primary" href="{{route('channel.upgrade',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-open"></i> Nâng cấp</a>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"><i class="glyphicon glyphicon-stats"></i> Thống kê lượt xem</h4>
					</div>
					<div class="panel-body">
						<div class="text-center"><strong class="text-primary" style="font-size:36px;">{!!Site::price($channel['info']->channel_view)!!}</strong></div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		$("#channelUpgrade").click(function() {
			function getPackge(){
				$("#myModal .modal-header").empty(); 
				$("#myModal .modal-title").empty(); 
				$("#myModal .modal-body").empty(); 
				$("#myModal .modal-footer").empty(); 
				$("#myModal .modal-header").append("<div class=\"modal-title\">Chọn gói nâng cấp</div>"); 
				$("#myModal .modal-body").append("<div class=\"row appendPackge\"></div>"); 
				$("#myModal .modal-footer").append("<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Đóng</button>");
				$.ajax({
					url: "'.route('channel.packge.json',$channel['domain']->domain).'",
					type: "GET",
					dataType: "json",
					success: function (result) {
						$.each(JSON.parse(result.data), function(i, item) {
							if('.$channel['totalSize'].'>=$.parseJSON(item.attribute_value).limit_cloud || '.$channel['limitPosts'].'>=$.parseJSON(item.attribute_value).limit_post){
								$("#myModal .modal-body .appendPackge").append("<div class=\"col-lg-4 col-md-4 col-sm-6 col-xs-12\">"
									+"<div class=\"form-group\">"
										+"<div class=\"list-group-item text-center btn packgeCheck disabled\">"
											+"<h4 class=\"list-group-item-heading\">"+item.name+"</h4>"
											+"<div class=\"list-group-item-text\">"
												+"<p>Phí Đăng ký: <strong>"+(parseInt(item.price_re_order)+parseInt(item.price_order)).toLocaleString()+"<sup>đ</sup></strong>/ "+item.per+"</p>"
												+"<p><i class=\"glyphicon glyphicon-cloud\"></i> "+parseInt($.parseJSON(item.attribute_value).limit_cloud).toLocaleString()+" MB</p>"
												+"<p><i class=\"glyphicon glyphicon-check\"></i> "+parseInt($.parseJSON(item.attribute_value).limit_post).toLocaleString()+" Bài viết</p>"
											+"</div>"
											+"<input type=\"radio\" class=\"hidden\" value=\""+item.id+"\" name=\"channelPackge\">"
										+"</div>"
									+"</div>"
								+"</div>"); 
							}else{
								$("#myModal .modal-body .appendPackge").append("<div class=\"col-lg-4 col-md-4 col-sm-6 col-xs-12\">"
									+"<div class=\"form-group\">"
										+"<div class=\"list-group-item text-center btn packgeCheck\">"
											+"<h4 class=\"list-group-item-heading\">"+item.name+"</h4>"
											+"<div class=\"list-group-item-text\">"
												+"<p>Phí Đăng ký: <strong>"+(parseInt(item.price_re_order)+parseInt(item.price_order)).toLocaleString()+"<sup>đ</sup></strong>/ "+item.per+"</p>"
												+"<p><i class=\"glyphicon glyphicon-cloud\"></i> "+parseInt($.parseJSON(item.attribute_value).limit_cloud).toLocaleString()+" MB</p>"
												+"<p><i class=\"glyphicon glyphicon-check\"></i> "+parseInt($.parseJSON(item.attribute_value).limit_post).toLocaleString()+" Bài viết</p>"
											+"</div>"
											+"<input type=\"radio\" class=\"hidden\" value=\""+item.id+"\" name=\"channelPackge\">"
										+"</div>"
									+"</div>"
								+"</div>"); 
							}
						}); 
					}
				});
			}
			getPackge(); 
			$("#myModal").modal("show"); 
			return false; 
		}); 
		$("#myModal").on("click",".packgeCheck",function() {
			var packgeId=$(this).find("input[name=channelPackge]").val(); 
			var formData = new FormData();
			formData.append("cartType", "channelUpgrade"); 
			formData.append("packgeId", packgeId); 
			$.ajax({
				url: "'.route("create.cart",$channel["domain"]->domain).'",
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
						window.location.href = "'.route("pay.cart",$channel["domain"]->domain).'";
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
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('onload',' 
		var docHeight = jQuery(document).height();
		if(docHeight > jQuery(".mainpanel").height())
		jQuery(".mainpanel").height(docHeight);
	', $dependencies);
?>