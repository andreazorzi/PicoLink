@php
	$modalid = !empty($id) ? "modal-{$id}" : "modal";
@endphp
<div class="modal fade" id="{{$modalid}}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-{{$size ?? "md"}}">
		<form class="modal-content" onsubmit="return false;">
			
		</form>
	</div>
</div>

<script>
	let {{str_replace("-", "_", $modalid)}};
	
	document.addEventListener("DOMContentLoaded", function() {
		{{str_replace("-", "_", $modalid)}} = new bootstrap.Modal(document.getElementById('{{$modalid}}'));
		
		@if (!empty($id))
			{{str_replace("-", "_", $modalid)}}._element.addEventListener('hide.bs.modal', event => {
				modal.show();
			});
		@endif
	});
</script>