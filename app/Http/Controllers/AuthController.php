<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function userProfile() {
        return redirect(config('services.authentik.base_url').'/if/user/#/settings;%7B"page"%3A"page-details"%7D');
    }

    public function submit(Request $request){
        return Socialite::driver('authentik')->redirect();
    }

    public function callback(Request $request)
    {
        // Checking if user is authenticated or not
        try {
            $user = Socialite::driver('authentik')->user();
        } catch (Exception $ex) {
            return redirect()->route('backoffice.index');
        }

        User::updateOrCreate(
            [
                'username' => $user->nickname
            ],
            [
                'email' => $user->email,
                'name' => $user->name,
                'avatar' => $user->user["avatar"] ?? "https://ui-avatars.com/api/?background=random&name=".urlencode($user->name),
                'groups' => $user->groups,
                'type' => 'authentik',
            ]
        );

        Auth::loginUsingId($user->nickname, $remember = true);
        $request->session()->regenerate();
        return redirect()->route('backoffice.index');
    }

    public function logout(Request $request) {
        $request->session()->flush();
        Auth::logout();
        return redirect(config('services.authentik.base_url').'/if/session-end/'.config('services.authentik.slug'));
    }
}
