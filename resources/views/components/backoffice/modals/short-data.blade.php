@php
	use App\Models\Short;
	use App\Models\TagCategory;
@endphp
<div class="modal-header">
	<h1 class="modal-title fs-5" id="modalLabel">Short @isset($short) - {{$short->code}} @endisset</h1>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
	<div class="row g-3">
		@empty($short)
			<div class="col-12">
				<label>{{ucfirst(__('validation.attributes.custom_code'))}}</label>
				<div class="input-group">
					<span class="input-group-text">
						{{config("app.url")}}
					</span>
					@php
						$code = Short::generateCode();
					@endphp
					<input type="text" class="form-control" id="short-custom_code" name="custom_code" maxlength="50" placeholder="{{$code}}"></input>
					<input type="hidden" name="code" value="{{$code}}"></input>
				</div>
			</div>
			<div class="col-md-12">
				<div id="short-urls" class="row g-3">
					<div class="col-12">
						<label>{{ucwords(__('validation.attributes.urls._default'))}}</label>
						<div class="input-group">
							<span class="input-group-text p-0 overflow-hidden">
								<img class="url-flag" title="Default" alt="Default" src="{{asset("images/lang/default.svg")}}">
							</span>
							<input type="text" class="form-control" id="short-defult_url" name="urls[_default]">
						</div>
					</div>
					<div class="col-12">
						<label>{{__("app.pages.short.language_urls")}}</label>
					</div>
				</div>
			</div>
			<div class="col-12 text-center">
				<button class="btn btn-primary btn-sm fs-7" hx-post="{{route("short.add-url-modal")}}" hx-target="#modal-2 .modal-content">
					<i class="fa-solid fa-plus"></i>
					{{__("app.pages.short.add_language")}}
				</button>
			</div>
		@endempty
		<div class="col-12">
			<label>{{ucfirst(__('validation.attributes.description'))}}</label>
			<textarea class="form-control" id="short-description" name="description" rows="3" maxlength="255">{{$short->description ?? ""}}</textarea>
		</div>
		<div class="col-12">
			<label>{{ucfirst(__('validation.attributes.tags'))}}</label>
			<select class="selectize" id="short-tags" name="tags[]" multiple>
				@foreach (TagCategory::orderBy("name")->get() as $category)
					<optgroup label="{{$category->name}}">
						@foreach ($category->tags as $tag)
							<option value="{{$tag->id}}" data-backgroundcolor="{{$tag->background_color}}" data-textcolor="{{$tag->text_color}}" @selected(in_array($tag->id, $short?->tags->pluck("id")->toArray() ?? []))>{{$tag->name}}</option>
						@endforeach
					</optgroup>
				@endforeach
			</select>
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
	$(".selectize").selectize({
		plugins: ["remove_button"],
		sortField: 'text',
		lockOptgroupOrder: true,
		render: {
			item: function (item, escape) {
				return `
					<div class="me-2" style="color: `+item.textcolor+`; background-color: `+item.backgroundcolor+`; --text-color: `+item.textcolor+`;">
						`+item.text+`
					</div>
				`;
			},
		}
	});
	
	modal.show();
</script>