@php
	use App\Models\Short;
	use App\Models\TagCategory;
@endphp
<div class="row justify-content-center">
	<div class="col-md-8">
		<div class="col-md-12 text-end">
			<button class="btn btn-primary btn-sm" onclick="$('#advanced-search').toggleClass('d-none')">
				<i class="fa-brands fa-searchengin"></i>
				{{__("app.pages.tags.advanced_search")}}
			</button>
		</div>
		<div id="advanced-search" class="row gy-2 d-none">
			<div class="col-md-4">
				<label>{{ucwords(__("validation.attributes.code"))}}</label>
				<select class="selectize selectize" multiple name="advanced_search[code][]">
					@foreach (Short::orderBy("code")->get() as $short)
						<option>{{$short->code}}</option>
					@endforeach
				</select>
			</div>
			<div class="col-md-4">
				<label>{{ucwords(__("validation.attributes.tags"))}}</label>
				<select class="selectize selectize-tag" multiple name="advanced_search[tags][]">
					@foreach (TagCategory::orderBy("name")->get() as $category)
						<optgroup label="{{$category->name}}">
							@foreach ($category->tags as $tag)
								<option value="{{$tag->id}}" data-backgroundcolor="{{$tag->background_color}}" data-textcolor="{{$tag->text_color}}">
									{{$tag->name}}
								</option>
							@endforeach
						</optgroup>
					@endforeach
				</select>
			</div>
			<div class="col-md-4">
				<label>{{ucwords(__("validation.attributes.description"))}}</label>
				<input type="text" class="form-control" name="advanced_search[description]" onkeyup="htmx.trigger('#page', 'change');">
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		$(".selectize:not(.selectize-tag)").selectize({
			plugins: ["remove_button"],
			onChange: function(value) {
				$("#page").val(1);
				htmx.trigger("#page", "change");
			}
		});
		
		$(".selectize-tag").selectize({
			plugins: ["remove_button"],
			sortField: 'text',
			lockOptgroupOrder: true,
			render: {
				item: function (item, escape) {
					return `
						<span class="badge fs-6_5 me-2 mb-1" style="background-color: `+item.backgroundcolor+`; color: `+item.textcolor+`;" role="button">
							`+item.text+`
						</span>
					`;
				},
				option: function (item, escape) {
					return `
						<div class="my-1 ps-3">
							<span class="badge fs-6_5 me-2" style="background-color: `+item.backgroundcolor+`; color: `+item.textcolor+`;" role="button">
								`+item.text+`
							</span>
						</div>
					`;
				}
			},
			onChange: function(value) {
				$("#page").val(1);
				htmx.trigger("#page", "change");
			}
		});
	});
</script>