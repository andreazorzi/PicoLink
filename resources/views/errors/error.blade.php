@php
	$lang = request()->segment(1);

	if((strlen($lang) === 2 && in_array($lang, config('routes.languages')))){
		app()->setLocale($lang);
	}
	else if(isset($request->language)){
		app()->setLocale($request->language);
	}
@endphp
<!DOCTYPE html>
<html lang="{{$lang}}">
	<x-head title="{{$error_code}}"/>
    
    <body class="container-fluid">
        {{-- Header --}}
        <header class="row fixed-top pt-2 pb-4 px-2" style="background: linear-gradient(180deg, rgba(0, 0, 0, 0.5) 0%, rgba(255, 255, 255, 0) 100%); color: #ffffff; border-bottom: 0px solid #d3d3d3;">
			<div class="col py-2 px-3 align-self-center">
				<a href="{{url("")}}" class="text-body fw-bold text-decoration-none">
					<img src="{{asset("images/logo.png")}}" class="logo me-2">
				</a>
			</div>
		</header>
        
        <div class="row">
			<div class="col-md-12 p-0 vh-100" style="position: relative;">
				<img src="{{ $src ?? asset("images/error.webp") }}" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.5);">
				<div class="centered text-center">
					<h1>{{ $error_code }}</h1>
					<span class="fs-2">{{$error_message ?? __('http-statuses.'.$error_code)}}</span>
					<br>
					@if ($error_code != 503)
						<a href="/" class="btn fs-5 btn-outline-info btn-lg mt-3">
							{{ __('http-statuses.sail-button')}}
						</a>
					@else
						<h5 class="mt-3">{{__("app.pages.maintenance.subtitle")}}</h5>
					@endif
				</div>
			</div>
		</div>
		
        <style>
			.centered {
				backdrop-filter: 
				font-family: math;
				color: white;
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				
				& h1 {
					font-size: 5em;
				}
			}
			
			#dracones{
				object-fit: cover;
				filter: brightness(0.5);
			}
		</style>
        
        {{-- Menu --}}
        
        @vite(['resources/js/app.js', 'resources/js/main.js'])
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                
            });
        </script>
    </body>
</html>