<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfNotHaveApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Required Api Key
        if(!$request->api_key || $request->api_key != '$2y10oru771fc5qgV6M0EI8e46uKN5qdjRgh6qz91jd') {
            return response()->json(['errors'=>'Invalid Api Key.']);
        }
        return $next($request);
    }
}
