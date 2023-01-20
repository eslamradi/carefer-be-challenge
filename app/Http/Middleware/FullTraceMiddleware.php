<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FullTraceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $correlationId = Str::uuid();

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/requests.log'),
        ])->info("request: {$correlationId}", [
            'request' => $request,
        ]);

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/responses.log'),
        ])->info("response: {$correlationId}", [
            'response' => $response,
        ]);

        return $response;
    }
}
