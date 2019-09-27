<?php

namespace App\Http\Middleware;

use Closure;
use App;
use View;

class LocaleMiddleware
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
        // Get locale from URL and check if it is allowed
        $allowed_locales = ['en', 'ar'];
        $locale = substr(request()->path(), 0, 2);
        if (!in_array($locale, $allowed_locales)) abort(404);
        App::setLocale($locale);

        View::share(compact(
            'locale'
        ));

        return $next($request);
    }
}
