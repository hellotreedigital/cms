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
        $response = $next($request);

        try {
            HttpLog::create([
                'ip' => request()->ip(),
                'method' => request()->method(),
                'url' => request()->url(),
                'headers' => json_encode(request()->header()),
                'request' => json_encode(request()->toArray()),
                'response' => $response->getContent(),
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $response;
    }
}
