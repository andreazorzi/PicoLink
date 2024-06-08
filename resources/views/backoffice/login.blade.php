<!DOCTYPE html>
<html lang="en">
    
	<x-backoffice.head></x-backoffice.head>

    <body class="container-fluid login-container vh-100" hx-headers='{"X-CSRF-TOKEN": "{{csrf_token()}}"}' hx-ext="ajax-header">
		<header class="row sticky-top">
			@if (app()->isDownForMaintenance())
				<x-maintenance-banner />
			@endif
		</header>
        <div class="row h-100 justify-content-center">
			<div class="col-md-12 align-self-center text-center" style="max-width: 450px;">
				<div class="col-md-12 p-4 align-self-center text-center" style="margin-top: -100px;">
					<img class="w-75" src="{{asset("images/logo.png")}}">
				</div>
				<div class="col-md-12 align-self-center text-center">
					<div class="card">
						<div class="card-body p3">
							<h2 class="mb-4">Login</h2>
							<form action="{{route("web-auth.login")}}" method="post">
								<input type="text" id="username" name="username" class="form-control mb-3" placeholder="Username" value="{{request()->old("username")}}">
								<input type="password" id="password" name="password" class="form-control mb-3" placeholder="Password">
								
								@csrf
								
								<button class="btn btn-primary w-100">Accedi</button>
								
								{{-- @if(!empty(request()->old("username")))
									<div class="mt-3">
										<u role="button" hx-post="{{route("user.send-reset-password-user", [request()->old("username")])}}">
											Hai dimenticato la password?
										</u>
									</div>
								@endif --}}
							</form>
							@if (!is_null(config("services.authentik.base_url")))
								<hr class="my-4">
								<a href="{{route("auth.login")}}" class="btn btn-authentik w-100">
									<img src="{{asset("images/authentik.png")}}" class="align-middle" style="height: 15px;">
									<span class="align-middle">Authentik</span>
								</a>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
        
        {{-- x-script loads resources/js/app.js and can run script at page load --}}
        <x-backoffice.script></x-backoffice.script>
		
		<div id="request-response"></div>
    </body>
</html>
