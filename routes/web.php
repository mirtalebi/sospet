<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'home')->name('home');
Route::livewire('/about-us', 'pages::about-us')->name('about-us');
Route::livewire('/support', 'pages::support')->name('support');
Route::livewire('/terms', 'pages::terms')->name('terms');
Route::livewire('/privacy', 'pages::privacy')->name('privacy');
Route::livewire('/pet/{id}', 'pet-details')->name('pet-details');
Route::livewire('/match', 'match-pets')->name('match-pets');
Route::livewire('/profile', 'user-profile')->middleware('auth');
Route::livewire('/report', 'report-pet')->middleware('auth');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::get('/temp-test', function () {
    try {
        $tmp = @tempnam(sys_get_temp_dir(), 'lw_');

        return [
            'temp_dir' => sys_get_temp_dir(),
            'temp_file' => $tmp,
            'exists' => $tmp ? file_exists($tmp) : false,
            'writable' => is_writable(sys_get_temp_dir()),
        ];
    } catch (Throwable $e) {
        return $e->getMessage();
    }
});

require __DIR__.'/settings.php';
