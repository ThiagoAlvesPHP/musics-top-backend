<?php

use App\Enum\Music\StatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Resources\MusicResource;
use App\Models\Music;
use App\Models\User;

use function Pest\Livewire\livewire;

const URL = "https://www.youtube.com/watch?v=";

// Para rodar o grupo no terminal:
// vendor/bin/pest --group=musics
uses()->group('musics');

it('can list musics', function () {
    Music::factory()->count(10)->create();
    $list = Music::query()->limit(10)->get();

    livewire(MusicResource\Pages\ListMusic::class)
        ->assertCanSeeTableRecords($list);
});

//criando
it('can create musics', function () {
    $newData = [
        'user_id' => User::factory()->create()->id,
        'thumb' => fake()->name(),
        'title' => fake()->email(),
        'url' => URL.'Zc-FiEEhCo4&t=2735s',
        'count_views' => rand(1000, 9999999),
        'youtube_id' => rand(1000, 9999999),
        'status' => StatusEnum::PENDING->value,
    ];

    livewire(MusicResource\Pages\CreateMusic::class)
        ->fillForm($newData)
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Music::class, \Illuminate\Support\Arr::only($newData, ['name', 'email']));
});

//Preenchendo dados existentes
it('can retrieve data musics', function () {
    $find = Music::factory()->create();

    livewire(MusicResource\Pages\EditMusic::class, [
        'record' => $find->getRouteKey(),
    ])
        ->assertFormSet([
            'thumb' => $find->thumb,
            'title' => $find->title,
            'count_views' => $find->count_views,
            'status' => $find->status->value,
        ]);
});

//Salvando
it('can edit save musics', function () {
    $find = Music::factory()->create();

    $newData = [
        'thumb' => fake()->name(),
        'title' => fake()->email(),
        'count_views' => rand(1000, 9999999),
        'youtube_id' => rand(1000, 9999999),
        'status' => StatusEnum::PENDING->value,
    ];

    livewire(MusicResource\Pages\EditMusic::class, [
        'record' => $find->getRouteKey(),
    ])
        ->fillForm($newData)
        ->call('save')
        ->assertHasNoFormErrors();

    expect($find->refresh());
});

//validando inputs
it('can validate input musics', function () {
    $find = Music::factory()->create();

    livewire(MusicResource\Pages\EditMusic::class, [
        'record' => $find->getRouteKey(),
    ])
        ->fillForm([
            "user_id" => User::factory()->create()->id,
            "title" => null,
            "count_views" => rand(1000, 9999999),
            "youtube_id" => rand(1000, 9999999),
            "thumb" => "Mozell Krajcik",
            "status" => StatusEnum::PENDING->value
        ])
        ->call('save')
        ->assertHasFormErrors([
            'user_id' => 'required',
            'title' => 'required',
            'count_views' => 'required',
            'thumb' => 'required',
            'status' => 'required',
        ]);
})->todo();

//deletando
it('can delete musics', function () {
    $find = Music::factory()->create();

    livewire(MusicResource\Pages\EditMusic::class, [
        'record' => $find->getRouteKey(),
    ])
        ->callAction(\Filament\Actions\DeleteAction::class);

    $this->assertModelMissing($find);
});

