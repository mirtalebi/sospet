<?php

namespace App\Support\Seo;

use App\Models\Pet;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SitemapBuilder
{
    private const CHUNK_SIZE = 5000;

    public function __construct(private SeoManager $seo) {}

    public function index(): string
    {
        return $this->remember('index', fn (): string => view('seo.sitemap-index', [
            'sitemaps' => $this->indexEntries(),
        ])->render());
    }

    public function pages(): string
    {
        return $this->remember('pages', fn (): string => view('seo.sitemap-urls', [
            'urls' => $this->pageUrls(),
        ])->render());
    }

    public function locations(): string
    {
        return $this->remember('locations', fn (): string => view('seo.sitemap-urls', [
            'urls' => $this->locationUrls(),
        ])->render());
    }

    public function pets(int $page): string
    {
        return $this->remember('pets-'.$page, fn (): string => view('seo.sitemap-urls', [
            'urls' => $this->petUrls($page),
        ])->render());
    }

    public function petPageCount(): int
    {
        return (int) ceil($this->indexablePets()->count() / self::CHUNK_SIZE);
    }

    /**
     * @return array{pets: int, cities: int, species: int, pages: int}
     */
    public function counts(): array
    {
        $cities = $this->cityGroups()->map(fn (Pet $row): string => $row->type.'|'.$row->city)->unique();
        $species = $this->speciesGroups()
            ->filter(fn (Pet $row): bool => $this->seo->speciesSlug((string) $row->pet_type) !== null)
            ->map(fn (Pet $row): string => $row->type.'|'.$row->pet_type)
            ->unique();

        return [
            'pets' => $this->indexablePets()->count(),
            'cities' => $cities->count(),
            'species' => $species->count(),
            'pages' => count($this->pageUrls()),
        ];
    }

    public function generatedAt(): ?string
    {
        return Cache::get('sospet.sitemap.generated-at');
    }

    public function forget(): void
    {
        Cache::forever('sospet.sitemap.version', (int) Cache::get('sospet.sitemap.version', 1) + 1);
        Cache::forget('sospet.seo.city-map');
    }

    /**
     * @return array<int, array{loc: string, lastmod: ?string}>
     */
    private function indexEntries(): array
    {
        $entries = [
            ['loc' => route('sitemap.pages'), 'lastmod' => null],
            ['loc' => route('sitemap.locations'), 'lastmod' => $this->latestUpdate($this->cityGroups()->max('last_updated'))],
        ];

        $petPages = $this->petPageCount();

        for ($page = 1; $page <= $petPages; $page++) {
            $entries[] = [
                'loc' => route('sitemap.pets', ['page' => $page]),
                'lastmod' => $this->latestUpdate($this->indexablePets()->max('updated_at')),
            ];
        }

        Cache::forever('sospet.sitemap.generated-at', now()->toIso8601String());

        return $entries;
    }

    /**
     * @return array<int, array{loc: string, lastmod: ?string}>
     */
    private function pageUrls(): array
    {
        $urls = [
            ['loc' => $this->seo->canonicalUrl(route('home')), 'lastmod' => null],
        ];

        foreach (['about-us', 'support', 'terms', 'privacy'] as $routeName) {
            $urls[] = ['loc' => $this->seo->canonicalUrl(route($routeName)), 'lastmod' => null];
        }

        foreach (array_keys(SeoManager::TYPES) as $type) {
            $urls[] = ['loc' => $this->seo->hubUrl($type), 'lastmod' => null];
        }

        return $urls;
    }

    /**
     * @return array<int, array{loc: string, lastmod: ?string}>
     */
    private function locationUrls(): array
    {
        $urls = [];

        foreach ($this->cityGroups() as $row) {
            if (! isset(SeoManager::TYPES[$row->type])) {
                continue;
            }

            $urls[] = [
                'loc' => $this->seo->cityUrl($row->type, (string) $row->city),
                'lastmod' => $this->latestUpdate($row->last_updated),
            ];
        }

        foreach ($this->speciesGroups() as $row) {
            if (! isset(SeoManager::TYPES[$row->type])) {
                continue;
            }

            $speciesSlug = $this->seo->speciesSlug((string) $row->pet_type);

            if ($speciesSlug === null) {
                continue;
            }

            $urls[] = [
                'loc' => route(SeoManager::TYPES[$row->type]['species'], ['species' => $speciesSlug]),
                'lastmod' => $this->latestUpdate($row->last_updated),
            ];
        }

        return $urls;
    }

    /**
     * @return array<int, array{loc: string, lastmod: ?string, images: array<int, string>}>
     */
    private function petUrls(int $page): array
    {
        $pets = $this->indexablePets()
            ->with('images')
            ->orderBy('id')
            ->forPage($page, self::CHUNK_SIZE)
            ->get();

        return $pets->map(fn (Pet $pet): array => [
            'loc' => $this->seo->canonicalUrl(route('pet-details', $pet)),
            'lastmod' => $this->latestUpdate($pet->updated_at),
            'images' => $pet->images->map(fn ($image): string => $image->image_url)->all(),
        ])->all();
    }

    private function indexablePets(): Builder
    {
        return Pet::query()
            ->active()
            ->whereNotNull('slug')
            ->where('seo_noindex', false)
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->whereHas('images');
    }

    /**
     * @return Collection<int, Pet>
     */
    private function cityGroups()
    {
        return $this->indexablePets()
            ->selectRaw('type, city, MAX(updated_at) as last_updated')
            ->groupBy('type', 'city')
            ->orderBy('type')
            ->orderBy('city')
            ->get();
    }

    /**
     * @return Collection<int, Pet>
     */
    private function speciesGroups()
    {
        return $this->indexablePets()
            ->selectRaw('type, pet_type, MAX(updated_at) as last_updated')
            ->groupBy('type', 'pet_type')
            ->orderBy('type')
            ->orderBy('pet_type')
            ->get();
    }

    private function latestUpdate(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return Carbon::parse($value)->toAtomString();
    }

    private function remember(string $key, Closure $callback): string
    {
        $version = (int) Cache::get('sospet.sitemap.version', 1);

        return Cache::remember("sospet.sitemap.v{$version}.{$key}", now()->addHour(), $callback);
    }
}
