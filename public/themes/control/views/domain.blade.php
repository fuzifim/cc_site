<?
	$channel['theme']->setTitle('Cài đặt tên miền');
	$channel['theme']->setKeywords('Cài đặt tên miền');
	$channel['theme']->setDescription('Cài đặt tên miền '.$channel['info']->channel_name); 
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}
{!!Theme::asset()->add('bootstrap-datepicker', 'assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->add('bootstrap-datepicker', 'assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js', array('core-script'))!!}
@if($channel['security']==true)
{!!Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'))!!}
@endif
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
		@if($channel['info']->service_attribute_id==1)
			<div class="form-group text-center">
				<div class="alert alert-danger">
					<h4>Tính năng này không khả dụng</h4> 
					<p>Bạn không thể thay đổi tên miền trong gói miễn phí. Vui lòng <a href="{{route('channel.upgrade',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-open"></i> Nâng cấp</a> để sử dụng tính năng này! </p>
				</div>
			</div>
		@endif
		<div class="row row-pad-5" style="position:relative; ">
			@if($channel['info']->service_attribute_id==1)
				<div class="notificationLimit"></div>
			@endif
			<div class="panel-group panel-group-dark newDomain" id="accordion">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse1" class="openCollapse"><i class="glyphicon glyphicon-globe"></i> Đăng ký tên miền mới <i class="glyphicon glyphicon-chevron-down"></i></a>
						</h4>
					</div>
					<div id="collapse1" class="panel-collapse collapse in">
						<div class="panel-body panelNewRegisterDomain">
							<div class="checkDomain"></div>
							<div class="domainResult"></div>
							<div class="messageDomain"></div>
							<div class="btnAddDomain"></div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="collapsed"><i class="glyphicon glyphicon-globe"></i> Đã có tên miền <i class="glyphicon glyphicon-chevron-down"></i></a>
						</h4>
					</div>
					<div id="collapse2" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="input-group">      
								<input type="text" class="form-control" name="domainOldRegister" data-type="outSite" placeholder="Nhập tên miền bạn đã có">
								<span class="input-group-btn">
									<button class="btn btn-primary addDomainOldRegister" type="button"><span class="glyphicon glyphicon-ok"></span> Thêm</button>
								</span>
							</div>
							<code>Lưu ý: Khi thêm tên miền, bạn cần phải cấu hình Record A của DNS tên miền và trỏ về địa chỉ ip: <span class="text-success">{{config('app.ip_address')}}</span> để tên miền được hoạt động. </code>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">Danh sách tên miền</h3>
				</div>
				<ul class="list-group listDomain">
					@foreach($channel['info']->domainAll as $domains)
						@if($domains->domain->domain_location=='local')
							<li class="list-group-item list-group-item-default">
								{{$domains->domain->domain}} <code>Miễn phí</code>
								<p>
									@if($domains->domain->domain_primary=='default')
									<button type="button" class="btn label label-primary disabled"><i class="glyphicon glyphicon-ok"></i> Tên miền chính</button> 
									@else 
										<button type="button" class="btn label label-default setDomainPrimary" data-id="{{$domains->domain->id}}"><i class="glyphicon glyphicon-ok"></i> Tên miền chính</button> 
									@endif
								</p>
							</li>
						@elseif($domains->domain->domain_location=='register')
							<li class="list-group-item @if($domains->domain->date_end < \Carbon\Carbon::now()->format('Y-m-d H:i:s')) list-group-item-danger @elseif($domains->domain->date_end < \Carbon\Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')) list-group-item-warning @elseif($domains->domain->date_end > \Carbon\Carbon::now()->format('Y-m-d H:i:s')) list-group-item-success @endif dropdown">
								@if($domains->domain->status=='active')
									@if($domains->domain->date_end < \Carbon\Carbon::now()->format('Y-m-d H:i:s')) <span class="pull-right"><i class="glyphicon glyphicon-ok"></i> Đã hết hạn</span>@elseif($domains->domain->date_end < \Carbon\Carbon::now()->addMonth(1)->format('Y-m-d H:i:s'))<span class="pull-right"><i class="glyphicon glyphicon-ok"></i> Sắp hết hạn</span> @elseif($domains->domain->date_end > \Carbon\Carbon::now()->format('Y-m-d H:i:s')) <span class="pull-right"><i class="glyphicon glyphicon-ok"></i> Đang sử dụng</span> @endif
								@else 
									<code>Bạn cần phải thanh toán để kích hoạt tên miền. </code>
									<span class="pull-right"><i class="glyphicon glyphicon-ok"></i> Chưa kích hoạt</span>
								@endif
								<h4 class="list-group-item-heading">{{$domains->domain->domain}}</h4>
								<div class="list-group-item-text">
									@if($domains->domain->status=='active')
										<p><span class=""><i class="glyphicon glyphicon-time"></i> Ngày đăng ký: {!!Site::Date($domains->domain->date_begin)!!}</span></p>
										<p><span class="text-danger"><i class="glyphicon glyphicon-time"></i> Ngày hết hạn: {!!Site::Date($domains->domain->date_end)!!}</span></p>
										<p><strong><span><i class="glyphicon glyphicon-usd"></i> {!!Site::price($domains->domain->serviceAttribute->price_re_order)!!}</span></strong><sup>VND</sup>/ {{$domains->domain->serviceAttribute->order_unit_month}} {{$domains->domain->serviceAttribute->per}}</p>
										<p>
											<button type="button" class="btn label label-primary reOrder" data-id="{{$domains->domain->id}}"><i class="glyphicon glyphicon-repeat"></i> Gia hạn</button> 
											@if($domains->domain->domain_primary=='default')
											<button type="button" class="btn label label-primary disabled"><i class="glyphicon glyphicon-ok"></i> Tên miền chính</button>
											@else 
												@if($domains->domain->date_end > \Carbon\Carbon::now()->format('Y-m-d H:i:s'))<button type="button" class="btn label label-default setDomainPrimary" data-id="{{$domains->domain->id}}"><i class="glyphicon glyphicon-ok"></i> Tên miền chính</button> @endif
											@endif
											@role(['admin','manage'])
												<button type="button" class="btn label label-default domainEdit" data-id="{{$domains->domain->id}}" domain="{{$domains->domain->domain}}"><i class="glyphicon glyphicon-edit"></i> Sửa</button>
											@endrole
										</p>
									@elseif($domains->domain->status=='pending')
										<p><strong><span><i class="glyphicon glyphicon-usd"></i> {!!Site::price($domains->domain->serviceAttribute->price_re_order+$domains->domain->serviceAttribute->price_order)!!}</span></strong><sup>VND</sup>/ {{$domains->domain->serviceAttribute->order_unit_month}} {{$domains->domain->serviceAttribute->per}}</p>
										<p>
											<button type="button" class="btn label label-primary btnDomainAddPay" data-id="{{$domains->domain->id}}"><i class="glyphicon glyphicon-hand-right"></i> Thanh toán</button> 
											@if($domains->domain->domain_primary=='default')
												<button type="button" class="btn label label-primary disabled"><i class="glyphicon glyphicon-ok"></i> Tên miền chính</button>
											@else 
												@if($domains->domain->status=='active' || $domains->domain->date_end > \Carbon\Carbon::now()->format('Y-m-d H:i:s'))<button type="button" class="btn label label-default setDomainPrimary" data-id="{{$domains->domain->id}}"><i class="glyphicon glyphicon-ok"></i> Tên miền chính</button> @endif
											@endif
											<button type="button" class="btn label label-danger domainDelete" data-id="{{$domains->domain->id}}" ><i class="glyphicon glyphicon-ok"></i> Xóa</button>
											@role(['admin','manage'])
												<button type="button" class="btn label label-default domainEdit" data-id="{{$domains->domain->id}}" domain="{{$domains->domain->domain}}"><i class="glyphicon glyphicon-edit"></i> Sửa</button>
											@endrole
										</p>
									@endif
								</div>
								<div class="domainMessage"></div>
							</li>
						@elseif($domains->domain->domain_location=='outside')
							<li class="list-group-item list-group-item-default disabled">
								<h4 class="list-group-item-heading">{{$domains->domain->domain}}</h4>
								<div class="list-group-item-text">
									<p class="text-danger">Tên miền bên ngoài</p>
									<p>
									@if($domains->domain->domain_primary=='default')
									<button type="button" class="btn label label-primary disabled"><i class="glyphicon glyphicon-ok"></i> Tên miền chính</button> 
									@else 
										<button type="button" class="btn label label-default setDomainPrimary" data-id="{{$domains->domain->id}}"><i class="glyphicon glyphicon-ok"></i> Tên miền chính</button> 
									@endif
									<button type="button" class="btn label label-danger domainDelete" data-id="{{$domains->domain->id}}" ><i class="glyphicon glyphicon-ok"></i> Xóa</button>
									@role(['admin','manage'])
										<button type="button" class="btn label label-default domainEdit" data-id="{{$domains->domain->id}}" domain="{{$domains->domain->domain}}"><i class="glyphicon glyphicon-edit"></i> Sửa</button>
									@endrole
									</p>
								</div>
							</li>
						@endif
					@endforeach
				</ul>
			</div>
		</div>
	</div>

</div><!-- mainpanel -->
</section>
<?
	$getService=\App\Model\Services::find(4); 
	$listLtd=''; 
	foreach($getService->attributeAll as $serviceDomain){
	$listLtd.='
	+"<div class=\"col-lg-3 col-md-3 col-sm-6 col-xs-6\">"
		+"<div class=\"form-group text-left\">"
			+"<div class=\"ckbox ckbox-success\">"
				+"<input type=\"checkbox\" class=\"filled-in\" name=\"ltdDomain\" data-type=\"register\" value=\"'.$serviceDomain->attribute_type.'\" id=\"'.$serviceDomain->attribute_type.'\" checked=\"\" >"
				+"<label for=\"'.$serviceDomain->attribute_type.'\"> .'.$serviceDomain->attribute_type.'</label>"
			+"</div>"
		+"</div>"
	+"</div>"'; 
	}

	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		jQuery(document).ready(function(){
			"use strict"; 
			function convertToSlug(title)
			{
			  //Đổi chữ hoa thành chữ thường
				var slug = title.toLowerCase();
			 
				//Đổi ký tự có dấu thành không dấu
				slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, "a");
				slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, "e");
				slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, "i");
				slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, "o");
				slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, "u");
				slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, "y");
				slug = slug.replace(/đ/gi, "d");
				//Xóa các ký tự đặt biệt
				slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\"|\"|\:|\;|_/gi, "");
				//Đổi khoảng trắng thành ký tự gạch ngang
				slug = slug.replace(/ /gi, "");
				//Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
				//Phòng trường hợp người nhập vào quá nhiều ký tự trắng
				slug = slug.replace(/\-\-\-\-\-/gi, "-");
				slug = slug.replace(/\-\-\-\-/gi, "-");
				slug = slug.replace(/\-\-\-/gi, "-");
				slug = slug.replace(/\-\-/gi, "-");
				//Xóa các ký tự gạch ngang ở đầu và cuối
				slug = "@" + slug + "@";
				slug = slug.replace(/\@\-|\-\@|\@/gi, "");
				//In slug ra textbox có id “slug”
				
			  return slug;
			}
			getCheckDomain(); 
			function getCheckDomain(){
				$(".panelNewRegisterDomain .checkDomain").append("<div class=\"form-group\"><div class=\"input-group\">"      
					+"<input type=\"text\" class=\"form-control\" name=\"domainNewRegister\" placeholder=\"Nhập tên miền đăng ký\">"
					+"<span class=\"input-group-btn\">"
						+"<button class=\"btn btn-primary\" type=\"button\" id=\"btnCheckDomain\"><span class=\"glyphicon glyphicon-retweet\"></span> Kiểm tra</button>"
					+"</span>"
				+"</div></div>"
				+"<div class=\"row\">"
					'.$listLtd.'
				+"</div>");
			}
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
			$(".newDomain").on("click",".addDomainOldRegister",function() {
				$("#loading").css("visibility", "visible"); 
				$(".message").empty(); 
				var domain=$("input[name=domainOldRegister]").val(); 
				var formData = new FormData();
				formData.append("domain", domain); 
				$.ajax({
					url: "'.route("channel.domain.add",$channel["domainPrimary"]).'",
					type: "POST",
					cache: false,
					contentType: false,
					processData: false,
					dataType:"json",
					data:formData,
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					success: function (result) {
						console.log(result); 
						$("#loading").css("visibility", "hidden"); 
						if(result.success==true){
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-success",
								sticky: false,
								time: ""
							}); 
							location.reload();
						}else if(result.success==false){
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-danger",
								sticky: false,
								time: ""
							}); 
						}
					},
					error: function(result) {
						console.log(123); 
					}
				});
			});
			$(".btnDomainAddPay").click(function () {
				var formData = new FormData();
				formData.append("domainId", $(this).attr("data-id")); 
				formData.append("cartType", "domainAdd"); 
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
			$(".setDomainPrimary").click(function () {
				if(confirm("Bạn có chắc muốn kích hoạt tên miền này là chính?")){
					var formData = new FormData();
					formData.append("domainId", $(this).attr("data-id")); 
					$.ajax({
						url: "'.route("channel.domain.set.primary",$channel["domain"]->domain).'",
						type: "POST",
						cache: false,
						contentType: false,
						processData: false,
						dataType:"json",
						data:formData,
						headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
						success: function (result) {
							//console.log(result); 
							location.reload();
						}
					});
				}
			});
			$("#btnCheckDomain").click(function(){
				$(".newDomain input[name=ltdDomain]:checked").each(function () {
					$(".domainResult").empty(); 
					var ltdDomain=$(this).val(); 
					var Domain=convertToSlug($(".newDomain input[name=domainNewRegister]").val()); 
					var formData = new FormData();
					formData.append("domain", Domain); 
					formData.append("ltdDomain", ltdDomain); 
					formData.append("domainType", $(this).attr("data-type")); 
					formData.append("checkType", "status"); 
					$.ajax({
						url: "'.route("channel.domain.check",$channel["domain"]->domain).'",
						type: "POST",
						cache: false,
						contentType: false,
						processData: false,
						dataType:"json",
						data:formData,
						headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
						success: function (result) {
							console.log(result); 
							if(result.success==true){
								if(result.domainInfo.code==1){
									if(result.serviceAttribute.price_re_order==0){
										$(".domainResult").append("<li class=\"list-group-item list-group-item-info\">"
											+"<div class=\"pull-right\">"
												+"<div class=\"form-group\"><strong class=\"text-info\">Miễn phí</strong> <button type=\"button\" class=\"btn btn-xs btn-success selectDomain\" domain-data=\""+result.domain+"\" domain-type=\""+result.domainType+"\" data-check=\"\"><i class=\"glyphicon glyphicon-unchecked\"></i> chọn</button></div>"
											+"</div> "
											+"<strong>"+result.domainInfo.domainName+"</strong> <small class=\"hidden-xs\">(tên miền này chưa được đăng ký)</small>" 
										+"</li>"); 
									}else{
										$(".domainResult").append("<li class=\"list-group-item list-group-item-success\">"
											+"<div class=\"pull-right\">"
												+"<div class=\"form-group\"><strong class=\"text-danger\">"+(parseInt(result.serviceAttribute.price_re_order)+parseInt(result.serviceAttribute.price_order)).toLocaleString()+"<sup>VND</sup></strong> <button type=\"button\" class=\"btn btn-xs btn-success selectDomain\" domain-data=\""+result.domain+"\" domain-type=\""+result.domainType+"\" data-check=\"\"><i class=\"glyphicon glyphicon-unchecked\"></i> chọn</button></div>"
											+"</div> "
											+"<strong>"+result.domainInfo.domainName+"</strong> <small class=\"hidden-xs\">(tên miền này chưa được đăng ký)</small>" 
										+"</li>"); 
									}
								}else if(result.domainInfo.code==0){
									$(".domainResult").append("<li class=\"list-group-item list-group-item-warning disabled\"><button type=\"button\" class=\"btn btn-xs btn-default pull-right btnDetail\" data-domainName=\""+result.domainInfo.domainName+"\" data-registrar=\""+result.domainInfo.registrar+"\" data-nameServer=\""+result.domainInfo.nameServer+"\" data-creationDate=\""+result.domainInfo.creationDate+"\" data-expirationDate=\""+result.domainInfo.expirationDate+"\" data-registrantName=\""+result.domainInfo.registrantName+"\"><i class=\"glyphicon glyphicon-eye-open\"></i> Xem</button> <strong>"+result.domainInfo.domainName+"</strong> <small class=\"text-danger hidden-xs\">(tên miền này đã được đăng ký)</small> </li>"); 
								}else{
									$(".domainResult").append("<li class=\"list-group-item list-group-item-warning disabled\"><strong>"+result.domainInfo.domainName+"</strong> </li>"); 
								}
								var docHeight = jQuery(document).height();
								if(docHeight > jQuery(".mainpanel").height())
								jQuery(".mainpanel").height(docHeight);
							}else{
								$(".domainResult").empty(); 
								$(".domainResult").append("<div class=\"alert alert-danger alert-dismissable\" id=\"alertError\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"+result.message+"</div>"); 
							}
						}
					});
				});
			});
			$(".domainResult").on("click",".btnDetail",function(){
				$("#myModal .modal-title").empty(); 
				$("#myModal .modal-body").empty(); 
				$("#myModal .modal-footer").empty(); 
				$("#myModal .modal-footer").append("<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Đóng</button> "); 
				$("#myModal .modal-title").html("Thông tin tên miền "+$(this).attr("data-domainName")); 
				var nameServer=$(this).attr("data-nameServer"); 
				$("#myModal .modal-body").append(""
					+"<div class=\"form-group\">"
						+"<p><strong>Tên miền: </strong>"+$(this).attr("data-domainName")+"</p>"
						+"<p><strong>Đăng ký bởi: </strong>"+$(this).attr("data-registrar")+"</p>"
						+"<p><strong>Name Server: </strong>"+nameServer+"</p>"
						+"<p><strong>Ngày đăng ký: </strong>"+$(this).attr("data-creationDate")+"</p>"
						+"<p><strong>Ngày hết hạn: </strong>"+$(this).attr("data-expirationDate")+"</p>" 
						+"<p><strong>Người đăng ký: </strong>"+$(this).attr("data-registrantName")+"</p>"
					+"</div>"
				+""); 
				$("#myModal").modal("show"); 
			}); 
			$(".domainResult").on("click",".selectDomain",function(){
				$(".btnAddDomain .btnAddNewDomain").removeClass("disabled"); 
				if($(this).hasClass("btn-default")){
					$(this).find("i").removeClass( "glyphicon glyphicon-check selectedDomain" ).addClass( "glyphicon glyphicon-unchecked" ); 
					$(this).removeClass("btn-default").addClass("btn-success"); 
					$(this).attr("data-check", "");
				}else{
					$(this).find("i").removeClass( "glyphicon glyphicon-unchecked" ).addClass( "glyphicon glyphicon-check selectedDomain" ); 
					$(this).removeClass("btn-success").addClass("btn-default"); 
					$(this).attr("data-check", "check");
				}
				var count_elements = $(".domainResult .selectedDomain").length; 
				$(".btnAddDomain").html("<div class=\"panel-footer text-right\"><button type=\"button\" class=\"btn btn-danger btnAddNewDomain\"><i class=\"glyphicon glyphicon-plus\"></i> Thêm "+count_elements+"</button></div>"); 
			});
			$(".btnAddDomain").on("click",".btnAddNewDomain",function(){ 
				$(".messageDomain").empty(); 
				var dataDomain={};
				$(".btnAddDomain .btnAddNewDomain").addClass("disabled"); 
				$.each($(".domainResult .selectDomain"), function(i,item){  
					if($(this).attr("data-check")=="check"){ 
						var myObject = new Object();
						myObject.type=$(this).attr("domain-type"); 
						myObject.name=$(this).attr("domain-data"); 
						//dataDomain[$(this).attr("data-type")] = $(this).attr("domain-data"); 
						dataDomain[i] = myObject; 
						//dataDomain.name[i] = $(this).attr("domain-data"); 
					}
				});
				var Domains=JSON.stringify(dataDomain); 
				var formData = new FormData();
				formData.append("Domains", Domains); 
				$.ajax({
					url: "'.route("channel.domain.add.new",$channel["domain"]->domain).'",
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					type: "post",
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					dataType:"json",
					success:function(result){
						console.log(result); 
						if(result.success==true){
							$(".messageDomain").append("<div class=\"form-group\"><div class=\"alert alert-success\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"+result.message+"</div></div>"); 
							$("html, body").animate({scrollTop: $(".messageDomain").offset().top}, "slow"); 
							location.reload(); 
						}else{
							$(".messageDomain").append("<div class=\"form-group\"><div class=\"alert alert-danger\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"+result.message+"</div></div>"); 
							$("html, body").animate({scrollTop: $(".messageDomain").offset().top}, "slow");
						}
					},
					error: function(result) {
					}
				});
			}); 
			$(".domainEdit").click(function(){
				var domainId=$(this).attr("data-id"); 
				getInfoDomain(domainId); 
			}); 
			function getInfoDomain(domainId){
				$("#myModal .modal-title").empty(); 
				$("#myModal .modal-body").empty(); 
				$("#myModal .modal-footer").empty(); 
				var formData = new FormData();
				formData.append("domainId", domainId); 
				$.ajax({
					url: "'.route("domain.get.id",$channel["domain"]->domain).'",
					type: "POST",
					cache: false,
					contentType: false,
					processData: false,
					dataType:"json",
					data:formData,
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					success: function (result) {
						console.log(result); 
						if(result.success==true){
							$("#myModal .modal-title").html("<i class=\"glyphicon glyphicon-globe\"></i> "+result.domain); 
							$("#myModal .modal-body").append(""
								+"<div class=\"form-group\">"
									+"<label for=\"date_begin\" class=\"control-label\">Ngày đăng ký</label>"
									+"<div class=\"input-group\">"
										+"<span class=\"input-group-addon\" id=\"basic-addon1\"><i class=\"glyphicon glyphicon-calendar\"></i></span>"
										+"<input placeholder=\""+result.date_begin+"\" id=\"date_begin\" name=\"date_begin\" value=\""+result.date_begin+"\" type=\"text\" data-date-format=\"dd-mm-yyyy\" class=\"form-control datepicker\">"
									+"</div>"
								+"</div>"
								+"<div class=\"form-group\">"
									+"<label for=\"date_end\" class=\"control-label\">Ngày Hết hạn</label>"
									+"<div class=\"input-group\">"
										+"<span class=\"input-group-addon\" id=\"basic-addon1\"><i class=\"glyphicon glyphicon-calendar\"></i></span>"
										+"<input placeholder=\""+result.date_end+"\" id=\"date_end\" name=\"date_end\" value=\""+result.date_end+"\" type=\"text\" data-date-format=\"dd-mm-yyyy\" class=\"form-control datepicker\">"
									+"</div>"
								+"</div>"
								+"<div class=\"form-group\">"
									+"<label for=\"domainLocation\" class=\"control-label\">Vị trí tên miền</label>"
									+"<div class=\"input-group\">"
										+"<div class=\"checkbox text-primary\">"
											+"<label><input type=\"radio\" name=\"domainLocation\" value=\""+result.getDomain.domain_location+"\" checked disabled> "+result.getDomain.domain_location+"</label>"
										+"</div>"
										+"<div class=\"checkbox text-primary\">"
											+"<label><input type=\"radio\" name=\"domainLocation\" value=\"register\" > Đăng ký</label>"
										+"</div>"
										+"<div class=\"checkbox text-primary\">"
											+"<label><input type=\"radio\" name=\"domainLocation\" value=\"outside\" > Bên ngoài</label>"
										+"</div>"
									+"</div>"
								+"</div>"
								+"<div class=\"form-group\">"
									+"<label for=\"domainStatus\" class=\"control-label\">Tình trạng</label>"
									+"<div class=\"input-group\">"
										+"<div class=\"checkbox text-primary\">"
											+"<label><input type=\"radio\" name=\"domainStatus\" value=\""+result.getDomain.status+"\" checked disabled> "+result.getDomain.status+"</label>"
										+"</div>"
										+"<div class=\"checkbox text-primary\">"
											+"<label><input type=\"radio\" name=\"domainStatus\" value=\"active\" > Active</label>"
										+"</div>"
										+"<div class=\"checkbox text-primary\">"
											+"<label><input type=\"radio\" name=\"domainStatus\" value=\"delete\" > Delete</label>"
										+"</div>"
									+"</div>"
								+"</div>"
							+""); 
							$("#myModal .modal-footer").append("<div class=\"form-group\"><button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Đóng</button> <button type=\"button\" class=\"btn btn-primary\" id=\"saveChangeDomainInfo\" data-id=\""+result.getDomain.id+"\"><i class=\"glyphicon glyphicon-ok\"></i> Lưu</button></div>"); 
							$("#myModal .datepicker").datepicker();
							$("#myModal").modal("show"); 
						}
					}
				});
			}
			$("#myModal").on("click","#saveChangeDomainInfo",function() {
				var formData = new FormData();
				formData.append("domainId", $(this).attr("data-id")); 
				formData.append("date_begin", $("#myModal input[name=date_begin]").val()); 
				formData.append("date_end", $("#myModal input[name=date_end]").val()); 
				formData.append("domainLocation", $("#myModal input[name=domainLocation]:checked").val()); 
				formData.append("domainStatus", $("#myModal input[name=domainStatus]:checked").val()); 
				$.ajax({
					url: "'.route("domain.save.id",$channel["domain"]->domain).'",
					type: "POST",
					cache: false,
					contentType: false,
					processData: false,
					dataType:"json",
					data:formData,
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					success: function (result) {
						console.log(result); 
						location.reload();
					}
				});
			});
			$(".listDomain").on("click",".domainDelete",function() {
				if(confirm("Bạn có chắc muốn xóa?")){
					$(".message").empty(); 
					var domainId=$(this).attr("data-id"); 
					var formData = new FormData();
					formData.append("domainId", domainId); 
					$.ajax({
						url: "'.route("channel.domain.delete",$channel["domain"]->domain).'",
						type: "POST",
						cache: false,
						contentType: false,
						processData: false,
						dataType:"json",
						data:formData,
						headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
						success: function (result) {
							$("#loading").css("visibility", "hidden"); 
							if(result.success==true){
								location.reload();
							}
						}
					});
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
@if($channel['security']==true)
<?

	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('customAdmin','
	', $dependencies);
?>
@endif