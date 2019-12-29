<div class="headerbar">
	<a class="menutoggle"><i class="fa fa-bars"></i></a>
	<div itemscope itemtype="http://schema.org/WebSite"> 
		<meta itemprop="url" content="{{route('channel.home',$channel['domainPrimary'])}}"/>
		<form itemprop="potentialAction" itemscope itemtype="http://schema.org/SearchAction" id="searchform" class="searchform" action="{{route('search.query',$channel['domainPrimary'])}}" method="get">
			<meta itemprop="target" content="{{route('search.query',array($channel['domainPrimary']))}}?v={v}"/>
			<input itemprop="query-input"  type="text" class="form-control" name="v" id="searchAll" placeholder="Tìm kiếm..." />
			<input type="hidden" name="t" id="searchType" value="">
			<input type="hidden" name="i" id="searchId" value="">
		</form>
	</div>
	<div class="header-right">
		<ul class="headermenu">
			<li>
				<a class="btn btn-default dropdown-toggle" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-home"></i> <span class="hidden-xs">@lang('main.home')</span></a>
			</li>
			@if (Auth::check())
			@if($channel['info']->channel_parent_id!=0)
				<li>
					<div class="btn-group">
						<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<i class="glyphicon glyphicon-envelope"></i>
							<span class="badge"></span>
						</button>
					</div>
				</li>
				<li>
					<div class="btn-group">
						<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<i class="glyphicon glyphicon-globe"></i>
							<span class="badge"></span>
						</button>
					</div>
				</li>
			@endif
			@if($channel['security']!=true)
				@if(\App\Model\Channel_role::where('parent_id','=',$channel['info']->id)->where('user_id','=',Auth::user()->id)->count()<=0)
					<li>
						<div class="btn-group">
							<button type="button" id="btnJoinChannel" class="btn btn-default"><span class="text-success"><i class="glyphicon glyphicon-ok-sign"></i><span class="hidden-xs"> Gia nhập</span></span></button>
						</div>
					</li>
				@endif
			@endif
			<li>
				<a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
					<i class="glyphicon glyphicon-user"></i>
					<span class="hidden-xs">{{Auth::user()->name}}</span>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu dropdown-menu-usermenu pull-right">
					@if($channel['security']==true)
					<li class="dropdown-header">Quản lý dịch vụ</li>
					<li><a href="{{route('channel.me',config('app.url'))}}" target="_blank"><i class="glyphicon glyphicon-list"></i> Website của tôi</a></li>
					<li class="divider"></li>
					@endif
					<li class="dropdown-header">Tài khoản</li>
					<li><a href="{{route('channel.profile.info',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-user"></i> Hồ sơ</a></li>
					<li><a href="{{route('pay.history',$channel['domain']->domain)}}"><i class="glyphicon  glyphicon-credit-card"></i> Thanh toán</a></li>
					<li><a href="http://help.cungcap.net/" target="_blank"><i class="glyphicon glyphicon-question-sign"></i> Trợ giúp</a></li>
					<li><a href="{{route('channel.logout',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-log-out"></i> Đăng xuất</a></li>
				</ul>
			</li>
			@else
			<li>
				<div class="btn-group">
					<a href="{{route('channel.login',$channel['domain']->domain)}}" class="btn btn-default dropdown-toggle"><span class="hidden-xs"><i class="glyphicon glyphicon-log-in"></i> Đăng nhập</span><span class="visible-xs"><small><i class="glyphicon glyphicon-log-in"></i> Đăng nhập</small></span></a>
				</div>
			</li>
			@endif
		</ul>
	</div><!-- header-right -->

</div>

@if($channel['info']->id === 31061)
	<a href="https://docs.google.com/forms/d/e/1FAIpQLSdi2O6634V7Gc7AjSk9VylAz9ZM-oK3AslbY9sW_hpAkG1F9w/viewform" rel="nofollow"><img src="{{asset('assets/img/tiep-nhan-yeu-cau-small.jpg')}}" class="img-responsive"></a>
@endif

<!-- headerbar -->
@if(Auth::check())
	@if(Auth::user()->user_status!='active' && !empty(Auth::user()->email))
		<div class="alert alert-warning">
			Xin chào <strong>{!!Auth::user()->name!!}!</strong> Tài khoản của bạn chưa được kích hoạt, vui lòng kiểm tra email đăng ký <strong>{{Auth::user()->email}}</strong> để nhận mã kích hoạt tài khoản hoặc truy cập vào <a href="{{route('channel.profile.info',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-user"></i> Hồ sơ</a> để thay đổi email hoặc gửi lại mã kích hoạt!  
		</div>
	@elseif(Auth::user()->user_status!='active' && empty(Auth::user()->email))
		<div class="alert alert-warning">
			Xin chào <strong>{!!Auth::user()->name!!}!</strong> Tài khoản của bạn chưa được kích hoạt, vui lòng truy cập vào <a href="{{route('channel.profile.info',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-user"></i> Hồ sơ</a> thêm địa chỉ email và gửi mã kích hoạt vào email!  
		</div>
	@endif
@endif
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('searchAll','
		jQuery.ajax({
			  url: "'.Theme::asset()->usePath()->url('js/jquery.autocomplete.min.js').'",
			  dataType: "script",
			  cache: true
		}).done(function() {
			if($("#searchAll").length>0){ 
				$("#searchAll").autocomplete({ 
					serviceUrl: "'.route("search.all",$channel["domain"]->domain).'",
					type:"GET",
					paramName:"txt",
					dataType:"json",
					minChars:2,
					deferRequestBy:100,
					onSearchComplete: function(){
						$(".autocomplete-suggestions").css({
							"width":+$(".headerbar").outerWidth()-$(".menutoggle").outerWidth()
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
@if(Auth::check())
<?
	if(!empty($channel['info']->joinAddress[0]->address->joinRegion->region->id)){
		$regionDefaultId=$channel['info']->joinAddress[0]->address->joinRegion->region->id; 
		$regionDefaultIso=mb_strtolower($channel['info']->joinAddress[0]->address->joinRegion->region->iso); 
	}else{
		$regionDefaultId=""; 
		$regionDefaultIso=""; 
	}
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		$("#btnJoinChannel").click(function() {
			var rootUrl=$("meta[name=root]").attr("content"); 
			$.ajax({
				url: "'.route("channel.profile.joinchannel",$channel["domain"]->domain).'",
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				type: "post",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				success:function(result){
					jQuery.gritter.add({
						title: "Thông báo!",
						text: "Gia nhập thành công! ", 
						class_name: "growl-success",
						sticky: false,
						time: ""
					});
					location.reload(); 
				},
				error: function(result) {
				}
			});
		}); 
	', $dependencies);
?>
@endif