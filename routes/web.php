<?php

use App\Http\Controllers\SitemapController;
use App\Models\Pet;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'home')->name('home');
Route::livewire('/about-us', 'pages::about-us')->name('about-us');
Route::livewire('/support', 'pages::support')->name('support');
Route::livewire('/terms', 'pages::terms')->name('terms');
Route::livewire('/privacy', 'pages::privacy')->name('privacy');

Route::livewire('/lost-pets', 'pet-listings')->defaults('listingType', 'lost')->name('lost-pets.index');
Route::livewire('/lost-pets/city/{city}', 'pet-listings')->defaults('listingType', 'lost')->name('lost-pets.city');
Route::livewire('/lost-pets/species/{species}', 'pet-listings')->defaults('listingType', 'lost')->name('lost-pets.species');

Route::livewire('/found-pets', 'pet-listings')->defaults('listingType', 'found')->name('found-pets.index');
Route::livewire('/found-pets/city/{city}', 'pet-listings')->defaults('listingType', 'found')->name('found-pets.city');
Route::livewire('/found-pets/species/{species}', 'pet-listings')->defaults('listingType', 'found')->name('found-pets.species');

Route::livewire('/adoption', 'pet-listings')->defaults('listingType', 'adoption')->name('adoption.index');
Route::livewire('/adoption/city/{city}', 'pet-listings')->defaults('listingType', 'adoption')->name('adoption.city');
Route::livewire('/adoption/species/{species}', 'pet-listings')->defaults('listingType', 'adoption')->name('adoption.species');

Route::get('/pet/{id}', function (int $id) {
    $pet = Pet::query()->visible()->findOrFail($id);

    return redirect()->route('pet-details', $pet, 301);
})->whereNumber('id');

Route::livewire('/pet/{pet}', 'pet-details')->name('pet-details');
Route::livewire('/match', 'match-pets')->name('match-pets');
Route::livewire('/profile', 'user-profile')->middleware('auth');
Route::livewire('/report', 'report-pet')->middleware('auth');

Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Allow: /',
        'Disallow: /admin',
        'Disallow: /profile',
        'Disallow: /report',
        'Disallow: /settings',
        'Disallow: /login',
        'Disallow: /temp-test',
        '',
        'Sitemap: '.url('/sitemap.xml'),
    ];

    return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->name('robots');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemaps/pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemaps/locations.xml', [SitemapController::class, 'locations'])->name('sitemap.locations');
Route::get('/sitemaps/pets-{page}.xml', [SitemapController::class, 'pets'])->whereNumber('page')->name('sitemap.pets');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::livewire('/', 'pages::admin.dashboard')->name('dashboard');
    Route::livewire('/users', 'pages::admin.users')->name('users');
    Route::livewire('/pets', 'pages::admin.pets')->name('pets');
    Route::livewire('/seo', 'pages::admin.seo')->name('seo');
});

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
