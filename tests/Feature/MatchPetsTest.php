<?php

use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('match page returns 404 for invalid mode', function () {
    $this->get('/match?mode=invalid')->assertNotFound();
});

test('lost mode shows found unresolved pets only', function () {
    Pet::factory()->create([
        'type' => 'found',
        'city' => 'تهران',
        'is_resolved' => false,
        'title' => 'Found One',
    ]);

    Pet::factory()->create([
        'type' => 'lost',
        'city' => 'تهران',
        'is_resolved' => false,
        'title' => 'Lost One',
    ]);

    Pet::factory()->create([
        'type' => 'found',
        'city' => 'تهران',
        'is_resolved' => true,
        'title' => 'Resolved Found',
    ]);

    $response = $this->get('/match?mode=lost');

    $response->assertOk();
    $response->assertSee('Found One');
    $response->assertDontSee('Lost One');
    $response->assertDontSee('Resolved Found');
});

test('found mode shows lost unresolved pets only', function () {
    Pet::factory()->create([
        'type' => 'lost',
        'city' => 'تهران',
        'is_resolved' => false,
        'title' => 'Lost Visible',
    ]);

    Pet::factory()->create([
        'type' => 'found',
        'city' => 'تهران',
        'is_resolved' => false,
        'title' => 'Found Hidden',
    ]);

    $response = $this->get('/match?mode=found');

    $response->assertOk();
    $response->assertSee('Lost Visible');
    $response->assertDontSee('Found Hidden');
});

test('within radius scope keeps pets in 5km and excludes far pets', function () {
    Pet::factory()->create([
        'type' => 'found',
        'is_resolved' => false,
        'city' => 'تهران',
        'title' => 'Near Pet',
        'latitude' => 35.6892,
        'longitude' => 51.3890,
    ]);

    Pet::factory()->create([
        'type' => 'found',
        'is_resolved' => false,
        'city' => 'تهران',
        'title' => 'Far Pet',
        'latitude' => 35.8000,
        'longitude' => 51.3890,
    ]);

    $titles = Pet::query()
        ->active()
        ->ofType('found')
        ->withinRadiusKm(35.6892, 51.3890, 5)
        ->pluck('title')
        ->all();

    expect($titles)->toContain('Near Pet');
    expect($titles)->not->toContain('Far Pet');
});

test('city fallback filter works with unresolved target type', function () {
    Pet::factory()->create([
        'type' => 'lost',
        'is_resolved' => false,
        'city' => 'شیراز',
        'title' => 'Shiraz Lost',
    ]);

    Pet::factory()->create([
        'type' => 'lost',
        'is_resolved' => false,
        'city' => 'تهران',
        'title' => 'Tehran Lost',
    ]);

    $titles = Pet::query()
        ->active()
        ->ofType('lost')
        ->inCity('شیراز')
        ->pluck('title')
        ->all();

    expect($titles)->toContain('Shiraz Lost');
    expect($titles)->not->toContain('Tehran Lost');
});
