@php
	$type = $user->type ?? request()->type ?? ""
@endphp
<div class="modal-header">
	<h1 class="modal-title fs-5" id="modalLabel">User</h1>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-6 mb-3">
			<img src="https://ui-avatars.com/api/?background=random&name={{Str::slug($user->name ?? "")}}" class="rounded-circle">
		</div>
		<div class="col-md-6 mb-3">
			<label>Attivo</label>
			<select id="user-enabled" name="enabled">
				<option value="0" @selected(!($user->enabled ?? 1)) data-text="danger">Disattivo</option>
				<option value="1" @selected($user->enabled ?? 1) data-text="success">Attivo</option>
			</select>
		</div>
		<div class="col-md-6 mb-3">
			<label>Username</label>
			<input type="text" class="form-control" id="user-username" name="username" value="{{$user->username ?? ""}}" @readonly(!empty($user->username))>
		</div>
		<div class="col-md-6 mb-3">
			<label>Nome</label>
			<input type="text" class="form-control" id="user-name" name="name" value="{{$user->name ?? ""}}">
		</div>
		<div class="col-md-12 mb-3">
			<label>Email</label>
			<input type="text" class="form-control" id="user-email" name="email" value="{{$user->email ?? ""}}">
		</div>
		
		@php
			$reset_link = DB::table('password_resets')->where("user", $user->username ?? "")->orderByDesc("expiration")->first();
		@endphp
		@if (!is_null($reset_link))
			<div class="col-md-12">
				<label>Ultimo password reset link inviato:</label>
				<span class="text-{{time() < strtotime($reset_link->expiration) ? "success" : "danger"}}">{{date("d/m/Y H:i:s", strtotime($reset_link->expiration." - 1 day"))}}</span>
			</div>
		@endif
	</div>
</div>
<div class="modal-footer">
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
</div>

<script>
	user_private_list = new SelectSearch("#user-private-list", {
		custom_class: {
			placeholder: "form-select mw-100 p-2"
		}
	});
	
	user_enabled = new SelectSearch("#user-enabled", {
		custom_class: {
			placeholder: "form-select mw-100 p-2"
		},
		render(element){
			return "<span class='text-"+element.getAttribute("data-text")+"'>" + element.textContent + "</span> "
		}
	});
	
	modal.show();
</script>