@php
	use App\Models\Short;
	use App\Models\TagCategory;
@endphp
<div class="filters row justify-content-center">
	<div class="col-md-8">
		<div class="col-md-12 text-end">
			<button class="btn btn-primary btn-sm" onclick="$('#advanced-search').toggleClass('d-none')">
				<i class="fa-brands fa-searchengin"></i>
				{{__("app.pages.tags.advanced_search")}}
			</button>
			
			<div class="dropdown d-inline-block">
				<button class="btn btn-success btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
					<i class="fa-solid fa-qrcode me-2"></i>
					Download
				</button>
				<ul class="dropdown-menu">
					<li>
						<a class="dropdown-item" role="button" hx-get="{{route("short.multiple-download")}}" hx-include="[name='filter'],[name^='advanced_search']">QRCode</a>
					</li>
					<li>
						<a class="dropdown-item" role="button" hx-get="{{route("short.multiple-download", ["logo" => true])}}" hx-include="[name='filter'],[name^='advanced_search']">QRCode + Logo Space</a>
					</li>
				</ul>
			</div>
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
								<option data-backgroundcolor="{{$tag->background_color}}" data-textcolor="{{$tag->text_color}}">
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
		$(".filters .selectize:not(.selectize-tag)").selectize({
			plugins: ["remove_button"],
			onChange: function(value) {
				$("#page").val(1);
				htmx.trigger("#page", "change");
			},
			onDropdownOpen: function() {
				for (const select of $(".selectize.selectized")) {
					if(select !== this.$input[0]){
						select.selectize.close();
					}
				}
			}
		});
		
		$(".filters .selectize-tag").selectize({
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
			},
			onDropdownOpen: function() {
				for (const select of $(".selectize.selectized")) {
					if(select !== this.$input[0]){
						select.selectize.close();
					}
				}
			}
		});
	});
</script>