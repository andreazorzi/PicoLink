@php
    use App\Models\User;
	
	$group = "";
@endphp
<div class="menu-bg fixed-top w-100 h-100 d-none bg-dark bg-opacity-25"></div>
<div class="menu fixed-top">
	<div class="row">
		<div class="col-md-12 p-3 px-4 text-end fs-4">
			<i id="menu-close" class="fa-solid fa-xmark p-1"></i>
		</div>
		@foreach (Route::getRoutes() as $route)
			@if(strpos($route->getName(), "backoffice.") !== false && User::current()->canAccessRoute($route) && ($route->defaults["headers"]["menu"] ?? false))
				@if(($route->defaults["headers"]["menu-group"] ?? "") != $group)
					@if($group != "")
						</div></div>
					@endif
					@php
						$group = $route->defaults["headers"]["menu-group"] ?? "";
					@endphp
					@if($group != "")
						@php
							$open = $group == (Route::current()->defaults["headers"]["menu-group"] ?? "");
						@endphp
						<div class="page-link col-md-12 p-3 {{$open ? "open" : ""}}" data-submenu="submenu-{{$group}}">
							<i class="fa-solid {{__('app.menu.'.$group.'.icon')}}"></i>
							{{__('app.menu.'.$group.'.title')}}
							<i class="menu-chevron fa-solid fa-chevron-down float-right"></i>
						</div>
						<div class="submenu col-md-12 {{$open ? "" : "d-none"}}" data-submenu="submenu-{{$group}}">
							<div class="row">
					@endif
				@endif
				<a href="{{route($route->getName())}}" class="page-link col-md-12 p-3 {{$group != "" ? "ps-5" : ""}} {{Route::current()->getName() == $route->getName() ? "active" : ""}}">
					<i class="fa-solid {{__('app.'.$route->getName().'.icon')}}"></i>
					{{__('app.'.$route->getName().'.title')}}
				</a>
			@endif
		@endforeach
		@if($group != "")
			</div></div>
		@endif
		{{-- <div class="page-link col-md-12 p-3" data-submenu="submenu-1">
			<i class="fa-solid fa-circle" style="color: transparent;"></i>
			Submenu
			<i class="menu-chevron fa-solid fa-chevron-down float-right"></i>
		</div>
		<div class="submenu col-md-12 ps-5 d-none" data-submenu="submenu-1">
			<div class="row">
				<a href="#" class="page-link col-md-12 p-3">
					<i class="fa-solid fa-circle-check"></i>
					Page 2
				</a>
				<a href="#" class="page-link col-md-12 p-3">
					<i class="fa-solid fa-circle-check"></i>
					Page 3
				</a>
			</div>
		</div> --}}
	</div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		// Init menu
		$(".menu").addClass("active");
		
		// Open menu
		$("#menu-button").on("click", function(){
			$(".menu-bg").removeClass("d-none");
			$(".menu").addClass("open");
		});
		
		// Close menu
		$("#menu-close, .menu-bg").on("click", function(){
			$(".menu-bg").addClass("d-none");
			$(".menu").removeClass("open");
		});
		
		// Submenu buttons
		$(".menu .page-link[data-submenu]").on("click", function(){
			let is_open = $(this).hasClass("open");
			let submenu = $(this).attr("data-submenu");
			
			$('.submenu[data-submenu="'+submenu+'"]').toggleClass("d-none", is_open);
			$(this).toggleClass("open", !is_open);
		});
	});
</script>