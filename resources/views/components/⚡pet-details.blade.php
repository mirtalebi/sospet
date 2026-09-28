<?php

use App\Models\Pet;
use Livewire\Component;

new class extends Component {
    public Pet $pet;
    public int $totalImages = 0;

    public function mount($id)
    {
        $this->pet = Pet::with(['images', 'user'])->findOrFail($id);
        $this->totalImages = $this->pet->images->count();
    }
};
?>

<main class="flex-1 overflow-y-auto no-scrollbar pb-24 md:pb-8 bg-zinc-50" x-data="{ listingType: '{{ $pet->type }}', active: 0 }">

    <!-- Wrapper to limit large viewport spreads -->
    <div class="max-w-5xl mx-auto md:p-6 grid grid-cols-1 md:grid-cols-12 md:gap-8 items-start">

        <!-- Left Side Media Gallery Column Layout -->
        <div class="md:col-span-6 lg:col-span-5 relative md:sticky md:top-6">
            <div class="relative h-[21rem] md:h-[26rem] md:rounded-2xl md:overflow-hidden md:shadow-sm bg-zinc-200">
                <div x-ref="track"
                    @scroll.debounce.100ms="active = Math.round(-$event.target.scrollLeft / $event.target.clientWidth)"
                    class="flex h-full overflow-x-auto no-scrollbar scroll-smooth snap-x snap-mandatory">

                    @if ($totalImages > 0)
                        @foreach ($pet->images as $index => $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                class="w-full h-full object-cover shrink-0 snap-center"
                                alt="{{ $pet->title }} - عکس {{ $index + 1 }}">
                        @endforeach
                    @else
                        <img src="https://placedog.net/640/672?id=21"
                            class="w-full h-full object-cover shrink-0 snap-center" alt="تصویر پیش‌فرض">
                    @endif
                </div>

                <div
                    class="absolute top-0 inset-x-0 h-24 bg-gradient-to-b from-black/40 to-transparent pointer-events-none">
                </div>

                <a href="/" wire:navigate aria-label="بازگشت"
                    class="absolute top-4 start-4 z-10 grid place-items-center w-10 h-10 rounded-full bg-white/90 text-zinc-800 backdrop-blur active:scale-95 transition shadow-sm hover:bg-white">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6" />
                    </svg>
                </a>

                <div class="absolute top-4 end-4 z-10 flex items-center gap-2">
                    <button x-data="{ saved: false }" @click="saved=!saved" :aria-pressed="saved" aria-label="ذخیره"
                        class="grid place-items-center w-10 h-10 rounded-full bg-white/90 backdrop-blur active:scale-95 transition shadow-sm hover:bg-white">
                        <svg width="16" height="16" viewBox="0 0 24 24" :fill="saved ? '#ef4444' : 'none'"
                            :stroke="saved ? '#ef4444' : '#52525b'" stroke-width="2">
                            <path
                                d="M12 21s-7.5-4.7-10-9.3C0.3 8.4 1.7 5 5 4.3 7.3 3.8 9.6 5 12 8c2.4-3 4.7-4.2 7-3.7 3.3.7 4.7 4.1 3 7.4C19.5 16.3 12 21 12 21Z" />
                        </svg>
                    </button>
                    <button aria-label="اشتراک‌گذاری"
                        class="grid place-items-center w-10 h-10 rounded-full bg-white/90 text-zinc-700 backdrop-blur active:scale-95 transition shadow-sm hover:bg-white">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="6" cy="12" r="2.4" />
                            <circle cx="17" cy="6" r="2.4" />
                            <circle cx="17" cy="18" r="2.4" />
                            <path d="M8 13.2l7 3.6M8 10.8l7-3.6" />
                        </svg>
                    </button>
                </div>

                <span
                    class="absolute bottom-4 start-4 z-10 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm"
                    :class="listingType === 'adoption' ? 'bg-amber-500' : listingType === 'found' ? 'bg-emerald-600' :
                        'bg-rose-500'">
                    {{ $pet->getTypeText() }}
                </span>

                <span
                    class="absolute bottom-4 end-4 z-10 bg-black/60 text-white text-[11px] font-semibold px-2.5 py-1 rounded-full"
                    x-text="(active+1) + ' / ' + ({{ $totalImages }} || 1)"></span>

                @if ($totalImages > 1)
                    <div class="absolute bottom-[3.6rem] inset-x-0 z-10 flex justify-center gap-1.5">
                        @foreach ($pet->images as $index => $img)
                            <button
                                @click="$refs.track.scrollTo({left: $refs.track.clientWidth * {{ $index }}, behavior:'smooth'})"
                                :class="active === {{ $index }} ? 'w-4 bg-white' : 'w-1.5 bg-white/50'"
                                class="h-1.5 rounded-full transition-all" aria-label="عکس {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Side Core Content Metadata Column -->
        <div class="md:col-span-6 lg:col-span-7 px-5 md:px-0 mt-5 md:mt-0 space-y-6">

            <!-- Section Meta Titles -->
            <section>
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h1 class="text-2xl md:text-3xl text-zinc-900 font-bold tracking-tight">{{ $pet->title }}</h1>
                        <p class="text-sm text-zinc-500 mt-1.5">
                            {{ $pet->pet_type }} {{ $pet->breed ? '(' . $pet->breed . ')' : '' }} ·
                            {{ $pet->age ?? 'سن نامشخص' }} · {{ $pet->gender }}
                            {{ $pet->gender === 'ماده' ? '♀' : '♂' }}
                        </p>
                    </div>
                    <div class="text-end shrink-0">
                        <p class="text-xs text-zinc-400" x-show="listingType !== 'lost'">
                            {{ $pet->created_at->diffForHumans() }}</p>
                        <p class="text-xs font-bold text-rose-600" x-show="listingType === 'lost'" x-cloak>گمشده</p>
                        <p class="text-xs text-zinc-500 mt-1.5 flex items-center gap-1 justify-end">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z" />
                                <circle cx="12" cy="9.5" r="2.3" />
                            </svg>
                            {{ $pet->area }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- Emergency Warning Banner -->
            <section x-show="listingType === 'lost'" x-cloak>
                <div class="bg-rose-50 border border-rose-100 rounded-2xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span
                            class="grid place-items-center w-7 h-7 rounded-full bg-rose-500 text-white shrink-0 shadow-sm">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 9v4M12 16.5h.01" />
                                <path
                                    d="M10.3 4.3 2.6 18a1.8 1.8 0 0 0 1.6 2.7h15.6a1.8 1.8 0 0 0 1.6-2.7L13.7 4.3a1.8 1.8 0 0 0-3.4 0Z" />
                            </svg>
                        </span>
                        <h2 class="font-bold text-sm text-rose-700">گزارش گمشدگی اضطراری</h2>
                    </div>
                    <p class="text-xs text-rose-900 leading-6">
                        این حیوان در محدوده <span class="font-bold text-rose-950">{{ $pet->city }}،
                            {{ $pet->area }}</span> گم شده است. اگر نشانه‌ای از او دیده‌اید، لطفاً فوراً با صاحب
                        حیوان تماس بگیرید.
                    </p>
                </div>
            </section>

            <!-- Attributes Specifications Grid -->
            <section>
                <h2 class="font-bold text-base text-zinc-900 mb-3">مشخصات</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    <div class="bg-white border border-zinc-200 rounded-xl p-3 shadow-sm">
                        <p class="text-[10px] text-zinc-400">نوع</p>
                        <p class="font-bold text-sm text-zinc-800 mt-0.5">{{ $pet->pet_type }}</p>
                    </div>
                    <div class="bg-white border border-zinc-200 rounded-xl p-3 shadow-sm">
                        <p class="text-[10px] text-zinc-400">نژاد</p>
                        <p
                            class="font-bold text-sm text-zinc-800 mt-0.5 text-ellipsis overflow-hidden whitespace-nowrap">
                            {{ $pet->breed ?? 'بومی / مخلوط' }}</p>
                    </div>
                    <div class="bg-white border border-zinc-200 rounded-xl p-3 shadow-sm">
                        <p class="text-[10px] text-zinc-400">حدود سن</p>
                        <p class="font-bold text-sm text-zinc-800 mt-0.5">{{ $pet->age ?? 'نامشخص' }}</p>
                    </div>
                    <div class="bg-white border border-zinc-200 rounded-xl p-3 shadow-sm">
                        <p class="text-[10px] text-zinc-400">جنسیت</p>
                        <p class="font-bold text-sm text-zinc-800 mt-0.5">{{ $pet->gender }}</p>
                    </div>
                </div>
            </section>

            <!-- Long Form Description -->
            <section>
                <h2 class="font-bold text-base text-zinc-900 mb-2">توضیحات آگهی</h2>
                <p
                    class="text-sm text-zinc-600 leading-7 text-justify bg-white border border-zinc-200 rounded-2xl p-4 shadow-sm">
                    {{ $pet->description ?? 'توضیحات تکمیلی برای این گزارش ثبت نشده است.' }}
                </p>
            </section>

            <!-- Location Map Representation Box -->
            <section>
                <h2 class="font-bold text-base text-zinc-900 mb-3"
                    x-text="listingType === 'lost' ? 'آخرین مکان دیده‌شدن' : 'موقعیت تقریبی'"></h2>
                <div class="rounded-2xl overflow-hidden border border-zinc-200 shadow-sm bg-white">
                    <div class="relative h-32 bg-zinc-100">
                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 320 110" preserveAspectRatio="none">
                            <rect width="320" height="110" fill="#f4f4f5" />
                            <path d="M0 70 Q80 40 160 65 T320 50" stroke="#e4e4e7" stroke-width="6" fill="none" />
                            <path d="M0 30 Q100 55 180 25 T320 35" stroke="#e4e4e7" stroke-width="6"
                                fill="none" />
                            <path d="M40 0 L40 110 M250 0 L250 110" stroke="#e4e4e7" stroke-width="4" />
                        </svg>
                        <span
                            class="absolute top-1/2 start-1/2 -translate-x-1/2 -translate-y-1/2 grid place-items-center w-9 h-9 rounded-full shadow-md text-white"
                            :class="listingType === 'lost' ? 'bg-rose-500' : 'bg-amber-500'">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z" />
                            </svg>
                        </span>
                    </div>
                    <div class="p-3.5 border-t border-zinc-100">
                        <p class="font-semibold text-sm text-zinc-800">{{ $pet->city }}، {{ $pet->area }}</p>
                        <p class="text-xs text-zinc-400 mt-1">اطلاعات موقعیت دقیق پس از ارتباط با آگهی‌دهنده هماهنگ
                            می‌شود.</p>
                    </div>
                </div>
            </section>

            <!-- User Submitting Row Profile Info -->
            <section>
                <div
                    class="bg-white rounded-2xl border border-zinc-200 p-4 flex items-center justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-3 min-w-0">
                        <div
                            class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 font-bold flex items-center justify-center text-sm shrink-0 border border-amber-100">
                            {{ mb_substr($pet->user?->first_name ?? 'ک', 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-zinc-800 flex items-center gap-1">
                                {{ $pet->user?->name ?? 'کاربر همیار' }}
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="#f59e0b">
                                    <path
                                        d="M12 2l2.4 2.1 3.1-.4 1 3 2.8 1.5-.7 3.1 1.7 2.7-2.4 2.1.4 3.1-3.1.6-1.7 2.7-3-1-3 1-1.7-2.7-3.1-.6.4-3.1-2.4-2.1 1.7-2.7-.7-3.1L6.5 4.7l3.1.4Z" />
                                    <path d="M9 12.5l2 2 4-4.5" stroke="#fff" stroke-width="1.8" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </p>
                            <p class="text-[11px] text-zinc-400 mt-0.5 text-ellipsis overflow-hidden whitespace-nowrap"
                                x-text="listingType === 'lost' ? 'گزارش‌دهنده ساس‌پت' : 'ثبت‌کننده آگهی ساس‌پت'"></p>
                        </div>
                    </div>
                    <button
                        class="shrink-0 text-xs font-semibold text-zinc-600 border border-zinc-200 rounded-full px-3 py-1.5 hover:bg-zinc-50 transition active:scale-95">پروفایل</button>
                </div>
            </section>

            <!-- Important Support Tips Panel -->
            <section>
                <div class="bg-amber-50/60 border border-amber-100 rounded-2xl p-4 shadow-sm">
                    <h3 class="font-bold text-xs text-amber-800 mb-2 flex items-center gap-1.5">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7Z" />
                        </svg>نکات حمایتی مهم
                    </h3>
                    <ul class="text-xs text-amber-900/80 space-y-1 list-disc ps-4 leading-6">
                        <li>جلسه اول ملاقات با حیوان را ترجیحاً در کلینیک دامپزشکی بگذارید.</li>
                        <li>ساس‌پت بستری رایگان است؛ قبل از تحویل حیوان هیچ مبلغی به عنوان بیعانه واریز نکنید.</li>
                        <li>از سلامت ظاهری و وضعیت شناسنامه حمایتی حیوان مطمئن شوید.</li>
                    </ul>
                </div>
            </section>

            <!-- Desktop Actions Row Form Panel Integration -->
            <div class="hidden md:flex items-center gap-3 bg-white p-4 rounded-2xl border border-zinc-200 shadow-sm">
                <a href="tel:{{ $pet->phone }}" aria-label="تماس"
                    class="shrink-0 grid place-items-center w-12 h-12 rounded-xl bg-zinc-100 text-zinc-700 hover:bg-zinc-200/70 transition">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path
                            d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z" />
                    </svg>
                </a>
                <a href="sms:{{ $pet->phone }}"
                    class="flex-1 flex items-center justify-center gap-1.5 h-12 rounded-xl bg-zinc-100 text-zinc-700 font-bold text-sm hover:bg-zinc-200/70 transition">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 11.5a8.5 8.5 0 1 1-3.8-7.1L21 3l-1.3 3.8c.8 1.3 1.3 2.9 1.3 4.7Z" />
                    </svg>پیامک
                </a>
                <a href="tel:{{ $pet->phone }}"
                    class="flex-[1.5] h-12 rounded-xl text-white font-bold text-sm hover:opacity-95 transition flex items-center justify-center shadow-sm"
                    :class="listingType === 'adoption' ? 'bg-amber-500 shadow-amber-500/10' : listingType === 'found' ?
                        'bg-emerald-600 shadow-emerald-600/10' : 'bg-rose-500 shadow-rose-500/10'"
                    x-text="listingType === 'adoption' ? 'درخواست فرزندخواهی' : (listingType === 'found' ? 'فرزند منه، تماس بگیر' : 'من دیدمش، تماس بگیر')">
                </a>
            </div>
        </div>
    </div>

    <!-- Floating Device Bottom Navigation Action Form (Mobile Viewport Pinned Only) -->
    <div class="fixed bottom-0 inset-x-0 z-50 bg-white/95 backdrop-blur-md border-t border-zinc-200 px-4 py-3 flex items-center gap-2 md:hidden shadow-lg"
        style="padding-bottom: calc(env(safe-area-inset-bottom) + 0.75rem)">

        <a href="tel:{{ $pet->phone }}" aria-label="تماس"
            class="shrink-0 grid place-items-center w-12 h-12 rounded-full bg-zinc-100 text-zinc-700 active:scale-95 transition">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z" />
            </svg>
        </a>

        <a href="sms:{{ $pet->phone }}"
            class="flex-1 flex items-center justify-center gap-1.5 h-12 rounded-2xl bg-zinc-100 text-zinc-700 font-bold text-sm active:scale-95 transition">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <path d="M21 11.5a8.5 8.5 0 1 1-3.8-7.1L21 3l-1.3 3.8c.8 1.3 1.3 2.9 1.3 4.7Z" />
            </svg>پیامک
        </a>

        <a href="tel:{{ $pet->phone }}"
            class="flex-[1.4] h-12 rounded-2xl text-white font-bold text-sm active:scale-95 transition flex items-center justify-center shadow"
            :class="listingType === 'adoption' ? 'bg-amber-500' : listingType === 'found' ? 'bg-emerald-600' :
                'bg-rose-500'"
            x-text="listingType === 'adoption' ? 'درخواست فرزندخواهی' : (listingType === 'found' ? 'فرزند منه، تماس بگیر' : 'من دیدمش، تماس بگیر')">
        </a>
    </div>
</main>
