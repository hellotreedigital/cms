<?php

namespace Hellotreedigital\Cms\Middlewares;

use Hellotreedigital\Cms\Models\HttpLog;
use Closure;

class HttpLogsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        HttpLog::create([
            'ip' => request()->ip(),
            'request' => json_encode(request()->toArray()),
        ]);

        return $next($request);
    }
}
