<?php

namespace App\Http\Controllers;

use App\Helpers\UnifiedJsonResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return UnifiedJsonResponse::error([], __('Unauthorized'), 400);
        }

        $user = Auth::user();


        return UnifiedJsonResponse::success([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], __('Logged in Successfully'));
    }

    public function register(RegisterRequest $request, UserRepository $userRepository)
    {
        $user = $userRepository->createCustomer($request->validated());

        $token = Auth::login($user);

        return UnifiedJsonResponse::success([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], __('User created successfully'));
    }

    public function logout()
    {
        Auth::logout();
        return UnifiedJsonResponse::success([], __('Successfully logged out'));
    }

    public function me()
    {
        return UnifiedJsonResponse::success(['user' => Auth::user()]);
    }

    public function refresh()
    {
        return UnifiedJsonResponse::success([
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
