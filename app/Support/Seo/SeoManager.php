<?php

namespace App\Support\Seo;

use App\Models\Pet;
use App\Support\Settings\SettingsRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SeoManager
{
    public const TYPES = [
        'lost' => [
            'adjective' => 'گمشده',
            'plural' => 'حیوانات گمشده',
            'resolved' => 'به خانه برگشت',
            'hub' => 'lost-pets.index',
            'city' => 'lost-pets.city',
            'species' => 'lost-pets.species',
        ],
        'found' => [
            'adjective' => 'پیدا شده',
            'plural' => 'حیوانات پیدا شده',
            'resolved' => 'به خانه برگشت',
            'hub' => 'found-pets.index',
            'city' => 'found-pets.city',
            'species' => 'found-pets.species',
        ],
        'adoption' => [
            'adjective' => 'آماده واگذاری',
            'plural' => 'حیوانات آماده واگذاری',
            'resolved' => 'واگذاری انجام شد',
            'hub' => 'adoption.index',
            'city' => 'adoption.city',
            'species' => 'adoption.species',
        ],
    ];

    public const SPECIES = [
        'dog' => 'سگ',
        'cat' => 'گربه',
        'bird' => 'پرنده',
        'other' => 'سایر',
    ];

    public const SPECIES_PLURAL = [
        'dog' => 'سگ‌های',
        'cat' => 'گربه‌های',
        'bird' => 'پرنده‌های',
        'other' => 'حیوانات',
    ];

    private const DEFAULTS = [
        'site_name' => 'ساس‌پت',
        'default_title' => 'ساس‌پت — برای هر حیوونی، یه خونه',
        'default_description' => 'ساس‌پت محل ثبت و جست‌وجوی آگهی‌های حیوانات گمشده، پیدا شده و آماده واگذاری در ایران است.',
        'default_og_image' => null,
        'default_robots' => 'index,follow,max-image-preview:large',
        'canonical_base_url' => null,
        'twitter_handle' => null,
        'ga4_measurement_id' => null,
        'gsc_verification' => null,
        'locale' => 'fa',
        'og_locale' => 'fa_IR',
        'page_overrides' => [],
    ];

    private const INFO_PAGES = [
        'about-us' => [
            'key' => 'about_us',
            'title' => 'درباره ساس‌پت',
            'description' => 'با هدف و مسیر ساس‌پت آشنا شوید؛ فضایی برای ثبت آگهی حیوانات گمشده، پیدا شده و آماده واگذاری.',
            'type' => 'AboutPage',
        ],
        'support' => [
            'key' => 'support',
            'title' => 'پشتیبانی و تماس با ساس‌پت',
            'description' => 'راه‌های ارتباط با تیم پشتیبانی ساس‌پت برای گزارش مشکل، پیشنهاد و سوال درباره آگهی‌ها.',
            'type' => 'ContactPage',
        ],
        'terms' => [
            'key' => 'terms',
            'title' => 'قوانین و مقررات ساس‌پت',
            'description' => 'شرایط استفاده از ساس‌پت، مسئولیت ثبت‌کنندگان آگهی و قواعد انتشار گزارش‌ها.',
            'type' => 'WebPage',
        ],
        'privacy' => [
            'key' => 'privacy',
            'title' => 'حریم خصوصی ساس‌پت',
            'description' => 'نحوه نگهداری و استفاده از اطلاعات کاربران و ثبت‌کنندگان آگهی در ساس‌پت.',
            'type' => 'WebPage',
        ],
    ];

    public function __construct(private SettingsRepository $settings) {}

    public function resolve(): SeoData
    {
        $request = request();

        $explicit = $request?->attributes->get('sospet.seo');

        if ($explicit instanceof SeoData) {
            return $explicit;
        }

        $routeName = $request?->route()?->getName();

        return match (true) {
            $routeName === 'home' => $this->forHome(),
            $routeName !== null && array_key_exists($routeName, self::INFO_PAGES) => $this->forInfoPage($routeName),
            $routeName === 'match-pets' => $this->forMatch(),
            $routeName === 'profile' || $routeName === 'report' => $this->forPrivate($routeName),
            $routeName !== null && Str::startsWith($routeName, 'settings.') => $this->forPrivate($routeName),
            default => $this->forUnknownPage(),
        };
    }

    public function set(SeoData $seo): void
    {
        request()->attributes->set('sospet.seo', $seo);
    }

    public function forHome(?string $search = null): SeoData
    {
        $override = $this->pageOverride('home');

        $title = $override['title'] ?? $this->value('default_title');
        $description = $override['description'] ?? $this->value('default_description');
        $canonical = $this->canonicalUrl(route('home'));

        $jsonLd = [
            $this->organizationJsonLd(),
            $this->websiteJsonLd(),
            $this->webPageJsonLd($title, $description, $canonical),
        ];

        return $this->make(
            title: $title,
            description: $description,
            canonical: $canonical,
            robots: filled($search) ? 'noindex,follow' : $this->defaultRobots(),
            jsonLd: $jsonLd,
        );
    }

    public function forPet(Pet $pet): SeoData
    {
        $type = self::TYPES[$pet->type] ?? self::TYPES['lost'];
        $canonical = $pet->seo_canonical ?: $this->canonicalUrl(route('pet-details', $pet));

        $title = $pet->seo_title;

        if (blank($title)) {
            $base = trim("{$pet->title} | {$pet->pet_type} {$type['adjective']} در {$pet->city}");
            $title = Str::limit($base, 55, '').' | '.$this->siteName();
        }

        if ($pet->is_resolved) {
            $title = "({$type['resolved']}) ".$title;
        }

        $description = $pet->seo_description ?: $this->petDescription($pet);

        $robots = $pet->seo_noindex ? 'noindex,nofollow' : $this->defaultRobots();

        if ($pet->isSeoIncomplete()) {
            $robots = 'noindex,follow';
        }

        $image = $pet->og_image
            ? $this->mediaUrl($pet->og_image)
            : ($pet->primaryImageUrl() ?: $this->defaultOgImage());

        $jsonLd = [
            $this->webPageJsonLd($title, $description, $canonical, image: $image, mainEntity: $this->petAnimalJsonLd($pet, $image)),
            $this->breadcrumbJsonLd([
                ['name' => 'خانه', 'url' => $this->canonicalUrl(route('home'))],
                ['name' => $type['plural'], 'url' => $this->canonicalUrl(route($type['hub']))],
                ['name' => $pet->title, 'url' => $canonical],
            ]),
        ];

        return $this->make(
            title: $title,
            description: $description,
            canonical: $canonical,
            robots: $robots,
            jsonLd: $jsonLd,
            ogImage: $image,
            ogType: 'article',
            preloadImage: $pet->primaryImageUrl(),
        );
    }

    /**
     * @param  array<int, Pet>  $pets
     */
    public function forListing(string $type, ?string $city, ?string $species, array $pets, int $total, int $page = 1): SeoData
    {
        $definition = self::TYPES[$type] ?? self::TYPES['lost'];

        $subject = $species
            ? trim((self::SPECIES_PLURAL[$species] ?? '').' '.$definition['adjective'])
            : $definition['plural'];

        $place = $city ? "در {$city}" : 'در ایران';
        $heading = trim("{$subject} {$place}");

        $title = Str::limit($heading, 55, '').' | '.$this->siteName();
        $description = Str::limit(
            "آخرین {$heading} در ساس‌پت؛ مشاهده گزارش‌های فعال، مشخصات حیوان و راه تماس با ثبت‌کننده.",
            160,
        );

        $url = url()->current();

        if ($page > 1) {
            $url .= '?page='.$page;
        }

        $canonical = $this->canonicalUrl($url);

        $items = collect($pets)->values()->map(fn (Pet $pet, int $index): array => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'url' => $this->canonicalUrl(route('pet-details', $pet)),
            'name' => $pet->title,
        ])->all();

        $jsonLd = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => $heading,
                'description' => $description,
                'url' => $canonical,
                'inLanguage' => 'fa-IR',
                'isPartOf' => [
                    '@type' => 'WebSite',
                    'name' => $this->siteName(),
                    'url' => $this->siteBase(),
                ],
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'numberOfItems' => $total,
                    'itemListElement' => $items,
                ],
            ],
            $this->listingBreadcrumbJsonLd($type, $definition, $city, $species),
        ];

        return $this->make(
            title: $title,
            description: $description,
            canonical: $canonical,
            robots: $total > 0 ? $this->defaultRobots() : 'noindex,follow',
            jsonLd: $jsonLd,
        );
    }

    public function forInfoPage(string $routeName): SeoData
    {
        $page = self::INFO_PAGES[$routeName] ?? self::INFO_PAGES['about-us'];
        $override = $this->pageOverride($page['key']);

        $title = $override['title'] ?? $page['title'].' | '.$this->siteName();
        $description = $override['description'] ?? $page['description'];
        $canonical = $this->canonicalUrl(route($routeName));

        $jsonLd = [
            $this->webPageJsonLd($title, $description, $canonical, $page['type']),
            $this->breadcrumbJsonLd([
                ['name' => 'خانه', 'url' => $this->canonicalUrl(route('home'))],
                ['name' => $page['title'], 'url' => $canonical],
            ]),
        ];

        return $this->make($title, $description, $canonical, $this->defaultRobots(), $jsonLd);
    }

    public function forMatch(): SeoData
    {
        return $this->make(
            title: 'جست‌وجوی حیوانات گمشده و پیدا شده | '.$this->siteName(),
            description: 'حیوانات گمشده و پیدا شده را بر اساس شهر یا موقعیت مکانی جست‌وجو کنید و به بازگشت آن‌ها به خانه کمک کنید.',
            canonical: null,
            robots: 'noindex,follow',
        );
    }

    public function forPrivate(string $routeName): SeoData
    {
        $title = match ($routeName) {
            'profile' => 'پروفایل من',
            'report' => 'ثبت گزارش جدید',
            default => 'تنظیمات حساب',
        };

        return $this->make(
            title: $title.' | '.$this->siteName(),
            description: null,
            canonical: null,
            robots: 'noindex,nofollow',
        );
    }

    public function forUnknownPage(): SeoData
    {
        return $this->make(
            title: $this->value('default_title'),
            description: $this->value('default_description'),
            canonical: $this->canonicalUrl(url()->current()),
            robots: $this->defaultRobots(),
        );
    }

    public function siteName(): string
    {
        return (string) $this->value('site_name');
    }

    public function siteBase(): string
    {
        return rtrim((string) ($this->raw('canonical_base_url') ?: config('app.url')), '/');
    }

    public function canonicalUrl(string $url): string
    {
        $base = $this->raw('canonical_base_url');

        if (blank($base)) {
            return $url;
        }

        $path = parse_url($url, PHP_URL_PATH) ?: '/';
        $query = parse_url($url, PHP_URL_QUERY);

        return rtrim((string) $base, '/').'/'.ltrim($path, '/').($query ? '?'.$query : '');
    }

    public function defaultRobots(): string
    {
        return (string) $this->value('default_robots');
    }

    public function defaultOgImage(): string
    {
        return $this->mediaUrl($this->raw('default_og_image')) ?: asset('assets/img/chat-1.png');
    }

    public function mediaUrl(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://', '//'])) {
            return $value;
        }

        if (Str::startsWith($value, '/')) {
            return url($value);
        }

        return asset('storage/'.ltrim($value, '/'));
    }

    public function value(string $key): mixed
    {
        $stored = $this->raw($key);

        if (filled($stored)) {
            return $stored;
        }

        return self::DEFAULTS[$key] ?? null;
    }

    public function raw(string $key): mixed
    {
        return $this->settings->get('seo.'.$key);
    }

    /**
     * @return array<string, string>
     */
    public function cityMap(): array
    {
        return Cache::remember('sospet.seo.city-map', now()->addMinutes(10), function (): array {
            $cities = Pet::query()
                ->active()
                ->whereNotNull('city')
                ->where('city', '!=', '')
                ->distinct()
                ->orderBy('city')
                ->pluck('city');

            $map = [];
            $used = [];

            foreach ($cities as $city) {
                $base = Str::slug($city) ?: 'city';
                $slug = $base;
                $suffix = 2;

                while (isset($used[$slug])) {
                    $slug = $base.'-'.$suffix++;
                }

                $used[$slug] = true;
                $map[$slug] = $city;
            }

            return $map;
        });
    }

    public function citySlug(string $city): string
    {
        $slug = array_search($city, $this->cityMap(), true);

        return is_string($slug) ? $slug : (Str::slug($city) ?: 'city');
    }

    public function cityFromSlug(string $slug): ?string
    {
        return $this->cityMap()[$slug] ?? null;
    }

    public function speciesFromSlug(string $slug): ?string
    {
        return self::SPECIES[$slug] ?? null;
    }

    public function speciesSlug(string $species): ?string
    {
        $slug = array_search($species, self::SPECIES, true);

        return is_string($slug) ? $slug : null;
    }

    public function speciesLabel(string $slug): ?string
    {
        return self::SPECIES[$slug] ?? null;
    }

    public function hubUrl(string $type): string
    {
        return $this->canonicalUrl(route(self::TYPES[$type]['hub']));
    }

    public function cityUrl(string $type, string $city): string
    {
        return $this->canonicalUrl(route(self::TYPES[$type]['city'], ['city' => $this->citySlug($city)]));
    }

    public function speciesUrl(string $type, string $species): string
    {
        return $this->canonicalUrl(route(self::TYPES[$type]['species'], ['species' => $this->speciesSlug($species) ?? 'other']));
    }

    /**
     * @return array{title?: string, description?: string}
     */
    private function pageOverride(string $key): array
    {
        $overrides = $this->raw('page_overrides');

        if (! is_array($overrides)) {
            return [];
        }

        $override = $overrides[$key] ?? null;

        if (! is_array($override)) {
            return [];
        }

        return array_filter([
            'title' => filled($override['title'] ?? null) ? trim((string) $override['title']) : null,
            'description' => filled($override['description'] ?? null) ? trim((string) $override['description']) : null,
        ]);
    }

    private function petDescription(Pet $pet): string
    {
        $type = self::TYPES[$pet->type] ?? self::TYPES['lost'];
        $breed = filled($pet->breed) ? " نژاد {$pet->breed}" : '';
        $area = filled($pet->area) ? "، {$pet->area}" : '';

        $lead = match ($pet->type) {
            'found' => "یک {$pet->pet_type}{$breed} در {$pet->city}{$area} پیدا شده است.",
            'adoption' => "{$pet->pet_type}{$breed} در {$pet->city}{$area} آماده واگذاری و سرپرستی است.",
            default => "گزارش گم شدن {$pet->pet_type}{$breed} در {$pet->city}{$area} ثبت شده است.",
        };

        if ($pet->is_resolved) {
            $lead = "این گزارش {$type['resolved']} است. ".$lead;
        }

        $details = [];

        if (filled($pet->age)) {
            $details[] = "حدود سن: {$pet->age}.";
        }

        if (filled($pet->gender) && $pet->gender !== 'نامشخص') {
            $details[] = "جنسیت: {$pet->gender}.";
        }

        if (filled($pet->description)) {
            $details[] = Str::limit(preg_replace('/\s+/u', ' ', strip_tags((string) $pet->description)), 120);
        }

        return Str::limit(trim($lead.' '.implode(' ', $details)), 160);
    }

    private function make(
        string $title,
        ?string $description,
        ?string $canonical,
        string $robots,
        array $jsonLd = [],
        ?string $ogImage = null,
        string $ogType = 'website',
        ?string $preloadImage = null,
    ): SeoData {
        return new SeoData(
            title: $title,
            description: $description,
            canonical: $canonical,
            robots: $robots,
            ogImage: $ogImage ?: $this->defaultOgImage(),
            ogType: $ogType,
            jsonLd: $jsonLd,
            locale: (string) $this->value('locale'),
            ogLocale: (string) $this->value('og_locale'),
            siteName: $this->siteName(),
            twitterHandle: $this->raw('twitter_handle') ?: null,
            ga4MeasurementId: $this->raw('ga4_measurement_id') ?: null,
            gscVerification: $this->raw('gsc_verification') ?: null,
            preloadImage: $preloadImage,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function organizationJsonLd(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $this->siteName(),
            'url' => $this->siteBase(),
            'logo' => $this->siteBase().'/apple-touch-icon.png',
            'description' => $this->value('default_description'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function websiteJsonLd(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $this->siteName(),
            'url' => $this->siteBase(),
            'inLanguage' => 'fa-IR',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function webPageJsonLd(string $name, ?string $description, ?string $url, string $type = 'WebPage', ?string $image = null, ?array $mainEntity = null): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => $type,
            'name' => $name,
            'url' => $url,
            'inLanguage' => 'fa-IR',
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => $this->siteName(),
                'url' => $this->siteBase(),
            ],
        ];

        if (filled($description)) {
            $data['description'] = $description;
        }

        if (filled($image)) {
            $data['primaryImageOfPage'] = [
                '@type' => 'ImageObject',
                'url' => $image,
            ];
        }

        if (filled($mainEntity)) {
            $data['mainEntity'] = $mainEntity;
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function petAnimalJsonLd(Pet $pet, ?string $image): array
    {
        $animal = [
            '@type' => 'Animal',
            'name' => $pet->title,
            'species' => $pet->pet_type,
            'url' => $this->canonicalUrl(route('pet-details', $pet)),
        ];

        if (filled($pet->breed)) {
            $animal['breed'] = $pet->breed;
        }

        if (filled($image)) {
            $animal['image'] = $image;
        }

        if (filled($pet->description)) {
            $animal['description'] = Str::limit(preg_replace('/\s+/u', ' ', strip_tags((string) $pet->description)), 160);
        }

        return $animal;
    }

    /**
     * @param  array<int, array{name: string, url: string}>  $items
     * @return array<string, mixed>
     */
    private function breadcrumbJsonLd(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(fn (array $item, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ])->all(),
        ];
    }

    /**
     * @param  array<string, mixed>  $definition
     * @return array<string, mixed>
     */
    private function listingBreadcrumbJsonLd(string $type, array $definition, ?string $city, ?string $species): array
    {
        $items = [
            ['name' => 'خانه', 'url' => $this->canonicalUrl(route('home'))],
            ['name' => $definition['plural'], 'url' => $this->hubUrl($type)],
        ];

        if ($city) {
            $items[] = ['name' => "در {$city}", 'url' => $this->cityUrl($type, $city)];
        }

        if ($species) {
            $items[] = ['name' => self::SPECIES[$species] ?? $species, 'url' => $this->speciesUrl($type, $species)];
        }

        return $this->breadcrumbJsonLd($items);
    }
}
