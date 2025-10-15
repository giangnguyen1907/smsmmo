<?php

namespace App\Http\Middleware;

use App\Http\Services\AdminService;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CheckPermissionAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Admin user is super admin 
        if (Auth::user()->is_super_admin) {
            return $next($request);
        }

        // Admin user has permission with current route
        if (in_array(Route::getCurrentRoute()->getName(), AdminService::getPermisionAccess()->function_code)) {
            return $next($request);
        }

        // Return route dashboard when permission denied
        return redirect()->route('dashboard')->with('errorMessage', __('Permission denied!'));
    }
}
