@php
	use chillerlan\QRCode\QRCode;
	use chillerlan\QRCode\QROptions;
	
	$options = new QROptions;
	$options->quietzoneSize = 0;
@endphp
<div class="modal-header">
	<h1 class="modal-title fs-5" id="modalLabel">
		{{__('app.pages.short.share')}} - {{$short->code}}
	</h1>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
	<div class="row justify-content-center">
		<div class="col-md-5">
			<img src="{{(new QRCode($options))->render($short->getLink())}}" class="logo">
			
			@php($options->quietzoneSize = 1)
			<a href="{{(new QRCode($options))->render($short->getLink())}}" download="{{$short->code}}.svg" class="btn btn-primary btn-sm w-100 mt-3">
				<i class="fa-solid fa-qrcode me-2"></i>
				Download
			</a>
			
			<button id="copy-link" class="btn btn-success btn-sm w-100 mt-2" data-link="{{$short->getLink()}}">
				<i class="fa-solid fa-clipboard me-2"></i>
				{{__("app.pages.short.copy_link")}}
			</button>
		</div>
	</div>
</div>

<script>
	$("#copy-link").on("click", function(){
		let link = $(this).attr("data-link");
		navigator.clipboard.writeText(link);

		Toastify({
			text: `{{__('app.pages.index.link_copied')}}!`,
			duration: `1400`,
			className: `success`,
			gravity: `bottom`,
			position: `center`,
			close: true
		}).showToast();
	});
	
	modal.show();
</script>