<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class Init
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        
        if(is_null(Cache::get("migrate"))){
            Artisan::call('migrate --force');
            Cache::forever("migrate", true);
        }
        
        // check if user admin exists
        if (!is_null(config("auth.admin.username")) && is_null(User::find(config("auth.admin.username")))) {
            Artisan::call('db:seed --force');
        }
        
        return $next($request);
    }
}