	<div id="myModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<div class="pull-right"><button type="button" class="btn btn-xs btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i></button></div>
			<h4 class="modal-title"></h4>
		  </div>
		  <div class="modal-body">
		  </div>
		  <div class="modal-footer">
		  </div>
		</div>
	  </div>
	</div>
	<div class="siteFooter">
		<div class="container">
			<div class="row">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<b><strong>CÔNG TY CỔ PHẦN CUNG CẤP</strong></b><br>
							<small><i class="glyphicon glyphicon-map-marker"></i> 104 Hoàng Diệu 2, P. Linh Chiểu, Q. Thủ Đức, HCM</small><br>
							<small>MST: 0314609089 - Email: <a href="mailto:contact@cungcap.net">contact@cungcap.net</a></small><br><small>Web: <a href='https://cungcap.net'>cungcap.net</a> - <a href='http://cungcap.com.vn'>cungcap.com.vn</a> - <a href='http://cungcap.vn'>cungcap.vn</a></small><br>
							
							<small>Cung Cấp không chịu bất kỳ trách nhiệm nào bởi người dùng đăng lên</small>
						</div>
					</div>
					<div class="col-md-8">
						<small>
						<a href="//{!! $channel['domainPrimary'] !!}/gioi-thieu"><i class="glyphicon glyphicon-info-sign"></i> Giới thiệu</a> |
						<a href="//{!! $channel['domainPrimary'] !!}/dieu-khoan-su-dung"><i class="glyphicon glyphicon-chevron-right"></i> Điều khoản sử dụng</a> |
						<a href="//{!! $channel['domainPrimary'] !!}/chinh-sach-bao-mat"><i class="glyphicon glyphicon-chevron-right"></i> Chính sách bảo mật</a> |
						<a href="//{!! $channel['domainPrimary'] !!}/quy-che-hoat-dong"><i class="glyphicon glyphicon-chevron-right"></i> Quy chế hoạt động</a> |
						<a href="{{route('channel.contact',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-envelope"></i> Liên hệ</a> |
						<a href="{{route('channel.list',$channel['domainPrimary'])}}" class=""><i class="glyphicon glyphicon-chevron-right"></i> Danh sách website</a>
						</small>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a href="#" id="back-to-top" title="Back to top"><i class="glyphicon glyphicon-chevron-up"></i></a>
	<div class="footerFixel"> 
		<div class="row footerFixelCopyright">
			<div class="container">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
						<div class="channelDisplayTextFooter">{!!$channel['info']->channel_name!!} ©  {{date('Y')}}</div> 
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
						<div class="pull-right pull-right-md pull-right-lg">
							<a href="{{route('channel.home',config('app.url'))}}"><i class="glyphicon glyphicon-ok"></i> Cung Cấp</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{!!Theme::asset()->container('footer')->add('jquery.lazy', '//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.6/jquery.lazy.min.js', array('core-script'))!!}
	{!!Theme::asset()->container('footer')->add('jquery.lazy.plugins', '//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.6/jquery.lazy.plugins.min.js', array('core-script'))!!}
	{!!Theme::asset()->container('footer')->usePath()->add('custom', 'js/custom.js?v=14', array('core-script'))!!}
<?
	$dependencies = array(); 
	Theme::asset()->writeScript('searchAll','
		jQuery.ajax({
				  url: "'.Theme::asset()->usePath()->url('js/jquery.autocomplete.min.js').'",
				  dataType: "script",
				  cache: true
			}).done(function() {
				if($("#searchAll").length>0){ 
					$("#searchAll").autocomplete({ 
						serviceUrl: "'.route("search.all",$channel["domainPrimary"]).'",
						type:"GET",
						paramName:"txt",
						dataType:"json",
						minChars:2,
						deferRequestBy:100,
						onSearchComplete: function(){
							$(".autocomplete-suggestions").css({
								"width":+$("#searchform").outerWidth()
							}); 
						},
						//lookup: currencies,
						onSelect: function (suggestion) {
							//$("#idCompany").val(suggestion.data); 
							console.log(suggestion); 
							$("#searchType").val(suggestion.type); 
							$("#searchId").val(suggestion.id); 
							$("#searchAll").val(suggestion.value); 
							$("#searchform").submit();
						}
					});
				}
			});
	', $dependencies);
?>