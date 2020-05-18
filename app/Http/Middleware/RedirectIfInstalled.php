<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfInstalled
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
        // config('app.installed')
        if (\Schema::hasTable('migrations')) {
            return redirect()->to('/');
        }

        return $next($request);
    }
}
