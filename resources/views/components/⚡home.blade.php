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
            'nearPets' => $petsQuery->latest()->take(4)->get(),
            'lostPets' => $lostPets,
        ];
    }
};
?>

<main class="flex-1 overflow-y-auto no-scrollbar pb-28">

    <section
        class="relative bg-gradient-to-b from-saffron-300/20 via-saffron-100/50 to-sand px-5 pt-2 pb-12 rounded-b-[2.5rem]">
        <span
            class="inline-flex items-center gap-1.5 bg-white/80 text-ink-soft text-xs font-medium px-3 py-1.5 rounded-full shadow-sm">
            <span class="w-1.5 h-1.5 rounded-full bg-saffron-500"></span>
            صدها حیوون منتظر یه خونه‌ن
        </span>

        <h1 class="font-display text-[2.1rem] leading-[1.35] text-ink mt-4">
            برای هر حیوونی،<br><span class="text-saffron-600">یه خونه.</span>
        </h1>
        <p class="text-ink-soft text-sm leading-7 mt-2 max-w-[19rem]">
            فرزندخواهی کن، میزبان موقت باش، یا کمک کن یه حیوون گمشده برگرده خونه.
        </p>

        <div class="relative -mb-16 mt-5 bg-white rounded-[1.4rem] shadow-tag p-2 flex items-center gap-2 -rotate-1">
            <span class="tag-hole" aria-hidden="true"></span>
            <button type="button"
                class="flex items-center gap-1 shrink-0 ps-3 pe-2.5 py-2 rounded-2xl bg-sand-100 text-ink-soft text-xs font-medium">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z" />
                    <circle cx="12" cy="9.5" r="2.3" />
                </svg>
                {{ $city }}
            </button>
            <input type="text" wire:model.live.debounce.250ms="search" placeholder="دنبال چی می‌گردی؟ گربه، سگ..."
                class="flex-1 bg-transparent text-sm text-ink placeholder:text-ink-soft/70 outline-none px-1 min-w-0">
        </div>
    </section>

    <section class="pt-20 px-5">
        <h2 class="font-bold text-base text-ink mb-3">چی می‌خوای انجام بدی؟</h2>
        <div class="flex gap-3 overflow-x-auto no-scrollbar -mx-5 px-5">
            <button wire:click="setTab('adopt')"
                class="shrink-0 w-[12.5rem] text-right @if ($activeTab == 'adopt') bg-white border border-ink/5 @else bg-saffron-50 border-saffron-100 @endif border rounded-2xl p-4 flex flex-col gap-2.5 active:scale-95 transition-transform">
                <div class="w-10 h-10 rounded-xl bg-saffron-100 flex items-center justify-center text-saffron-500">🐾
                </div>
                <h3 class="font-bold text-sm text-ink">فرزندخواهی</h3>
                <p class="text-xs text-ink-soft leading-5">صاحب همیشگی یه دوست تازه شو</p>
            </button>
            <button wire:click="setTab('found')"
                class="shrink-0 w-[12.5rem] text-right @if ($activeTab == 'found') bg-white border border-ink/5 @else bg-saffron-50 border-saffron-100 @endif border rounded-2xl p-4 flex flex-col gap-2.5 active:scale-95 transition-transform">
                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500">🏡</div>
                <h3 class="font-bold text-sm text-ink">گزارش حیوان گم شده</h3>
                <p class="text-xs text-ink-soft leading-5">کمک کن تا برگرده به جایی که تعلق دارهس</p>
            </button>
        </div>
    </section>

    <section class="pt-8 px-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-bold text-base text-ink">نزدیک تو</h2>
            <a href="#" class="text-xs font-semibold text-saffron-600">مشاهده همه</a>
        </div>

        <div class="inline-flex bg-sand-100 rounded-full p-1 mb-4 text-xs font-medium ring-1 ring-black/5">
            <button wire:click="setTab('all')"
                class="px-3.5 py-1.5 rounded-full transition {{ $activeTab === 'all' ? 'bg-white text-ink shadow-sm' : 'text-ink-soft' }}">همه</button>
            <button wire:click="setTab('adopt')"
                class="px-3.5 py-1.5 rounded-full transition {{ $activeTab === 'adopt' ? 'bg-white text-ink shadow-sm' : 'text-ink-soft' }}">فرزندخواهی</button>
            <button wire:click="setTab('found')"
                class="px-3.5 py-1.5 rounded-full transition {{ $activeTab === 'found' ? 'bg-white text-ink shadow-sm' : 'text-ink-soft' }}">پیدا
                شده</button>
        </div>

        <div class="grid grid-cols-2 gap-3">
            @forelse($nearPets as $pet)
                <a href="/pet/{{ $pet->id }}" wire:navigate
                    class="bg-white rounded-2xl overflow-hidden ring-1 ring-black/5 shadow-sm">
                    <div class="relative h-28 bg-sand-100">
                        <img src="{{ $pet->images->first()?->image_url }}" class="w-full h-full object-cover"
                            alt="{{ $pet->title }}">
                        <span
                            class="absolute bottom-2 start-2 text-white text-[10px] font-bold px-2 py-0.5 rounded-full {{ $pet->type === 'adoption' ? 'bg-saffron-500' : 'bg-green-600' }}">
                            {{ $pet->type === 'adoption' ? 'فرزندخواهی' : ($pet->type === 'found' ? 'پیدا شده' : 'گم شده') }}
                        </span>
                    </div>
                    <div class="p-2.5">
                        <h3 class="font-bold text-sm text-ink text-ellipsis overflow-hidden whitespace-nowrap">
                            {{ $pet->title }}</h3>
                        <p class="text-[11px] text-ink-soft mt-0.5">{{ $pet->pet_type }} · {{ $pet->breed }}</p>
                        <p class="text-[11px] text-saffron-600 font-semibold mt-1">{{ $pet->city }}،
                            {{ $pet->area }}</p>
                    </div>
                </a>
            @empty
                <div
                    class="col-span-2 text-center py-8 text-xs text-ink-soft bg-white/50 rounded-2xl border border-dashed border-ink/10">
                    هیچ موردی پیدا نشد.</div>
            @endforelse
        </div>
    </section>

    <section class="pt-8 px-5">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <h2 class="font-bold text-base text-ink">گزارش‌های گمشدگی</h2>
            </div>
            <a href="#" class="text-xs font-semibold text-saffron-600">مشاهده همه</a>
        </div>

        <div class="flex flex-col gap-3">
            @forelse($lostPets as $lost)
                <a href="/pet/{{ $lost->id }}" wire:navigate
                    class="flex items-center gap-3 bg-white rounded-2xl p-3 border-s-4 border-coral shadow-sm">
                    <img src="{{ $lost->images->first()?->image_url }}"
                        class="w-14 h-14 rounded-xl object-cover shrink-0 bg-sand-100">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-sm text-ink truncate">{{ $lost->title }}</h3>
                        <p class="text-[11px] text-ink-soft mt-0.5">منطقه: {{ $lost->city }}، {{ $lost->area }}
                        </p>
                    </div>
                    <a href="tel:{{ $lost->phone }}"
                        class="shrink-0 text-xs font-bold text-coral-dark border border-coral/30 rounded-full px-3 py-1.5 bg-coral-light hover:bg-coral hover:text-white transition-colors">دیدمش</a>
                </a>
            @empty
                <div
                    class="text-center py-6 text-xs text-ink-soft bg-white/50 rounded-2xl border border-dashed border-ink/10">
                    گزارش جدیدی ثبت نشده است.</div>
            @endforelse
        </div>
    </section>

</main>
