<?
	$channel['theme']->setTitle('Top Sites');
	$channel['theme']->setKeywords('Website rating, website information, website value');
	$channel['theme']->setDescription('Website rating and rating information'); 
	if(!empty($channel['info']->channelAttributeBanner[0]->media->media_name)){$channel['theme']->setImage(config('app.link_media').$channel['info']->channelAttributeBanner[0]->media->media_path.'thumb/'.$channel['info']->channelAttributeBanner[0]->media->media_name);} 
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
		<h1>{!! Theme::get('title') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel section-content">
		<div class="row row-pad-5">
			<div class="col-md-9">
				@if(count($getSite)>0)
				<div class="listItem">
					<div class="form-group">
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
						<!-- Ad News -->
						<ins class="adsbygoogle"
							 style="display:block"
							 data-ad-client="ca-pub-6739685874678212"
							 data-ad-slot="7536384219"
							 data-ad-format="auto"></ins>
						<script>
						(adsbygoogle = window.adsbygoogle || []).push({});
						</script>
					</div>
					<div class="panel panel-default row-pad-5">
						<div class="results-list">
						@foreach($getSite as $site)
							<li class="list-group-item"><img width="16" height="16" alt="{{$site->domain}}" src=" //www.google.com/s2/favicons?domain={{$site->domain}}" class="lazy"> <a class="siteLink" data-url='{{json_encode(route("go.to.url",array(config("app.url"),urlencode("http://".$site->domain))))}}' href="http://{{$site->domain}}.{{config('app.url')}}" target="_blank">{{$site->domain}}</a> <small><span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($site->updated_at,'en')!!}</span> - {{$site->view}} views</small>@if(!empty($site->domain_title))<p>{{str_limit(strip_tags($site->domain_title,''), 100)}}</p>@if(!empty($site->rank))<p>Rank <span class="label label-primary"><i class="glyphicon glyphicon-globe"></i> {{Site::price($site->rank)}}</span> @if(!empty($site->country_code)&&!empty($site->rank_country))
								<?
									$getRegion=\App\Model\Regions::where('iso','=',$site->country_code)->first(); 
								?>
								@if(!empty($getRegion->id))
									<span class="label label-primary"><i class="flag flag-{{mb_strtolower($getRegion->iso)}}"></i> {{$getRegion->country}} {{Site::price($site->rank_country)}}</span>
								@endif
							@endif</p>@endif @endif</li>
						@endforeach
						</div>
					</div>
				</div>
				<div id="loadChannel" class="text-center">
					<input id="curentPage-key" class="curentPage-key" type="hidden" value="{{$getSite->currentPage()}}" autocomplete="off"/>
					<input id="totalPage-key" class="totalPage-key" type="hidden" value="{{$getSite->total()}}" autocomplete="off"/>
					<input id="urlPage-key" class="urlPage-key" type="hidden" value="{{Request::url()}}" autocomplete="off"/>
					<input id="nextPage-key" class="nextPage-key" type="hidden" value="{{$getSite->nextPageUrl()}}" autocomplete="off"/>
					<input id="perPage-key" class="perPage-key" type="hidden" value="{{$getSite->perPage()}}" autocomplete="off"/>
					<input id="lastPage-key" class="lastPage-key" type="hidden" value="{{$getSite->lastPage()}}" autocomplete="off"/>
					@if(strlen($getSite->nextPageUrl())>0)
						<div class="panel-body text-center">
							<a href="{{$getSite->nextPageUrl()}}" class="viewMore btn btn-xs btn-primary"><i class="glyphicon glyphicon-hand-right"></i> View more...</a>
						</div>
					@endif
				</div>
				<?
					$dependencies = array(); 
					$channel['theme']->asset()->writeScript('moreChannel', '
						jQuery(document).ready(function(){
						"use strict"; 
						$("#loadChannel .viewMore").click(function(){
							var curentPage=parseInt($("#loadChannel #curentPage-key").val()); 
							var lastPage=parseInt($("#loadChannel #lastPage-key").val()); 
							var pageUrl=$("#loadChannel #urlPage-key").val(); 
							var page_int=curentPage+1;
							if(page_int<=lastPage){
								$("#loadChannel .viewMore").css("position","relative"); 
								$.ajax({
									type: "GET",
									url: pageUrl+"?page="+page_int,
									dataType: "html",
									contentType: "text/html",
									beforeSend: function() {
										$("#loadChannel .viewMore").append("<div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div>"); 
									},
									success: function(data) {
										$("#loadChannel #curentPage-key").val(page_int); 
										$(data).find(".listItem").ready(function() {
											var content_ajax = $(data).find(".listItem").html();
											$(".listItem").append(content_ajax); 
											$("#loadChannel .viewMore #preloaderInBox").remove(); 
										});
									}
								});
							}else{
								$("#loadChannel .viewMore").addClass("hidden");
							}
							return false; 
						}); 
					});
					', $dependencies);
				?>
				@endif
				<div class="form-group">
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Ad News -->
					<ins class="adsbygoogle"
						 style="display:block"
						 data-ad-client="ca-pub-6739685874678212"
						 data-ad-slot="7536384219"
						 data-ad-format="auto"></ins>
					<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
					</script>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading"><div class="panel-title">New keywords</div></div>
					<div class="list-group">
						@foreach($Keywords as $keyword)
							<li class="list-group-item"><a href="{{route('keyword.show',array(config('app.url'),WebService::characterReplaceUrl($keyword->keyword)))}}">{{str_replace('????','', $keyword->keyword)}}</a> <small><span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($keyword->created_at,'en')!!}</span> - Views {{$keyword->view}}</small></li>
						@endforeach
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Ad News -->
					<ins class="adsbygoogle"
						 style="display:block"
						 data-ad-client="ca-pub-6739685874678212"
						 data-ad-slot="7536384219"
						 data-ad-format="auto"></ins>
					<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
					</script>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading"><div class="panel-title">New company</div></div>
					<div class="list-group">
						@foreach($getCompany->chunk(1) as $chunk)
								{!!Theme::partial('listCompany', array('chunk' => $chunk))!!}
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('loadLazy','
		$(function() {
			$(".lazy").lazy();
		}); 
		$(".siteLink").click(function(){
			window.open(jQuery.parseJSON($(this).attr("data-url")),"_blank")
			return false; 
		});
	', $dependencies);
?>