<?php

use App\Models\User;

use function Pest\Laravel\postJson;

it('can register a new user and get a token', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'password' => 'testpassword123', // A senha do usuário
    ];

    $response = postJson(route('api.v1.user.store'), $userData);

    $response->assertStatus(200)
             ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
             ]);

    $token = $response->json('access_token');

    $this->assertNotNull($token);
});
