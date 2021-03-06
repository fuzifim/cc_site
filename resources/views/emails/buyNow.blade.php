<style>

</style>
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
	$messageArray=json_decode($message_body); 
?>
<div style="@if(!empty($channelColor->channelTitle)) background:{{$channelColor->channelTitle}}; @else background:#337ab7; @endif padding:10px;">
	<div style="text-align:center;padding:10px;">
		<img src="@if(!empty($channel->channelAttributeLogo->media->media_url_xs))http:{{$channel->channelAttributeLogo->media->media_url_xs}}@else{{asset('assets/img/logo-default.jpg')}}@endif" style="height:60px;background: #fff;padding: 2px;border: solid 1px #dadada;">
	</div>
	<div style="background:#ffffff;border:solid 1px #005c73;padding:10px;">
		<p>Xin chào <strong>{!!$channel->channel_name!!}</strong>, Bạn có một đơn đặt hàng từ <strong>{!!$messageArray->userName!!}</strong></p>
		@if(!empty($messageArray->userEmail))<strong>Email:</strong> {!!$messageArray->userEmail!!}<br> @endif
		@if(!empty($messageArray->userPhone))<strong>Điện thoại:</strong> {!!$messageArray->userPhone!!}<br> @endif
		@if(!empty($messageArray->userAddress))<strong>Địa chỉ:</strong> {!!$messageArray->userAddress!!}<br> @endif
		<strong>Vào lúc:</strong> {!!$msgValue->created_at!!}<br> 
		<div style="border:solid 1px #dadada;margin-top:10px;padding:10px;">
			<p><strong>Chi tiết đơn hàng</strong></p>
			<hr>
			<p><strong>Tên hàng: </strong> <a href="@if(!empty($messageArray->link)){!!$messageArray->link!!}@endif"><strong>{!!$messageArray->itemName!!}</strong></a> 
			@if(!empty($messageArray->itemPrice))
				<small><i>Giá: {!!Site::price($messageArray->itemPrice)!!}</i></small>
				@if(!empty($channel->joinAddress[0]->address->joinRegion->region->currency))
					<sup>{!!$channel->joinAddress[0]->address->joinRegion->region->currency!!}</sup>
				@endif
			@endif
			</p>
			<p><strong>Số lượng:</strong> {{$messageArray->buyQuanlity}}</p>
			@if(!empty($messageArray->content))
			<p><strong>Nội dung yêu cầu: </strong>{!!$messageArray->content!!}</p>
			@endif
		</div>
		<p><small>Bạn có thể liên hệ với <strong>{!!$messageArray->userName!!}</strong> bằng cách trả lời thư này hoặc liên hệ trực tiếp theo các thông tin trên. </small></p>
	</div>
	<div style="padding:10px;margin-top:10px;font-size:12px; @if(!empty($channelColor->channelTitle)) color:{{$channelColor->channelTitleText}}; @else color:#fff; @endif">
		<p><strong>{!!$channel->channel_name!!}</strong></p>
		<strong>✍ Địa chỉ:</strong> @if(!empty($channel->joinAddress[0]->address->address))
			{{$channel->joinAddress[0]->address->address}} 
			@if(!empty($channel->joinAddress[0]->address->joinWard->ward->id)) - {!!$channel->joinAddress[0]->address->joinWard->ward->ward_name!!}@endif
			@if(!empty($channel->joinAddress[0]->address->joinDistrict->district->id)) - {!!$channel->joinAddress[0]->address->joinDistrict->district->district_name!!}@endif
			@if(!empty($channel->joinAddress[0]->address->joinSubRegion->subregion->id)) - {!!$channel->joinAddress[0]->address->joinSubRegion->subregion->subregions_name!!}@endif
			@if(!empty($channel->joinAddress[0]->address->joinRegion->region->id)) - {!!$channel->joinAddress[0]->address->joinRegion->region->country!!}@endif
		@else
			<small><span style="font-style:italic; ">Chưa cập nhật địa chỉ</span></small>
		@endif<br>
		@if(!empty($channel->joinEmail[0]->email->email_address))<strong>✉ Email: </strong>{!!$channel->joinEmail[0]->email->email_address!!}<br>@endif
		@if(!empty($channel->joinPhone[0]->phone->phone_number))<strong>☎ Điện thoại: </strong>{!!$channel->joinPhone[0]->phone->phone_number!!}<br>@endif
		<strong>Website:</strong> <a href="{{route('channel.home',$domainPrimary)}}" style="@if(!empty($channelColor->channelTitle)) color:{{$channelColor->channelTitleText}}; @else color:#fff; @endif">{{$domainPrimary}}</a><br>
	</div>
</div>
@if(!empty($channel->channelParent->channel_name))
	<?
		if($channel->channelParent->domainJoinPrimary->domain->domain_primary!='default'){
			foreach($channel->channelParent->domainAll as $domain){
				if($domain->domain->domain_primary=='default'){
					$domainPrimaryParent=$domain->domain->domain; 
				}
			}
		}else{
			$domainPrimaryParent=$channel->channelParent->domainJoinPrimary->domain->domain; 
		}
	?>
	<div style="font-size:12px;background:#dadada;color:#666666;padding:10px;margin-top:5px;">
		Thư này được gửi từ <a href="{{route('channel.home',$domainPrimaryParent)}}" style="color:#666666;"><i class="glyphicon glyphicon-ok"></i> {!!$channel->channelParent->channel_name!!}</a>
	</div>
@endif