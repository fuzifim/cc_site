@if ($ads->qr_zalo)
<div class="panel panel-info qr_images">
	<div class="panel-heading">Kết bạn Zalo với Shop</div>
	<div class="panel-body container_zalo">
        {!! stripslashes($ads->qr_zalo) !!}
        <p class="text-center">Quét mã QR với Zalo app</p>
	</div>
</div>
@endif