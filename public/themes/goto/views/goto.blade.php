<?
	Theme::setTitle('Url Redirect'); 
?>
<script type="application/ld-json" id="json-url">{!!json_encode($url)!!}</script>
<script type="text/javascript">
<!--
var redirUrl=jQuery.parseJSON(jQuery("#json-url").html());

jQuery(document).ready(function(){
	jQuery("#linkContinue").attr("href",redirUrl);
	jQuery("#linkUrl").html(redirUrl);
});
-->
</script>

<div class="container">
	<div class="card-body">
		<div class="form-group">
			<div class="alert alert-dark">
				This URL (<strong><span id="linkUrl"></span></strong>) is not belong to Cung Cap. Net, if you want to continue, please click bellow button to redirect to 
			</div>
		</div>
		<div class="form-group">
		<a class="btn btn-success btn-block" id="linkContinue" href="">Click here to continue</a>
		</div>
	</div>
</div>