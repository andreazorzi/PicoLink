@php
	
@endphp
<div class="modal-header">
	<h1 class="modal-title fs-5" id="modalLabel">
		{{__('app.pages.short.share')}} - {{$short->code}}
	</h1>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
	<div class="row">
		
	</div>
</div>
{{-- <div class="modal-footer">
	<div class="row w-100 justify-content-end">
		@isset($user)
			<div class="col-md-6 p-0">
				<button id="user-delete" type="button" class="btn btn-secondary"
					hx-post="{{route("user.send-reset-password", [$user])}}" hx-target="#request-response" hx-confirm="Inviare l'email di reset password a {{$user->username}}?" hx-params="none">
					Reset Password
				</button>
			</div>
		@endisset
		<div class="col-md-6 p-0 text-end">
			@csrf
			<input type="hidden" name="type" value="{{$type}}">
			<button id="user-save" type="button" class="btn btn-primary"
				hx-put="{{route("user.".(is_null($user) ? "create" : "update"), [$user])}}" hx-target="#request-response">
				Salva
			</button>
		</div>
	</div>
</div> --}}

<script>
	modal.show();
</script>