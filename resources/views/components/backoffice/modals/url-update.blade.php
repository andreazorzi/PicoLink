<div class="modal-header">
	<h1 class="modal-title fs-5" id="modalLabel">{{__('app.pages.short.update_language')}}</h1>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
	<div class="row g-3">
		<div class="col-12">
			<label>{{Str::upper($short->language ?? "default")}}</label>
			<input type="text" class="form-control" name="url" value="{{$url->url}}">
		</div>
	</div>
</div>
<div class="modal-footer">
	<div class="row w-100 justify-content-end">
		<div class="col-md-6 p-0 text-end">
			@csrf
			<button id="url-save" type="button" class="btn btn-primary"
				hx-put="{{route("url.update", [$url])}}" hx-target="#request-response">
				{{__('app.pages.short.update')}}
			</button>
		</div>
	</div>
</div>

<script>
	modal.show();
</script>