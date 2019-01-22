@extends('inc.master',['txt' => $txt])
@section('seo')
<?php
	$data_seo = array(
		'title' => 'Tìm kiếm: '.$txt.' |'.config('app.seo_title'),
		'keywords' => $txt,
		'description' => $txt,
		'og_title' => 'Tìm kiếm: '.$txt.' |'.config('app.seo_title'),
		'og_description' => $txt,
		'og_url' => Request::url(),
		'og_sitename' => config('app.appname'),
		'og_img' => asset('img/logo.png'),
		'current_url' => Request::url()
	);
	$seo = WebService::getSEO($data_seo);
?>
@include('partials.seo')
@endsection
@section('content')
<div id="wrapper">
	<div id="sidebar-wrapper" class="menu_home hidden-xs">
		{!!WebService::leftMenuRender()!!}
	</div><!--sidebar-wrapper-->
	<div id="page-content-wrapper" class="page_content_primary clear">
		<div class="container-fluid entry_container xyz">
			<div class="row no-gutter mrb-5 country_option">
				<div class="col-lg-12">
					<div class="row no-gutter">
						<div class="col-md-12">
							<form name="frm_search" id="frm_search" <?php if(isset($txt) && strlen($txt)>0){?> action="http://cungcap.net/search/<?php echo addslashes($txt); ?>" <?php }else{?> action="" <?php } ?> class="" role="search">
								<div class="input-group" id="adv-search">
									<div class="input-group-btn">
										<div class="btn-group" role="group">
											<input id="txt_search" type="text" class="form-control" placeholder="Tìm kiếm: {{$txt}}" value="<?php if(isset($txt) && strlen($txt)>0){ echo $txt;}?>"/>
											<button id="search_btn" type="submit" class="btn btn-primary btn-search"><span class="glyphicon glyphicon-search"></span></button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row no-gutter">
				<div id="group_content_page_generate" class="col-lg-12 col-md-12 col-xs-12 list-item-group-company">
					@if(count($items))
						@foreach($items as $result)
							<div class="panel panel-default"> 
								<div class="panel-body panel-company">
									<h3>
										@if(!empty($result['image']))
											<img width="32" src="{{$result['image']}}">
										@endif
										<a href="{{$result['url']}}">{{$result['title']}}</a>
									</h3>
									<span class="the-article-publish">
										{!!WebService::time_request($result['updated_at'])!!} 
									</span>
									<p class="address">
										{!! str_limit(strip_tags(htmlspecialchars_decode($result['description']),""), $limit = 300, $end='...') !!}
									</p>
								</div>
							</div>  
						@endforeach
						<?php echo $items->render(); ?>
						
					@else
					<div class="col-xs-12 col-sm-12 col-md-12 alert_view">
						<div class="alert alert-danger">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							 <em>Không tìm thấy kết quả tìm kiếm {{$txt}} nào.  </em>
						</div>
					</div>
					@endif
				</div>
			</div>
		</div><!--container-fluid-->
	</div>
</div><!--wrapper-->
@endsection
