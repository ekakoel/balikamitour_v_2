<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogRouteUsage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->getName() ?? 'undefined';
        $routePath = $request->path();
        // \Log::info('Route accessed: ' . $request->route()->getName());
        \Log::channel('route_usage')->info("Route accessed: {$routeName} ({$routePath})");
        return $next($request);
    }
}
