<?php

use App\Models\Pet;
use Livewire\Component;

new class extends Component {
    public string $mode = 'lost';
    public string $targetType = 'found';

    public ?float $selectedLatitude = null;
    public ?float $selectedLongitude = null;
    public string $locationPermission = 'unknown';

    public string $selectedCity = 'تهران';

    public function mount(): void
    {
        $mode = request()->query('mode');

        if (!in_array($mode, ['lost', 'found'], true)) {
            abort(404);
        }

        $this->mode = $mode;
        $this->targetType = $this->mode === 'lost' ? 'found' : 'lost';

        $firstCity = Pet::query()->whereNotNull('city')->where('city', '!=', '')->distinct()->orderBy('city')->value('city');
        if ($firstCity) {
            $this->selectedCity = $firstCity;
        }
    }

    public function setLocation(float $latitude, float $longitude): void
    {
        $this->selectedLatitude = $latitude;
        $this->selectedLongitude = $longitude;
        $this->locationPermission = 'granted';
    }

    public function setLocationPermission(string $permission): void
    {
        if (in_array($permission, ['unknown', 'granted', 'denied', 'error'], true)) {
            $this->locationPermission = $permission;
        }
    }

    public function with(): array
    {
        $baseQuery = Pet::query()->with('images')->active()->ofType($this->targetType);

        if ($this->selectedLatitude !== null && $this->selectedLongitude !== null) {
            $pets = $baseQuery->withinRadiusKm($this->selectedLatitude, $this->selectedLongitude, 5)->orderBy('distance_km')->orderByDesc('created_at')->take(40)->get();
        } else {
            $pets = $baseQuery->inCity($this->selectedCity)->latest()->take(40)->get();
        }

        return [
            'pets' => $pets,
            'cities' => Pet::query()->whereNotNull('city')->where('city', '!=', '')->distinct()->orderBy('city')->pluck('city'),
            'reportType' => $this->mode === 'lost' ? 'found' : 'lost',
        ];
    }
};
?>

<main class="flex-1 overflow-y-auto no-scrollbar pb-28 bg-bg-secondary" data-match-root
    wire:key="match-page-root-{{ $mode }}">

    <!-- Header Component -->
    <section class="px-5 py-4 border-b border-border-custom bg-bg-main sticky top-0 z-20 shadow-sm">
        <div class="max-w-6xl mx-auto flex items-center justify-between gap-3">
            <a href="/" wire:navigate
                class="w-9 h-9 rounded-full bg-bg-secondary border border-border-custom flex items-center justify-center text-text-title active:scale-95 transition shrink-0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </a>

            <div class="grow text-right">
                <h1 class="font-bold text-base md:text-lg text-text-title">
                    {{ $mode === 'lost' ? 'یافتن حیوانات پیدا شده نزدیک شما' : 'یافتن حیوانات گمشده نزدیک شما' }}
                </h1>
                <p class="text-[11px] md:text-xs text-text-body mt-0.5">
                    {{ $mode === 'lost' ? 'لیست حیوانات پیدا شده اطراف شما نمایش داده می‌شود.' : 'لیست حیوانات گمشده اطراف شما نمایش داده می‌شود.' }}
                </p>
            </div>
        </div>
    </section>

    <!-- Content Split Grid Layout for Responsive Views -->
    <div class="max-w-6xl mx-auto lg:p-6 md:p-4 grid grid-cols-1 md:grid-cols-12 gap-6 items-start">

        <!-- Left Sidebar: Map Options Box -->
        <section class="px-5 md:px-0 pt-4 md:pt-0 md:col-span-5 lg:col-span-4 md:sticky md:top-24 z-10"
            data-match-section>
            <div class="rounded-2xl border border-border-custom bg-bg-main p-4 shadow-sm space-y-3">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="font-bold text-sm text-text-title">موقعیت حیوان</h2>
                    <span
                        class="text-[10px] px-2 py-1 rounded-full border border-primary/20 bg-primary/10 text-primary font-bold">
                        شعاع جستجو: ۵ کیلومتر
                    </span>
                </div>

                <div class="rounded-2xl border border-border-custom bg-bg-main overflow-hidden shadow-sm">
                    <div class="h-64 md:h-72 w-full" wire:ignore data-match-map wire:key="match-map-container"
                        data-initial-latitude="{{ $selectedLatitude ?? 35.6892 }}"
                        data-initial-longitude="{{ $selectedLongitude ?? 51.389 }}"></div>
                </div>

                <p class="text-[10px] md:text-[11px] text-text-body leading-5" data-match-status>
                    برای نمایش نزدیک‌ترین موارد، اجازه دسترسی به موقعیت را بدهید یا روی نقشه نقطه انتخاب کنید.
                </p>

                <button type="button" data-match-request-location
                    class="w-full rounded-xl border border-primary/20 bg-primary/10 px-4 py-3 text-xs font-bold text-primary transition active:scale-[0.99] hover:bg-primary/15">
                    دریافت موقعیت فعلی
                </button>

                <div data-match-location-loading
                    class="hidden items-center justify-center gap-2 rounded-xl border border-border-custom bg-bg-secondary px-3 py-2 text-[11px] text-text-body">
                    <span class="w-3 h-3 rounded-full border-2 border-primary border-t-transparent animate-spin"></span>
                    در حال دریافت موقعیت...
                </div>

                <input type="hidden" data-match-latitude wire:model.live="selectedLatitude">
                <input type="hidden" data-match-longitude wire:model.live="selectedLongitude">
                <input type="hidden" data-match-permission wire:model.live="locationPermission">
            </div>
        </section>

        <!-- Right Pane: Results Grid & Additional Actions -->
        <div class="md:col-span-7 lg:col-span-8 flex flex-col gap-6 w-full">

            <!-- Cards Section Listing Items -->
            <section class="px-5 md:px-0">
                <div class="flex items-center justify-between mb-3.5">
                    <h2 class="font-bold text-base text-text-title">
                        {{ $targetType === 'found' ? 'گزارش‌های پیدا شده' : 'گزارش‌های گم شده' }}
                    </h2>
                    @if ($selectedLatitude !== null && $selectedLongitude !== null)
                        <span class="text-[10px] md:text-xs text-success-custom font-bold">مرتب‌سازی: نزدیک‌ترین</span>
                    @else
                        <span class="text-[10px] md:text-xs text-text-muted font-bold">فیلتر: شهر
                            {{ $selectedCity }}</span>
                    @endif
                </div>

                <!-- Skeleton Loader Animation Box -->
                <div wire:loading wire:target="selectedLatitude,selectedLongitude,locationPermission,selectedCity"
                    class="w-full">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-3 w-full">
                        @for ($i = 0; $i < 6; $i++)
                            <div
                                class="bg-card rounded-2xl overflow-hidden border border-border-custom shadow-sm animate-pulse">
                                <div class="h-28 md:h-36 bg-bg-secondary"></div>
                                <div class="p-2.5 space-y-2">
                                    <div class="h-3 rounded bg-bg-secondary"></div>
                                    <div class="h-2.5 rounded bg-bg-secondary w-3/4"></div>
                                    <div class="h-2.5 rounded bg-bg-secondary w-1/2"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Real Loop Content Cards Elements -->
                <div wire:loading.remove
                    wire:target="selectedLatitude,selectedLongitude,locationPermission,selectedCity"
                    class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-3">
                    @forelse($pets as $pet)
                        <a href="/pet/{{ $pet->id }}" wire:navigate
                            class="bg-card rounded-2xl overflow-hidden border border-border-custom shadow-sm hover:shadow-md transition group">
                            <div class="relative h-28 md:h-36 bg-bg-secondary overflow-hidden">
                                <img src="{{ $pet->images->first()?->image_url }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                    alt="{{ $pet->title }}">
                                <span
                                    class="absolute bottom-2 inset-s-2 text-white text-[10px] font-bold px-2 py-0.5 rounded-full {{ $pet->type === 'found' ? 'bg-success-custom' : 'bg-danger-custom' }}">
                                    {{ $pet->type === 'found' ? 'پیدا شده' : 'گم شده' }}
                                </span>
                            </div>
                            <div class="p-2.5">
                                <h3
                                    class="font-bold text-sm text-text-title text-ellipsis overflow-hidden whitespace-nowrap">
                                    {{ $pet->title }}</h3>
                                <p class="text-[11px] text-text-body mt-0.5">{{ $pet->pet_type }} ·
                                    {{ $pet->breed }}</p>
                                <p class="text-[11px] text-primary font-semibold mt-1">{{ $pet->city }}،
                                    {{ $pet->area }}</p>
                                @if (isset($pet->distance_km))
                                    <p class="text-[10px] text-success-custom font-bold mt-1">
                                        فاصله تقریبی: {{ number_format($pet->distance_km, 1) }} کیلومتر
                                    </p>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div
                            class="col-span-full text-center py-12 text-xs md:text-sm text-text-muted bg-bg-main rounded-2xl border border-dashed border-border-custom px-4">
                            موردی پیدا نشد. شهر دیگری را انتخاب کنید یا بعداً دوباره بررسی کنید.
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Bottom Prompt CTA Form Section -->
            <section class="px-5 md:px-0 mb-6">
                <div
                    class="rounded-2xl border border-border-custom bg-bg-main p-4 md:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-sm md:text-base text-text-title">
                            {{ $targetType === 'found' ? 'حیوانتو' : 'صاحبشو' }} را پیدا نکردی؟</h3>
                        <p class="text-[11px] md:text-xs text-text-body mt-1 leading-5">
                            یک گزارش جدید ثبت کن تا افراد بیشتری ببینند.
                        </p>
                    </div>

                    <div class="sm:shrink-0">
                        @auth
                            <a href="/report?type={{ $mode }}" wire:navigate
                                class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-primary text-white text-xs font-bold px-5 py-3 active:scale-[0.99] transition shadow-md shadow-primary/10">
                                ثبت گزارش {{ $reportType === 'lost' ? 'پیدا شدن' : 'گم کردن' }}
                            </a>
                        @else
                            <button type="button" @click="$dispatch('open-auth')"
                                class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-primary text-white text-xs font-bold px-5 py-3 active:scale-[0.99] transition shadow-md shadow-primary/10">
                                برای ثبت گزارش ابتدا وارد شوید
                            </button>
                        @endauth
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
