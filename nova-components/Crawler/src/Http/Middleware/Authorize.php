<?php

namespace Acme\Crawler\Http\Middleware;

use Acme\Crawler\Crawler;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(Crawler::class)->authorize($request) ? $next($request) : abort(403);
    }
}
