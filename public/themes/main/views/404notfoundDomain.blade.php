<?
	Theme::setTitle('404 '.$domainName);
	Theme::setKeywords($domainName.' not found');
	Theme::setDescription($domainName.' Not found, please try again');
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
?>
<meta name="revisit-after" content="1 days">
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar_domain', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('title') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
		<div class="panel panel-default">
			<div class="panel panel-body channelTheme">
			This domain {{$domainName}} not found, please try again! 
			</div>
		</div>
	</div>

</div><!-- mainpanel -->
</section>
<div class="modal fade" id="ModalFacebook">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4>Like trang và chia sẻ để thấy nội dung</h4>
			</div>
			<div class="modal-body text-center">
				<p>Nhấn vào nút <strong>thích</strong> để thấy và theo dõi nội dung! </p>
				<div class="fb-like" data-href="https://www.facebook.com/cungcap.net/" data-width="" data-layout="button_count" data-action="like" data-size="large" data-show-faces="true" data-share="true"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><span id="timeLeft"></span></button>
			</div>
		</div>
	</div>
</div>
<?php
$channel['theme']->asset()->writeScript('customScript','
    jQuery(document).ready(function(){
        "use strict";
        $(window).on("load",function(){
            $("#ModalFacebook").modal("show");
        });
        var count = 30;
        setInterval(function(){
            document.getElementById("timeLeft").innerHTML = count;
            if (count == 0) {
                $("#ModalFacebook").modal("hide");
                document.getElementById("timeLeft").innerHTML = "Đóng";
            }
            count--;
        },1000);
        $("#ModalFacebook").modal({backdrop: "static", keyboard: false});
    });
', []);
?>