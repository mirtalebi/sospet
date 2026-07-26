<?php

use App\Models\Pet;
use Livewire\Component;
use Livewire\Attributes\Url;

new class extends Component {
    #[Url]
    public $activeTab = 'all';

    #[Url]
    public $search = '';

    public $city = 'تهران';

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function with(): array
    {
        // ۱. واکشی حیوانات نزدیک (واگذاری یا پیدا شده) بر اساس فیلترها
        $petsQuery = Pet::with('images')
            ->where('is_resolved', false)
            ->whereIn('type', ['adoption', 'found']);

        if ($this->activeTab !== 'all') {
            $mappedType = $this->activeTab === 'adopt' ? 'adoption' : 'found';
            $petsQuery->where('type', $mappedType);
        }

        if ($this->search) {
            $petsQuery->where(function ($query) {
                $query
                    ->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('pet_type', 'like', '%' . $this->search . '%')
                    ->orWhere('breed', 'like', '%' . $this->search . '%');
            });
        }

        // ۲. واکشی گزارش‌های گمشدگی (فوری)
        $lostPets = Pet::with('images')->where('type', 'lost')->where('is_resolved', false)->latest()->take(3)->get();

        return [
            'nearPets' => $petsQuery->latest()->take(8)->get(),
            'lostPets' => $lostPets,
        ];
    }
};
?>

<main class="flex-1 overflow-y-auto no-scrollbar pb-28 bg-bg-secondary">

    <!-- Top Hero Section -->
    <section
        class="relative bg-gradient-to-b from-primary/10 via-primary/5 to-bg-secondary px-5 pt-2 pb-12 rounded-b-[2.5rem]">
        {{-- <span
            class="inline-flex items-center gap-1.5 bg-bg-main text-text-muted text-xs font-medium px-3 py-1.5 rounded-full shadow-sm">
            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
            صدها حیوون منتظر یه خونه‌ن
        </span> --}}

        <div class="flex justify-between">
            <h1 class="font-display text-[2.1rem] leading-[1.35] text-text-title mt-4">
                برای تپیدن

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"
                    class="inline-block w-[1.15em] h-[1.15em] align-[-0.15em] mx-1" aria-hidden="true">
                    <!-- Heart -->
                    <path fill="#ef4444" d="M32 58
               C30 56 9 41 9 22
               C9 14 15 8 23 8
               C27 8 30 10 32 14
               C34 10 37 8 41 8
               C49 8 55 14 55 22
               C55 41 34 56 32 58Z" />

                    <!-- Paw -->
                    <g fill="#FFF7ED" transform="translate(18 17) rotate(-18 14 14)">
                        <!-- Main pad -->
                        <path d="M10 12
                   C7 12 5 14.5 5 18
                   C5 22.5 8 25.5 12 25.5
                   C16 25.5 19 22.5 19 18
                   C19 14.5 17 12 14 12
                   C13 11.5 11 11.5 10 12Z" />

                        <!-- Toes -->
                        <circle cx="5" cy="9" r="2.3" />
                        <circle cx="10" cy="5.5" r="2.4" />
                        <circle cx="16" cy="5.5" r="2.4" />
                        <circle cx="21" cy="9" r="2.3" />
                    </g>
                </svg>

                خونه ها
            </h1>

            <img src="/assets/img/chat-1.png" class="w- h-28 mt-2" alt="Hero Image">
        </div>
        {{-- <p class="text-text-body text-sm leading-7 mt-2 max-w-[19rem]">
            فرزندخواهی کن، میزبان موقت باش، یا کمک کن یه حیوون گمشده برگرده خونه.
        </p> --}}

        <!-- Search Bar Container -->
        <div
            class="relative -mb-16 mt-5 bg-bg-main border border-border-custom rounded-[1.4rem] shadow-sm p-2 flex items-center gap-2">
            <span class="tag-hole" aria-hidden="true"></span>
            <button type="button"
                class="flex items-center gap-1 shrink-0 ps-3 pe-2.5 py-2 rounded-2xl bg-bg-secondary text-text-body text-xs font-medium">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z" />
                    <circle cx="12" cy="9.5" r="2.3" />
                </svg>
                {{ $city }}
            </button>
            <input type="text" wire:model.live.debounce.250ms="search" placeholder="دنبال چی می‌گردی؟ گربه، سگ..."
                class="flex-1 bg-transparent text-sm text-text-title placeholder:text-text-muted outline-none px-1 min-w-0">
        </div>
    </section>

    <!-- Quick Actions -->
    {{-- <section class="pt-20 px-5">
        <h2 class="font-bold text-base text-text-title mb-3">چی می‌خوای انجام بدی؟</h2>
        <div class="flex gap-3 overflow-x-auto no-scrollbar -mx-5 px-5">

            <!-- Adopt Tab Button -->
            <button wire:click="setTab('adopt')"
                class="shrink-0 w-[12.5rem] text-right @if ($activeTab == 'adopt') bg-bg-main border-primary @else bg-bg-main border-border-custom @endif border rounded-2xl p-4 flex flex-col gap-2.5 active:scale-95 transition-transform shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-lg">🐾
                </div>
                <h3 class="font-bold text-sm text-text-title">فرزندخواهی</h3>
                <p class="text-xs text-text-body leading-5">صاحب همیشگی یه دوست تازه شو</p>
            </button>

            <!-- Found/Lost Tab Button -->
            <button wire:click="setTab('found')"
                class="shrink-0 w-[12.5rem] text-right @if ($activeTab == 'found') bg-bg-main border-primary @else bg-bg-main border-border-custom @endif border rounded-2xl p-4 flex flex-col gap-2.5 active:scale-95 transition-transform shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-cta/10 flex items-center justify-center text-cta text-lg">🏡</div>
                <h3 class="font-bold text-sm text-text-title">گزارش حیوان گم شده</h3>
                <p class="text-xs text-text-body leading-5">کمک کن تا برگرده به جایی که تعلق دارهس</p>
            </button>
        </div>
    </section> --}}

    <div class="mt-10 px-4 flex flex-col gap-3">

        <!-- دکمه اول: حیوانم گم شده -->
        <a href="{{ route('match-pets', ['mode' => 'lost']) }}" wire:navigate
            class="rounded-2xl p-4 flex items-center gap-3.5 w-full text-right active:scale-[0.98] transition-transform border bg-bg-main border-border-custom">
            <!-- فلش سمت چپ -->
            <span class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 text-white bg-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
            </span>
            <!-- متن آگهی -->
            <span class="flex-1 min-w-0 block text-right">
                <span class="block font-extrabold text-[14px] mb-0.5 text-primary">حیوانم گم شده</span>
                <span class="block text-[11.5px] leading-relaxed truncate text-text-body">آگهی گمشده‌تان را ثبت کنید تا
                    سریع‌تر پیدا شود</span>
            </span>
            <!-- آیکون سمت راست (ClipboardList) -->
            <span class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 bg-primary/10 text-primary">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    <line x1="9" y1="12" x2="15" y2="12"></line>
                    <line x1="9" y1="16" x2="15" y2="16"></line>
                    <line x1="9" y1="8" x2="13" y2="8"></line>
                </svg>
            </span>
        </a>

        <!-- دکمه دوم: حیوانی پیدا کرده‌ام -->
        <a href="{{ route('match-pets', ['mode' => 'found']) }}" wire:navigate
            class="rounded-2xl p-4 flex items-center gap-3.5 w-full text-right active:scale-[0.98] transition-transform border bg-bg-main border-border-custom">
            <!-- فلش سمت چپ -->
            <span class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 text-white bg-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
            </span>
            <!-- متن آگهی -->
            <span class="flex-1 min-w-0 block text-right">
                <span class="block font-extrabold text-[14px] mb-0.5 text-primary">حیوانی پیدا کرده‌ام</span>
                <span class="block text-[11.5px] leading-relaxed truncate text-text-body">حیوان پیدا‌شده را ثبت کنید تا
                    به صاحبش برگردد</span>
            </span>
            <!-- آیکون سمت راست (MapPin) -->
            <span class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 bg-primary/10 text-primary">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </span>
        </a>

        <!-- دکمه سوم: سرپرستی و واگذاری (CTA) -->
        <button type="button"
            class="rounded-2xl p-4 flex items-center gap-3.5 w-full text-right active:scale-[0.98] transition-transform border bg-bg-main border-border-custom">
            <!-- فلش سمت چپ -->
            <span class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 text-white bg-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
            </span>
            <!-- متن آگهی -->
            <span class="flex-1 min-w-0 block text-right">
                <span class="block font-extrabold text-[14px] mb-0.5 text-primary">سرپرستی و واگذاری</span>
                <span class="block text-[11.5px] leading-relaxed truncate text-text-body">به حیوانات بی‌سرپرست خانه‌ای
                    امن ببخشید</span>
            </span>
            <!-- آیکون سمت راست (Home) -->
            <span class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 bg-primary/10 text-primary">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </span>
        </button>

    </div>

    <!-- Near You Section -->
    <section class="pt-8 px-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-bold text-base text-text-title">نزدیک تو</h2>
            <a href="#" class="text-xs font-semibold text-primary hover:text-primary-hover">مشاهده همه</a>
        </div>

        <!-- Filter Sub-tabs -->
        <div class="inline-flex bg-bg-secondary border border-border-custom rounded-full p-1 mb-4 text-xs font-medium">
            <button wire:click="setTab('all')"
                class="px-3.5 py-1.5 rounded-full transition {{ $activeTab === 'all' ? 'bg-bg-main text-text-title shadow-sm' : 'text-text-body' }}">همه</button>
            <button wire:click="setTab('adopt')"
                class="px-3.5 py-1.5 rounded-full transition {{ $activeTab === 'adopt' ? 'bg-bg-main text-text-title shadow-sm' : 'text-text-body' }}">فرزندخواهی</button>
            <button wire:click="setTab('found')"
                class="px-3.5 py-1.5 rounded-full transition {{ $activeTab === 'found' ? 'bg-bg-main text-text-title shadow-sm' : 'text-text-body' }}">پیدا
                شده</button>
        </div>

        <!-- Grid Results -->
        <div class="grid grid-cols-2 gap-3">
            @forelse($nearPets as $pet)
                <a href="/pet/{{ $pet->id }}" wire:navigate
                    class="bg-card rounded-2xl overflow-hidden border border-border-custom shadow-sm">
                    <div class="relative h-28 bg-bg-secondary">
                        <img src="{{ $pet->images->first()?->image_url }}" class="w-full h-full object-cover"
                            alt="{{ $pet->title }}">

                        <!-- Status Badges mapped perfectly to success / primary -->
                        <span
                            class="absolute bottom-2 start-2 text-white text-[10px] font-bold px-2 py-0.5 rounded-full {{ $pet->type === 'adoption' ? 'bg-primary' : 'bg-success-custom' }}">
                            {{ $pet->type === 'adoption' ? 'فرزندخواهی' : ($pet->type === 'found' ? 'پیدا شده' : 'گم شده') }}
                        </span>
                    </div>
                    <div class="p-2.5">
                        <h3 class="font-bold text-sm text-text-title text-ellipsis overflow-hidden whitespace-nowrap">
                            {{ $pet->title }}</h3>
                        <p class="text-[11px] text-text-body mt-0.5">{{ $pet->pet_type }} · {{ $pet->breed }}</p>
                        <p class="text-[11px] text-primary font-semibold mt-1">{{ $pet->city }}،
                            {{ $pet->area }}</p>
                    </div>
                </a>
            @empty
                <div
                    class="col-span-2 text-center py-8 text-xs text-text-muted bg-bg-main rounded-2xl border border-dashed border-border-custom">
                    هیچ موردی پیدا نشد.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Urgent/Lost Reports Section -->
    {{-- <section class="pt-8 px-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-bold text-base text-text-title">گزارش‌های گمشدگی</h2>
            <a href="#" class="text-xs font-semibold text-primary hover:text-primary-hover">مشاهده همه</a>
        </div>

        <div class="flex flex-col gap-3">
            @forelse($lostPets as $lost)
                <a href="/pet/{{ $lost->id }}" wire:navigate
                    class="flex items-center gap-3 bg-card rounded-2xl p-3 border-s-4 border-danger-custom border-y border-e border-border-custom shadow-sm">
                    <img src="{{ $lost->images->first()?->image_url }}"
                        class="w-14 h-14 rounded-xl object-cover shrink-0 bg-bg-secondary">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-sm text-text-title truncate">{{ $lost->title }}</h3>
                        <p class="text-[11px] text-text-body mt-0.5">منطقه: {{ $lost->city }}، {{ $lost->area }}
                        </p>
                    </div>

                    <!-- Call Button mapped to CTA styling -->
                    <a href="tel:{{ $lost->phone }}"
                        class="shrink-0 text-xs font-bold text-cta border border-cta/30 rounded-full px-3 py-1.5 bg-cta/10 hover:bg-cta hover:text-white transition-colors">
                        دیدمش
                    </a>
                </a>
            @empty
                <div
                    class="text-center py-6 text-xs text-text-muted bg-bg-main rounded-2xl border border-dashed border-border-custom">
                    گزارش جدیدی ثبت نشده است.
                </div>
            @endforelse
        </div>
    </section> --}}

</main>
