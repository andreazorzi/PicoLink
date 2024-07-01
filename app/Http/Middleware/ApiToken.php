<?php

namespace App\Http\Middleware;

use App\Models\Import;
use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token= request()->bearerToken();
        
        if(!is_null(config('auth.api.token')) && $token == config('auth.api.token')){
            return $next($request);
        }
        
        return abort(401, 'Unauthorized');
    }
}
