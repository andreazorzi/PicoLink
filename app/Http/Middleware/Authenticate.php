<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        session([
            "website.redirect" => $request->url()
        ]);
        
        return $request->expectsJson() ? null : route('backoffice.index');
    }
    
    /**
     * This function overrides the authenticate methods of a user based on their current authentication status and
     * authorization groups.
     * 
     * @param request  is an instance of the Illuminate\Http\Request class, which represents an
     * incoming HTTP request. It contains information about the request such as the HTTP method,
     * headers, and parameters.
     * @param array guards The `` parameter is an array of authentication guards that should be
     * checked for authentication. If no guards are specified, it defaults to `[null]`, which means the
     * default guard will be used.
     * 
     * @return If the user is authenticated and belongs to the required authentication groups, the
     * function will return the guard that should be used. If the user is not authenticated or does not
     * belong to the required authentication groups, the function will call the `unauthenticated`
     * method.
     */
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }
        
        $current_user = User::current();
        
        $auth_groups = is_null($current_user) ? false : $current_user->checkAuthGroups();

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check() && $auth_groups) {
                return $this->auth->shouldUse($guard);
            }
        }
        
        $this->unauthenticated($request, $guards);
    }
}
