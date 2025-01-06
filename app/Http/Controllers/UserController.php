<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StorePostRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthTrait;

class UserController extends Controller
{
    /**
     * Register new user.
     */
    public function store(StorePostRequest $request)
    {
        $user = $request->only('name', 'email', 'password');
        $user = User::create($user);

        $token = Auth::attempt($request->only('email', 'password'));

        return AuthTrait::respondWithToken($token);
    }
}
