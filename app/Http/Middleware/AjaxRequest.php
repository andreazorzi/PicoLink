<?php 

namespace App\Http\Middleware;

class AjaxRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if ( ! $request->ajax()){
			return response('Forbidden.', 403);
        }

        return $next($request);
    }
}