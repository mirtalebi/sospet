<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('login page redirects to the OTP modal on the home page', function () {
    $this->get(route('login'))->assertRedirect(route('home', ['login' => 1]));
});

test('guests hitting a protected page are sent to the OTP modal', function () {
    $this->get('/profile')->assertRedirect(route('home', ['login' => 1]));
});

test('existing users can log in with OTP', function () {
    $user = User::factory()->create(['mobile' => '09120000000']);

    Livewire::test('auth-modal')
        ->set('mobile', '09120000000')
        ->call('sendOtp')
        ->set('otp', '1234')
        ->call('verifyOtp');

    $this->assertAuthenticatedAs($user);
});

test('wrong OTP does not log in', function () {
    User::factory()->create(['mobile' => '09120000000']);

    Livewire::test('auth-modal')
        ->set('mobile', '09120000000')
        ->call('sendOtp')
        ->set('otp', '0000')
        ->call('verifyOtp')
        ->assertHasErrors('otp');

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('home'));

    $this->assertGuest();
});
