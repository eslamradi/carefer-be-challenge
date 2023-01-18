<?php

namespace Tests\Unit;

use App\Http\Middleware\ValidateBookingSession;
use App\Models\Session;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Tests\TestCaseWithAcceptJson;

class SessionMiddlewareTest extends TestCaseWithAcceptJson
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_no_session()
    {
        Session::truncate();
        
        $user = User::factory()->customer()->create();
        $slot = Slot::factory()->create();

        $this->actingAs($user);

        $request = Request::create('/api/order', 'POST', [
            'slot_id' => $slot->id
        ]);

        $middleware = new ValidateBookingSession();


        $response = $middleware->handle($request, function () {
        });

        $this->assertEquals($response->getStatusCode(), 403);

        $request = Request::create('/api/session/start', 'POST', [
            'slot_id' => $slot->id,
            'debug' => true
        ]);
        
        $check = false;
        $middleware->handle($request, function ($req) use (&$check) {
            $check = true;
        });

        $this->assertTrue($check);
    }

    public function test_no_slotId()
    {
        $user = User::factory()->customer()->create();
        $slot = Slot::factory()->create();

        $this->actingAs($user);

        $request = Request::create('/api/order', 'POST');

        $middleware = new ValidateBookingSession();

        $response = $middleware->handle($request, function () {
        });

        $this->assertEquals($response->getStatusCode(), 422);
    }

    public function test_session_is_reserved()
    {
        $user = User::factory()->customer()->create();
        $otherUser = User::factory()->customer()->create();
        $slot = Slot::factory()->create();

        $session = Session::factory()->create([
            'slot_id' => $slot->id,
            'user_id' => $user->id
        ]);

        $this->actingAs($otherUser);

        $request = Request::create('/api/order', 'POST', [
            'slot_id' => $slot->id
        ]);

        $middleware = new ValidateBookingSession();

        $response = $middleware->handle($request, function () {
        });

        $this->assertEquals($response->getStatusCode(), 403);
    }

    public function test_session_is_reserved_by_current_user()
    {
        $user = User::factory()->customer()->create();
        $slot = Slot::factory()->create();

        $session = Session::factory()->create([
            'slot_id' => $slot->id,
            'user_id' => $user->id
        ]);

        $this->actingAs($user);

        $request = Request::create('/api/order', 'POST', [
            'slot_id' => $slot->id
        ]);

        $middleware = new ValidateBookingSession();

        $check = false;
        $middleware->handle($request, function ($req) use (&$check) {
            $check = true;
        });

        $this->assertTrue($check);
    }
}
