<?
	$channel['theme']->setTitle('Đổi tên miền hosting');
	$channel['theme']->setKeywords('Đổi tên miền, thay đổi tên miền');
	$channel['theme']->setDescription('Thay đổi tên miền chính của hosting ');
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
		<form class="form-group">
			<div class="form-group">
				<label class="control-label">Tên miền hiện tại</label>
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span> 
					<input type="text" class="form-control" id="domainOld" name="domainOld" placeholder="Tên miền cũ" required="" value="{{$hosting->name}}" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label">Tên miền mới</label>
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span> 
					<input type="text" class="form-control" id="domainNew" name="domainNew" placeholder="Nhập tên miền mới" required="" value="">
				</div>
			</div>
			<div class="form-group">
				<a class="btn btn-sm btn-default" href="{{route('channel.hosting.list',$channel['domainPrimary'])}}">Quay lại</a> <button type="submit" class="btn btn-sm btn-success" id="btnSaveNewDomain" data-id="{{$hosting->id}}"><i class="fa fa-check"></i> Cập nhật</button>
			</div>
		</form>
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
		$("#btnSaveNewDomain").click(function () {
			var formData = new FormData();
			formData.append("idHosting", $(this).attr("data-id")); 
			formData.append("domainNew", $("input[name=domainNew]").val()); 
			$("#preloaderInBox").css("display", "block"); 
			$.ajax({
				url: "'.route("channel.hosting.change.domain.save",$channel["domainPrimary"]).'",
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
			return false; 
		});
	', $dependencies);
?>