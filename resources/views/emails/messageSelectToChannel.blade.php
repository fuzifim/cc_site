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
?>
<div style="@if(!empty($channelColor->channelTitle)) background:{{$channelColor->channelTitle}}; @else background:#337ab7; @endif padding:10px;">
	<div style="text-align:center;padding:10px;">
		<img src="@if(!empty($channel->channelAttributeLogo->media->media_url_xs))http:{{$channel->channelAttributeLogo->media->media_url_xs}}@else{{asset('assets/img/logo-default.jpg')}}@endif" style="height:60px;background: #fff;padding: 2px;border: solid 1px #dadada;">
	</div>
	<div style="background:#ffffff;border:solid 1px #005c73;padding:10px;">
		<p>Xin chào <strong>{!!$channel->channel_name!!}</strong>, Bạn có một yêu cầu liên hệ từ <strong>{!!$user->name!!}</strong></p>
		@if(!empty($user->email))<strong>Email:</strong> {!!$user->email!!}<br> @endif
		@if(!empty($user->phone))<strong>Điện thoại:</strong> {!!$user->phone!!}<br> @endif
		@if(!empty($user->getSubRegion->subRegion->subregions_name) && !empty($user->getRegion->region->country))<strong>Địa chỉ:</strong> {!!$user->getSubRegion->subRegion->subregions_name!!}, {!!$user->getRegion->region->country!!}<br> @endif
		<strong>Vào lúc:</strong> {!!$msgValue->created_at!!}<br> 
		<div style="border:solid 1px #dadada;margin-top:10px;padding:10px;">
			<p><strong>Các mục đã chọn</strong></p>
			<?
				$messageArray=json_decode($message_body); 
			?>
			@foreach($messageArray->cartContent as $listSelect)
			<div><a href="{!!$listSelect->attributes->link!!}"><strong>{!!$listSelect->name!!}</strong></a> <small><i>{!!Site::price($listSelect->price)!!}<sup>{!!$listSelect->attributes->currency!!}</sup></i></small></div>
			@endforeach
			@if(!empty($messageArray->cartMessage))
				<hr>
				<p>{!!$messageArray->cartMessage!!}</p>
			@endif
		</div>
		<p><small>Bạn có thể liên hệ với <strong>{!!$user->name!!}</strong> bằng cách trả lời thư này hoặc liên hệ trực tiếp theo các thông tin trên. </small></p>
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