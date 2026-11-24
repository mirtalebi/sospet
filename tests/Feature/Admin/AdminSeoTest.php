<?php

use App\Models\Pet;
use App\Models\PetImage;
use App\Models\User;
use App\Support\Settings\SettingsRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('admin seo settings page is only available to admins', function () {
    $this->get(route('admin.seo'))->assertRedirect();

    $this->actingAs(User::factory()->create())
        ->get(route('admin.seo'))
        ->assertForbidden();

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.seo'))
        ->assertOk()
        ->assertSee('مدیریت سئو');
});

test('admin can persist global seo settings and they affect public pages', function () {
    $this->actingAs(User::factory()->admin()->create());

    Livewire::test('pages::admin.seo')
        ->set('siteName', 'ساس‌پت')
        ->set('defaultTitle', 'عنوان پیش‌فرض جدید')
        ->set('defaultDescription', 'توضیحات پیش‌فرض جدید برای صفحه خانه.')
        ->set('canonicalBase', 'https://sospet.example')
        ->set('ga4MeasurementId', 'G-ABC12345')
        ->set('pages.about_us.title', 'عنوان سفارشی درباره ما')
        ->call('save')
        ->assertHasNoErrors();

    $settings = app(SettingsRepository::class);

    expect($settings->get('seo.default_title'))->toBe('عنوان پیش‌فرض جدید')
        ->and($settings->get('seo.ga4_measurement_id'))->toBe('G-ABC12345')
        ->and($settings->get('seo.page_overrides')['about_us']['title'])->toBe('عنوان سفارشی درباره ما');

    $this->get(route('about-us'))
        ->assertOk()
        ->assertSee('<title>عنوان سفارشی درباره ما</title>', false)
        ->assertSee('<link rel="canonical" href="https://sospet.example/about-us"', false);
});

test('admin seo form validates measurement id and description length', function () {
    $this->actingAs(User::factory()->admin()->create());

    Livewire::test('pages::admin.seo')
        ->set('ga4MeasurementId', 'invalid-id')
        ->set('defaultDescription', str_repeat('ا', 321))
        ->call('save')
        ->assertHasErrors(['ga4MeasurementId', 'defaultDescription']);
});

test('admin can regenerate the sitemap cache', function () {
    $this->actingAs(User::factory()->admin()->create());

    $before = (int) Cache::get('sospet.sitemap.version', 1);

    Livewire::test('pages::admin.seo')
        ->call('regenerateSitemap')
        ->assertHasNoErrors();

    expect((int) Cache::get('sospet.sitemap.version', 1))->toBeGreaterThan($before);
});

test('admin can edit per-listing seo overrides', function () {
    $this->actingAs(User::factory()->admin()->create());

    $pet = Pet::factory()->create([
        'type' => 'lost',
        'city' => 'تهران',
        'title' => 'آگهی آزمایشی سئو',
    ]);

    PetImage::create(['pet_id' => $pet->id, 'image_path' => 'pets/admin-seo.jpg']);

    Livewire::test('pages::admin.pets')
        ->call('openSeoEditor', $pet->id)
        ->assertSet('seoModalOpen', true)
        ->set('seoTitle', 'عنوان سفارشی مدیر')
        ->set('seoDescription', 'توضیح سفارشی که مدیر ثبت کرده است.')
        ->set('seoNoindex', true)
        ->call('saveSeo')
        ->assertHasNoErrors()
        ->assertSet('seoModalOpen', false);

    $pet->refresh();

    expect($pet->seo_title)->toBe('عنوان سفارشی مدیر')
        ->and($pet->seo_description)->toBe('توضیح سفارشی که مدیر ثبت کرده است.')
        ->and($pet->seo_noindex)->toBeTrue();

    $this->get(route('pet-details', $pet))
        ->assertSee('<title>عنوان سفارشی مدیر</title>', false)
        ->assertSee('<meta name="robots" content="noindex,nofollow">', false);
});

test('per-listing seo fields are validated', function () {
    $this->actingAs(User::factory()->admin()->create());

    $pet = Pet::factory()->create(['city' => 'تهران']);

    Livewire::test('pages::admin.pets')
        ->call('openSeoEditor', $pet->id)
        ->set('seoTitle', str_repeat('a', 71))
        ->set('seoCanonical', 'not-a-url')
        ->call('saveSeo')
        ->assertHasErrors(['seoTitle', 'seoCanonical']);
});
