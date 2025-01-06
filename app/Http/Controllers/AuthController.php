<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginPostRequest;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthTrait;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login']]);
    // }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginPostRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Senha incorreta!'], 401);
        }

        return AuthTrait::respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::user()->with('musics')->first());
    }
}
