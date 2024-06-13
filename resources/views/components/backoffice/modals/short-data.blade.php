@php
	
@endphp
<div class="modal-header">
	<h1 class="modal-title fs-5" id="modalLabel">Short - {{$short->code}}</h1>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-12 mb-3">
			<label>{{ucfirst(__('validation.attributes.description'))}}</label>
			<textarea class="form-control" id="short-description" name="description" rows="3">{{$short->description}}</textarea>
		</div>
	</div>
</div>
<div class="modal-footer">
	<div class="row w-100 justify-content-end">
		{{-- @isset($short)
			<div class="col-md-6 p-0">
				<button id="short-delete" type="button" class="btn btn-secondary"
					hx-post="{{route("short.send-reset-password", [$short])}}" hx-target="#request-response" hx-confirm="Inviare l'email di reset password a {{$short->shortname}}?" hx-params="none">
					Reset Password
				</button>
			</div>
		@endisset --}}
		<div class="col-md-6 p-0 text-end">
			@csrf
			<button id="short-save" type="button" class="btn btn-primary"
				hx-put="{{route("short.".(is_null($short) ? "create" : "update"), [$short])}}" hx-target="#request-response">
				Salva
			</button>
		</div>
	</div>
</div>

<script>
	modal.show();
</script>