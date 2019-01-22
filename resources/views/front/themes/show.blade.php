@extends('inc.master')
@section('seo')
<?php
   $data_seo = array(
		'title' => 'Mẫu kênh website',
		'keywords' => 'Mẫu Kênh',
		'description' => 'Mẫu kênh website',
		'og_type' => 'website',
		'og_site_name' => 'Mẫu kênh website',
		'og_title' => 'Mẫu kênh website',
		'og_description' => 'Mẫu kênh website',
		'og_url' => Request::url(),
		'og_sitename' => config('app.appname'),
		'og_img' => '',
		'current_url' => Request::url()
	    );
	$seo = WebService::getSEO($data_seo);   
?>
@include('partials.seo')
@endsection

@section('content')
<div class="site-head">
	<div class="panel-headding" style="text-align:center; background:#00a6cf; color:#fff; padding:5px 0px;   ">
		<h1>@lang('main.themeChannel')</h1>
		<p>@lang('main.descriptionThemeChannel')</p>
	</div>
	<div class="panel-body" style="padding:20px 0px; color:#fff; background-attachment: fixed !important; background-position: 50% 0;  background-repeat: no-repeat; background-size: cover; overflow: hidden; background-image: url({{asset('assets/img/banner-themes.jpg')}});">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-4">
					<img class="img-responsive img-thumbnail" src="{{$themes[0]->media_url}}">
				</div>
				<div class="col-xs-12 col-sm-6 col-md-8" style="text-align: justify; padding:0px 20px; ">
					{!!htmlspecialchars_decode($themes[0]->temp_content)!!}
					<p><button class="btn btn-success pull-right" type="button" data-id="{{$themes[0]->theme_id}}" id="addTheme"><i class="glyphicon glyphicon-ok"></i> Chọn mẫu này</button></p>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$( "#addTheme").click(function() {
		$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')} }); 
		var formData = new FormData();
		formData.append("themeId",$( this ).attr('data-id')); 
		$.ajax({
			url: "{{route('front.theme.join')}}",
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			success:function(result){
				if(result.success==true){
					window.location.href="{{route('front.channel.add')}}"
				}else if(result.success==false){
					console.log(result);
				}
			},
			error: function(result) {
			}
		});
	});
</script>
@endsection

