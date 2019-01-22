<?
	$channel['theme']->setTitle('Danh mục'); 
	$channel['theme']->setDescription('Vd: Sản phẩm, Tin tức, Hình ảnh, Video...');
?>
@include('themes.admin.inc.header')
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				@include('themes.admin.partial.menuManageService')
			</div>
			<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Danh sách danh mục
						<button type="button" class="btn btn-xs btn-success pull-right" id="btnAddCategory"><i class="glyphicon glyphicon-plus"></i> Thêm mới</button>
					</div>
					<div class="messageContent"></div>
					<div class="list-group addCategoryList">
						@if(count($channel['info']->getCategory)>0)
							@foreach($channel['info']->getCategory as $category)
								<div class="list-group-item">
									<a href="#" class="close btnDeleteCategory" data-id="{{$category->category->id}}" data-dismiss="alert" aria-label="close">&times;</a>
									<button type="button" class="btn btn-xs btnCategoryName" data-id="{{$category->category->id}}" data-parent-id="{{$category->category->parent_id}}" data-name="{{$category->category->category_name}}" data-slug="{{$category->category->getSlug->slug_value}}" data-order-by="{{$category->order_by}}"><strong>{{$category->category->category_name}}</strong> <small>{{$category->category->category_description}}</small></button> 
								</div>
							@endforeach
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="modalAddCategory" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			Danh mục
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<div class="modal-body">
			<div class="message"></div>
			<input type="hidden" name="categoryId" id="categoryId" value="">
			<div class="form-group">
				<label class="control-label" for="phone">Tên Danh mục: <span class="text-danger">(*)</span></label>
				<input id="item-category" name="categoryName" value="" type="text" class="form-control" placeholder="Vd: Sản phẩm"> 
			</div>
			<div class="form-group">
				<div id="categoryDescription" style="" contentEditable="true" placeholder="Mô tả..."></div>
				<input id="item-description" name="categoryDescription" value="" type="hidden" class="form-control" placeholder="Mô tả...">
				<script>
					$('#categoryDescription').keyup(function () {
						 $('#item-description').val($(this).text());
					 });
				</script>
			</div>
			<div class="form-group">
				<label class="control-label" for="phone">Thuộc nhóm: </label>
				<select class="form-control" name="categoryParentId" id="categoryParentId">
					
				</select>
			</div>
			<div class="form-group">
				<label class="control-label" for="phone">Thứ tự: </label>
				<input id="item-categoryOrderBy" name="categoryOderBy"  type="number" class="form-control" placeholder="0">
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label><input type="checkbox" name="options[]" data-type="hiddenHomeCategory" value="" >Ẩn hiển thị trên trang chủ</label>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary" id="addCategory"><i class="glyphicon glyphicon-ok"></i> Lưu</button>
		</div>
    </div>

  </div>
</div>
<div id="loading">
	<ul class="bokeh">
		<li></li>
		<li></li>
		<li></li>
	</ul>
</div>
<script>
function convertToSlug(title)
{
  //Đổi chữ hoa thành chữ thường
    slug = title.toLowerCase();
 
    //Đổi ký tự có dấu thành không dấu
    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
    slug = slug.replace(/đ/gi, 'd');
    //Xóa các ký tự đặt biệt
    slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
    //Đổi khoảng trắng thành ký tự gạch ngang
    slug = slug.replace(/ /gi, "-");
    //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
    //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
    slug = slug.replace(/\-\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-/gi, '-');
    slug = slug.replace(/\-\-/gi, '-');
    //Xóa các ký tự gạch ngang ở đầu và cuối
    slug = '@' + slug + '@';
    slug = slug.replace(/\@\-|\-\@|\@/gi, '');
    //In slug ra textbox có id “slug”
	
  return slug;
}
$('#btnAddCategory').click(function () {
	$('#loading').css('visibility', 'visible'); 
	$('#categoryParentId').empty(); 
	$('#categoryId').val(''); 
	$.ajax({
		url: "{{route('channel.category.select',$channel['domain']->domain)}}",
		type: "GET",
		dataType: "html",
		success: function (data) {
			$('#categoryParentId').append(data); 
			$('#loading').css('visibility', 'hidden'); 
			$('#modalAddCategory').modal('show'); 
		}
	});
});
$('#addCategory').click(function () {
	$('.message').empty(); 
	$('.messageContent').empty(); 
	var dataJson={};
	$.each($('input[name="options[]"]:checked'), function(i,item){ 
		dataJson[$(this).attr('data-type')] = 'checked'; 
	});
	var dataOptions=JSON.stringify(dataJson); 
	var formData = new FormData();
	formData.append("categoryId", $('input[name=categoryId]').val()); 
	formData.append("categoryName", $('input[name=categoryName]').val()); 
	formData.append("slugValue", $('input[name=slugValue]').val()); 
	formData.append("categoryDescription", $('input[name=categoryDescription]').val()); 
	formData.append("categoryParentId", $('select[name=categoryParentId]').val()); 
	formData.append("categoryOrderBy", $('input[name=categoryOderBy]').val()); 
	formData.append("options", dataOptions); 
	$.ajax({
		url: "{{route('channel.category.add',$channel['domain']->domain)}}",
		type: "POST",
		headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
		cache: false,
		contentType: false,
		processData: false,
		dataType:'json',
		data:formData,
		success: function (result) {
			if(result.success==false){
				if(result.messageType=='validation'){
					$('.message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>'); 
					var res = jQuery.parseJSON(JSON.stringify(result.message)); 
					var name;
					jQuery.each(res, function(i, val) {
						if(i=='categoryName'){
							name='Chưa nhập tên danh mục: ';
						}else{
							name='';
						}
						$('#alertError').append('<li><b>'+name+'</b> '+val+'</li>');
					});
				}else{
					$('.message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
				}
				
			}else if(result.success==true){
				//console.log(result); 
				location.reload(); 
			}
		}
	});
});
$('.addCategoryList').on("click",".btnCategoryName",function() { 
	$('#categoryParentId').empty(); 
	var categoryId=$(this).attr('data-id');
	var categoryName=$(this).attr('data-name'); 
	var categoryParentId=$(this).attr('data-parent-id'); 
	$('#categoryId').val(categoryId); 
	$('#item-category').val(categoryName); 
	$('#slugValue-xs').text($(this).attr('data-slug')); 
	$('#item-categoryOrderBy').val($(this).attr('data-order-by')); 
	$.ajax({
		url: "{{route('channel.category.select',$channel['domain']->domain)}}",
		type: "GET",
		dataType: "html",
		success: function (data) {
			$('#categoryParentId').append(data); 
			$('#loading').css('visibility', 'hidden'); 
			$('#modalAddCategory').modal('show'); 
		}
	});
});
$('.addCategoryList').on("click",".btnDeleteCategory",function() {
		if(confirm('Bạn có chắc muốn xóa?')){
			$('.messageContent').empty(); 
			var categoryId=$(this).attr("data-id"); 
			var formData = new FormData();
			formData.append("categoryId", categoryId); 
			$.ajax({
				url: "{{route('channel.category.delete',$channel['domain']->domain)}}",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:'json',
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
				success: function (result) {
					$('#loading').css('visibility', 'visible'); 
					$('.addCategoryList').empty(); 
					if(result.success==true){
						location.reload(); 
					}
					
				}
			});
		}
	});
</script>
@include('themes.admin.inc.footer')