<?php

namespace App\Http\Controllers;

use App\Models\Utente;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class WebAuthController extends Controller
{

	// Authenticate user
	public function authenticate(Request $request)
	{
		$credentials = $request->validate([
			'username' => ['required'],
			'password' => ['required'],
		]);

		// Custom attempt to verify if user login is succesfull
        // Works only with lovewrcase password fieldname
		if (Auth::attempt(['username' => $credentials["username"], 'password' => $credentials["password"]])) {
			$request->session()->regenerate();

			return redirect()->route('backoffice.index');
		}

		// Else return error
		return back()->withErrors(['login-form' => 'Utente o password non corretti.'])->onlyInput('username');
	}

	// Logout
	public function logout(){
		Session::flush();
		Auth::logout();

		return redirect()->route('backoffice.index');
	}
	
	// Reset password page
	public function reset_password(Request $request, PasswordReset $reset_link){
		return view("backoffice.reset-password", ["reset_link" => $reset_link, "error" => !$reset_link->isValid()]);
	}
}