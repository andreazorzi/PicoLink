@php
	use App\Models\User;
@endphp
<header class="row sticky-top">
	@if (app()->isDownForMaintenance())
		<x-maintenance-banner />
	@endif
	<div class="col-auto py-2 px-3 align-self-center">
		<i id="menu-button" class="fa-solid fa-bars fs-4"></i>
	</div>
	<div class="col py-2 px-1 align-self-center">
		<a href="{{route("backoffice.index")}}" class="text-body fw-bold text-decoration-none">
			<img src="{{asset("images/favicon.png")}}" class="logo me-2">
			<h2 class="d-inline-block align-middle m-0 fw-bold">{{config("app.name")}}</h2>
		</a>
	</div>
	<div id="user-box" class="col-auto p-3 align-self-center position-relative">
		<h5 class="d-inline-block align-middle m-0 fs-6">{{User::current()->name}}</h5>
		<img src="https://ui-avatars.com/api/?background=random&name={{Str::slug(User::current()->name ?? "")}}" class="avatar ms-2">
		
		<div id="user-actions">
			<div class="row">
				<a href="{{route("web-auth.logout")}}" class="page-link col-md-12 p-3">
					<i class="fa-solid fa-arrow-right-from-bracket"></i>
					Logout
				</a>
			</div>
		</div>
	</div>
</header>