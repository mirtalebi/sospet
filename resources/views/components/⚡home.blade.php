<?php

use App\Models\Pet;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

new class extends Component {
    use WithPagination;

    #[Url]
    public $activeTab = 'all';

    #[Url]
    public $search = '';

    public $city = 'تهران';

    // Tracks total items to show; increments on mobile scroll
    public $perPage = 8;

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Triggered automatically when mobile user scrolls down
    public function loadMore()
    {
        $this->perPage += 8;
    }

    public function with(): array
    {
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

        $lostPets = Pet::with('images')->where('type', 'lost')->where('is_resolved', false)->latest()->take(3)->get();
        $provinces = DB::table('province_cities')->where('parent', 0)->get();

        return [
            // Changed from ->take()->get() to ->paginate() to support both pagination strategies
            'nearPets' => $petsQuery->latest()->paginate($this->perPage),
            'lostPets' => $lostPets,
            'provinces' => $provinces,
        ];
    }
};
?>

<div class="flex flex-col min-h-screen bg-bg-secondary">
    <main class="flex-1 overflow-y-auto no-scrollbar pb-12">

        <!-- Top Hero Section -->
        <section
            class="relative bg-gradient-to-b from-primary/10 via-primary/5 to-bg-secondary px-5 pt-2 pb-12 md:px-10 md:pt-6 md:pb-20 rounded-b-[2.5rem] md:rounded-b-[4rem]">
            <div class="max-w-6xl mx-auto w-full">
                <div class="flex justify-between md:items-center md:max-w-4xl md:mx-auto">
                    <h1 class="font-display text-[2.1rem] md:text-5xl leading-[1.35] text-text-title mt-4">
                        برای تپیدن
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"
                            class="inline-block w-[1.15em] h-[1.15em] align-[-0.15em] mx-1" aria-hidden="true">
                            <path fill="#ef4444"
                                d="M32 58 C30 56 9 41 9 22 C9 14 15 8 23 8 C27 8 30 10 32 14 C34 10 37 8 41 8 C49 8 55 14 55 22 C55 41 34 56 32 58Z" />
                            <g fill="#FFF7ED" transform="translate(18 17) rotate(-18 14 14)">
                                <path
                                    d="M10 12 C7 12 5 14.5 5 18 C5 22.5 8 25.5 12 25.5 C16 25.5 19 22.5 19 18 C19 14.5 17 12 14 12 C13 11.5 11 11.5 10 12Z" />
                                <circle cx="5" cy="9" r="2.3" />
                                <circle cx="10" cy="5.5" r="2.4" />
                                <circle cx="16" cy="5.5" r="2.4" />
                                <circle cx="21" cy="9" r="2.3" />
                            </g>
                        </svg>
                        خونه ها
                    </h1>
                    <img src="/assets/img/chat-1.png" class="h-28 mt-2 md:h-40 object-contain" alt="Hero Image">
                </div>

                <!-- Search Bar Container -->
                <div
                    class="relative -mb-16 mt-5 bg-bg-main border border-border-custom rounded-[1.4rem] shadow-sm p-2 flex items-center gap-2 md:max-w-2xl md:mx-auto md:mt-10 md:-mb-26">
                    <span class="tag-hole" aria-hidden="true"></span>
                    <button id="cityBtn" type="button"
                        class="flex items-center gap-1 shrink-0 ps-3 pe-2.5 py-2 rounded-2xl bg-bg-secondary text-text-body text-xs font-medium">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z" />
                            <circle cx="12" cy="9.5" r="2.3" />
                        </svg>
                        <span id="cityLabel">{{ $city }}</span>
                    </button>

                    <!-- City Modal -->
                    <div id="cityModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
                        <div class="bg-white rounded-lg p-5 w-full max-w-sm mx-4">
                            <h3 class="text-right font-bold mb-2">شهر خود را انتخاب کنید</h3>
                            <select id="cityInput" class="w-full border rounded p-2 text-right mb-3">
                                <option value="" disabled selected>استان یا شهر...</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->title }}</option>
                                @endforeach
                            </select>
                            <div class="flex justify-between gap-2">
                                <button id="cityCancel"
                                    class="flex-1 px-3 py-2 rounded bg-border-custom">انصراف</button>
                                <button id="citySave"
                                    class="flex-1 px-3 py-2 rounded bg-primary text-white">ذخیره</button>
                            </div>
                        </div>
                    </div>

                    <script>
                        (function() {
                            var btn = document.getElementById('cityBtn');
                            var modal = document.getElementById('cityModal');
                            var input = document.getElementById('cityInput');
                            var save = document.getElementById('citySave');
                            var cancel = document.getElementById('cityCancel');
                            var label = document.getElementById('cityLabel');

                            function setCookie(name, value, days) {
                                var d = new Date();
                                d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
                                document.cookie = name + '=' + encodeURIComponent(value) + ';path=/;expires=' + d.toUTCString();
                            }

                            function getCookie(name) {
                                var re = new RegExp('(?:^|; )' + name + '=([^;]*)');
                                var m = document.cookie.match(re);
                                return m ? decodeURIComponent(m[1]) : null;
                            }

                            var provinces = @json($provinces);
                            var saved = getCookie('sospet_city');
                            if (saved) label.textContent = provinces.find(p => p.id == saved)?.title || saved;

                            btn.addEventListener('click', function() {
                                input.value = label.textContent || '';
                                modal.classList.remove('hidden');
                                modal.classList.add('flex');
                                input.focus();
                            });

                            cancel.addEventListener('click', function() {
                                modal.classList.add('hidden');
                                modal.classList.remove('flex');
                            });

                            save.addEventListener('click', function() {
                                var v = input.value.trim();
                                if (!v) return;
                                setCookie('sospet_city', v, 365);
                                label.textContent = provinces.find(p => p.id == v)?.title || saved;
                                modal.classList.add('hidden');
                                modal.classList.remove('flex');
                            });

                            modal.addEventListener('click', function(e) {
                                if (e.target === modal) {
                                    modal.classList.add('hidden');
                                    modal.classList.remove('flex');
                                }
                            });
                        })();
                    </script>
                    <input type="text" wire:model.live.debounce.250ms="search"
                        placeholder="دنبال چی می‌گردی؟ گربه، سگ..."
                        class="flex-1 bg-transparent text-sm text-text-title placeholder:text-text-muted outline-none px-1 min-w-0">
                </div>
            </div>
        </section>

        <!-- Quick Actions -->
        <div
            class="mt-20 px-4 flex flex-col gap-3 md:grid md:grid-cols-3 md:gap-5 md:px-10 md:mt-32 max-w-6xl mx-auto w-full">
            <a href="{{ route('match-pets', ['mode' => 'lost']) }}" wire:navigate
                class="rounded-2xl p-4 flex items-center gap-3.5 w-full text-right active:scale-[0.98] transition-transform border bg-bg-main border-border-custom md:flex-col-reverse md:justify-between md:items-start md:p-6">
                <span
                    class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 text-white bg-primary md:self-end">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                </span>
                <span class="flex-1 min-w-0 block text-right md:mt-4">
                    <span class="block font-extrabold text-[14px] md:text-base mb-0.5 text-primary">حیوانم گم شده</span>
                    <span
                        class="block text-[11.5px] md:text-xs leading-relaxed text-text-body md:whitespace-normal">آگهی
                        گمشده‌تان را ثبت کنید تا سریع‌تر پیدا شود</span>
                </span>
                <span
                    class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 bg-primary/10 text-primary">
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

            <a href="{{ route('match-pets', ['mode' => 'found']) }}" wire:navigate
                class="rounded-2xl p-4 flex items-center gap-3.5 w-full text-right active:scale-[0.98] transition-transform border bg-bg-main border-border-custom md:flex-col-reverse md:justify-between md:items-start md:p-6">
                <span
                    class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 text-white bg-primary md:self-end">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                </span>
                <span class="flex-1 min-w-0 block text-right md:mt-4">
                    <span class="block font-extrabold text-[14px] md:text-base mb-0.5 text-primary">حیوانی پیدا
                        کرده‌ام</span>
                    <span
                        class="block text-[11.5px] md:text-xs leading-relaxed text-text-body md:whitespace-normal">حیوان
                        پیدا‌شده را ثبت کنید تا به صاحبش برگردد</span>
                </span>
                <span
                    class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 bg-primary/10 text-primary">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </span>
            </a>

            <button type="button"
                class="rounded-2xl p-4 flex items-center gap-3.5 w-full text-right active:scale-[0.98] transition-transform border bg-bg-main border-border-custom md:flex-col-reverse md:justify-between md:items-start md:p-6">
                <span
                    class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 text-white bg-primary md:self-end">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                </span>
                <span class="flex-1 min-w-0 block text-right md:mt-4">
                    <span class="block font-extrabold text-[14px] md:text-base mb-0.5 text-primary">سرپرستی و
                        واگذاری</span>
                    <span class="block text-[11.5px] md:text-xs leading-relaxed text-text-body md:whitespace-normal">به
                        حیوانات بی‌سرپرست خانه‌ای امن ببخشید</span>
                </span>
                <span
                    class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 bg-primary/10 text-primary">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </span>
            </button>
        </div>

        <!-- Near You Section -->
        <section class="pt-8 px-5 md:px-10 md:pt-14 max-w-6xl mx-auto w-full">
            <div class="flex items-center justify-between mb-3 md:mb-5">
                <h2 class="font-bold text-base md:text-xl text-text-title">نزدیک تو</h2>
                <a href="#"
                    class="text-xs font-semibold text-primary hover:text-primary-hover md:text-sm">مشاهده همه</a>
            </div>

            <!-- Filter Sub-tabs -->
            <div
                class="inline-flex bg-bg-secondary border border-border-custom rounded-full p-1 mb-4 text-xs font-medium md:mb-6">
                <button wire:click="setTab('all')"
                    class="px-3.5 py-1.5 md:px-5 md:py-2 rounded-full transition {{ $activeTab === 'all' ? 'bg-bg-main text-text-title shadow-sm' : 'text-text-body' }}">همه</button>
                <button wire:click="setTab('adopt')"
                    class="px-3.5 py-1.5 md:px-5 md:py-2 rounded-full transition {{ $activeTab === 'adopt' ? 'bg-bg-main text-text-title shadow-sm' : 'text-text-body' }}">فرزندخواهی</button>
                <button wire:click="setTab('found')"
                    class="px-3.5 py-1.5 md:px-5 md:py-2 rounded-full transition {{ $activeTab === 'found' ? 'bg-bg-main text-text-title shadow-sm' : 'text-text-body' }}">پیدا
                    شده</button>
            </div>

            <!-- Grid Results -->
            <div class="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4 md:gap-5">
                @forelse($nearPets as $pet)
                    <a href="/pet/{{ $pet->id }}" wire:navigate
                        class="bg-card rounded-2xl overflow-hidden border border-border-custom shadow-sm hover:shadow-md transition-shadow">
                        <div class="relative h-28 md:h-44 bg-bg-secondary">
                            <img src="{{ $pet->images->first()?->image_url }}" class="w-full h-full object-cover"
                                alt="{{ $pet->title }}">
                            <span
                                class="absolute bottom-2 start-2 text-white text-[10px] md:text-xs font-bold px-2 py-0.5 md:px-3 md:py-1 rounded-full {{ $pet->type === 'adoption' ? 'bg-primary' : 'bg-success-custom' }}">
                                {{ $pet->type === 'adoption' ? 'فرزندخواهی' : ($pet->type === 'found' ? 'پیدا شده' : 'گم شده') }}
                            </span>
                        </div>
                        <div class="p-2.5 md:p-4">
                            <h3
                                class="font-bold text-sm md:text-base text-text-title text-ellipsis overflow-hidden whitespace-nowrap">
                                {{ $pet->title }}</h3>
                            <p class="text-[11px] md:text-xs text-text-body mt-0.5">{{ $pet->pet_type }} ·
                                {{ $pet->breed }}</p>
                            <p class="text-[11px] md:text-xs text-primary font-semibold mt-1">{{ $pet->city }}،
                                {{ $pet->area }}</p>
                        </div>
                    </a>
                @empty
                    <div
                        class="col-span-2 md:col-span-3 lg:col-span-4 text-center py-12 text-xs md:text-sm text-text-muted bg-bg-main rounded-2xl border border-dashed border-border-custom">
                        هیچ موردی پیدا نشد.
                    </div>
                @endforelse
            </div>

            <!-- DESKTOP ONLY: Pagination Controls -->
            <div class="hidden md:block mt-10">
                {{ $nearPets->links('partials.custom-pagination') }}
            </div>

            <!-- MOBILE ONLY: Infinite Scroll Intersection Trigger -->
            @if ($nearPets->hasMorePages())
                <div class="block md:hidden text-center py-6 mt-4">
                    <div x-data x-intersect="$wire.loadMore()" class="h-2 w-full"></div>
                    <div wire:loading wire:target="loadMore"
                        class="text-xs text-text-muted flex items-center justify-center flex-row gap-2">
                        <svg class="animate-spin h-4 w-4 text-primary" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                </div>
            @endif
        </section>
    </main>

    <!-- DESKTOP ONLY: Footer Elements -->
    <footer class="hidden md:block bg-bg-main border-t border-border-custom py-8 mt-auto">
        <div
            class="max-w-6xl mx-auto px-10 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-text-body">
            <div>
                <p>© 2026 ساس‌پت. تمامی حقوق محفوظ است.</p>
            </div>
            <div class="flex gap-6 text-xs text-text-muted font-medium">
                <a href="#" class="hover:text-primary transition-colors">درباره ما</a>
                <a href="#" class="hover:text-primary transition-colors">تماس با پشتیبانی</a>
                <a href="#" class="hover:text-primary transition-colors">قوانین و مقررات</a>
                <a href="#" class="hover:text-primary transition-colors">حریم خصوصی</a>
            </div>
        </div>
    </footer>
</div>
