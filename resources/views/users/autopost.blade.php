@extends('inc.master')
@section('seo')
<?php 
	$data_seo = array(
		'title' => 'Danh Sách cấu hình Auto Post |'.config('seo_title'),
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
	       <div class="col-sm-12 col-md-9 col-lg-9">
	           <div class="row">
	                <div role="tabpanel" class="col-xs-12 col-md-12 col-lg-12">
	                    <br />
					<!-- Nav tabs -->
					<ul class="nav nav-tabs nav-info" role="tablist">

						<li role="presentation" class="active">
							 	<a title="Danh Sách Cấu Hình Auto Post" href="#log-coin" aria-controls="log-coin" role="tab" data-toggle="tab">Danh Sách Cấu Hình Auto Post</a>
						</li>
					  
						
					</ul>

				  	<!-- Tab panes -->
					<div class="tab-content">

					
    					<div role="tabpanel" class="tab-pane active" id="log-coin"> 
        						<div class="row ">
        						    @if(count($post) != 0)
        						    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                        	<th>Tên cấu hình</th>
                                            <th>Thời gian tạo</th>
                                            <th>Fan page</th>
                                          
                                            <th>Hành động</th>
                                            
                                            
                                        </tr>
                                        </thead>
                                        <tbody>
                                    
                                       @foreach($post as $item)
                                       <tr>
                                       	  <td>{{$item->name}}</td>
                                          <td>{{date('d-m-Y',strtotime($item->created_at))}}</td>
                                          <td>{{$item->name}}</td>
                                          <td><a href="{{route('user.autopost.destroy',$item->id)}}">Xóa</a></td>
                                          
                                         
                                        </tr> 
                                       @endforeach
                                      
                                       </tbody>
                                    </table>
                                     <nav class="pull-right">{!! $post->render() !!}</nav>
                                      @else
                                      <center style="margin-top:50px"><p class="text-success"><i>Hiện Bạn chưa có cấu hình chức năng này!</i></p></center>
                                                                                       </tr>
                                       @endif
                                </div>
    					</div>
					</div><!-- end Tabs content-->

				</div><!-- end Tabs tabpanel-->
	           </div>
	       </div>
	</div><!-- end row -->
</div><!-- end container -->

@endsection