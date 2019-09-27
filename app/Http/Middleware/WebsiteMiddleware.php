<?php

namespace App\Http\Middleware;

use App\WebsiteTitle;
use Closure;
use View;
use App;

class WebsiteMiddleware
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
        // Get locale
        $locale = App::getLocale();

        // Get fixed titles
        $titles = [];
        $titles_db = WebsiteTitle::select([ 'slug', 'title_' . $locale . ' as title' ])->get()->toArray();
        foreach ($titles_db as $title_db) $titles[$title_db['slug']] = $title_db['title'];

        // Share variables
        View::share(compact('titles'));

        return $next($request);
    }
}
