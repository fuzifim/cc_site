@extends('inc.master')
@section('seo')
<?php 
	$data_seo = array(
		'title' => 'Thiết lập cấu hình auto post|'.config('title_seo'),
		'keywords' => '',
		'description' => '',
		'og_title' => '',
		'og_description' => '',
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
<div class="container" >

	<div class="row">
		@include('partials.message')
    	@if (count($errors) > 0)
		    <div class="alert alert-danger">
		        <strong>Thông báo!</strong> Có lỗi xảy ra.<br><br>
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif
		@include('partials.usersidebar')
		<div id="post-fanpage" class="col-sm-12 col-md-9 col-lg-9">
		    <h2>Cấu hình Facebook Auto Post </h2>
		    <hr>
		    <div id="post-form-container" class="col-sm-12 col-md-12 col-lg-12">
		      <form id="post-about-form" action="{{route('user.autopost.store')}}" method="post" accept-charset="utf-8" role="form" class="form-horizontal" enctype="multipart/form-data">
			        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
			      
			  
				    <div class="form-group">
						<div class="col-xs-5">
							<h5><strong>Các Danh Mục Của Bạn</strong></h5>
					        <select name="from[]" id="multiselect" class="form-control" size="8" multiple="multiple">
					            @foreach(WebService::getAllCategoryUser(Auth::user()->id) as $cate)
	                	         <option style="padding:10px 0px; border-bottom:1px solid #ccc" value="{{$cate->id}}">{{$cate->cat_name}}</option>
	                	      
	                	         @endforeach
					        </select>
					    </div>
					    
					    <div class="col-xs-2">
					    	<p></p>
					        <button type="button" id="multiselect_rightAll" class="btn btn-block"><i class="fa fa-fast-forward"></i></button>
					        <button type="button" id="multiselect_rightSelected" class="btn btn-block"><i class="fa fa-forward"></i></button>
					        <button type="button" id="multiselect_leftSelected" class="btn btn-block"><i class="fa fa-backward"></i></button>
					        <button type="button" id="multiselect_leftAll" class="btn btn-block"><i class="fa fa-fast-backward"></i></button>
					    </div>
					    
					    <div class="col-xs-5">
					    	<h5><strong>Các Danh Mục Sẽ Đăng Lên Fanpage</strong></h5>
					        <select name="cate[]" id="multiselect_to" class="form-control" size="8" multiple="multiple"></select>
					    </div>
					</div>
					      <div class="form-group">
						<label class="col-sm-3 control-label" for="fanpage">Chọn Fan Page Post Tin</label>
					    <div class="col-sm-9">
					    
					    	<select name="fanpage" id="fanpage"  class="form-control">
	                	      <option style="padding:10px 0px; border-bottom:1px solid #ccc"  value="-1" disabled>Chọn Fan Page:</option>
	                	         @foreach($page as $item)
	                	          <option style="padding:5px 0px; border-bottom:1px solid #ccc" value="{{$item['id']}}" >{{$item['name']}}</option>
	                	          
	                	         @endforeach
	                	    </select>
	                	   
					     </div>
					</div>
					<div class="form-group">
						<div class="col-md-12 col-ms-12 ">
							 <button class="btn btn-primary pull-right" type="submit" name="send">Lưu
							    <i class="fa fa-send"></i>
							  </button>
							 
						</div>
					</div>
				</div>
			  </form>
		    </div>
		</div>
		</div>
</div>
	<script src="{{ asset('/js/bootstrap-formhelpers.js') }}"></script>
	<script src="{{ asset('/js/multiselect.min.js') }}"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
		    $('#multiselect').multiselect();
		});
	</script>
@endsection