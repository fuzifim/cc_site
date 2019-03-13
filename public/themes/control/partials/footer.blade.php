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
			@if($channel['info']->channel_parent_id==0) 
			<div class="row">
				<div class="col-md-4">
					<small><strong>CÔNG TY CỔ PHẦN CUNG CẤP</strong> - MST: 0314609089</small><br>
					<small>Trụ sở chính: 104 Hoàng Diệu 2, P. Linh Chiểu, Q. Thủ Đức, Tp. HCM</small><br>
					<small>Hotline: <a href="tel:0903706288">0903706288</a> - Email: <a href="mailto:contact@cungcap.net">contact@cungcap.net</a></small><br>
					<small>Cung Cấp. Net không chịu bất kỳ trách nhiệm nào từ các thông tin bởi người dùng đăng lên</small>
				</div>
				<div class="col-md-4">
					<ul class="listFooter">
						<li><a href="http://cungcap.net/vi/gioi-thieu"><i class="glyphicon glyphicon-info-sign"></i> Giới thiệu</a></li>
						<li><a href="{{route('channel.contact',$channel['domainPrimary'])}}"><i class="glyphicon glyphicon-envelope"></i> Liên hệ</a></li>
						<li><a href="http://cungcap.net/vi/dieu-khoan-su-dung"><i class="glyphicon glyphicon-chevron-right"></i> Điều khoản sử dụng</a></li>
						<li><a href="http://cungcap.net/vi/chinh-sach-bao-mat"><i class="glyphicon glyphicon-chevron-right"></i> Chính sách bảo mật</a></li>
						<li><a href="http://cungcap.net/vi/quy-che-hoat-dong"><i class="glyphicon glyphicon-chevron-right"></i> Quy chế hoạt động</a></li>
					</ul>
				</div>
				<div class="col-md-4">
					<ul class="listFooter">
						<li><a href="http://www.facebook.com/cungcap.net" rel="nofollow"><i class="fa fa-facebook-square"></i> Facebook</a></li> 
						<li><a href="https://plus.google.com/u/0/113087254631418458221" rel="nofollow"><i class="fa fa-google-plus"></i> Google+</a></li>
						<li><a href="https://www.youtube.com/user/fuzifim" rel="nofollow"><i class="fa fa-youtube-play"></i> Youtube</a></li>
					</ul>
				</div>
			</div>
			@else
				<div class="row">
					<div class="addressInfo">
						@if(isset($channel['info']->companyJoin->company))
							<h3 class="subtitle mb5"><a href="{{route('channel.home',$channel['domainPrimary'])}}"><strong>{!!$channel['info']->companyJoin->company->company_name!!}</strong></a></h3>
						@else
							<h3 class="subtitle mb5"><a href="{{route('channel.home',$channel['domainPrimary'])}}"><strong>{!!$channel['info']->channel_name!!}</strong></a></h3>
						@endif
						@if(count($channel['info']->joinAddress)>0)
							@foreach($channel['info']->joinAddress as $joinAddress)
								<div class="mb5 addressItemGroup">
									<div class="addressItem">
										<i class="glyphicon glyphicon-map-marker"></i> {!!$joinAddress->address->address!!} 
										@if(!empty($joinAddress->address->joinWard->ward->id)) - {!!$joinAddress->address->joinWard->ward->ward_name!!}@endif
										@if(!empty($joinAddress->address->joinDistrict->district->id)) - {!!$joinAddress->address->joinDistrict->district->district_name!!}@endif
										@if(!empty($joinAddress->address->joinSubRegion->subregion->id)) - {!!$joinAddress->address->joinSubRegion->subregion->subregions_name!!}@endif
										@if(!empty($joinAddress->address->joinRegion->region->id)) - <i class="flag flag-{{mb_strtolower($joinAddress->address->joinRegion->region->iso)}}"></i> {!!$joinAddress->address->joinRegion->region->country!!}@endif
									</div>
								</div>
							@endforeach
						@else
							<div class="mb5 addressItemGroup">
								<div class="addressItem">
									<small><i class="glyphicon glyphicon-map-marker"></i> <span style="font-style:italic; ">Chưa cập nhật địa chỉ...</small>
								</div>
							</div>
						@endif
						<div class="mb5 emailPhoneItemGroup">
						@if(count($channel['info']->joinEmail)>0)
							@foreach($channel['info']->joinEmail as $joinEmail)
								<i class="glyphicon glyphicon-envelope"></i> <a href="mailto:{{$joinEmail->email->email_address}}">{{$joinEmail->email->email_address}}</a>
							@endforeach
						@endif
						@if(count($channel['info']->joinPhone)>0)
							@foreach($channel['info']->joinPhone as $joinPhone)
								- <i class="glyphicon glyphicon-earphone"></i> <a href="tel:{{$joinPhone->phone->phone_number}}">{{$joinPhone->phone->phone_number}}</a>
							@endforeach
						@endif
						</div>
						<div class="mb5">
							<i class="glyphicon glyphicon-globe"></i> Website: <a href="{{route('channel.home',$channel['domainPrimary'])}}">http://{!!$channel['domainPrimary']!!}</a>
						</div>
						@if($channel['security']==true)<div class="mb5"><a href="{{route('channel.contact',$channel['domain']->domain)}}" class="text-danger"><i class="fa fa-pencil"></i> Chỉnh sửa</a></div>@endif
					</div>
				</div>
			@endif
		</div>
	</div>
	<a href="#" id="back-to-top" title="Back to top"><i class="glyphicon glyphicon-chevron-up"></i></a>
	<div class="footerFixel">
		<div class="footerFixelIcon">
			<div class="btn-group btn-group-sm" role="group" aria-label=>
				@if(count($channel['info']->joinPhone))
				<a href="tel:{{$channel['info']->joinPhone[0]->phone->phone_number}}" class="btn btn-primary btn-secondary"><i class="glyphicon glyphicon-earphone"></i> <strong>{{$channel['info']->joinPhone[0]->phone->phone_number}}</strong></a>
				@endif
				@if(!empty($channel['nameFanpageFacebook']))
					<a href="https://m.me/{{$channel['nameFanpageFacebook']}}" class="btn btn-primary btn-secondary ctrlq fb-button btnMessage"><i class="fa fa-facebook-square"></i> Nhắn tin <span class="badge">1</span></a>
					<?
						$dependencies = array(); 
						$channel['theme']->asset()->writeScript('facebookChat','
							$(document).ready(function(){function detectmob(){if( navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i) ){return true;}else{return false;}}var t={delay: 125, overlay: $(".fb-overlay"), widget: $(".fb-widget"), button: $(".fb-button")}; setTimeout(function(){$("div.fb-livechat").fadeIn()}, 8 * t.delay); if(!detectmob()){$(".ctrlq").on("click", function(e){e.preventDefault(), t.overlay.is(":visible") ? (t.overlay.fadeOut(t.delay), t.widget.stop().animate({bottom: 0, opacity: 0}, 2 * t.delay, function(){$(this).hide("slow"), t.button.show()})) : t.button.fadeOut("medium", function(){t.widget.stop().show().animate({bottom: "30px", opacity: 1}, 2 * t.delay), t.overlay.fadeIn(t.delay)})})}});
						', $dependencies);
					?>
				@endif
				@if(!empty($channel['zaloAccount']))
					<a class="btn btn-primary btn-secondary" href="http://zalo.me/{{$channel['zaloAccount']}}"><i class="fa fa-comment"></i> Zalo</a>
				@endif
			</div>
			@if(!empty($channel['nameFanpageFacebook']))
				<style>.btnMessage{padding-right:18px !important;}.btnMessage .badge{top:0px;background:#D9534F;right:0px;position:absolute;padding:2px 5px;color:#fff;}.fb-livechat, .fb-widget{display: none}.ctrlq.fb-close{position: fixed; right: 24px; cursor: pointer}.fb-widget{background: #fff; z-index: 1000; position: fixed; width: 360px; height: 435px; overflow: hidden; opacity: 0; bottom: 0; right: 24px; border-radius: 6px; -o-border-radius: 6px; -webkit-border-radius: 6px; box-shadow: 0 5px 40px rgba(0, 0, 0, .16); -webkit-box-shadow: 0 5px 40px rgba(0, 0, 0, .16); -moz-box-shadow: 0 5px 40px rgba(0, 0, 0, .16); -o-box-shadow: 0 5px 40px rgba(0, 0, 0, .16)}.fb-credit{text-align: center; margin-top: 8px}.fb-credit a{transition: none; color: #bec2c9; font-family: Helvetica, Arial, sans-serif; font-size: 12px; text-decoration: none; border: 0; font-weight: 400}.ctrlq.fb-overlay{z-index: 0; position: fixed; height: 100vh; width: 100vw; -webkit-transition: opacity .4s, visibility .4s; transition: opacity .4s, visibility .4s; top: 0; left: 0; background: rgba(0, 0, 0, .05); display: none}.ctrlq.fb-close{z-index: 4; padding: 0 6px; background: #365899; font-weight: 700; font-size: 11px; color: #fff; margin: 8px; border-radius: 3px}.ctrlq.fb-close::after{content: "X"; font-family: sans-serif}.bubble-msg{width: 120px; left: -140px; top: 5px; position: relative; background: rgba(59, 89, 152, .8); color: #fff; padding: 5px 8px; border-radius: 8px; text-align: center; font-size: 13px;}</style><div class="fb-livechat"> <div class="ctrlq fb-overlay"></div><div class="fb-widget"> <div class="ctrlq fb-close"></div><div class="fb-page" data-href="https://www.facebook.com/{{$channel['nameFanpageFacebook']}}" data-tabs="messages" data-width="360" data-height="400" data-small-header="true" data-hide-cover="true" data-show-facepile="false"> </div></div></div>
			@endif
		</div>
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
	@if(!empty($channel['color']->footerScript)){!!$channel['color']->footerScript!!}@endif
	<?php
	Theme::asset()->container('footer')->add('jquery.lazy', '//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.6/jquery.lazy.min.js', array('core-script'));
	Theme::asset()->container('footer')->add('jquery.lazy.plugins', '//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.6/jquery.lazy.plugins.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('custom', 'js/custom.min.js?v=9', array('core-script'));
	?>