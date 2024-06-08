@php
	
@endphp
<div class="modal-header">
	<h1 class="modal-title fs-5" id="modalLabel">Dipinto</h1>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12 mb-3">
			<label>Nome</label>
			<input type="text" class="form-control" id="example-name" name="name" value="{{$example->name ?? ""}}">
		</div>
		<div class="col-md-12 mb-3">
			<label>Autore</label>
			<input type="text" class="form-control" id="example-author" name="author" value="{{$example->author ?? ""}}">
		</div>
	</div>
</div>
<div class="modal-footer">
	<div class="row w-100 justify-content-end">
		@isset($example)
			<div class="col-md-6 p-0">
				<button id="example-delete" type="button" class="btn btn-danger"
					hx-delete="{{route("example.delete", [$example])}}" hx-target="#request-response" hx-confirm="Eliminare il dipinto {{$example->name}}?" hx-params="none">
					Elimina esempio
				</button>
			</div>
		@endisset
		<div class="col-md-6 p-0 text-end">
			@csrf
			<button id="example-save" type="button" class="btn btn-primary"
				hx-put="{{route("example.".(is_null($example) ? "create" : "update"), [$example])}}" hx-target="#request-response">
				Salva
			</button>
		</div>
	</div>
</div>

<script>
	modal.show();
</script>