<?
	$channel['theme']->setTitle('Quản lý tên miền');
?>
@include('themes.admin.inc.header')
<link rel="stylesheet" href="{{asset('assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
<script src="{{asset('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/checkbox.css')}}">
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				@include('themes.admin.partial.menuManageService')
			</div>
			<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12" id="panelContent">
				<div class="row">
					<div class="message"></div>
				</div>
				<div class="row">
					<div class="panel-group newDomain" id="accordion">
						<div class="panel panel-primary" style="border-radius:0;">
							<div class="panel-heading" style="border-radius:0;">
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
						<div class="panel panel-info" style="border-radius:0;">
							<div class="panel-heading" style="border-radius:0;">
								<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="openCollapse"><i class="glyphicon glyphicon-globe"></i> Đã có tên miền <i class="glyphicon glyphicon-chevron-down"></i></a>
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
									<code>Lưu ý: Khi thêm tên miền, bạn cần phải cấu hình Record A của DNS tên miền và trỏ về địa chỉ ip: <span class="text-success">139.59.241.224</span> để tên miền được hoạt động. </code>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-primary">
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
											<button type="button" class="btn btn-xs btn-primary disabled"><i class="glyphicon glyphicon-ok"></i> Tên miền chính</button> 
											@else 
												<button type="button" class="btn btn-xs btn-default setDomainPrimary" data-id="{{$domains->domain->id}}"><i class="glyphicon glyphicon-ok"></i> Tên miền chính</button> 
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
											<p><strong><span><i class="glyphicon glyphicon-usd"></i> {!!Site::price($domains->domain->serviceAttribute->price_re_order)!!}</span></strong><sup>{{$channel['info']->channelJoinRegion->region->currency_code}}</sup>/ {{$domains->domain->serviceAttribute->order_unit_month}} {{$domains->domain->serviceAttribute->per}}</p>
											@elseif($domains->domain->status=='pending')
												<p><strong><span><i class="glyphicon glyphicon-usd"></i> {!!Site::price($domains->domain->serviceAttribute->price_re_order+$domains->domain->serviceAttribute->price_order)!!}</span></strong><sup>{{$channel['info']->channelJoinRegion->region->currency_code}}</sup>/ {{$domains->domain->serviceAttribute->order_unit_month}} {{$domains->domain->serviceAttribute->per}}</p>
											@endif
											<p>
												<button type="button" class="btn btn-xs btn-primary reOrder" domain="{{$domains->domain->domain}}">@if($domains->domain->status=='pending')<i class="glyphicon glyphicon-hand-right"></i> Thanh toán @else <i class="glyphicon glyphicon-repeat"></i> Gia hạn @endif</button> 
												@if($domains->domain->domain_primary=='default')
												@if($domains->domain->status=='active')<button type="button" class="btn btn-xs btn-primary disabled"><i class="glyphicon glyphicon-ok"></i> Tên miền chính</button> @endif
												@else 
													@if($domains->domain->status=='active')<button type="button" class="btn btn-xs btn-default setDomainPrimary" data-id="{{$domains->domain->id}}"><i class="glyphicon glyphicon-ok"></i> Tên miền chính</button> @endif
												@endif
												@role(['admin','manage'])
													<button type="button" class="btn btn-xs btn-default domainEdit" data-id="{{$domains->domain->id}}" domain="{{$domains->domain->domain}}"><i class="glyphicon glyphicon-edit"></i> Sửa</button>
												@endrole
											</p>
										</div>
										<div class="domainMessage"></div>
									</li>
								@elseif($domains->domain->domain_location=='outside')
									<li class="list-group-item list-group-item-default disabled">
										<a href="#" class="close domainDelete" data-id="{{$domains->domain->id}}" data-dismiss="alert" aria-label="close">&times;</a>
										<h4 class="list-group-item-heading">{{$domains->domain->domain}}</h4>
										<div class="list-group-item-text">
											<p class="text-danger">Tên miền bên ngoài</p>
											<p>
											@if($domains->domain->domain_primary=='default')
											<button type="button" class="btn btn-xs btn-primary disabled"><i class="glyphicon glyphicon-ok"></i> Tên miền chính</button> 
											@else 
												<button type="button" class="btn btn-xs btn-default setDomainPrimary" data-id="{{$domains->domain->id}}"><i class="glyphicon glyphicon-ok"></i> Tên miền chính</button> 
											@endif
											@role(['admin','manage'])
												<button type="button" class="btn btn-xs btn-default domainEdit" data-id="{{$domains->domain->id}}" domain="{{$domains->domain->domain}}"><i class="glyphicon glyphicon-edit"></i> Sửa</button>
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
		</div>
	</div>
</div>
<div id="loading">
	<ul class="bokeh">
		<li></li>
		<li></li>
		<li></li>
	</ul>
</div>
<script>
	function convertToSlug(title)
	{
	  //Đổi chữ hoa thành chữ thường
		slug = title.toLowerCase();
	 
		//Đổi ký tự có dấu thành không dấu
		slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
		slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
		slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
		slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
		slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
		slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
		slug = slug.replace(/đ/gi, 'd');
		//Xóa các ký tự đặt biệt
		slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
		//Đổi khoảng trắng thành ký tự gạch ngang
		slug = slug.replace(/ /gi, "");
		//Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
		//Phòng trường hợp người nhập vào quá nhiều ký tự trắng
		slug = slug.replace(/\-\-\-\-\-/gi, '-');
		slug = slug.replace(/\-\-\-\-/gi, '-');
		slug = slug.replace(/\-\-\-/gi, '-');
		slug = slug.replace(/\-\-/gi, '-');
		//Xóa các ký tự gạch ngang ở đầu và cuối
		slug = '@' + slug + '@';
		slug = slug.replace(/\@\-|\-\@|\@/gi, '');
		//In slug ra textbox có id “slug”
		
	  return slug;
	}
	getCheckDomain(); 
	function getCheckDomain(){
		$('.panelNewRegisterDomain .checkDomain').append('<div class="input-group">'      
			+'<input type="text" class="form-control" name="domainNewRegister" placeholder="Nhập tên miền đăng ký">'
			+'<span class="input-group-btn">'
				+'<button class="btn btn-primary" type="button" id="btnCheckDomain"><span class="glyphicon glyphicon-retweet"></span> Kiểm tra</button>'
			+'</span>'
		+'</div>'
		+'<div class="row">'
			<?
				$getService=\App\Model\Services::find(4); 
			?>
			@foreach($getService->attributeAll as $serviceDomain)
			+'<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">'
				+'<div class="form-group text-left">'
					+'<div class="checkbox checkbox-success">'
						+'<input type="checkbox" class="filled-in" name="ltdDomain" data-type="register" value="{!!$serviceDomain->attribute_type!!}" id="{!!$serviceDomain->attribute_type!!}" checked/>'
						+'<label for="{!!$serviceDomain->attribute_type!!}"> .{!!$serviceDomain->attribute_type!!}</label>'
					+'</div>'
				+'</div>'
			+'</div>'
			@endforeach
		+'</div>'); 
	}
	$('.newDomain').on("click",".addDomainOldRegister",function() {
		$('#loading').css('visibility', 'visible'); 
		$('.message').empty(); 
		var domain=$('input[name=domainOldRegister]').val(); 
		var formData = new FormData();
		formData.append("domain", domain); 
        $.ajax({
            url: "{{route('channel.domain.add',$channel['domain']->domain)}}",
            type: "POST",
            cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
            success: function (result) {
				$('#loading').css('visibility', 'hidden'); 
				if(result.success==true){
					location.reload();
				}else if(result.success==false){
					$('.message').append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'! </div>'); 
					$('html, body').animate({scrollTop: $("#panelContent").offset().top}, 'slow');
				}
            }
        });
	});
	$('.setDomainPrimary').click(function () {
		if(confirm('Bạn có chắc muốn kích hoạt tên miền này là chính?')){
			var formData = new FormData();
			formData.append("domainId", $(this).attr('data-id')); 
			$.ajax({
				url: "{{route('channel.domain.set.primary',$channel['domain']->domain)}}",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:'json',
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
				success: function (result) {
					//console.log(result); 
					location.reload();
				}
			});
		}
	});
	$('#btnCheckDomain').click(function(){
		$('.newDomain input[name=ltdDomain]:checked').each(function () {
			$('.domainResult').empty(); 
			var ltdDomain=$(this).val(); 
			var Domain=convertToSlug($('.newDomain input[name=domainNewRegister]').val()); 
			var formData = new FormData();
			formData.append("domain", Domain); 
			formData.append("ltdDomain", ltdDomain); 
			formData.append("domainType", $(this).attr('data-type')); 
			formData.append("checkType", 'status'); 
			$.ajax({
				url: "{{route('channel.domain.check',$channel['domain']->domain)}}",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:'json',
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
				success: function (result) {
					//console.log(result); 
					if(result.success==true){
						if(result.domainInfo.channel.status=='Available'){
							if(result.serviceAttribute.price_re_order==0){
								$('.domainResult').append('<li class="list-group-item list-group-item-info">'
									+'<div class="pull-right">'
										+'<div class="form-group"><strong class="text-info">Miễn phí</strong> <button type="button" class="btn btn-xs btn-success selectDomain" domain-data="'+result.domain+'" domain-type="'+result.domainType+'" data-check=""><i class="glyphicon glyphicon-unchecked"></i> chọn</button></div>'
									+'</div> '
									+'<strong>'+result.domainInfo.channel.title+'</strong> <small class="hidden-xs">(tên miền này chưa được đăng ký)</small>' 
								+'</li>'); 
							}else{
								$('.domainResult').append('<li class="list-group-item list-group-item-success">'
									+'<div class="pull-right">'
										+'<div class="form-group"><strong class="text-danger">'+(parseInt(result.serviceAttribute.price_re_order)+parseInt(result.serviceAttribute.price_order)).toLocaleString()+'<sup>{{$channel['info']->channelJoinRegion->region->currency_code}}</sup></strong> <button type="button" class="btn btn-xs btn-success selectDomain" domain-data="'+result.domain+'" domain-type="'+result.domainType+'" data-check=""><i class="glyphicon glyphicon-unchecked"></i> chọn</button></div>'
									+'</div> '
									+'<strong>'+result.domainInfo.channel.title+'</strong> <small class="hidden-xs">(tên miền này chưa được đăng ký)</small>' 
								+'</li>'); 
							}
						}else if(result.domainInfo.channel.status=='Unavailable'){
							$('.domainResult').append('<li class="list-group-item list-group-item-warning disabled"><button type="button" class="btn btn-xs btn-default pull-right"><i class="glyphicon glyphicon-eye-open"></i> Xem</button> <strong>'+result.domainInfo.channel.title+'</strong> <small class="text-danger hidden-xs">(tên miền này đã được đăng ký)</small> </li>'); 
						}else{
							$('.domainResult').append('<li class="list-group-item list-group-item-warning disabled"><strong>'+result.domainInfo.channel.title+'</strong> </li>'); 
						}
					}else{
						$('.domainResult').empty(); 
						$('.domainResult').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					}
				}
			});
		});
	});
	$('.domainResult').on("click",".selectDomain",function(){
		if($(this).hasClass('disabled')){
			$(this).find('i').removeClass( "glyphicon glyphicon-check selectedDomain" ).addClass( "glyphicon glyphicon-unchecked" ); 
			$(this).removeClass("disabled").addClass("btn-success"); 
			$(this).attr('data-check', '');
		}else{
			$(this).find('i').removeClass( "glyphicon glyphicon-unchecked" ).addClass( "glyphicon glyphicon-check selectedDomain" ); 
			$(this).removeClass("btn-success").addClass("disabled"); 
			$(this).attr('data-check', 'check');
		}
		var count_elements = $('.domainResult .selectedDomain').length; 
		$('.btnAddDomain').html('<div class="panel-footer text-right"><button type="button" class="btn btn-danger btnAddNewDomain"><i class="glyphicon glyphicon-plus"></i> Thêm '+count_elements+'</button></div>'); 
	});
	$('.btnAddDomain').on("click",".btnAddNewDomain",function(){ 
		$('.messageDomain').empty(); 
		var dataDomain={};
		$.each($(".domainResult .selectDomain"), function(i,item){  
			if($(this).attr('data-check')=='check'){ 
				var myObject = new Object();
				myObject.type=$(this).attr('domain-type'); 
				myObject.name=$(this).attr('domain-data'); 
				//dataDomain[$(this).attr('data-type')] = $(this).attr('domain-data'); 
				dataDomain[i] = myObject; 
				//dataDomain.name[i] = $(this).attr('domain-data'); 
			}
		});
		var Domains=JSON.stringify(dataDomain); 
		var formData = new FormData();
		formData.append("Domains", Domains); 
		$.ajax({
			url: "{{route('channel.domain.add.new',$channel['domain']->domain)}}",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				console.log(result); 
				if(result.success==true){
					$('.messageDomain').append('<div class="form-group"><div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div></div>'); 
					$('html, body').animate({scrollTop: $(".messageDomain").offset().top}, 'slow'); 
					location.reload(); 
				}else{
					$('.messageDomain').append('<div class="form-group"><div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div></div>'); 
					$('html, body').animate({scrollTop: $(".messageDomain").offset().top}, 'slow');
				}
			},
			error: function(result) {
			}
		});
	}); 
	$('.domainEdit').click(function(){
		var domainId=$(this).attr('data-id'); 
		getInfoDomain(domainId); 
	}); 
	function getInfoDomain(domainId){
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		var formData = new FormData();
		formData.append("domainId", domainId); 
		$.ajax({
			url: "{{route('domain.get.id',$channel['domain']->domain)}}",
			type: "POST",
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			success: function (result) {
				console.log(result); 
				if(result.success==true){
					$('#myModal .modal-title').html('<i class="glyphicon glyphicon-globe"></i> '+result.domain); 
					$('#myModal .modal-body').append(''
						+'<div class="form-group">'
							+'<label for="date_begin" class="control-label">Ngày đăng ký</label>'
							+'<div class="input-group">'
								+'<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-calendar"></i></span>'
								+'<input placeholder="'+result.date_begin+'" id="date_begin" name="date_begin" value="'+result.date_begin+'" type="text" data-date-format="dd-mm-yyyy" class="form-control datepicker">'
							+'</div>'
						+'</div>'
						+'<div class="form-group">'
							+'<label for="date_end" class="control-label">Ngày Hết hạn</label>'
							+'<div class="input-group">'
								+'<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-calendar"></i></span>'
								+'<input placeholder="'+result.date_end+'" id="date_end" name="date_end" value="'+result.date_end+'" type="text" data-date-format="dd-mm-yyyy" class="form-control datepicker">'
							+'</div>'
						+'</div>'
						+'<div class="form-group">'
							+'<label for="domainLocation" class="control-label">Vị trí tên miền</label>'
							+'<div class="input-group">'
								+'<div class="checkbox text-primary">'
									+'<label><input type="radio" name="domainLocation" value="'+result.getDomain.domain_location+'" checked disabled> '+result.getDomain.domain_location+'</label>'
								+'</div>'
								+'<div class="checkbox text-primary">'
									+'<label><input type="radio" name="domainLocation" value="register" > Đăng ký</label>'
								+'</div>'
								+'<div class="checkbox text-primary">'
									+'<label><input type="radio" name="domainLocation" value="outside" > Bên ngoài</label>'
								+'</div>'
							+'</div>'
						+'</div>'
						+'<div class="form-group">'
							+'<label for="domainStatus" class="control-label">Tình trạng</label>'
							+'<div class="input-group">'
								+'<div class="checkbox text-primary">'
									+'<label><input type="radio" name="domainStatus" value="'+result.getDomain.status+'" checked disabled> '+result.getDomain.status+'</label>'
								+'</div>'
								+'<div class="checkbox text-primary">'
									+'<label><input type="radio" name="domainStatus" value="active" > Active</label>'
								+'</div>'
								+'<div class="checkbox text-primary">'
									+'<label><input type="radio" name="domainStatus" value="delete" > Delete</label>'
								+'</div>'
							+'</div>'
						+'</div>'
					+''); 
					$('#myModal .modal-footer').append('<div class="form-group"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button> <button type="button" class="btn btn-primary" id="saveChangeDomainInfo" data-id="'+result.getDomain.id+'"><i class="glyphicon glyphicon-ok"></i> Lưu</button></div>'); 
					$('#myModal .datepicker').datepicker();
					$('#myModal').modal('show'); 
				}
			}
		});
	}
	$('#myModal').on("click","#saveChangeDomainInfo",function() {
		var formData = new FormData();
		formData.append("domainId", $(this).attr('data-id')); 
		formData.append("date_begin", $('#myModal input[name=date_begin]').val()); 
		formData.append("date_end", $('#myModal input[name=date_end]').val()); 
		formData.append("domainLocation", $('#myModal input[name=domainLocation]:checked').val()); 
		formData.append("domainStatus", $('#myModal input[name=domainStatus]:checked').val()); 
		$.ajax({
			url: "{{route('domain.save.id',$channel['domain']->domain)}}",
			type: "POST",
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			success: function (result) {
				console.log(result); 
				location.reload();
			}
		});
	}); 
	$('.reOrder').click(function(){
		var formData = new FormData();
		formData.append("cartType", 'domainReOrder'); 
		formData.append("cartName", $(this).attr('domain')); 
		$.ajax({
			url: "{{route('cart.add',$channel['domain']->domain)}}",
			type: "POST",
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			success: function (result) {
				//console.log(result); 
				if(result.success==true){
					window.location.href="{{route('cart.show',$channel['domain']->domain)}}"; 
				}else{
					$('.domainMessage').append('<div class="form-group"><div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div></div>'); 
					$('html, body').animate({scrollTop: $(".listDomain").offset().top}, 'slow');
				}
			}
		});
	}); 
	$('.resultCheckDomain').on("click",".btnRegisterDomain",function() {
		$('.resultCheckDomain').empty(); 
		var formData = new FormData();
		formData.append("cartType", 'domain'); 
		formData.append("cartName", $(this).attr('domain')); 
        $.ajax({
            url: "{{route('cart.add',$channel['domain']->domain)}}",
            type: "POST",
            cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
            success: function (result) {
				//console.log(result); 
				if(result.success==true){
					window.location.href="{{route('cart.show',$channel['domain']->domain)}}"; 
				}else{
					$('.resultCheckDomain').append('<div class="form-group"><div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div></div>'); 
				}
            }
        });
	});
	$('.resultCheckDomain').on("click","#domainView",function() {
		$('#loading').css('visibility', 'visible'); 
		$('.message').empty(); 
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		var domain=$('input[name=domain]').val(); 
		var formData = new FormData();
		formData.append("domain", domain); 
		formData.append("checkType", 'info'); 
        $.ajax({
            url: "{{route('channel.domain.check',$channel['domain']->domain)}}",
            type: "POST",
            cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
            success: function (result) {
				//console.log(result); 
				$('#loading').css('visibility', 'hidden'); 
				$('#myModal .modal-title').text(result.domainInfo.channel.title); 
				if(result.domainInfo.channel.item.description===null){
					$('#myModal .modal-body').append('<div class="alert alert-warning">Không tìm thấy thông tin chủ sở hữu tên miền '+domain+'! </div>'); 
				}else{
					if(typeof result.domainInfo.channel.item[1]!=='undefined'){
						$('#myModal .modal-body').append(result.domainInfo.channel.item[1].description); 
					}else{
						$('#myModal .modal-body').append(result.domainInfo.channel.item.description); 
					}
				}
				$('#myModal').modal('show'); 
            }
        });
	});
	$('.listDomain').on("click",".domainDelete",function() {
		if(confirm('Bạn có chắc muốn xóa?')){
			$('.message').empty(); 
			var domainId=$(this).attr('data-id'); 
			var formData = new FormData();
			formData.append("domainId", domainId); 
			$.ajax({
				url: "{{route('channel.domain.delete',$channel['domain']->domain)}}",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:'json',
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
				success: function (result) {
					$('#loading').css('visibility', 'hidden'); 
					if(result.success==true){
						location.reload();
					}
				}
			});
		}
	});
</script>
@include('themes.admin.inc.footer')