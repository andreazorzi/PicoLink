@php
	$error ??= false;
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Reset Password - {{config("app.name")}}</title>
        
        <!-- Icons -->
        <link rel="icon" type="image/png" href="{{asset("images/favicon.png")}}" />

        <!-- Meta -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- CSS -->
        @vite(['resources/css/app.css', 'resources/scss/theme.scss'])
    </head>
    <body class="container login-container vh-100" hx-headers='{"X-CSRF-TOKEN": "{{csrf_token()}}"}' hx-ext="ajax-header" hx-vals='{"language": "{{App::getLocale()}}"}'>
        <div class="row h-100 justify-content-center">
			<div class="col-md-5 align-self-center text-center">
				<div class="col-md-12 p-4 align-self-center text-center" style="margin-top: -100px;">
					<img class="w-75" src="{{asset("images/logo.png")}}">
				</div>
				<div class="col-md-12 align-self-center text-center">
					<div class="card">
						<div class="card-body p3">
							<h2 class="mb-4">Reset Password</h2>
							@if (!$error)
								<form>
									<input type="password" id="current_password" name="current_password" class="form-control mb-3" placeholder="Password">
									<input type="password" id="password" name="password" class="form-control mb-3" placeholder="Password">
									<input type="password" id="password_confirmation" name="password_confirmation" class="form-control mb-3" placeholder="Conferma Password">
									
									<input type="hidden" name="token" value="{{request()->token}}">
									
									<button class="btn btn-primary w-100" hx-put="{{route("user.change-password")}}" hx-target="#request-result">
										Aggiorna
									</button>
									
									<table id="password-checks" class="mt-3 text-start px-3 fs-7">
										<tr class="text-danger">
											<td class="ps-3 pe-2"><i class="fa-solid fa-x"></i></td>
											<td>Minimo 8 caratteri</td>
										</tr>
										<tr class="text-danger">
											<td class="ps-3 pe-2"><i class="fa-solid fa-x"></i></td>
											<td>Almeno un carattere MAIUSCOLO e uno minuscolo</td>
										</tr>
										<tr class="text-danger">
											<td class="ps-3 pe-2"><i class="fa-solid fa-x"></i></td>
											<td>Almeno un numero</td>
										</tr>
										<tr class="text-danger">
											<td class="ps-3 pe-2"><i class="fa-solid fa-x"></i></td>
											<td>Almeno un simbolo (@, $, !, %, *, #, ?, &)</td>
										</tr>
										<tr class="text-danger">
											<td class="ps-3 pe-2"><i class="fa-solid fa-x"></i></td>
											<td>Le due password devono coincidere</td>
										</tr>
									</table>
								</form>
							@else
								<span class="text-danger">
									Link scaduto o non valido
								</span>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div id="request-result"></div>
        
        @vite(['resources/js/app.js'])
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                $("#password, #password_confirmation").on("keyup", function(){
					let password = $("#password").val();
					let password_confirmation = $("#password_confirmation").val();
					
					let result = ``;
					
					result += checkRule((password.length > 8), "Minimo 8 caratteri");
					result += checkRule(password.search(/[A-Z]/) != -1, "Almeno un carattere MAIUSCOLO e uno minuscolo");
					result += checkRule(password.search(/[0-9]/) != -1, "Almeno un numero");
					result += checkRule(password.search(/\p{Z}|\p{S}|\p{P}/u) != -1, "Almeno un simbolo (@, $, !, %, *, #, ?, &)");
					result += checkRule(password == password_confirmation && password.length > 0, "Le due password devono coincidere");
					
					$("#password-checks").html(result);
				});
            });
			
			function checkRule(check, text){
				let response =  `
					<tr class="text-`+(check ? "success" : "danger")+`">
						<td class="ps-3 pe-2"><i class="fa-solid fa-`+(check ? "check" : "x")+`"></i></td>
						<td>`+text+`</td>
					</tr>
				`;
				
				return response;
			}
        </script>
    </body>
</html>