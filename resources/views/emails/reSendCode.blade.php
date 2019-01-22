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
?>
<p>Xin chào <strong>{!!$user->name!!}</strong></p>
<p>Bạn đã nhận được một yêu cầu kích hoạt tài khoản trên <strong><a href="{{route('channel.home',$domainPrimary)}}">{!!$channel->channel_name!!}</a></strong> vào lúc {!!$date_send!!}</p>
<p>Kích hoạt tài khoản bằng cách truy cập vào đường dẫn <a href="{{route('channel.user.active.code',array($domainPrimary,$confirmation_code))}}">{{route('channel.user.active.code',array($domainPrimary,$confirmation_code))}}</a></p>