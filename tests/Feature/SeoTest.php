<?php

use App\Models\Pet;
use App\Models\PetImage;
use App\Support\Seo\SeoManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;

uses(RefreshDatabase::class);

function seoPet(array $attributes = [], bool $withImage = true): Pet
{
    $pet = Pet::factory()->create($attributes);

    if ($withImage) {
        PetImage::create([
            'pet_id' => $pet->id,
            'image_path' => 'pets/seo-test-'.$pet->id.'.jpg',
        ]);
    }

    return $pet->fresh('images');
}

/**
 * @return array<int, array<string, mixed>>
 */
function seoJsonLd(TestResponse $response): array
{
    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $response->getContent(), $matches);

    return array_map(
        fn (string $json): array => json_decode($json, true, 512, JSON_THROW_ON_ERROR),
        $matches[1],
    );
}

test('pet pages expose generated title, description, canonical, social tags and structured data', function () {
    $pet = seoPet([
        'type' => 'lost',
        'title' => 'سگ گمشده در تهران',
        'pet_type' => 'سگ',
        'breed' => 'هاسکی',
        'city' => 'تهران',
    ]);

    $response = $this->get(route('pet-details', $pet))->assertOk();

    $response
        ->assertSee('<meta name="robots" content="index,follow,max-image-preview:large">', false)
        ->assertSee('<link rel="canonical" href="'.route('pet-details', $pet).'"', false)
        ->assertSee('<meta property="og:type" content="article">', false)
        ->assertSee('<meta property="og:image"', false)
        ->assertSee('<meta name="twitter:card" content="summary_large_image">', false)
        ->assertSee('گزارش گم شدن سگ', false);

    $schemas = seoJsonLd($response);
    $types = array_column($schemas, '@type');

    expect($types)->toContain('WebPage')
        ->and($types)->toContain('BreadcrumbList')
        ->and($schemas[0]['mainEntity']['@type'])->toBe('Animal')
        ->and($schemas[0]['mainEntity']['species'])->toBe('سگ')
        ->and($schemas[0]['mainEntity']['breed'])->toBe('هاسکی');
});

test('custom pet seo fields override generated values', function () {
    $pet = seoPet([
        'seo_title' => 'عنوان سفارشی برای آگهی',
        'seo_description' => 'توضیح سفارشی برای آگهی آزمایشی.',
        'seo_canonical' => 'https://example.com/canonical-pet',
        'seo_noindex' => true,
    ]);

    $response = $this->get(route('pet-details', $pet))->assertOk();

    $response
        ->assertSee('<title>'.$pet->seo_title.'</title>', false)
        ->assertSee('<meta name="description" content="'.$pet->seo_description.'">', false)
        ->assertSee('<link rel="canonical" href="https://example.com/canonical-pet"', false)
        ->assertSee('<meta name="robots" content="noindex,nofollow">', false);
});

test('incomplete listings are noindexed but still viewable', function () {
    $pet = seoPet(['title' => 'آگهی بدون تصویر'], withImage: false);

    $this->get(route('pet-details', $pet))
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex,follow">', false);
});

test('resolved listings stay accessible with updated content but leave the sitemap', function () {
    seoPet(['type' => 'found', 'city' => 'تهران', 'title' => 'آگهی فعال سایت‌مپ']);

    $resolved = seoPet(['type' => 'found', 'is_resolved' => true, 'title' => 'پیدا شده و به خانه برگشت']);

    $this->get(route('pet-details', $resolved))
        ->assertOk()
        ->assertSee($resolved->resolvedLabel());

    $sitemap = $this->get(route('sitemap.pets', ['page' => 1]))->assertOk();

    $sitemap->assertDontSee(route('pet-details', $resolved));
});

test('hidden listings return 404', function () {
    $pet = seoPet(['is_hidden' => true]);

    $this->get(route('pet-details', $pet))->assertNotFound();
    $this->get('/pet/'.$pet->id)->assertNotFound();
});

test('slugs are unique, stable and legacy numeric urls redirect permanently', function () {
    $first = seoPet(['title' => 'گربه گمشده']);
    $second = seoPet(['title' => 'گربه گمشده']);

    expect($first->slug)->not->toBe($second->slug)
        ->and($first->slug)->toEndWith('-'.$first->id)
        ->and($second->slug)->toEndWith('-'.$second->id);

    $first->update(['title' => 'عنوان کاملاً جدید']);

    expect($first->fresh()->slug)->toBe($first->slug);

    $this->get('/pet/'.$first->id)
        ->assertStatus(301)
        ->assertRedirect(route('pet-details', $first->fresh()));

    $this->get('/pet/'.$first->slug)->assertOk();
    $this->get('/pet/does-not-exist')->assertNotFound();
});

test('listing hubs expose collection metadata and only unresolved listings', function () {
    $active = seoPet(['type' => 'lost', 'city' => 'تهران', 'title' => 'گمشده فعال']);
    $resolved = seoPet(['type' => 'lost', 'city' => 'تهران', 'is_resolved' => true, 'title' => 'گمشده حل شده']);

    $response = $this->get(route('lost-pets.index'))->assertOk();

    $response
        ->assertSee('گمشده فعال')
        ->assertDontSee('گمشده حل شده')
        ->assertSee('<link rel="canonical" href="'.route('lost-pets.index').'"', false)
        ->assertSee('<meta name="robots" content="index,follow,max-image-preview:large">', false);

    $schemas = seoJsonLd($response);

    expect(array_column($schemas, '@type'))->toContain('CollectionPage')
        ->and($schemas[0]['mainEntity']['@type'])->toBe('ItemList');
});

test('city and species landing pages use real data and unknown slugs 404', function () {
    $seo = app(SeoManager::class);

    $tehran = seoPet(['type' => 'lost', 'city' => 'تهران', 'pet_type' => 'سگ', 'title' => 'سگ تهران']);
    $shiraz = seoPet(['type' => 'lost', 'city' => 'شیراز', 'pet_type' => 'گربه', 'title' => 'گربه شیراز']);

    $cityUrl = route('lost-pets.city', ['city' => $seo->citySlug('تهران')]);

    $this->get($cityUrl)
        ->assertOk()
        ->assertSee('سگ تهران')
        ->assertDontSee('گربه شیراز')
        ->assertSee('در تهران');

    $this->get(route('lost-pets.species', ['species' => 'cat']))
        ->assertOk()
        ->assertSee('گربه شیراز')
        ->assertDontSee('سگ تهران');

    $this->get('/lost-pets/city/unknown-city')->assertNotFound();
    $this->get('/lost-pets/species/dragon')->assertNotFound();
});

test('empty listing pages are noindexed', function () {
    $this->get(route('adoption.index'))
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex,follow">', false);
});

test('match page is never indexed', function () {
    $this->get('/match?mode=lost')
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex,follow">', false);
});

test('sitemap index and listing sitemap include only indexable listings with images', function () {
    $indexable = seoPet(['city' => 'تهران', 'title' => 'آگهی کامل']);
    $noImage = seoPet(['city' => 'تهران', 'title' => 'آگهی بدون تصویر'], withImage: false);
    $noindex = seoPet(['city' => 'تهران', 'seo_noindex' => true, 'title' => 'آگهی noindex']);
    $resolved = seoPet(['city' => 'تهران', 'is_resolved' => true, 'title' => 'آگهی حل شده']);
    $hidden = seoPet(['city' => 'تهران', 'is_hidden' => true, 'title' => 'آگهی پنهان']);

    $this->get(route('sitemap.index'))
        ->assertOk()
        ->assertSee(route('sitemap.pages'))
        ->assertSee(route('sitemap.locations'))
        ->assertSee(route('sitemap.pets', ['page' => 1]));

    $sitemap = $this->get(route('sitemap.pets', ['page' => 1]))->assertOk();

    $sitemap->assertSee(route('pet-details', $indexable))
        ->assertDontSee(route('pet-details', $noImage))
        ->assertDontSee(route('pet-details', $noindex))
        ->assertDontSee(route('pet-details', $resolved))
        ->assertDontSee(route('pet-details', $hidden));

    $imageUrl = $indexable->images->first()->image_url;

    $sitemap->assertSee('<image:loc>'.$imageUrl.'</image:loc>', false);
});

test('location sitemap contains only groups built from real listings', function () {
    seoPet(['type' => 'found', 'city' => 'کرج', 'pet_type' => 'پرنده', 'title' => 'پرنده کرج']);

    $seo = app(SeoManager::class);

    $this->get(route('sitemap.locations'))
        ->assertOk()
        ->assertSee(route('found-pets.city', ['city' => $seo->citySlug('کرج')]))
        ->assertSee(route('found-pets.species', ['species' => 'bird']))
        ->assertDontSee(route('lost-pets.city', ['city' => 'مشهد']));
});

test('robots.txt allows public pages, blocks private areas and references the sitemap', function () {
    $this->get('/robots.txt')
        ->assertOk()
        ->assertSee('User-agent: *', false)
        ->assertSee('Disallow: /admin', false)
        ->assertSee('Disallow: /profile', false)
        ->assertSee('Allow: /', false)
        ->assertSee('Sitemap: '.url('/sitemap.xml'), false);
});
