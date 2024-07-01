@php
	
@endphp
<div class="modal-header">
	<h1 class="modal-title fs-5" id="modalLabel">{{__('app.pages.short.add_language')}}</h1>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
	<div class="row g-3">
		<div class="col-12">
			<label>{{ucfirst(__('validation.attributes.language'))}}</label>
			<select class="form-select" id="url-language" name="language">
				<option value="">---</option>
				@foreach(__("languages") as $key => $value)
					@continue($key == "default" || in_array($key, array_keys($urls)))
					<option value="{{$key}}" data-flag="{{asset("images/lang/".$key.".svg")}}">{{$value}}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>
<div class="modal-footer">
	<div class="row w-100 justify-content-end">
		<div class="col-md-6 p-0 text-end">
			@csrf
			<button id="url-save" type="button" class="btn btn-primary"
				hx-post="{{route("short.add-url")}}" hx-target="#short-urls" hx-swap="beforeend">
				Aggiungi
			</button>
		</div>
	</div>
</div>

<script>
	language = new SelectSearch("#url-language", {
		custom_class: {
			placeholder: "form-select mw-100 p-2"
		},
		render(element){
			if(element.value == "") return element.textContent
			
			return `<img src="`+element.getAttribute("data-flag")+`" class="me-2" style="width: 25px">` + element.textContent
		}
	});
	
	modal.hide();
	modal_2.show();
</script>