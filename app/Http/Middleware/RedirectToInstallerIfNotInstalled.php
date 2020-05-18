<?php

namespace App\Http\Middleware;

use Closure;

class RedirectToInstallerIfNotInstalled
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
        // ! config('app.installed') && ! $request->is('install/*')
        if (! \Schema::hasTable('migrations') && ! $request->is('install/*')) {
            return redirect('install/pre-installation');
        }

        return $next($request);
    }
}
