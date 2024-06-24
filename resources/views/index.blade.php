@php
	$lang = App::getLocale();
@endphp
<!DOCTYPE html>
<html lang="{{$lang}}">
	<x-head :title="config('app.name')"/>
    
    <body class="container-fluid">
        {{-- Header --}}
        {{-- <header class="row fixed-top pt-2 pb-4 px-2" style="background: linear-gradient(180deg, rgba(0, 0, 0, 0.5) 0%, rgba(255, 255, 255, 0) 100%); color: #ffffff; border-bottom: 0px solid #d3d3d3;">
			<div class="col py-2 px-3 align-self-center">
				<a href="{{url("")}}" class="text-body fw-bold text-decoration-none">
					
				</a>
			</div>
		</header> --}}
        
        <div class="row">
			<div class="col-md-12 p-0 vh-100" style="position: relative;">
				{{-- <img src="{{ $src ?? asset("images/error.webp") }}" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.5);"> --}}
				<div class="centered text-center card">
					<div class="card-body">
						<img src="{{asset("images/logo.png")}}" class="logo mb-3" style="max-height: 100px;"><br>
						<span class="fs-2" style="color: #178bd0; font-family: cursive;">Where every link is possible!</span><br>
						<a class="btn btn-dark bg-black mt-5" href="https://github.com/andreazorzi/PicoLink" target="_blank">
							<i class="fa-brands fa-github me-2"></i>
							View on GitHub
						</a>
					</div>
				</div>
			</div>
		</div>
		
        <style>
			.centered {
				/* backdrop-filter:  */
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
				/* object-fit: cover; */
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