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
            ->active()
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

        $lostPets = Pet::with('images')->where('type', 'lost')->active()->latest()->take(3)->get();
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
    <main class="flex-1 overflow-y-auto no-scrollbar pb-40">

        <!-- Top Hero Section -->
        <section class="relative overflow-hidden px-5 pb-5 pt-4 md:px-10 md:pb-7 md:pt-5">
            <!-- Soft storybook backdrop -->
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-[#EAF6F8] via-[#FBF6EC] to-bg-secondary"></div>
            <div class="pointer-events-none absolute -top-20 start-[18%] h-56 w-56 rounded-full bg-primary/15 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 end-0 h-56 w-56 rounded-full bg-cta/10 blur-3xl"></div>

            <!-- Wandering paw prints -->
            <svg class="pointer-events-none absolute start-[5%] top-6 hidden h-8 w-8 -rotate-12 text-primary/10 lg:block"
                viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <g>
                    <ellipse cx="12" cy="5" rx="1.9" ry="2.4" />
                    <ellipse cx="6.9" cy="8" rx="1.7" ry="2.2" transform="rotate(-22 6.9 8)" />
                    <ellipse cx="17.1" cy="8" rx="1.7" ry="2.2" transform="rotate(22 17.1 8)" />
                    <ellipse cx="4.5" cy="13.2" rx="1.5" ry="1.9" transform="rotate(-38 4.5 13.2)" />
                    <ellipse cx="19.5" cy="13.2" rx="1.5" ry="1.9" transform="rotate(38 19.5 13.2)" />
                    <path d="M12 11.3c-2.5 0-4.6 1.6-5.5 3.8-.8 2 .4 4.2 2.6 4.6.9.2 1.9 0 2.9-.6.7-.4 1.4-.4 2.1 0 1 .6 2 .8 2.9.6 2.2-.4 3.4-2.6 2.6-4.6-.9-2.2-3-3.9-5.6-3.9Z" />
                </g>
            </svg>
            <svg class="pointer-events-none absolute end-[7%] bottom-8 h-7 w-7 rotate-12 text-cta/20" viewBox="0 0 24 24"
                fill="currentColor" aria-hidden="true">
                <g>
                    <ellipse cx="12" cy="5" rx="1.9" ry="2.4" />
                    <ellipse cx="6.9" cy="8" rx="1.7" ry="2.2" transform="rotate(-22 6.9 8)" />
                    <ellipse cx="17.1" cy="8" rx="1.7" ry="2.2" transform="rotate(22 17.1 8)" />
                    <ellipse cx="4.5" cy="13.2" rx="1.5" ry="1.9" transform="rotate(-38 4.5 13.2)" />
                    <ellipse cx="19.5" cy="13.2" rx="1.5" ry="1.9" transform="rotate(38 19.5 13.2)" />
                    <path d="M12 11.3c-2.5 0-4.6 1.6-5.5 3.8-.8 2 .4 4.2 2.6 4.6.9.2 1.9 0 2.9-.6.7-.4 1.4-.4 2.1 0 1 .6 2 .8 2.9.6 2.2-.4 3.4-2.6 2.6-4.6-.9-2.2-3-3.9-5.6-3.9Z" />
                </g>
            </svg>

            <div class="relative mx-auto w-full max-w-6xl">
                <div class="grid items-center gap-5 lg:grid-cols-[1fr_auto] lg:gap-10">

                    <!-- Copy + search -->
                    <div class="text-center lg:text-right">
                        <span
                            class="inline-flex items-center gap-1 rounded-full border border-primary/20 bg-white/70 px-2.5 py-1 text-[10px] font-bold text-primary shadow-sm backdrop-blur">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <g>
                                    <ellipse cx="12" cy="5" rx="1.9" ry="2.4" />
                                    <ellipse cx="6.9" cy="8" rx="1.7" ry="2.2" transform="rotate(-22 6.9 8)" />
                                    <ellipse cx="17.1" cy="8" rx="1.7" ry="2.2" transform="rotate(22 17.1 8)" />
                                    <ellipse cx="4.5" cy="13.2" rx="1.5" ry="1.9" transform="rotate(-38 4.5 13.2)" />
                                    <ellipse cx="19.5" cy="13.2" rx="1.5" ry="1.9" transform="rotate(38 19.5 13.2)" />
                                    <path d="M12 11.3c-2.5 0-4.6 1.6-5.5 3.8-.8 2 .4 4.2 2.6 4.6.9.2 1.9 0 2.9-.6.7-.4 1.4-.4 2.1 0 1 .6 2 .8 2.9.6 2.2-.4 3.4-2.6 2.6-4.6-.9-2.2-3-3.9-5.6-3.9Z" />
                                </g>
                            </svg>
                            به ساس‌پت خوش اومدی
                        </span>

                        <h1 class="mt-2 text-balance font-display text-[1.75rem] leading-[1.55] text-text-title sm:text-[2.1rem] lg:text-[2.5rem]">
                            برای تپیدن
                            <span class="whitespace-nowrap">
                                <span class="relative inline-block px-1 text-primary">
                                    خونه‌ها
                                    <svg class="absolute inset-x-0 -bottom-1 h-2 w-full text-cta/70" viewBox="0 0 160 12"
                                        preserveAspectRatio="none" fill="none" aria-hidden="true">
                                        <path d="M3 9C30 3 60 2 80 5s45 4 77-2" stroke="currentColor" stroke-width="4"
                                            stroke-linecap="round" />
                                    </svg>
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"
                                    class="mx-1 inline-block h-[0.85em] w-[0.85em] align-[-0.05em]" aria-hidden="true">
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
                            </span>
                        </h1>

                        <p class="mx-auto mt-2 max-w-md text-[11.5px] leading-6 text-text-body lg:mx-0 lg:max-w-lg lg:text-[13px]">
                            اینجا حیوون‌های گم‌شده دوباره پیدا می‌شن و بی‌خونه‌ها یه خانواده‌ی مهربون. تو هم می‌تونی بخشی
                            از این داستان باشی.
                        </p>

                        <!-- Search Bar -->
                        <div class="relative mx-auto mt-4 w-full max-w-md md:max-w-xl lg:mx-0">
                            <div
                                class="flex items-center gap-1 rounded-[1.15rem] border border-border-custom bg-white p-1 shadow-[0_16px_35px_-28px_rgba(34,50,74,0.55)] transition focus-within:border-primary/50 focus-within:ring-4 focus-within:ring-primary/10">
                                <button id="cityBtn" type="button"
                                    class="flex shrink-0 items-center gap-1.5 rounded-full bg-bg-secondary px-2.5 py-2 text-[11px] font-bold text-text-body transition hover:text-primary">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z" />
                                        <circle cx="12" cy="9.5" r="2.3" />
                                    </svg>
                                    <span id="cityLabel">{{ $city }}</span>
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" aria-hidden="true">
                                        <path d="M6 9l6 6 6-6" />
                                    </svg>
                                </button>

                                <span class="h-6 w-px shrink-0 bg-border-custom" aria-hidden="true"></span>

                                <svg class="ms-1 shrink-0 text-text-muted" width="17" height="17" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <circle cx="11" cy="11" r="7" />
                                    <path d="M20 20l-3.5-3.5" stroke-linecap="round" />
                                </svg>

                                <input type="text" wire:model.live.debounce.250ms="search"
                                    placeholder="دنبال چی می‌گردی؟ گربه، سگ..."
                                    class="min-w-0 flex-1 bg-transparent px-1 py-1.5 text-[13px] text-text-title placeholder:text-text-muted outline-none">
                            </div>

                            <div class="mt-2 flex flex-wrap items-center justify-center gap-1.5 lg:justify-start">
                                <span class="text-[10px] font-bold text-text-muted">پیشنهاد دوستانه:</span>
                                @foreach (['گربه', 'سگ', 'پیدا شده'] as $suggestion)
                                    <button type="button" wire:click="$set('search', '{{ $suggestion }}')"
                                        class="rounded-full border border-border-custom bg-white/80 px-2.5 py-1 text-[10px] font-bold text-text-body transition hover:border-primary/40 hover:text-primary">{{ $suggestion }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Illustration (compact, desktop only) -->
                    <div class="relative mx-auto hidden w-[260px] lg:block xl:w-[300px]">
                        <div
                            class="relative rounded-[1.8rem] bg-white/55 p-2 shadow-[0_24px_45px_-30px_rgba(34,50,74,0.45)] ring-1 ring-white/80 backdrop-blur-sm">
                            <div
                                class="overflow-hidden rounded-[1.4rem] bg-gradient-to-br from-[#FFF4E6] via-[#F6FBF8] to-[#E9F6F8]">
                                <img src="/assets/img/chat-1.png" alt="هم‌آغوشی مهربان یک آدم با سگ و گربه"
                                    class="h-[240px] w-full object-contain object-bottom xl:h-[270px]">
                            </div>

                            <div
                                class="absolute -start-3 top-4 flex rotate-[-3deg] items-center gap-1.5 rounded-xl bg-white/95 px-2.5 py-1.5 text-[10px] font-bold text-text-title shadow-lg ring-1 ring-border-custom">
                                <span class="grid h-5 w-5 place-items-center rounded-full bg-primary/10 text-primary">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <g>
                                            <ellipse cx="12" cy="5" rx="1.9" ry="2.4" />
                                            <ellipse cx="6.9" cy="8" rx="1.7" ry="2.2" transform="rotate(-22 6.9 8)" />
                                            <ellipse cx="17.1" cy="8" rx="1.7" ry="2.2" transform="rotate(22 17.1 8)" />
                                            <ellipse cx="4.5" cy="13.2" rx="1.5" ry="1.9" transform="rotate(-38 4.5 13.2)" />
                                            <ellipse cx="19.5" cy="13.2" rx="1.5" ry="1.9" transform="rotate(38 19.5 13.2)" />
                                            <path d="M12 11.3c-2.5 0-4.6 1.6-5.5 3.8-.8 2 .4 4.2 2.6 4.6.9.2 1.9 0 2.9-.6.7-.4 1.4-.4 2.1 0 1 .6 2 .8 2.9.6 2.2-.4 3.4-2.6 2.6-4.6-.9-2.2-3-3.9-5.6-3.9Z" />
                                        </g>
                                    </svg>
                                </span>
                                کنار هم، امن و خوشحال
                            </div>
                        </div>
                    </div>
                </div>

                <!-- City Modal -->
                <div id="cityModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-text-title/40 p-4 backdrop-blur-sm">
                    <div class="w-full max-w-sm rounded-[2rem] border border-border-custom bg-bg-main p-5 shadow-2xl">
                        <div class="mb-4 flex items-start justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <span class="grid h-10 w-10 place-items-center rounded-full bg-primary/10 text-primary">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z" />
                                        <circle cx="12" cy="9.5" r="2.3" />
                                    </svg>
                                </span>
                                <div class="text-right">
                                    <h3 class="text-sm font-bold text-text-title">شهرت رو انتخاب کن</h3>
                                    <p class="mt-0.5 text-[10px] text-text-muted">نتایج نزدیک به شهرت رو ببین.</p>
                                </div>
                            </div>
                            <button id="cityCancel" type="button" aria-label="بستن"
                                class="grid h-8 w-8 place-items-center rounded-full bg-bg-secondary text-text-muted transition hover:text-text-title">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.4" stroke-linecap="round">
                                    <path d="M6 6l12 12M18 6L6 18" />
                                </svg>
                            </button>
                        </div>

                        <label for="cityInput" class="mb-1.5 block text-[11px] font-bold text-text-body">استان</label>
                        <select id="cityInput"
                            class="w-full rounded-xl border border-border-custom bg-bg-secondary p-3 text-xs font-semibold text-text-title outline-none focus:border-primary">
                            <option value="" disabled selected>استان یا شهر...</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->title }}</option>
                            @endforeach
                        </select>

                        <button id="citySave" type="button"
                            class="mt-4 w-full rounded-xl bg-primary px-4 py-3 text-xs font-bold text-white shadow-lg shadow-primary/20 transition hover:bg-primary-hover active:scale-[0.99]">ذخیره
                            شهر</button>
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
                        if (!btn || !modal || !input || !save || !cancel || !label) return;

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

                        function openModal() {
                            input.value = saved || '';
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                            input.focus();
                        }

                        function closeModal() {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }

                        btn.addEventListener('click', openModal);
                        cancel.addEventListener('click', closeModal);

                        save.addEventListener('click', function() {
                            var v = input.value;
                            if (!v) return;
                            saved = v;
                            setCookie('sospet_city', v, 365);
                            label.textContent = provinces.find(p => p.id == v)?.title || label.textContent;
                            closeModal();
                        });

                        modal.addEventListener('click', function(e) {
                            if (e.target === modal) closeModal();
                        });
                    })();
                </script>
            </div>
        </section>

        <!-- Quick Actions -->
        <div
            class="mt-2 px-4 flex flex-col gap-3 md:grid md:grid-cols-3 md:gap-5 md:px-10 md:mt-4 max-w-6xl mx-auto w-full">
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
                    @include('partials.pet-card', ['pet' => $pet])
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

        @include('partials.site-footer')
    </main>
</div>
