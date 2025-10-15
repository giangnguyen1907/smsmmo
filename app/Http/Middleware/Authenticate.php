<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {

            if ($request->is('admin') || $request->is('admin/*') || $request->is('filemanager/*') || $request->is('filemanager')) {
                return route('admin.login');
            }
            if ($request->is('user') || $request->is('user/*')) {
                return route('frontend.login');
            }
            return route('frontend.login');
        }
    }
}
