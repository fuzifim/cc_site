<?
	$channel['theme']->setTitle($channelInfo->channel_name);
	$channel['theme']->setKeywords($channelInfo->channel_name);
	$channel['theme']->setDescription(str_limit(strip_tags(html_entity_decode($channelInfo->channel_description),""), $limit = 250, $end='...')); 
	if(!empty($channelInfo->channelAttributeBanner[0]->media->media_name)){$channel['theme']->setImage(config('app.link_media').$channelInfo->channelAttributeBanner[0]->media->media_path.'thumb/'.$channelInfo->channelAttributeBanner[0]->media->media_name);}
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel section-content channelView">
		@if($channelInfo->channel_status!='delete')
			@if(!empty($channelInfo->domainJoinPrimary->domain->domain))
			<?
				if($channelInfo->domainJoinPrimary->domain->domain_primary!='default'){
					if(count($channelInfo->domainAll)>0){
						foreach($channelInfo->domainAll as $domain){
							if($domain->domain->domain_primary=='default'){
								$domainPrimary=$domain->domain->domain; 
							}
						}
					}else{
						$domainPrimary=$channelInfo->domainJoinPrimary->domain->domain; 
					}
				}else{
					$domainPrimary=$channelInfo->domainJoinPrimary->domain->domain; 
				}
				$channelColor=(!empty($channelInfo->channelAttributeColor->channel_attribute_value)) ? json_decode($channelInfo->channelAttributeColor->channel_attribute_value) : false; 
			?>
				<div class="row-pad-5">
					<div class="col-lg-8 col-md-8 col-sm-12 col-sm-12">
						<div class="form-group">
							@if(!empty($channelInfo->channelAttributeBanner[0]->media->media_name))
								<img src="{{config('app.link_media').$channelInfo->channelAttributeBanner[0]->media->media_path.'thumb/'.$channelInfo->channelAttributeBanner[0]->media->media_name}}" class="img-responsive">
							@else
								<div style="text-align: center;padding: 60px 0px;border: solid 1px #dadada;background:#dadada;">
									<i class="preview glyphicon glyphicon-picture" style="font-size: 180px;color: #999;"></i>
								</div>
							@endif
						</div>
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="panel-title"><i class="glyphicon glyphicon-info-sign"></i> Thông tin</div>
							</div>
							<div class="panel-body">
								<div class="form-group">
									<h3 class="channelNameTitle nomargin text-center"><img src="@if(!empty($channelInfo->channelAttributeLogo->media->media_url_xs)){{$channelInfo->channelAttributeLogo->media->media_url_xs}} @else {{asset('assets/img/logo-default.jpg')}} @endif" alt="" style="max-height:22px;"> <a href="{{route('channel.home',$domainPrimary)}}">{!!$channelInfo->channel_name!!}</a></h3>
								</div>
								<div class="form-group">
									@if(isset($channelInfo->companyJoin->company))<div class="form-group"><h5 class="subtitle nomargin"><i class="glyphicon glyphicon-info-sign"></i><a href="@if(!empty($company->joinChannelParent->channel->id) && !empty($channelInfo->channelParent->id) && $company->joinChannelParent->channel->id==$channelInfo->id){{route('company.view.slug',array($channel['domainParentPrimary'],$channelInfo->companyJoin->company->id.'-'.Str::slug($channelInfo->companyJoin->company->company_name)))}}@else{{route('company.view.slug',array(config('app.url'),$channelInfo->companyJoin->company->id.'-'.Str::slug($channelInfo->companyJoin->company->company_name)))}}@endif"><strong> {{$channelInfo->companyJoin->company->company_name}}</strong></a></h5></div>@endif
									@if(count($channelInfo->joinAddress)>0)
										@foreach($channelInfo->joinAddress as $joinAddress)
											<p>
												<i class="glyphicon glyphicon-map-marker"></i> {{$joinAddress->address->address}} 
												@if(!empty($joinAddress->address->joinWard->ward->id)) - <a href="{{route('channel.slug',array($channel['domainPrimary'],str_replace('/VN/','',$joinAddress->address->joinWard->ward->SolrID)))}}">{!!$joinAddress->address->joinWard->ward->ward_name!!}</a>@endif
												@if(!empty($joinAddress->address->joinDistrict->district->id)) - <a href="{{route('channel.slug',array($channel['domainPrimary'],str_replace('/VN/','',$joinAddress->address->joinDistrict->district->SolrID)))}}">{!!$joinAddress->address->joinDistrict->district->district_name!!}</a>@endif
												@if(!empty($joinAddress->address->joinSubRegion->subregion->id)) - <a href="{{route('channel.slug',array($channel['domainPrimary'],str_replace('/VN/','',$joinAddress->address->joinSubRegion->subregion->SolrID)))}}">{!!$joinAddress->address->joinSubRegion->subregion->subregions_name!!}</a>@endif
												@if(!empty($joinAddress->address->joinRegion->region->id)) - <i class="flag flag-{{mb_strtolower($joinAddress->address->joinRegion->region->iso)}}"></i> {!!$joinAddress->address->joinRegion->region->country!!}@endif
											</p>
										@endforeach
									@endif
									@if(!empty($channelInfo->joinPhone[0]->phone->phone_number))
										@if(Auth::check())
											<p><i class="glyphicon glyphicon-earphone"></i> <a href="tel:{{$channelInfo->joinPhone[0]->phone->phone_number}}">{{$channelInfo->joinPhone[0]->phone->phone_number}}</a></p>
										@else 
											<p><span class="text-danger"><i class="glyphicon glyphicon-lock"></i> Đăng nhập để xem điện thoại</span></p>
										@endif
									@endif
									@if(!empty($channelInfo->joinEmail[0]->email->email_address))
										@if(Auth::check())
											<p><i class="glyphicon glyphicon-envelope"></i> <a href="mailto:{{$channelInfo->joinEmail[0]->email->email_address}}" target="_blank">{{$channelInfo->joinEmail[0]->email->email_address}}</a></p>
										@else 
											<p><span class="text-danger"><i class="glyphicon glyphicon-lock"></i> Đăng nhập để xem email</span></p>
										@endif
									@endif
									<p><i class="glyphicon glyphicon-globe"></i> <a href="{{route('channel.home',$domainPrimary)}}">{{$domainPrimary}}</a></p>
									<small><span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($channelInfo->channel_updated_at)!!}</span> <span class="post-view">{{$channelInfo->channel_view}} lượt xem</span></small>
									@if(count($channelInfo->role)>0)
										@foreach($channelInfo->role as $author)
											<small><span class="author"><i class="glyphicon glyphicon-user"></i> {!!\App\User::find($author->user_id)->name!!}</span></small>
										@endforeach
									@endif
									
									@if(count($channelInfo->fields)>0)
										@foreach($channelInfo->fields as $field)
											@if(!empty($channelInfo->joinAddress[0]->address->joinWard->ward->id))
												<i class="glyphicon glyphicon-folder-open"></i> <a href="{{route('channel.slug',array($channel['domainPrimary'],str_replace('/VN/','',$channelInfo->joinAddress[0]->address->joinWard->ward->SolrID.'/'.Str::slug($field->field->SolrID))))}}">{!!$field->field->name!!}</a>
											@elseif(!empty($channelInfo->joinAddress[0]->address->joinDistrict->district->id))
												<i class="glyphicon glyphicon-folder-open"></i> <a href="{{route('channel.slug',array($channel['domainPrimary'],str_replace('/VN/','',$channelInfo->joinAddress[0]->address->joinDistrict->district->SolrID.'/'.Str::slug($field->field->SolrID))))}}">{!!$field->field->name!!}</a>
											@elseif(!empty($channelInfo->joinAddress[0]->address->joinSubRegion->subregion->id))
												<i class="glyphicon glyphicon-folder-open"></i> <a href="{{route('channel.slug',array($channel['domainPrimary'],str_replace('/VN/','',$channelInfo->joinAddress[0]->address->joinSubRegion->subregion->SolrID.'/'.Str::slug($field->field->SolrID))))}}">{!!$field->field->name!!}</a>
											@endif
										@endforeach
									@endif
								</div>
								@if(!empty($channelInfo->channelAttributeLogo->media->media_name))
								<script type="application/ld+json">
									{
										"@context": "http://schema.org",
										"@type": "Organization",
										"url": "{{route('channel.home',$domainPrimary)}}",
										"logo": "{{config('app.link_media').$channelInfo->channelAttributeLogo->media->media_path.'xs/'.$channelInfo->channelAttributeLogo->media->media_name}}"
									}
								</script>
								@endif
								<script type="application/ld+json">
								{
								  "@context": "http://schema.org",
								  "@type": "LodgingBusiness",
								  @if(!empty($channelInfo->channelAttributeBanner[0]->media->media_name))"image": "{{config('app.link_media').$channelInfo->channelAttributeBanner[0]->media->media_path.'thumb/'.$channelInfo->channelAttributeBanner[0]->media->media_name}}", @endif
								  "@id": "{{route('channel.home',$domainPrimary)}}",
								  "name": "{!!$channelInfo->channel_name!!}",
								  "address": {
									"@type": "PostalAddress",
									"streetAddress": "@if(!empty($channelInfo->joinAddress[0]->address->address)){!!$channelInfo->joinAddress[0]->address->address!!}@endif",
									"addressLocality": "@if(!empty($channelInfo->joinAddress[0]->address->joinDistrict->district->id)){!!$channelInfo->joinAddress[0]->address->joinDistrict->district->district_name!!}@endif",
									"addressRegion": "@if(!empty($channelInfo->joinAddress[0]->address->joinSubRegion->subregion->id)){!!$channelInfo->joinAddress[0]->address->joinSubRegion->subregion->subregions_name!!}@endif",
									"addressCountry": "@if(!empty($channelInfo->joinAddress[0]->address->joinRegion->region->id)){!!$channelInfo->joinAddress[0]->address->joinRegion->region->country!!}@endif"
								  },
								  @if(!empty($channelInfo->joinAddress[0]->address->joinSubRegion->subregion->id))
								  "geo": {
									"@type": "GeoCoordinates",
									"latitude": {{$channelInfo->joinAddress[0]->address->joinSubRegion->subregion->lat}},
									"longitude": {{$channelInfo->joinAddress[0]->address->joinSubRegion->subregion->lng}}
								  },
								  @endif
								  "url": "{{route('channel.home',$domainPrimary)}}", 
								  @if(!empty($channelInfo->joinPhone[0]->phone->id))
								  "telephone": "{{$channelInfo->joinPhone[0]->phone->phone_number}}",
								  @endif
								  "openingHoursSpecification": [
									{
									  "@type": "OpeningHoursSpecification",
									  "dayOfWeek": [
										"Monday",
										"Tuesday"
									  ],
									  "opens": "11:30",
									  "closes": "22:00"
									},
									{
									  "@type": "OpeningHoursSpecification",
									  "dayOfWeek": [
										"Wednesday",
										"Thursday",
										"Friday"
									  ],
									  "opens": "11:30",
									  "closes": "23:00"
									},
									{
									  "@type": "OpeningHoursSpecification",
									  "dayOfWeek": "Saturday",
									  "opens": "16:00",
									  "closes": "23:00"
									},
									{
									  "@type": "OpeningHoursSpecification",
									  "dayOfWeek": "Sunday",
									  "opens": "16:00",
									  "closes": "22:00"
									}
								  ],
								  "acceptsReservations": "True"
								}
								</script>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-sm-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="panel-title"><i class="glyphicon glyphicon-list-alt"></i> Bài viết</div>
							</div>
							<div class="panel-body">
								<?
									$getPostJoinChannel=\App\Model\Posts_join_channel::where('posts_join_channel.channel_id','=',$channelInfo->id)
									->join('posts','posts.id','=','posts_join_channel.posts_id')
									->where('posts.posts_status','=','active')
									->orderBy('posts.posts_updated_at','desc')
									->get(); 
								?>
								@if(count($getPostJoinChannel)>0)
									@foreach($getPostJoinChannel->take(4) as $post)
										@if(!empty($post->getPost->gallery[0]->media->media_url))
											<div class="row no-gutter">
												<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
													<a class="image" href="{{route('channel.slug',array($domainPrimary,$post->getPost->getSlug->slug_value))}}">
														@if($post->getPost->gallery[0]->media->media_storage=='youtube')
															<img src="//img.youtube.com/vi/{{$post->getPost->gallery[0]->media->media_name}}/default.jpg" class="img-responsive img-thumbnail" alt="" title="" >
														@elseif($post->getPost->gallery[0]->media->media_storage=='video')
														<div class="groupThumb" style="position:relative;">
															<span class="btnPlayVideoClickSmall"><i class="glyphicon glyphicon-play"></i></span>
															<img itemprop="image" class="img-responsive imgThumb lazy" alt="{{$post->getPost->posts_title}}" src="{{config('app.link_media').$post->getPost->gallery[0]->media->media_path.'xs/'.$post->getPost->gallery[0]->media->media_id_random.'.png'}}"/>
														</div>
														@elseif($post->getPost->gallery[0]->media->media_storage=='files')
														<img src="{!!asset('assets/img/file.jpg')!!}" class="img-responsive imgThumb lazy" alt="" title="" >
														@else
															<img src="{{config('app.link_media').$post->getPost->gallery[0]->media->media_path.'xs/'.$post->getPost->gallery[0]->media->media_name}}" class="img-responsive img-thumbnail" alt="" title="" >
														@endif
													</a>
												</div>
												<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
													<h5 class="postTitle"><a class="title" href="{{route('channel.slug',array($domainPrimary,$post->getPost->getSlug->slug_value))}}">{!!$post->getPost->posts_title!!}</a></h5>
													<small><span>{{$post->getPost->posts_view}} lượt xem</span></small>
												</div>
											</div>
										@endif
									@endforeach
								@else 
									<div class="alert alert-info">
										<strong>{!!$channelInfo->channel_name!!}</strong> chưa có bài viết nào trên {!!$channel['info']->channel_name!!}. 
									</div>
								@endif
							</div>
						</div>
						@if(!empty($channelInfo->joinAddress[0]->address->joinWard->ward->id))
						<div class="panel panel-default">
							<?
								$getAllWard=\App\Model\Region_ward::where('region_district_id','=',$channelInfo->joinAddress[0]->address->joinWard->ward->region_district_id)->get(); 
							?>
							<div class="list-group" id="list">
								@foreach($getAllWard as $ward)
									<a href="{{route('channel.slug',array($channel['domain']->domain,str_replace('/VN/','',$ward->SolrID)))}}" class="list-group-item"><i class="glyphicon glyphicon-map-marker"></i> {!!$ward->ward_name!!}</a>
								@endforeach
							</div>
							<div class="panel-footer text-center">
								<button type="button" class="btn btn-xs btn-primary show_button"><i class="glyphicon glyphicon-chevron-down"></i> Hiển thị thêm</button>
							</div>
						</div>
						@elseif(!empty($channelInfo->joinAddress[0]->address->joinDistrict->district->id))
						<div class="panel panel-default">
							<?
								$getAllRegionDistrict=\App\Model\Region_district::where('subregions_id','=',$channelInfo->joinAddress[0]->address->joinDistrict->district->subregions_id)->get(); 
							?>
							<div class="list-group" id="list">
								@foreach($getAllRegionDistrict as $district)
									<a href="{{route('channel.slug',array($channel['domain']->domain,str_replace('/VN/','',$district->SolrID)))}}" class="list-group-item"><i class="glyphicon glyphicon-map-marker"></i> {!!$district->district_name!!}</a>
								@endforeach
							</div>
							<div class="panel-footer text-center">
								<button type="button" class="btn btn-xs btn-primary show_button"><i class="glyphicon glyphicon-chevron-down"></i> Hiển thị thêm</button>
							</div>
						</div>
						@elseif(!empty($channelInfo->joinAddress[0]->address->joinSubRegion->subregion->id))
						<div class="panel panel-default">
							<?
								$getAllSubRegion=\App\Model\Subregions::where('region_id','=',$channelInfo->joinAddress[0]->address->joinSubRegion->subregion->region_id)->get(); 
							?>
							<div class="list-group" id="list">
								@foreach($getAllSubRegion as $subRegion)
									<a href="{{route('channel.slug',array($channel['domain']->domain,str_replace('/VN/','',$subRegion->SolrID)))}}" class="list-group-item"><i class="glyphicon glyphicon-map-marker"></i> {!!$subRegion->subregions_name!!}</a>
								@endforeach
							</div>
							<div class="panel-footer text-center">
								<button type="button" class="btn btn-xs btn-primary show_button"><i class="glyphicon glyphicon-chevron-down"></i> Hiển thị thêm</button>
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
		@else 
		<div class="section-error">
			<div class="alert alert-warning">
				Không tìm thấy dữ liệu <strong>{!!$channelInfo->channel_name!!}</strong> 
			</div>
		</div>
		@endif
		@else 
		<div class="section-error">
			<div class="alert alert-warning">
				 <strong>{!!$channelInfo->channel_name!!} đã xóa hoặc không tồn tại. </strong> 
			</div>
		</div>
	@endif
	</div>

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		jQuery(document).ready(function(){
			"use strict"; 
			$("#list .list-group-item:gt(6)").hide();
			$(".show_button").click(function() {
				$("#list .list-group-item:hidden:lt(6)").slideToggle();
				if( ! $("#list .list-group-item").is(":hidden") )
					$(this).hide();
				return false;
			});
		}); 
	', $dependencies);
?>
@if($channel['security']==true)
<?

	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('customAdmin','
	', $dependencies);
?>
@endif
