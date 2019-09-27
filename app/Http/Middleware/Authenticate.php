<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use View;
use Auth;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if ($guards[0] == 'admin') {
            $admin = Auth::guard('admin')->user();
            if ($guards[0] == 'admin' && !$admin) return redirect(route('admin-login'));
            View::share(compact('admin'));
        }


        return $next($request);
    }
}
