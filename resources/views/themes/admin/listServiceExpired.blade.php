<?
	$channel['theme']->setTitle('Dịch vụ sắp hết hạn');
?>
@include('themes.admin.inc.header')
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				@include('themes.admin.partial.menuManageService')
			</div>
			<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12" id="panelContent">
				<div class="row">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Danh sách dịch vụ ({{$totalService}})</h3>
							<span>Tổng tiền: {{Site::price($totalPrice)}}<sup>{{$channel['info']->channelJoinRegion->region->currency_code}}</sup></span>
						</div>
						<ul class="list-group listDomain">
							<?
								$count=0;
							?>
							@foreach($resultList as $listService)
								@foreach($listService as $service)
								<?
									if($service['channel']->domainJoinPrimary->domain->domain_primary!='default'){
										if(count($service['channel']->domainAll)>0){
											foreach($service['channel']->domainAll as $domain){
												if($domain->domain->domain_primary=='default'){
													$domainPrimary=$domain->domain->domain; 
												}
											}
										}else{
											$domainPrimary=$service['channel']->domainJoinPrimary->domain->domain; 
										}
									}else{
										$domainPrimary=$service['channel']->domainJoinPrimary->domain->domain; 
									}
								?>
								<li class="list-group-item @if($count % 2 == 0) list-group-item-default @else  list-group-item-info @endif">
									<h4 class="list-group-item-heading"><a href="{{route('channel.home',$domainPrimary)}}">{!!$service['channel']->channel_name!!}</a></h4>
									<div class="list-group-item-text">
										<strong>{{$service['service_attribute']->name}} - {{$service['service_name']}}</strong>
										<p>Ngày hết hạn: {!!Site::Date($service['service_date_end'])!!}</p>
										<p class="text-danger"><strong>Số tiền: {!!Site::price($service['service_attribute']->price_re_order)!!}<sup>{{$channel['info']->channelJoinRegion->region->currency_code}}</sup>/{!!$service['service_attribute']->order_unit_month!!} {!!$service['service_attribute']->per!!}</strong></p>
										<p class="text-warning">Số lần gửi thông báo: {{$service['channel']->notify}}</p>
									</div>
								</li>
								<?
									++$count; 
								?>
								@endforeach
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('themes.admin.inc.footer')