<?php

namespace Tests\Unit;

use App\Http\Middleware\RoleMiddleware;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Tests\TestCaseWithAcceptJson;

class RoleMiddlewareTest extends TestCaseWithAcceptJson
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_with_role_passes()
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin);

        $request = Request::create('/', 'GET');

        $middleware = new RoleMiddleware();

        $check = false;
        $middleware->handle($request, function ($req) use (&$check) {
            $check = true;
        }, Role::ADMIN);

        $this->assertTrue($check);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_without_role_dont_pass()
    {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer);

        $request = Request::create('/', 'GET');

        $middleware = new RoleMiddleware();

        $response = $middleware->handle($request, function () {
        }, Role::ADMIN);
        $this->assertEquals($response->getStatusCode(), 403);
    }
}
