<?php

namespace App\Http\Middleware;

use App\Helpers\UnifiedJsonResponse;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (auth()->check()) {
            if (auth()->user()->hasRole($role)) {
                return $next($request);
            }
        }
        return UnifiedJsonResponse::error([], __('Unauthorized'), 403);
    }
}
