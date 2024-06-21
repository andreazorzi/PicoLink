<span class="badge fs-6_5 me-2" style="background-color: {{$tag->background_color ?? "#000000"}}; color: {{$tag->text_color ?? "#ffffff"}};" role="button"
	@if($editable ?? false)
		hx-post="{{route("tag.details", [$tag])}}" hx-target="#modal .modal-content"
	@endif
	>
	{{$tag->name ?? "-"}}
</span>