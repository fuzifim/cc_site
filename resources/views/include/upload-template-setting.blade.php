<span class="btn btn-success fileinput-button">
    <i class="fa fa-upload"></i>
    <span>Chọn ảnh từ máy của bạn</span>
    <!-- The file input field used as target for the file upload widget -->
    <input id="fileupload" type="file" name="file[]" multiple>
</span>

<div id="dropbox" class="">
    @if(count($adsimages) > 0 )
        @foreach ($adsimages as $img)
           <div class="col-md-3 preview">
                <img class="img-responsive img-thumbnail" src="{{ $img->file_url }}" />
                <div class="item-action">
                    <a href="javascript:void(0)" onclick="removeFile(this)" data-file_id = "{{ $img->id }}" class="remove_img pull-right">
                    <i class="glyphicon glyphicon-remove"></i></a>
                    .
                </div>
           </div>
        @endforeach
    @else
         <span class="message">Kéo file để upload.</span>
    @endif

</div>
<label for="" class="text-danger" id="upload-msg"></label>

<div id="progress" class="progress">
    <div class="progress-bar progress-bar-success"></div>
</div>
<link rel="stylesheet" href="{{ url('lib/jQuery-File-Upload/styles.css') }}">
<script src="{{ url('lib/jQuery-File-Upload/js/vendor/jquery.ui.widget.js') }}"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="{{ url('lib/jQuery-File-Upload/js/jquery.iframe-transport.js') }} "></script>
<!-- The basic File Upload plugin -->
<script src="{{ url('lib/jQuery-File-Upload/js/jquery.fileupload.js') }}"></script>
<script type="text/javascript">

function removeFile(file){
    var file_id = $(file).data('file_id');
    var that = $(file);
    $.ajax({
        url: '/file/removefilemask',
        type: 'post',
        dataType: 'json',
        data: {
            file_id : file_id,
            id_template : $('input[name="id_template"]').val(),
            '_token': $('input[name=_token]').val()
        },
        success:function(data){
            console.log(data);
            if(data.success){
                that.parent().parent().remove();
            }
        }
    });
}



$(function () {


    'use strict';


    // Change this to the location of your server-side upload handler:
    $('#fileupload').fileupload({
        url: '/file/uploadfilemask',
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 100000,
        singleFileUploads: false,
        //limitMultiFileUploads: 5,
        dropZone: '#dropbox',
        formData: {
            '_token': $('meta[name=_token]').attr('content'),
            'id_template' : $('input[name="id_template"]').val()
        },
        add: function(e, data){
            $('.message','#dropbox').remove();
            data.submit();
        },

        done: function (e, data) {
            console.log(data);

            if(!data.result.success){
                $('label#upload-msg').text(data.result.msg.alert);
                return;
            }else{
                $('label#upload-msg').text('');
                $.each(data.result.msg.alert, function (key, data) {
                    $('label#upload-msg').text($('label#upload-msg').text()+key+':'+data+'\r\n');
                });

                $.each(data.result.file,function(index,item){
                    var preview = $(template);
                    $('img', preview)
                        .attr('src',item.file_url)
                        .addClass('img-responsive img-thumbnail');
                    $('a', preview).attr('data-file_id',item.file_id);
                    $('input[name="ads_thumbnail[]"]', preview)
                        .attr('data-file_id',item.file_id)
                        .attr('onchange','set_thumbnail(this)');
                    $(preview).appendTo('#dropbox');
                });

            }
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
var template = '<div class="col-md-3 preview">'+
                    '<img />'+
                    '<div class="item-action">'+
                        '<a href="javascript:void(0)" onclick="removeFile(this)" class="remove_img pull-right"><i class="glyphicon glyphicon-remove"></a>.'+
                    '</div>'+
                '</div>';
</script>