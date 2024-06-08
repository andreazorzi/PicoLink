<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;

class RouteController extends Controller
{
    // Index
    public function index(Request $request, User $user){
        if(Auth::check() && $user->checkAuthGroups()){
            if(!is_null(session("website.redirect"))){
                $redirect = session("website.redirect");
                session()->forget("website.redirect");
                return redirect($redirect);
            }
            
            return view('backoffice.index');
        }
        
        Auth::logout();
        
        return view('backoffice.login');
    }
    
    public static function translated_routes(){
        foreach (config('routes.languages') ?? [] as $language) {
            $lang_prefix = config('app.locale') != $language ? "{$language}/" : '';
            
            foreach(config('routes.pages') as $page){
                $slug = $page == "index" ? "" : Str::slug(Lang::get('app.pages.'.$page.'.meta_title', locale: $language));
                Route::view($lang_prefix.$slug, $page, ['page' => $page], headers: ["page" => $page])->name("{$page}.{$language}");
            }
        }
    }
}
