<div class="row justify-content-center mt-2 g-2">
	<div class="col-md-12 text-center">
		<h3 id="title" class="mb-0">{{$title}}</h3>
	</div>
	@if(!empty($subtitle))
		<div class="col-md-12 text-center">
			<h5 id="subtile" class="text-secondary fw-normal mb-0">
				{{ $subtitle }}
			</h5>
		</div>
	@endif
</div>