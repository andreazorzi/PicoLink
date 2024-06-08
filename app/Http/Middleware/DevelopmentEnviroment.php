<?php

namespace App\Http\Middleware;

use Closure;

class DevelopmentEnviroment
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!app()->environment('local')) {
            return abort(403, 'You are not authorized to access this');
        }

        return $next($request);
    }
}