<?php

use App\Models\Music;
use App\Models\User;
use App\Traits\MusicTrait;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

// https://www.youtube.com/watch?v=lpGGNA6_920
beforeEach(function () {
    $this->mock(MusicTrait::class, function ($mock) {
        $mock->shouldReceive('extractVideoId')
            ->andReturn('lpGGNA6_920'); // Retorno fictício para o ID do vídeo

        $mock->shouldReceive('getVideoInfo')
            ->andReturn([
                'title' => 'Tiao Carreiro e Pardinho-Pagode em Brasilia',
                'count_views' => 12345,
                'youtube_id' => 'lpGGNA6_920',
                'thumb' => 'https://img.youtube.com/vi/lpGGNA6_920/hqdefault.jpg',
            ]); // Retorno fictício para as informações do vídeo
    });
});

it('can list music', function () {
    Music::factory()->count(10)->create();

    getJson('/api/v1/music')
        ->assertStatus(200);
});

it('can search music by title', function () {
    Music::factory()->create(['title' => 'My Favorite Song']);
    Music::factory()->create(['title' => 'Another Song']);

    getJson(route('api.v1.music.index', ['search' => 'Favorite']))
        ->assertStatus(200)
        ->assertJsonFragment(['title' => 'My Favorite Song']);
});

it('can retrieve a specific music by id', function () {
    $music = Music::factory()->create();

    getJson(route('api.v1.music.show', ['id' => $music->id]))
        ->assertStatus(200)
        ->assertJson([
            'id' => $music->id,
            'title' => $music->title,
        ]);
});

it('returns 404 if the music does not exist', function () {
    getJson(route('api.v1.music.show', ['id' => '999']))
        ->assertStatus(404)
        ->assertJson(['message' => 'Música não encontrada!']);
});

it('can create a new music with valid data', function () {
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

    $url = "https://www.youtube.com/watch?v=lpGGNA6_920";
    postJson(route('api.v1.music.store'), ['url' => $url], [
        'Authorization' => "Bearer {$token}"
    ])
        ->assertStatus(201)
        ->assertJson([
            'title' => 'Tiao Carreiro e Pardinho-Pagode em Brasilia',
            'youtube_id' => 'lpGGNA6_920',
            'thumb' => 'https://img.youtube.com/vi/lpGGNA6_920/hqdefault.jpg',
        ]);
});

it('returns an error for an invalid youtube url', function () {
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

    postJson(route('api.v1.music.store'), ['url' => 'https://invalid-url.com'], [
        'Authorization' => "Bearer {$token}"
    ])
        ->assertStatus(500)
        ->assertJson(['message' => 'URL do Youtube inválida!']);
});
