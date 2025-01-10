<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Resources\UserResource;
use App\Models\User;

use function Pest\Livewire\livewire;

// Para rodar o grupo no terminal:
// vendor/bin/pest --group=users
uses(RefreshDatabase::class)->group('users');

it('can list users', function () {
    User::factory()->count(10)->create();
    $list = User::query()->limit(10)->get();

    livewire(UserResource\Pages\ListUsers::class)
        ->assertCanSeeTableRecords($list);
});

//criando
it('can create users', function () {
    $newData = [
        'name' => fake()->name().' - Create',
        'email' => fake()->email(),
        'password' => '123456789',
    ];

    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm($newData)
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, \Illuminate\Support\Arr::only($newData, ['name', 'email']));
});

//Preenchendo dados existentes
it('can retrieve data users', function () {
    $find = User::factory()->create();

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $find->getRouteKey(),
    ])
        ->assertFormSet([
            'name' => $find->name,
            'email' => $find->email,
        ]);
});

//Salvando
it('can edit save users', function () {
    $find = User::factory()->create();

    $newData = [
        'name' => fake()->name(),
        'email' => fake()->email(),
        'password' => fake()->password(8, 12), // Gera uma senha válida
    ];

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $find->getRouteKey(),
    ])
        ->fillForm($newData)
        ->call('save')
        ->assertHasNoFormErrors();

    // Verifique os dados atualizados no banco de dados
    $updatedUser = $find->refresh();

    expect($updatedUser)
        ->name->toBe($newData['name'])
        ->email->toBe($newData['email']);

    // Verifique se a senha foi corretamente criptografada
    expect(\Illuminate\Support\Facades\Hash::check($newData['password'], $updatedUser->password))->toBeTrue();
});


//validando inputs
it('can validate input users', function () {
    $find = User::factory()->create();

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $find->getRouteKey(),
    ])
        ->fillForm([
            'name' => null,
            'email' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required', 'email' => 'required']);
});

//deletando
it('can delete users', function () {
    $find = User::factory()->create();

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $find->getRouteKey(),
    ])
        ->callAction(\Filament\Actions\DeleteAction::class);

    $this->assertModelMissing($find);
});
