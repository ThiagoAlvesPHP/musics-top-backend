<?php

use App\Models\User;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('can log in with valid credentials', function () {
    $user = User::factory()->create();
    
    $credentials = [
        'email' => $user->email,
        'password' => 'password',
    ];

    postJson(route('api.v1.auth.login'), $credentials)
        ->assertStatus(200);
});

it('cannot log in with invalid credentials', function () {
    $invalidCredentials = [
        'email' => 'invalid@example.com',
        'password' => 'wrong-password',
    ];

    postJson(route('api.v1.auth.login'), $invalidCredentials)
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => [
                'email'
            ],
        ]);
});

it('unable to login incorrect password credential', function () {
    $user = User::factory()->create();

    $invalidCredentials = [
        'email' => $user->email,
        'password' => 'wrong-password',
    ];

    postJson(route('api.v1.auth.login'), $invalidCredentials)
        ->assertStatus(401)
        ->assertJsonStructure([
            'error'
        ]);
});

it('can log in with valid credentials and get token', function () {
    $user = User::factory()->create();

    $credentials = [
        'email' => $user->email,
        'password' => 'password',
    ];

    $response = postJson(route('api.v1.auth.login'), $credentials)
        ->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ]);
    
    $token = $response->json('access_token');
    
    $response = postJson(route('api.v1.auth.me'), [], [
        'Content-Type' => 'application/json',
        'Authorization' => "Bearer $token"
    ])
        ->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'name',
            'email',
            'email_verified_at',
            'is_admin',
            'created_at',
            'updated_at',
        ]);
});
