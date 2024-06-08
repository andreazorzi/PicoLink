@php
	$durations = [
		"success" => 2000,
		"danger" => 4000,
		"info" => 1500,
		"warning" => 4000,
	];
	
	$status = $status ?? "info";
	
	$duration =  $duration ?? $durations[$status];
	
	$alert_data = '
		Toastify({
			text: "'.$message.'",
			duration: '.$duration.',
			className: "'.$status.'",
			gravity: "bottom",
			position: "center",
			close: true,
			callback: function(){
				'.($callback ?? "").'
			}
		}).showToast();
	';
@endphp

<script>
	@if (!empty($beforeshow))
		{!!$beforeshow!!}
	@endif
	
	if(document.readyState === "complete") {
		{!!$alert_data!!}
	}
	else {
		window.addEventListener("DOMContentLoaded", () => {
			{!!$alert_data!!}
		});
	}
</script>