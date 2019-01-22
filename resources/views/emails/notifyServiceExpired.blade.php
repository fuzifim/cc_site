@if(!empty($channel->channelParent->channel_name))
<?
	$channelParent=\App\Model\Channel::find(2); 
	if($channelParent->domainJoinPrimary->domain->domain_primary!='default'){
		if(count($channelParent->domainAll)>0){
			foreach($channelParent->domainAll as $domain){
				if($domain->domain->domain_primary=='default'){
					$domainPrimary=$domain->domain->domain; 
				}
			}
		}else{
			$domainPrimary=$channelParent->domainJoinPrimary->domain->domain; 
		}
	}else{
		$domainPrimary=$channelParent->domainJoinPrimary->domain->domain; 
	}
	$channelColor=(!empty($channelParent->channelAttributeColor->channel_attribute_value)) ? json_decode($channelParent->channelAttributeColor->channel_attribute_value) : false; 
?>
@else
	<?
	if($channel->domainJoinPrimary->domain->domain_primary!='default'){
		if(count($channel->domainAll)>0){
			foreach($channel->domainAll as $domain){
				if($domain->domain->domain_primary=='default'){
					$domainPrimary=$domain->domain->domain; 
				}
			}
		}else{
			$domainPrimary=$channel->domainJoinPrimary->domain->domain; 
		}
	}else{
		$domainPrimary=$channel->domainJoinPrimary->domain->domain; 
	}
	$channelColor=(!empty($channel->channelAttributeColor->channel_attribute_value)) ? json_decode($channel->channelAttributeColor->channel_attribute_value) : false; 
?>
@endif
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <title>{!!$message_title!!}</title>
      
      <style type="text/css">
         /* Client-specific Styles */
         #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
         /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
         .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
         .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing.  */
         #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
         img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
         a img {border:none;}
         .image_fix {display:block;}
         p {margin: 0px 0px !important;}
         table td {border-collapse: collapse;}
         table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
         a {color: #33b9ff;text-decoration: none;text-decoration:none!important;}
         /*STYLES*/
         table[class=full] { width: 100%; clear: both; }
         /*IPAD STYLES*/
         @media only screen and (max-width: 640px) {
         a[href^="tel"], a[href^="sms"] {
         text-decoration: none;
         color: #33b9ff; /* or whatever your want */
         pointer-events: none;
         cursor: default;
         }
         .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
         text-decoration: default;
         color: #33b9ff !important;
         pointer-events: auto;
         cursor: default;
         }
         table[class=devicewidth] {width: 100%!important;text-align:center!important;}
         table[class=devicewidthinner] {width: 420px!important;text-align:center!important;}
         img[class=banner] {width: 440px!important;height:220px!important;}
         img[class=colimg2] {width: 440px!important;height:220px!important;}
         
         
         }
         /*IPHONE STYLES*/
         @media only screen and (max-width: 480px) {
         a[href^="tel"], a[href^="sms"] {
         text-decoration: none;
         color: #ffffff; /* or whatever your want */
         pointer-events: none;
         cursor: default;
         }
         .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
         text-decoration: default;
         color: #ffffff !important; 
         pointer-events: auto;
         cursor: default;
         }
         table[class=devicewidth] {width: 100%!important;text-align:center!important;}
         table[class=devicewidthinner] {width: 260px!important;text-align:center!important;}
         img[class=banner] {width: 280px!important;height:140px!important;}
         img[class=colimg2] {width: 280px!important;height:140px!important;}
         td[class="padding-top15"]{padding-top:15px!important;}
         }
      </style>
   </head>
   <body style="width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; font-family: Helvetica Neue, Helvetica, Arial, Lucida Grande, sans-serif; line-height:25px; font-size:14px; ">
<!-- Start of preheader -->
<div style="min-width:600px; ">
	<table width="100%" bgcolor="#2a2a2a" cellpadding="0" cellspacing="0" border="0" style="margin: 0; padding: 0; width: 100% !important; line-height: 100% !important;" st-sortable="preheader" >
	   <tbody>
		  <tr>
			 <td>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
				   <tbody>
					  <tr>
						 <td width="100%">
							<table width="90%" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
							   <tbody>
								  <!-- Spacing -->
								  <tr>
									 <td width="100%" height="10"></td>
								  </tr>
								  <!-- Spacing -->
								  <tr>
									 <td>
										<table width="48%" align="left" border="0" cellpadding="0" cellspacing="0">
										   <tbody>
											  <tr>
												 <td align="left" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 13px;color: #fff; padding-left:5px; " st-content="viewonline">
												 ☎ Hotline: @if(!empty($channelParent->channel_name)) <a href="tel:{!!$channelParent->joinPhone[0]->phone->phone_number!!}">{!!$channelParent->joinPhone[0]->phone->phone_number!!}</a> @else <a href="tel:{!!$channel->joinPhone[0]->phone->phone_number!!}">{!!$channel->joinPhone[0]->phone->phone_number!!}</a> @endif
												 </td>
											  </tr>
										   </tbody>
										</table>
										<table width="48%" align="right" border="0" cellpadding="0" cellspacing="0">
										   <tbody>
											  <tr>
												 <td align="right" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 13px;color: #fff; padding-right:5px; " st-content="forward">
													<a href="{{route('channel.home',$channel->domainJoinPrimary->domain->domain)}}" style="text-decoration: none; color: #ffffff">Vào quản lý dịch vụ </a> 
												 </td>
											  </tr>
										   </tbody>
										</table>
									 </td>
								  </tr>
								  <!-- Spacing -->
								  <tr>
									 <td width="100%" height="10"></td>
								  </tr>
								  <!-- Spacing -->
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>
			 </td>
		  </tr>
	   </tbody>
	</table>
	<!-- End of preheader -->    
		<div style="width: 100%;  padding: 20px 0px;; background:@if(!empty($channelColor->channelMenu)) {{$channelColor->channelMenu}}; @else #f6f6f6; @endif ">
			<div style="display: block !important; max-width: 800px !important; margin: 0 auto !important; clear: both !important;">
				<table width="100%">
					@if(!empty($channelParent->channel_name))
						<tr>
							<td style="text-align:center; background:url( @if(!empty($channelParent->channelAttributeLogo->media->media_url_xs))http:{{$channelParent->channelAttributeLogo->media->media_url_xs}}@else{{asset('assets/img/logo-default.jpg')}}@endif ) no-repeat center center; height:55px; "></td>
						</tr>
					@else
						<tr>
							<td style="text-align:center; background:url( @if(!empty($channel->channelAttributeLogo->media->media_url_xs))http:{{$channel->channelAttributeLogo->media->media_url_xs}}@else{{asset('assets/img/logo-default.jpg')}}@endif ) no-repeat center center; height:55px; "></td>
						</tr>
					@endif
				</table>
			</div>
		</div>
	<!-- Start of header -->
	<table style="background-color: #f6f6f6; width: 100%;">
		<tr>
			<td></td>
			<td style="display: block !important; max-width: 800px !important; margin: 0 auto !important; clear: both !important;" width="100%">
				<div style="width:90%;  margin: 0 auto; display: block;">
					<table style="background: #fff; border: 1px solid #e9e9e9; border-radius: 3px; margin-bottom:40px; margin-top:40px; " width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td style="text-align:center; padding:20px;">
								<table width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td style="padding:0px; ">
											<h1>{!!$message_title!!}</h1>
										</td>
									</tr>
									<tr>
										<td style="padding: 0px;">
											<table style="margin: 10px auto; text-align: left; width: 100%;" width="100%">
												<tr>
													<td style="padding: 5px 0;">
														<b>Kính gửi:</b> {!!$channel->channel_name!!}<br>
														<b>Địa chỉ:</b>
														@if(!empty($channel->joinAddress[0]->address->address))
															{{$channel->joinAddress[0]->address->address}} 
															@if(!empty($channel->joinAddress[0]->address->joinWard->ward->id)) - {!!$channel->joinAddress[0]->address->joinWard->ward->ward_name!!}@endif
															@if(!empty($channel->joinAddress[0]->address->joinDistrict->district->id)) - {!!$channel->joinAddress[0]->address->joinDistrict->district->district_name!!}@endif
															@if(!empty($channel->joinAddress[0]->address->joinSubRegion->subregion->id)) - {!!$channel->joinAddress[0]->address->joinSubRegion->subregion->subregions_name!!}@endif
															@if(!empty($channel->joinAddress[0]->address->joinRegion->region->id)) - {!!$channel->joinAddress[0]->address->joinRegion->region->country!!}@endif
														@else
															<small><span style="font-style:italic; ">Chưa cập nhật địa chỉ</span></small>
														@endif
														<br> 
														<b>Điện thoại:</b> 
														@if(!empty($channel->joinPhone[0]->phone->phone_number)){!!$channel->joinPhone[0]->phone->phone_number!!}
														@else 
															<small><span style="font-style:italic; ">Chưa cập nhật số điện thoại</span></small>
														@endif<br> 
														<b>Email:</b> 
														@if(!empty($channel->joinEmail[0]->email->email_address)){!!$channel->joinEmail[0]->email->email_address!!}
														@else 
															<small><span style="font-style:italic; ">Chưa cập nhật số điện thoại</span></small>
														@endif<br> 
													</td>
												</tr>
												<tr>
													<td style="padding: 5px 0;">
														<strong><i>Danh sách các dịch vụ sắp hết hạn</i></strong>
													</td>
												</tr>
												<tr>
													<td style="padding: 5px 0;">
														<table style="width: 100%;" cellpadding="0" cellspacing="0" width="100%">
															<thead>
																<tr>
																	<td style="border-top: #eee 1px solid; padding: 5px 0;"><strong>Mã DV</strong></td>
																	<td style="border-top: #eee 1px solid; padding: 5px 0;"><strong>Tên dịch vụ</strong></td>
																	<td style="border-top: #eee 1px solid; padding: 5px 0; text-align:center;"><strong>Ngày đăng ký</strong></td>
																	<td style="border-top: #eee 1px solid; padding: 5px 0; text-align:center; background:#dadada; "><strong>Ngày hết hạn</strong></td>
																	<td style="border-top: #eee 1px solid; padding: 5px 0; text-align:right;"><strong>Số tiền/ năm</strong></td>
																</tr>
															</thead>
															<tbody>
																<?
																	$totalPrice=0;
																?>
																@foreach($listService as $service)
																	<tr>
																		<td style="border-top: #eee 1px solid; padding: 5px 0;">@if($service['service_type']=='channel')CN-{{$channel->id}}-{{$service['service_id']}}@elseif($service['service_type']=='domain')DM-{{$channel->id}}-{{$service['service_id']}}@elseif($service['service_type']=='hosting')HT-{{$channel->id}}-{{$service['service_id']}}@elseif($service['service_type']=='cloud')CL-{{$channel->id}}-{{$service['service_id']}}@elseif($service['service_type']=='mail_server')EM-{{$channel->id}}-{{$service['service_id']}}@endif</td>
																		<td style="border-top: #eee 1px solid; padding: 5px 0;"><small>{!!$service['service_attribute']->name!!} - {!!$service['service_name']!!}</small></td>
																		<td style="border-top: #eee 1px solid; padding: 5px 0; text-align:center;">{!!Site::Date($service['service_date_begin'])!!}</td>
																		<td style="border-top: #eee 1px solid; padding: 5px 0; text-align:center; background:#dadada; ">{!!Site::Date($service['service_date_end'])!!}</td>
																		<td style="border-top: #eee 1px solid; padding: 5px 0; text-align:right;">
																		@if($service['service_type']=='channel')
																		{!!Site::price($service['service_attribute']->price_re_order*12)!!}
																		@else
																			{!!Site::price($service['service_attribute']->price_re_order)!!}
																		@endif
																		<sup>VND</sup>/
																		@if($service['service_type']=='channel' || $service['service_type']=='hosting' )
																			1 năm
																		@else 
																			{!!$service['service_attribute']->order_unit_month!!} {!!$service['service_attribute']->per!!}
																		@endif
																		</td>
																	</tr>
																	<?
																		if($service['service_type']=='channel' || $service['service_type']=='hosting'){
																			$totalPrice+=$service['service_attribute']->price_re_order*12;
																		}else{
																			$totalPrice+=$service['service_attribute']->price_re_order;
																		}
																	?>
																@endforeach
																<tr style="border-top: #eee 1px solid;">
																	<td class="thick-line"></td>
																	<td class="thick-line"></td>
																	<td class="thick-line"></td>
																	<td style="text-align:center; "><strong>Tổng cộng</strong></td>
																	<td style="text-align:right; font-weight:bold;">{!!Site::price($totalPrice)!!}<sup>VND</sup></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</table>
											<div style="padding:10px 0px; text-align:center">
												<a href="{{route('channel.home',$domainPrimary)}}" style="padding:10px;background:@if(!empty($channelColor->channelTitle)){{$channelColor->channelTitle}}; @else #d41b29; @endif color:@if(!empty($channelColor->channelTitleText)){{$channelColor->channelTitleText}}; @else #fff; @endif">Click vào đây để quản lý và thanh toán trực tuyến</a>
											</div>
											<div style="text-align:left; ">
												<b>THANH TOÁN TRỰC TUYẾN</b><br>
												- Quý khách có thể thanh toán trực tuyến thông qua tài khoản Internet Banking các Ngân hàng bằng cách truy cập vào địa chỉ <a href="{{route('channel.home',$domainPrimary)}}">{{route('channel.home',$domainPrimary)}}</a> sau đó đăng nhập vào <b>Bảng điều khiển</b> để quản lý và gia hạn các dịch vụ.<br> 
												- Nếu quý khách chưa có thông tin tài khoản quản lý trang này, vui lòng liên hệ với chúng tôi bằng cách gửi nội dung tới ✉ Email @if(!empty($channelParent->channel_name) && !empty($channelParent->joinEmail[0]->email->email_address)) <a href="mailto:{!!$channelParent->joinEmail[0]->email->email_address!!}" target="_blank">{!!$channelParent->joinEmail[0]->email->email_address!!}</a> @elseif(!empty($channel->joinEmail[0]->email->email_address)) <a href="mailto:{!!$channel->joinEmail[0]->email->email_address!!}" target="_blank">{!!$channel->joinEmail[0]->email->email_address!!}</a> @endif ☎ Hotline: @if(!empty($channelParent->channel_name) && !empty($channelParent->joinPhone[0]->phone->phone_number)) <a href="tel:{!!$channelParent->joinPhone[0]->phone->phone_number!!}">{!!$channelParent->joinPhone[0]->phone->phone_number!!}</a> @elseif(!empty($channel->joinPhone[0]->phone->phone_number)) <a href="tel:{!!$channel->joinPhone[0]->phone->phone_number!!}">{!!$channel->joinPhone[0]->phone->phone_number!!}</a> @endif để nhận thông tin quản lý. <br>
												<b>THANH TOÁN CHUYỂN KHOẢN</b><br>
												- Thanh toán chuyển khoản theo thông tin thanh toán bên dưới.<br>
												- Ở phần nội dung thanh toán, quý khách vui lòng ghi rõ <span style="color: #ff0000;">Mã dịch vụ + Số điện thoại</span>.<br> 
												- Thanh toán trước 10 ngày hết hạn để đảm bảo dịch vụ.</i> 
												<p style="text-align:center; "><strong>Cảm ơn quý khách đã quan tâm sử dụng dịch vụ tại @if(!empty($channelParent->channel_name)) {!!$channelParent->channel_name!!}! @else {!!$channel->channel_name!!}! @endif </strong></p>
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
			</td>
			<td></td>
		</tr>
	</table>
	<!-- End of seperator -->   
	<!-- footer -->
	<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 0; padding: 0; width: 100% !important; line-height: 100% !important;color:@if(!empty($channelColor->channelTitle)){{$channelColor->channelTitleText}}; @else #fff; @endif background:@if(!empty($channelColor->channelTitle)){{$channelColor->channelTitle}}; @else #d41b29; @endif">
	   <tbody>
		  <tr>
			 <td>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
				   <tbody>
					  <tr>
						 <td width="100%">
							<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
							   <tbody>
								  <tr>
									 <td>
										@if(!empty($channelParent->channel_name))
										<table width="400" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth" style="margin-left:20px; ">
										   <tbody>
											  <!-- Spacing -->
											  <tr>
												 <td width="100%" height="20"></td>
											  </tr>
											  <!-- Spacing -->
											  <tr>
												 <td style="font-family: Helvetica, arial, sans-serif; font-size: 18px;text-align:left;">
													<b>@if(!empty($channelParent->companyJoin->company)){{$channelParent->companyJoin->company->company_name}}@else{!!$channelParent->channel_name!!}@endif</b>
												 </td>
											  </tr>
											  <!-- Spacing -->
											  <tr>
												 <td width="100%" height="10"></td>
											  </tr>
											  <!-- Spacing -->
											  <tr>
												 <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; text-align:left; line-height:20px; ">
													<p>
														✍ @if(!empty($channelParent->joinAddress[0]->address->address))
															{{$channelParent->joinAddress[0]->address->address}} 
															@if(!empty($channelParent->joinAddress[0]->address->joinWard->ward->id)) - {!!$channelParent->joinAddress[0]->address->joinWard->ward->ward_name!!}@endif
															@if(!empty($channelParent->joinAddress[0]->address->joinDistrict->district->id)) - {!!$channelParent->joinAddress[0]->address->joinDistrict->district->district_name!!}@endif
															@if(!empty($channelParent->joinAddress[0]->address->joinSubRegion->subregion->id)) - {!!$channelParent->joinAddress[0]->address->joinSubRegion->subregion->subregions_name!!}@endif
															@if(!empty($channelParent->joinAddress[0]->address->joinRegion->region->id)) - {!!$channelParent->joinAddress[0]->address->joinRegion->region->country!!}@endif
														@else
															<small><span style="font-style:italic; ">Chưa cập nhật địa chỉ</span></small>
														@endif
														<br>
														@if(!empty($channelParent->joinPhone[0]->phone->phone_number))
															☎ <a href="tel:{!!$channelParent->joinPhone[0]->phone->phone_number!!}" style="color:@if(!empty($channelColor->channelTitle)){{$channelColor->channelTitleText}}; @else #fff; @endif ">{!!$channelParent->joinPhone[0]->phone->phone_number!!}</a><br> 
														@else
															☎ <i>Chưa cập nhật số điện thoại</i><br> 
														@endif
														@if(!empty($channelParent->joinEmail[0]->email->email_address))
															✉ <a href="mailto:{!!$channelParent->joinEmail[0]->email->email_address!!}" style="color:@if(!empty($channelColor->channelTitle)){{$channelColor->channelTitleText}}; @else #fff; @endif ">{!!$channelParent->joinEmail[0]->email->email_address!!}</a><br> 
														@else
															✉ <i>Chưa cập nhật email</i><br> 
														@endif
														Website: <a href="{{route('channel.home',$domainPrimary)}}" style="color:@if(!empty($channelColor->channelTitle)){{$channelColor->channelTitleText}}; @else #fff; @endif ">{{route('channel.home',$domainPrimary)}}</a>
													</p>
												 </td>
											  </tr>
											  <!-- Spacing -->
											  <tr>
												 <td width="100%" height="10"></td>
											  </tr>
											  <!-- Spacing -->
										   </tbody>
										</table>
										@else
											<table width="400" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth" style="margin-left:20px; ">
											   <tbody>
												  <!-- Spacing -->
												  <tr>
													 <td width="100%" height="20"></td>
												  </tr>
												  <!-- Spacing -->
												  <tr>
													 <td style="font-family: Helvetica, arial, sans-serif; font-size: 18px; text-align:left;">
														<b>{!!$channel->channel_name!!}</b>
													 </td>
												  </tr>
												  <!-- Spacing -->
												  <tr>
													 <td width="100%" height="10"></td>
												  </tr>
												  <!-- Spacing -->
												  <tr>
													 <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; text-align:left; line-height:20px; ">
														<p>
														✍ @if(!empty($channel->joinAddress[0]->address->address))
															{{$channel->joinAddress[0]->address->address}} 
															@if(!empty($channel->joinAddress[0]->address->joinWard->ward->id)) - {!!$channel->joinAddress[0]->address->joinWard->ward->ward_name!!}@endif
															@if(!empty($channel->joinAddress[0]->address->joinDistrict->district->id)) - {!!$channel->joinAddress[0]->address->joinDistrict->district->district_name!!}@endif
															@if(!empty($channel->joinAddress[0]->address->joinSubRegion->subregion->id)) - {!!$channel->joinAddress[0]->address->joinSubRegion->subregion->subregions_name!!}@endif
															@if(!empty($channel->joinAddress[0]->address->joinRegion->region->id)) - {!!$channel->joinAddress[0]->address->joinRegion->region->country!!}@endif
														@else
															<small><span style="font-style:italic; ">Chưa cập nhật địa chỉ</span></small>
														@endif
														<br>
														@if(!empty($channel->joinPhone[0]->phone->phone_number))
														☎ <a href="tel:{!!$channel->joinPhone[0]->phone->phone_number!!}" style="color:@if(!empty($channelColor->channelTitle)){{$channelColor->channelTitleText}}; @else #fff; @endif ">{!!$channel->joinPhone[0]->phone->phone_number!!}</a><br> 
														@else
															☎ <i>Chưa cập nhật số điện thoại</i><br> 
														@endif
														@if(!empty($channel->joinEmail[0]->email->email_address))
														✉ <a href="mailto:{!!$channel->joinEmail[0]->email->email_address!!}" style="color:@if(!empty($channelColor->channelTitle)){{$channelColor->channelTitleText}}; @else #fff; @endif ">{!!$channel->joinEmail[0]->email->email_address!!}</a><br> 
														@else
															✉ <i>Chưa cập nhật email</i><br> 
														@endif
														Website: <a href="{{route('channel.home',$domainPrimary)}}" style="color:@if(!empty($channelColor->channelTitle)){{$channelColor->channelTitleText}}; @else #fff; @endif ">{{route('channel.home',$domainPrimary)}}</a>
													</p>
													 </td>
												  </tr>
												  <!-- Spacing -->
												  <tr>
													 <td width="100%" height="10"></td>
												  </tr>
												  <!-- Spacing -->
											   </tbody>
											</table>
										@endif
										<!-- end of left column -->
										<!-- start of right column -->
										<table width="300" align="right" border="0" cellpadding="0" cellspacing="0" class="devicewidth" style="margin-left:20px; ">
										   <tbody>
											  <!-- Spacing -->
											  <tr>
												 <td width="100%" height="20"></td>
											  </tr>
											  <!-- Spacing -->
											  <tr>
												 <td style="font-family: Helvetica, arial, sans-serif; font-size: 18px; text-align:left;">
												   <b>THÔNG TIN THANH TOÁN</b>
												 </td>
											  </tr>
											  <!-- Spacing -->
											  <tr>
												 <td width="100%" height="10"></td>
											  </tr>
											  <!-- Spacing -->
											  <tr>
												 <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; text-align:left; line-height:20px; ">
													Số TK: 0071000672017<br>
													Tên TK: Nguyễn Hùng Thanh<br>
													Ngân hàng: Vietcombank, HCM<br> 
													Số TK: 0721000627164<br>
													Tên TK: Công ty cổ phần Cung Cấp<br>
													Ngân hàng: Vietcombank, HCM<br>
												 </td>
											  </tr>
											  <!-- Spacing -->
											  <tr>
												 <td width="100%" height="20"></td>
											  </tr>
											  <!-- Spacing -->
										   </tbody>
										</table>
										<!-- end of right column -->
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>
			 </td>
		  </tr>
	   </tbody>
	</table>
	<!-- end of footer -->
	<!-- Start of Postfooter -->
	<table width="100%" bgcolor="#2a2a2a" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter" >
	   <tbody>
		  <tr>
			 <td>
				<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
				   <tbody>
					  <tr>
						 <td width="100%">
							<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
							   <tbody>
								  <!-- Spacing -->
								  <tr>
									 <td width="100%" height="20"></td>
								  </tr>
								  <!-- Spacing -->
								  <tr>
									 <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 13px;color: #ffffff" st-content="preheader">
										Email tự động gửi từ hệ thống, vui lòng không reply email này. 
									 </td>
								  </tr>
								  <!-- Spacing -->
								  <tr>
									 <td width="100%" height="20"></td>
								  </tr>
								  <!-- Spacing -->
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>
			 </td>
		  </tr>
	   </tbody>
	</table>
</div>
<!-- End of postfooter -->   
   </body>
   </html>