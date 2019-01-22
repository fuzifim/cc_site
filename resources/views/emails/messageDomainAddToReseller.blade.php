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
		<p>Xin chào <strong>{!!$channel->channel_name!!}</strong>, </p>
		<p>Có một đơn đăng ký tên miền đã thanh toán thành công tại {!!$messageArray->channel->channel_name!!}</p>
		<p><strong>Vào lúc:</strong> {!!$msgValue->created_at!!}</p>
		<p>Địa chỉ tên miền đăng ký: {!!$messageArray->domain->domain!!}</p>
		<p>Địa chỉ kênh đăng ký: {!!$messageArray->channel->domainJoinPrimary->domain->domain!!}</p>
	</div>
	<div style="padding:10px;margin-top:10px;font-size:12px; @if(!empty($channelColor->channelTitle)) color:{{$channelColor->channelTitleText}}; @else color:#fff; @endif">
		<p><strong>{!!$channel->channel_name!!}</strong></p>
		<strong>✍ Địa chỉ:</strong> {!!$channel->companyJoin->company->company_address!!}<br>
		<strong>✉ Email: </strong>{!!$channel->emailJoin->email->email_address!!}<br>
		<strong>☎ Điện thoại: </strong>{!!$channel->phoneJoin->phone->phone_number!!}<br>
		<strong>Website:</strong> <a href="{{route('channel.home',$domainPrimary)}}" style="@if(!empty($channelColor->channelTitle)) color:{{$channelColor->channelTitleText}}; @else color:#fff; @endif">{{$domainPrimary}}</a><br>
	</div>
</div>