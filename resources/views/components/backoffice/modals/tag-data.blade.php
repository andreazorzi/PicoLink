@php
	use App\Models\TagCategory;
@endphp
<div class="modal-header">
	<h1 class="modal-title fs-5" id="modalLabel">Tag</h1>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
	<div class="row g-3">
		<div id="tag-preview" class="col-12 text-center">
			<x-tag :tag="$tag" />
		</div>
		<div class="col-6">
			<label>{{ucwords(__('validation.attributes.name'))}}</label>
			<input type="text" class="form-control" id="tag-name" name="name" value="{{$tag->name ?? ""}}">
		</div>
		<div class="col-6">
			<label>{{ucwords(__('validation.attributes.tag_category'))}}</label>
			<select class="selectize" id="tag-tag_category" name="tag_category" multiple>
				@foreach (TagCategory::orderBy("name")->get() as $category)
					<option @selected(($tag->tag_category_id ?? null) == $category->id)>{{$category->name}}</option>
				@endforeach
			</select>
		</div>
		<div class="col-6">
			<label>{{ucwords(__('validation.attributes.background_color'))}}</label>
			<input type="color" oninput="console.log(this.value);" class="form-control" id="tag-background_color" name="background_color" value="{{$tag->background_color ?? "#000000"}}">
		</div>
		<div class="col-6">
			<label>{{ucwords(__('validation.attributes.text_color'))}}</label>
			<input type="color" class="form-control" id="tag-text_color" name="text_color" value="{{$tag->text_color ?? "#ffffff"}}">
		</div>
	</div>
</div>
<div class="modal-footer">
	<div class="row w-100 justify-content-end">
		<div class="col-md-6 p-0 text-end">
			@csrf
			<button id="tag-save" type="button" class="btn btn-primary"
				hx-put="{{route("tag.".(is_null($tag) ? "create" : "update"), [$tag])}}" hx-target="#request-response">
				Salva
			</button>
		</div>
	</div>
</div>

<script>
	$("#tag-name, #tag-background_color, #tag-text_color").on("keyup change input", function() {
		let name = $("#tag-name").val();
		let background_color = $("#tag-background_color").val();
		let text_color = $("#tag-text_color").val();
		$("#tag-preview span").text(name).css("background-color", background_color).css("color", text_color);
	});
	
	$(".modal .selectize").selectize({
		plugins: ["remove_button"],
		maxItems: 1,
		copyClassesToDropdown: true,
		create: function (input) {
			return {
				value: input,
				text: input,
			};
		},
		onChange: function(value) {
			// $("#page").val(1);
			// htmx.trigger("#page", "change");
		}
	});
	
	modal.show();
</script>