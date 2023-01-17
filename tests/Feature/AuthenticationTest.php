<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\TestCaseWithAcceptJson;

class AuthenticationTest extends TestCaseWithAcceptJson
{
    use DatabaseTransactions;

    public function test_register_validation()
    {
        $response = $this->post('/api/register', [
            'name' => '',
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('errors.password')
                ->has('errors.email')
                ->has('errors.name')
                ->etc()
        );

        $response = $this->post('/api/register', [
            'name' => 'user',
            'email' => 'user-fake-kjk@mail.com',
            'password' => '1234',
        ]);

        $response->assertStatus(422);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('errors.password')->etc()
        );
    }

    public function test_register_successful()
    {
        $response = $this->post('/api/register', [
            'name' => 'user',
            'email' => 'user-fake-kjk@mail.com',
            'password' => '123456',
        ]);

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data.authorization.token')
                ->has('data.user')
                ->etc()
            // check user is registered as a customer
        )->assertJsonPath('data.user.role', Role::CUSTOMER);
    }

    public function test_login_successful()
    {
        $email = 'testuserlogin@mail.com';
        $password = 'password';

        $user = User::factory()->create([
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        $response = $this->post('/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data.authorization.token')->etc()
        );
    }

    public function test_login_fail()
    {
        $email = 'testuserlogin@mail.com';
        $password = 'correctpassword';

        $user = User::factory()->create([
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        $response = $this->post('/api/login', [
            'email' => $email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(400);


        $email = 'correct_mail@mail.com';
        $password = 'correctpassword';

        $user = User::factory()->create([
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        $response = $this->post('/api/login', [
            'email' => 'wrong_mail@mail.com',
            'password' => $password,
        ]);

        $response->assertStatus(400);
    }

    public function test_logout()
    {
        $user = User::factory()->create();
        $token = Auth::login($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->post('/api/logout');

        $response->assertStatus(200);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->get('/api/me');

        $response->assertStatus(401);
    }

    public function test_refresh_token()
    {
        $user = User::factory()->create();
        $token = Auth::login($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->post('/api/refresh');

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data.authorization.token')->has('data.user')->etc()
        );

        $responseContent = $response->json();
        $refreshToken = $responseContent['data']['authorization']['token'];

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$refreshToken}"
        ])->get('/api/me');

        $response->assertStatus(200);
    }
}
