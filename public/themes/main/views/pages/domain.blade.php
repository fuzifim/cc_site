<?
	$channel['theme']->setTitle('Cung Cấp Tên miền');
	$channel['theme']->setKeywords('Kiểm tra tên miền, đăng ký tên miền ');
	$channel['theme']->setDescription('Đăng ký tên miền để bảo vệ thương hiệu của bạn trên Internet! '); 
	$channel['theme']->setImage('http://'.$channel["domainPrimary"].Theme::asset()->url('img/cungcap-tenmien.jpg')); 
?>
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('title') !!}</h1>
		<span>{!! Theme::get('description') !!}</span>
	</div>
	<div class="contentpanel">
		<div id="collapse1" class="panel-collapse collapse in">
			<form id="formCheckDomain" class="form-group">
				<div id="preloaderInBox" style="display:none;">
						<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
				</div>
				<div class="panelNewRegisterDomain">
					<div class="checkDomain"></div>
					<div class="domainResult"></div>
					<div class="messageDomain"></div>
					<div class="btnAddDomain"></div>
				</div>
			</form>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading"><h2 class="panel-title">Cung Cấp tên miền Việt Nam và Quốc tế</h2></div>
			<ul class="list-group list-group-flush">
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong class="text-info">Chất lượng dịch vụ và chăm sóc khách hàng</strong> Sự hài lòng và phát triển của khách hàng là mục tiêu quan trọng nhất của chúng tôi</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong class="text-info">Hỗ trợ khách hàng 24/7</strong> Hỗ trợ, giải quyết các vấn đề khách hàng gặp phải nhanh chóng, kịp thời và chính xác</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong class="text-info">Hệ thống quản trị thông minh và chuyên nghiệp</strong> Công nghệ luôn được cập nhật mới nhất với khả năng bảo mật tối đa và tốc độ nhanh nhất, hoạt động tốt trên nhiều nền tảng</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong class="text-info">Thanh toán thuận tiện và bảo mật</strong> Hệ thống thanh toán luôn hướng tới sự bảo mật và thuận tiện nhất cho khách hàng</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong class="text-info">Bảo vệ quyền riêng tư và thông tin khách hàng</strong> Chúng tôi luôn tôn trọng và làm mọi cách để bảo vệ quyền riêng tư và thông tin của khách hàng sử dụng dịch vụ</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong class="text-info">Nhà đăng ký tên miền Việt Nam và Quốc tế</strong> Chúng tôi là Nhà đăng ký tên miền Việt Nam và Quốc tế từ những năm 2008</li>
			</ul>
		</div>
	</div>
</div>
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
				+"<input type=\"text\" class=\"form-control\" name=\"domainNewRegister\" placeholder=\"Nhập tên miền\" required>"
				+"<span class=\"input-group-btn\">"
					+"<button class=\"btn btn-primary\" type=\"submit\" id=\"btnCheckDomain\"><span class=\"glyphicon glyphicon-retweet\"></span> Kiểm tra</button>"
				+"</span>"
			+"</div>"
			+"<label class=\"error\" for=\"domainNewRegister\"></label>"
			+"</div>"
			+"<div class=\"row\">"
				'.$listLtd.'
			+"</div>");
		}
		var $validator = jQuery("#formCheckDomain").validate({
			highlight: function(element) {
			  jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
			},
			success: function(element) {
			  jQuery(element).closest(".form-group").removeClass("has-error");
			}
		});
		$(".panelNewRegisterDomain").on("click","#btnCheckDomain",function() {
			$(".panelNewRegisterDomain input[name=ltdDomain]:checked").each(function () {
				$(".domainResult").empty(); 
				var ltdDomain=$(this).val(); 
				var formData = new FormData();
				formData.append("domain", $(".panelNewRegisterDomain input[name=domainNewRegister]").val()); 
				formData.append("ltdDomain", ltdDomain); 
				formData.append("domainType", $(this).attr("data-type")); 
				formData.append("checkType", "status"); 
				$("#preloaderInBox").css("display", "block"); 
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
							$("#preloaderInBox").css("display", "none"); 
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
						}else{
							$("#preloaderInBox").css("display", "none"); 
							$(".domainResult").empty(); 
							$(".domainResult").append("<div class=\"alert alert-danger alert-dismissable\" id=\"alertError\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"+result.message+"</div>"); 
						}
					}
				});
			});
			return false; 
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
			formData.append("cartType", "domainAddCart"); 
			$("#preloaderInBox").css("display", "block");  
			$.ajax({
				url: "'.route("cart.domain.session",$channel["domainPrimary"]).'", 
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
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						});
						window.location.href = "'.route("cart.domain.info",$channel["domainPrimary"]).'";
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
		}); 
		$(".panelNewRegisterDomain").on("click",".btnDetail",function() {
			var domainName=$(this).attr("data-domainName"); 
			var dateCreated=$(this).attr("data-creationDate"); 
			var dateExpiration=$(this).attr("data-expirationDate"); 
			var domainRegistar=$(this).attr("data-registrar"); 
			var nameServer=$(this).attr("data-nameServer"); 
			var registrantName=$(this).attr("data-registrantName"); 
			$("#modalViewDomain .modal-title").empty(); 
			$("#modalViewDomain .modal-body").empty(); 
			$("#modalViewDomain .modal-title").text(domainName); 
			$("#modalViewDomain .modal-body").html(""
			+"<strong>Ngày đăng ký: </strong>"+dateCreated+"<br>" 
			+"<strong>Ngày hết hạn: </strong>"+dateExpiration+"<br>" 
			+"<strong>Chủ sở hữu: </strong>"+registrantName+"<br>" 
			+"<strong>Name Server: </strong>"+nameServer+"<br>" 
			+"<strong>Nhà đăng ký: </strong>"+domainRegistar+"<br>" 
			+"");
			$("#modalViewDomain").modal("show"); 
			
		});
	', $dependencies);
?>
<div id="modalViewDomain" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>