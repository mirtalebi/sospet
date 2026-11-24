<?php

use App\Models\Pet;
use App\Support\Seo\SeoManager;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    #[Locked]
    public string $listingType = 'lost';

    #[Locked]
    public ?string $city = null;

    #[Locked]
    public ?string $species = null;

    public function mount(SeoManager $seo): void
    {
        $type = request()->route()?->defaults['listingType'] ?? null;

        if (! is_string($type) || ! array_key_exists($type, SeoManager::TYPES)) {
            abort(404);
        }

        $this->listingType = $type;

        $citySlug = request()->route('city');

        if (filled($citySlug)) {
            $city = $seo->cityFromSlug((string) $citySlug);

            if ($city === null) {
                abort(404);
            }

            $this->city = $city;
        }

        $speciesSlug = request()->route('species');

        if (filled($speciesSlug)) {
            if ($seo->speciesFromSlug((string) $speciesSlug) === null) {
                abort(404);
            }

            $this->species = (string) $speciesSlug;
        }
    }

    public function with(): array
    {
        $seo = app(SeoManager::class);
        $definition = SeoManager::TYPES[$this->listingType];

        $query = Pet::query()
            ->with('images')
            ->active()
            ->ofType($this->listingType)
            ->when($this->city, fn ($builder) => $builder->inCity($this->city))
            ->when($this->species, fn ($builder) => $builder->where('pet_type', SeoManager::SPECIES[$this->species]));

        $pets = $query->latest()->paginate(12);

        $subject = $this->species
            ? trim(SeoManager::SPECIES_PLURAL[$this->species].' '.$definition['adjective'])
            : $definition['plural'];
        $heading = trim($subject.' '.($this->city ? "در {$this->city}" : 'در ایران'));

        $seo->set($seo->forListing(
            $this->listingType,
            $this->city,
            $this->species,
            $pets->items(),
            $pets->total(),
            $pets->currentPage(),
        ));

        $breadcrumbs = [
            ['label' => 'خانه', 'url' => route('home')],
            ['label' => $definition['plural'], 'url' => route($definition['hub'])],
        ];

        if ($this->city) {
            $breadcrumbs[] = ['label' => "در {$this->city}"];
        } elseif ($this->species) {
            $breadcrumbs[] = ['label' => SeoManager::SPECIES[$this->species]];
        }

        $cityLinks = Pet::query()
            ->active()
            ->ofType($this->listingType)
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->selectRaw('city, count(*) as total')
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(12)
            ->get()
            ->map(fn (Pet $row): array => [
                'label' => $row->city,
                'url' => route($definition['city'], ['city' => $seo->citySlug($row->city)]),
                'total' => (int) $row->total,
                'active' => $row->city === $this->city,
            ])
            ->all();

        $speciesLinks = Pet::query()
            ->active()
            ->ofType($this->listingType)
            ->selectRaw('pet_type, count(*) as total')
            ->groupBy('pet_type')
            ->get()
            ->map(function (Pet $row) use ($definition, $seo): ?array {
                $slug = $seo->speciesSlug($row->pet_type);

                if ($slug === null || (int) $row->total === 0) {
                    return null;
                }

                return [
                    'label' => SeoManager::SPECIES[$slug],
                    'url' => route($definition['species'], ['species' => $slug]),
                    'total' => (int) $row->total,
                    'active' => $slug === $this->species,
                ];
            })
            ->filter()
            ->values()
            ->all();

        return [
            'pets' => $pets,
            'heading' => $heading,
            'subject' => $subject,
            'definition' => $definition,
            'breadcrumbs' => $breadcrumbs,
            'cityLinks' => $cityLinks,
            'speciesLinks' => $speciesLinks,
        ];
    }
};
?>

<main class="flex-1 overflow-y-auto no-scrollbar pb-28 bg-bg-secondary">

    <!-- Hero -->
    <section class="relative overflow-hidden px-5 pb-6 pt-5 md:px-10 md:pb-10 md:pt-8">
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-[#EAF6F8] via-[#FBF6EC] to-bg-secondary"></div>
        <div class="pointer-events-none absolute -top-24 start-[15%] h-56 w-56 rounded-full bg-primary/15 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 end-0 h-56 w-56 rounded-full bg-cta/10 blur-3xl"></div>

        <div class="relative mx-auto w-full max-w-6xl">
            @include('partials.breadcrumbs', ['items' => $breadcrumbs])

            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div class="min-w-0 text-right">
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-primary/20 bg-bg-main/80 px-3 py-1 text-[10.5px] font-extrabold text-primary shadow-sm backdrop-blur-sm md:text-xs">
                        <span class="h-1.5 w-1.5 rounded-full bg-primary"></span>
                        گزارش‌های فعال
                        <span class="text-text-muted">/</span>
                        {{ number_format($pets->total()) }} مورد
                    </span>

                    <h1 class="mt-3 font-display text-2xl leading-tight text-text-title md:text-4xl">{{ $heading }}</h1>

                    <p class="mt-2 max-w-2xl text-[12px] leading-6 text-text-body md:text-sm md:leading-7">
                        آخرین {{ $subject }} {{ $this->city ? "در {$this->city}" : 'در سراسر ایران' }} را ببینید؛ هر آگهی
                        همراه عکس، مشخصات و راه تماس با ثبت‌کننده است. اگر موردی را می‌شناسید، به بازگشتش به خانه کمک کنید.
                    </p>
                </div>

                <div class="flex shrink-0 flex-wrap items-center gap-2">
                    <a href="{{ route('match-pets', ['mode' => $this->listingType === 'lost' ? 'lost' : 'found']) }}"
                        wire:navigate
                        class="inline-flex items-center gap-2 rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-xs font-bold text-text-title shadow-sm transition hover:border-primary/30 hover:text-primary active:scale-[0.98]">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" aria-hidden="true">
                            <path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z" />
                            <circle cx="12" cy="9.5" r="2.3" />
                        </svg>
                        جست‌وجو روی نقشه
                    </a>

                    @auth
                        <a href="{{ route('report', ['type' => $this->listingType]) }}" wire:navigate
                            class="inline-flex items-center gap-2 rounded-xl bg-cta px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-cta/25 transition hover:bg-cta-hover active:scale-[0.98]">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.6" stroke-linecap="round" aria-hidden="true">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            ثبت آگهی جدید
                        </a>
                    @else
                        <button type="button" @click="$dispatch('open-auth')"
                            class="inline-flex items-center gap-2 rounded-xl bg-cta px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-cta/25 transition hover:bg-cta-hover active:scale-[0.98]">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.6" stroke-linecap="round" aria-hidden="true">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            ثبت آگهی جدید
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Filters -->
    <section class="px-5 md:px-10">
        <div class="mx-auto w-full max-w-6xl space-y-3">

            @if (count($speciesLinks) > 0)
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                    <span class="shrink-0 text-[11px] font-bold text-text-muted">نوع حیوان:</span>

                    <a href="{{ route($definition['hub']) }}" wire:navigate
                        class="shrink-0 rounded-full border px-3.5 py-1.5 text-[11px] font-bold transition {{ $this->species === null ? 'border-primary bg-primary text-white shadow-sm' : 'border-border-custom bg-bg-main text-text-body hover:border-primary/30 hover:text-primary' }}">
                        همه
                    </a>

                    @foreach ($speciesLinks as $link)
                        <a href="{{ $link['url'] }}" wire:navigate
                            class="shrink-0 rounded-full border px-3.5 py-1.5 text-[11px] font-bold transition {{ $link['active'] ? 'border-primary bg-primary text-white shadow-sm' : 'border-border-custom bg-bg-main text-text-body hover:border-primary/30 hover:text-primary' }}">
                            {{ $link['label'] }}
                            <span class="{{ $link['active'] ? 'text-white/70' : 'text-text-muted' }}">{{ $link['total'] }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            @if (count($cityLinks) > 0)
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                    <span class="shrink-0 text-[11px] font-bold text-text-muted">شهر:</span>

                    <a href="{{ route($definition['hub']) }}" wire:navigate
                        class="shrink-0 rounded-full border px-3.5 py-1.5 text-[11px] font-bold transition {{ $this->city === null ? 'border-primary/30 bg-primary/10 text-primary' : 'border-border-custom bg-bg-main text-text-body hover:border-primary/30 hover:text-primary' }}">
                        همه شهرها
                    </a>

                    @foreach ($cityLinks as $link)
                        <a href="{{ $link['url'] }}" wire:navigate
                            class="shrink-0 rounded-full border px-3.5 py-1.5 text-[11px] font-bold transition {{ $link['active'] ? 'border-primary/30 bg-primary/10 text-primary' : 'border-border-custom bg-bg-main text-text-body hover:border-primary/30 hover:text-primary' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Results -->
    <section class="mt-5 px-5 md:mt-8 md:px-10">
        <div class="mx-auto w-full max-w-6xl">
            <div class="grid grid-cols-2 gap-3 md:grid-cols-3 md:gap-5 lg:grid-cols-4">
                @forelse ($pets as $pet)
                    @include('partials.pet-card', ['pet' => $pet])
                @empty
                    <div
                        class="col-span-2 rounded-3xl border border-dashed border-border-custom bg-bg-main px-5 py-12 text-center md:col-span-3 lg:col-span-4">
                        <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-primary/10 text-primary">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <g>
                                    <ellipse cx="12" cy="5" rx="1.9" ry="2.4" />
                                    <ellipse cx="6.9" cy="8" rx="1.7" ry="2.2" transform="rotate(-22 6.9 8)" />
                                    <ellipse cx="17.1" cy="8" rx="1.7" ry="2.2" transform="rotate(22 17.1 8)" />
                                    <ellipse cx="4.5" cy="13.2" rx="1.5" ry="1.9" transform="rotate(-38 4.5 13.2)" />
                                    <ellipse cx="19.5" cy="13.2" rx="1.5" ry="1.9" transform="rotate(38 19.5 13.2)" />
                                    <path
                                        d="M12 11.3c-2.5 0-4.6 1.6-5.5 3.8-.8 2 .4 4.2 2.6 4.6.9.2 1.9 0 2.9-.6.7-.4 1.4-.4 2.1 0 1 .6 2 .8 2.9.6 2.2-.4 3.4-2.6 2.6-4.6-.9-2.2-3-3.9-5.6-3.9Z" />
                                </g>
                            </svg>
                        </div>
                        <h2 class="mt-4 font-bold text-base text-text-title">هنوز موردی در این بخش ثبت نشده</h2>
                        <p class="mx-auto mt-2 max-w-md text-[12px] leading-6 text-text-body">
                            می‌توانید شهر یا نوع حیوان دیگری را انتخاب کنید، یا خودتان اولین گزارش را ثبت کنید تا دیگران
                            ببینند.
                        </p>
                        <div class="mt-5 flex flex-wrap items-center justify-center gap-2">
                            <a href="{{ route($definition['hub']) }}" wire:navigate
                                class="rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-xs font-bold text-text-title transition hover:border-primary/30 hover:text-primary">
                                مشاهده همه {{ $definition['plural'] }}
                            </a>
                            <a href="{{ route('match-pets', ['mode' => $this->listingType === 'lost' ? 'lost' : 'found']) }}"
                                wire:navigate
                                class="rounded-xl bg-primary px-4 py-2.5 text-xs font-bold text-white transition hover:bg-primary-hover">
                                جست‌وجو روی نقشه
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-9">
                {{ $pets->links('partials.seo-pagination') }}
            </div>
        </div>
    </section>

    <!-- Internal links: cities -->
    @if (count($cityLinks) > 1)
        <section class="mt-10 px-5 md:mt-14 md:px-10">
            <div class="mx-auto w-full max-w-6xl rounded-3xl border border-border-custom bg-bg-main p-5 shadow-sm md:p-7">
                <h2 class="font-bold text-base text-text-title md:text-lg">
                    {{ $definition['plural'] }} در شهرهای ایران
                </h2>
                <p class="mt-1 text-[11px] leading-5 text-text-body md:text-xs">
                    برای دیدن گزارش‌های هر شهر، روی نام شهر بزنید.
                </p>

                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($cityLinks as $link)
                        <a href="{{ $link['url'] }}" wire:navigate
                            class="inline-flex items-center gap-1.5 rounded-full border px-3.5 py-2 text-[11px] font-bold transition {{ $link['active'] ? 'border-primary/30 bg-primary/10 text-primary' : 'border-border-custom bg-bg-secondary text-text-body hover:border-primary/30 hover:text-primary' }}">
                            {{ $link['label'] }}
                            <span class="text-[10px] font-medium text-text-muted">{{ $link['total'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('partials.site-footer')
</main>
