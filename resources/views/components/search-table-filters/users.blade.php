@php
	use App\Models\User;
@endphp
<div class="row justify-content-center">
	<div class="col-md-8">
		<div class="col-md-12 text-end">
			<button class="btn btn-primary btn-sm" onclick="$('#advanced-search').toggleClass('d-none')">
				<i class="fa-brands fa-searchengin"></i>
				Ricerca Avanzata
			</button>
		</div>
		<div id="advanced-search" class="row gy-2 d-none">
			<div class="col-md-4">
				<label>Status</label>
				<select class="selectize" multiple name="advanced_search[status][]">
					@foreach (["Attivo", "Disattivo"] as $status)
						<option value="{{Str::lower($status)}}">{{$status}}</option>
					@endforeach
				</select>
			</div>
			<div class="col-md-4">
				<label>Tipo</label>
				<select class="selectize" multiple name="advanced_search[type][]">
					@foreach (User::groupBy("type")->pluck("type")->toArray() as $type)
						<option>{{$type}}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		$(".selectize").selectize({
			plugins: ["remove_button"],
			onChange: function(value) {
				$("#page").val(1);
				htmx.trigger("#page", "change");
			}
		});
	});
</script>