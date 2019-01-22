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
	$getChannelUser=\App\Model\Channel::find($messageArray->channel->id); 
?>
<div style="@if(!empty($channelColor->channelTitle)) background:{{$channelColor->channelTitle}}; @else background:#337ab7; @endif padding:10px;">
	<div style="text-align:center;padding:10px;">
		<img src="@if(!empty($channel->channelAttributeLogo->media->media_url_xs))http:{{$channel->channelAttributeLogo->media->media_url_xs}}@else{{asset('assets/img/logo-default.jpg')}}@endif" style="height:60px;background: #fff;padding: 2px;border: solid 1px #dadada;">
	</div>
	<div style="background:#ffffff;border:solid 1px #005c73;padding:10px;">
		<p>Xin chào <strong>{!!$user->name!!}</strong>,<br> 
		Chúc mừng bạn đã tạo thành công <strong>{!!$getChannelUser->channel_name!!}</strong></p> 
		<p>Địa chỉ website của bạn: <a href="{{route('channel.home',$getChannelUser->domainJoinPrimary->domain->domain)}}">{!!$getChannelUser->domainJoinPrimary->domain->domain!!}</a></p>
		<strong>Vào lúc:</strong> {!!$msgValue->created_at!!}<br> 
		Truy cập vào địa chỉ website của bạn và đăng nhập để quản lý trang web của bạn. <br>
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
		<strong>✉ Email: </strong>{!!$channel->joinEmail[0]->email->email_address!!}<br>
		<strong>☎ Điện thoại: </strong>{!!$channel->joinPhone[0]->phone->phone_number!!}<br>
		<strong>Website:</strong> <a href="{{route('channel.home',$domainPrimary)}}" style="@if(!empty($channelColor->channelTitle)) color:{{$channelColor->channelTitleText}}; @else color:#fff; @endif">{{$domainPrimary}}</a><br>
	</div>
</div>