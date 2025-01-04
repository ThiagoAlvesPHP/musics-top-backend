<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StorePostRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Register new user.
     */
    public function store(StorePostRequest $request)
    {
        $user = $request->only('name', 'email', 'password');
        $user = User::create($user);

        return $user;
    }
}
