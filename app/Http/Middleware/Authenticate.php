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
            if (!$admin) return redirect()->guest(route('admin-login'));
            session(compact('admin'));
        }


        return $next($request);
    }
}
