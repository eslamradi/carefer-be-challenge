<?php

namespace App\Http\Middleware;

use App\Helpers\UnifiedJsonResponse;
use App\Models\Session;
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

        $slotSession = $slot->sessions()->first();

        // if session on slot is active
        if ($slotSession && $slotSession->isValid()) {
            // check if session is started by user and not requesting another session
            if ($slotSession->user_id == auth()->user()->id && $request->is('api/session/start')) {
                return UnifiedJsonResponse::error([], __('You are allowed only one session at a time'), 403);
            }
            // check if session is started by user and working on the session
            elseif ($slotSession->user_id == auth()->user()->id) {
                return $next($request);
            }
            // session is started by other user, user not authorized
            else {
                return UnifiedJsonResponse::error([], __('Session is reserved'), 403);
            }
        }

        $userSession = auth()->user()->sessions()->latest()->first();

        // check if user requesting another session
        if ($userSession && $userSession->isValid()) {
            return UnifiedJsonResponse::error([], __('You are allowed only one session at a time'), 403);
        }

        // check if user is initialing a session
        if ($request->is('api/session/start')) {
            return $next($request);
        }

        // no session is intitialized
        return UnifiedJsonResponse::error([], __('No session initialized'), 403);
    }
}
