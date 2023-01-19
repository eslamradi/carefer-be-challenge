<?php

namespace App\Http\Middleware;

use App\Helpers\UnifiedJsonResponse;
use App\Models\Slot;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class ValidateBookingSession
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
        $slotId = $request->input('slot_id');

        if (! $slotId) {
            return UnifiedJsonResponse::error([
                'slot_id' => __('slot_id is required')
            ], __('Validation Error'), 422);
        }

        $slot = Slot::findOrFail($slotId);

        $session = $slot->session()->latest()->first();

        if ($session && $session->isValid()) {
            if ($session->user_id == auth()->user()->id && ! $request->is('api/session/start')) {
                return $next($request);
            } else {
                return UnifiedJsonResponse::error([], __('Slot is not available for reservation'), 403);
            }
        }
        if ($request->is('api/session/start')) {
            return $next($request);
        } else {
            return UnifiedJsonResponse::error([], __('Session is not initialized'), 403);
        }
    }
}
