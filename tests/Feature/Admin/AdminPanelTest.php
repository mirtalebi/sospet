<?php

use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('guests are redirected away from admin pages', function () {
    $this->get(route('admin.dashboard'))->assertRedirect();
});

test('non-admin users receive 403', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.users'))
        ->assertForbidden();
});

test('admins can open admin pages', function (string $routeName) {
    $this->actingAs(User::factory()->admin()->create())
        ->get(route($routeName))
        ->assertOk();
})->with(['admin.dashboard', 'admin.users', 'admin.pets']);

test('admin can promote and delete other users', function () {
    $admin = User::factory()->admin()->create();
    $target = User::factory()->create();

    $this->actingAs($admin);

    Livewire::test('pages::admin.users')
        ->call('toggleAdmin', $target->id);

    expect($target->fresh()->isAdmin())->toBeTrue();

    Livewire::test('pages::admin.users')
        ->call('deleteUser', $target->id);

    expect($target->fresh())->toBeNull();
});

test('admin cannot demote or delete themselves', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin);

    Livewire::test('pages::admin.users')
        ->call('toggleAdmin', $admin->id)
        ->call('deleteUser', $admin->id);

    expect($admin->fresh())->not->toBeNull()
        ->and($admin->fresh()->isAdmin())->toBeTrue();
});

test('admin can resolve, hide and delete reports', function () {
    $this->actingAs(User::factory()->admin()->create());
    $pet = Pet::factory()->create(['user_id' => User::factory()->create()->id]);

    Livewire::test('pages::admin.pets')
        ->call('toggleResolved', $pet->id)
        ->call('toggleHidden', $pet->id);

    expect($pet->fresh()->is_resolved)->toBeTruthy()
        ->and($pet->fresh()->is_hidden)->toBeTruthy();

    Livewire::test('pages::admin.pets')->call('deletePet', $pet->id);

    expect($pet->fresh())->toBeNull();
});

test('hidden reports are not shown publicly', function () {
    Pet::factory()->create([
        'user_id' => User::factory()->create()->id,
        'type' => 'found',
        'city' => 'تهران',
        'is_resolved' => false,
        'is_hidden' => true,
        'title' => 'Hidden Pet',
    ]);

    $this->get('/match?mode=lost')->assertOk()->assertDontSee('Hidden Pet');
});
