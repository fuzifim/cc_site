<?
	Theme::setTitle('Url Redirect to '.$url);
?>
<script type="application/ld-json" id="json-url">{!!json_encode($url)!!}</script>
<script type="text/javascript">
var redirUrl=jQuery.parseJSON(jQuery("#json-url").html());

jQuery(document).ready(function(){
	jQuery("#linkContinue").attr("href",redirUrl);
	jQuery("#linkUrl").html(redirUrl);
});
window.setInterval(function() {
	var timeLeft    = $("#timeLeft").html();
	if(eval(timeLeft) == 0){
		window.location= (redirUrl);
	}else{
		$("#timeLeft").html(eval(timeLeft)- eval(1));
	}
}, 1000);
</script>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
	(adsbygoogle = window.adsbygoogle || []).push({
		google_ad_client: "ca-pub-6739685874678212",
		enable_page_level_ads: true
	});
</script>
<div class="container">
	<div class="card-body">
		<div class="form-group">
			<div class="alert alert-dark">
				This URL (<strong><span id="linkUrl"></span></strong>) is not belong to Cung Cap. Net, if you want to continue, please click bellow button to redirect to 
			</div>
		</div>
		<div class="form-group">
			<ins class="adsbygoogle"
				 style="display:block"
				 data-ad-client="ca-pub-6739685874678212"
				 data-ad-slot="7536384219"
				 data-ad-format="auto"></ins>
			<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
		</div>
		<div class="form-group">
		<a class="btn btn-success btn-block" id="linkContinue" href=""><h2>Click here to continue <span id="timeLeft">5</span></h2></a>
		</div>
	</div>
</div>