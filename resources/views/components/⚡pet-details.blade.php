<?php

use App\Models\Pet;
use Livewire\Component;

new class extends Component {
    public Pet $pet;

    // تعداد تصاویر برای شمارنده و نقاط اسلایدر
    public int $totalImages = 0;

    public function mount($id)
    {
        // لود اطلاعات کامل حیوان به همراه تصاویر و کاربر ثبت‌کننده بر اساس ساختار جدید دیتابیس
        $this->pet = Pet::with(['images', 'user'])->findOrFail($id);
        $this->totalImages = $this->pet->images->count();
    }
};
?>

<main class="flex-1 overflow-y-auto no-scrollbar pb-24" x-data="{ listingType: '{{ $pet->type }}', active: 0 }">

    <div class="relative h-[21rem]">
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
                <img src="https://placedog.net/640/672?id=21" class="w-full h-full object-cover shrink-0 snap-center"
                    alt="تصویر پیش‌فرض">
            @endif
        </div>

        <div class="absolute top-0 inset-x-0 h-24 bg-gradient-to-b from-ink/35 to-transparent pointer-events-none">
        </div>

        <a href="/" wire:navigate aria-label="بازگشت"
            class="absolute top-4 start-4 z-10 grid place-items-center w-10 h-10 rounded-full bg-white/85 text-ink backdrop-blur active:scale-95 transition">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6" />
            </svg>
        </a>

        <div class="absolute top-4 end-4 z-10 flex items-center gap-2">
            <button x-data="{ saved: false }" @click="saved=!saved" :aria-pressed="saved" aria-label="ذخیره"
                class="grid place-items-center w-10 h-10 rounded-full bg-white/85 backdrop-blur active:scale-95 transition">
                <svg width="16" height="16" viewBox="0 0 24 24" :fill="saved ? '#D8503F' : 'none'"
                    stroke="#D8503F" stroke-width="2">
                    <path
                        d="M12 21s-7.5-4.7-10-9.3C0.3 8.4 1.7 5 5 4.3 7.3 3.8 9.6 5 12 8c2.4-3 4.7-4.2 7-3.7 3.3.7 4.7 4.1 3 7.4C19.5 16.3 12 21 12 21Z" />
                </svg>
            </button>
            <button aria-label="اشتراک‌گذاری"
                class="grid place-items-center w-10 h-10 rounded-full bg-white/85 text-ink backdrop-blur active:scale-95 transition">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="6" cy="12" r="2.4" />
                    <circle cx="17" cy="6" r="2.4" />
                    <circle cx="17" cy="18" r="2.4" />
                    <path d="M8 13.2l7 3.6M8 10.8l7-3.6" />
                </svg>
            </button>
        </div>

        <span class="absolute bottom-4 start-4 z-10 text-white text-xs font-bold px-3 py-1.5 rounded-full"
            :class="listingType === 'adoption' ? 'bg-saffron-500' : listingType === 'found' ? 'bg-pistachio-dark' :
                'bg-coral'">{{ $pet->getTypeText() }}</span>

        {{-- @dd($pet->getTypeText(), '') --}}
        <span
            class="absolute bottom-4 end-4 z-10 bg-ink/55 text-white text-[11px] font-semibold px-2.5 py-1 rounded-full"
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

    <section class="px-5 pt-4">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h1 class="font-display text-2xl text-ink">{{ $pet->title }}</h1>
                <p class="text-sm text-ink-soft mt-1">
                    {{ $pet->pet_type }} {{ $pet->breed ? '(' . $pet->breed . ')' : '' }} ·
                    {{ $pet->age ?? 'سن نامشخص' }}
                    · {{ $pet->gender }} {{ $pet->gender === 'ماده' ? '♀' : '♂' }}
                </p>
            </div>
            <div class="text-end shrink-0">
                <p class="text-xs text-ink-soft" x-show="listingType !== 'lost'">
                    {{ $pet->created_at->diffForHumans() }}</p>
                <p class="text-xs font-bold text-coral-dark" x-show="listingType === 'lost'" x-cloak>گمشده</p>
                <p class="text-xs text-ink-soft mt-1 flex items-center gap-1 justify-end">
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

    <section class="px-5 mt-4" x-show="listingType === 'lost'" x-cloak>
        <div class="bg-coral-light border border-coral/30 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="grid place-items-center w-7 h-7 rounded-full bg-coral text-white shrink-0">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 9v4M12 16.5h.01" />
                        <path
                            d="M10.3 4.3 2.6 18a1.8 1.8 0 0 0 1.6 2.7h15.6a1.8 1.8 0 0 0 1.6-2.7L13.7 4.3a1.8 1.8 0 0 0-3.4 0Z" />
                    </svg>
                </span>
                <h2 class="font-bold text-sm text-coral-dark">گزارش گمشدگی اضطراری</h2>
            </div>
            <p class="text-xs text-ink leading-6">
                این حیوان در محدوده <span class="font-semibold">{{ $pet->city }}، {{ $pet->area }}</span> گم شده
                است. اگر نشانه‌ای از او دیده‌اید، لطفاً فوراً از طریق دکمه‌های ارتباطی زیر با صاحب حیوان تماس بگیرید.
            </p>
        </div>
    </section>

    <section class="px-5 mt-5">
        <h2 class="font-bold text-base text-ink mb-3">مشخصات</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
            <div class="bg-sand-100 rounded-xl p-2.5">
                <p class="text-[10px] text-ink-soft">نوع</p>
                <p class="font-bold text-sm text-ink mt-0.5">{{ $pet->pet_type }}</p>
            </div>
            <div class="bg-sand-100 rounded-xl p-2.5">
                <p class="text-[10px] text-ink-soft">نژاد</p>
                <p class="font-bold text-sm text-ink mt-0.5">{{ $pet->breed ?? 'بومی / مخلوط' }}</p>
            </div>
            <div class="bg-sand-100 rounded-xl p-2.5">
                <p class="text-[10px] text-ink-soft">حدود سن</p>
                <p class="font-bold text-sm text-ink mt-0.5">{{ $pet->age ?? 'نامشخص' }}</p>
            </div>
            <div class="bg-sand-100 rounded-xl p-2.5">
                <p class="text-[10px] text-ink-soft">جنسیت</p>
                <p class="font-bold text-sm text-ink mt-0.5">{{ $pet->gender }}</p>
            </div>
        </div>
    </section>

    <section class="px-5 mt-5">
        <h2 class="font-bold text-base text-ink mb-2">توضیحات آگهی</h2>
        <p class="text-sm text-ink-soft leading-7 text-justify">
            {{ $pet->description ?? 'توضیحات تکمیلی برای این گزارش ثبت نشده است.' }}
        </p>
    </section>

    <section class="px-5 mt-5">
        <h2 class="font-bold text-base text-ink mb-3"
            x-text="listingType === 'lost' ? 'آخرین مکان دیده‌شدن' : 'موقعیت تقریبی'"></h2>
        <div class="rounded-2xl overflow-hidden ring-1 ring-ink/10">
            <div class="relative h-28 bg-sand-100">
                <svg class="absolute inset-0 w-full h-full" viewBox="0 0 320 110" preserveAspectRatio="none">
                    <rect width="320" height="110" fill="#F5E9D6" />
                    <path d="M0 70 Q80 40 160 65 T320 50" stroke="#E7D2B0" stroke-width="6" fill="none" />
                    <path d="M0 30 Q100 55 180 25 T320 35" stroke="#E7D2B0" stroke-width="6" fill="none" />
                    <path d="M40 0 L40 110 M250 0 L250 110" stroke="#E7D2B0" stroke-width="4" />
                </svg>
                <span
                    class="absolute top-1/2 start-1/2 -translate-x-1/2 -translate-y-1/2 grid place-items-center w-9 h-9 rounded-full"
                    :class="listingType === 'lost' ? 'bg-coral' : 'bg-saffron-500'">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="white">
                        <path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z" />
                    </svg>
                </span>
            </div>
            <div class="bg-white p-3">
                <p class="font-semibold text-sm text-ink">{{ $pet->city }}، {{ $pet->area }}</p>
                <p class="text-xs text-ink-soft mt-1">اطلاعات موقعیت دقیق پس از ارتباط با آگهی‌دهنده هماهنگ می‌شود.</p>
            </div>
        </div>
    </section>

    <section class="px-5 mt-5">
        <div class="bg-white rounded-2xl ring-1 ring-ink/10 p-3.5 flex items-center gap-3">
            <div
                class="w-12 h-12 rounded-full bg-saffron-100 text-saffron-600 font-bold flex items-center justify-center text-sm shrink-0">
                {{ mb_substr($pet->user?->first_name ?? 'ک', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-sm text-ink flex items-center gap-1">
                    {{ $pet->user?->name ?? 'کاربر همیار' }} <svg width="13" height="13" viewBox="0 0 24 24"
                        fill="#E2622A">
                        <path
                            d="M12 2l2.4 2.1 3.1-.4 1 3 2.8 1.5-.7 3.1 1.7 2.7-2.4 2.1.4 3.1-3.1.6-1.7 2.7-3-1-3 1-1.7-2.7-3.1-.6.4-3.1-2.4-2.1 1.7-2.7-.7-3.1L6.5 4.7l3.1.4Z" />
                        <path d="M9 12.5l2 2 4-4.5" stroke="#fff" stroke-width="1.8" fill="none"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </p>
                <p class="text-[11px] text-ink-soft mt-0.5"
                    x-text="listingType === 'lost' ? 'گزارش‌دهنده پاوِت' : 'ثبت‌کننده آگهی پاوِت'"></p>
            </div>
            <button
                class="shrink-0 text-xs font-semibold text-saffron-600 border border-saffron-200 rounded-full px-3 py-1.5">پروفایل</button>
        </div>
    </section>

    <section class="px-5 mt-5">
        <div class="bg-turmeric-100/60 rounded-2xl p-4">
            <h3 class="font-bold text-xs text-ink mb-2 flex items-center gap-1.5">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#993D17"
                    stroke-width="2">
                    <path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7Z" />
                </svg>
                نکات حمایتی مهم
            </h3>
            <ul class="text-xs text-ink-soft leading-6 list-disc ps-4 space-y-1">
                <li>جلسه اول ملاقات با حیوان را ترجیحاً در کلینیک دامپزشکی بگذارید.</li>
                <li>پاوِت بستری رایگان است؛ قبل از تحویل حیوان هیچ مبلغی به عنوان بیعانه واریز نکنید.</li>
                <li>از سلامت ظاهری و وضعیت شناسنامه حمایتی حیوان مطمئن شوید.</li>
            </ul>
        </div>
    </section>

    <div class="fixed bottom-0 inset-x-0 z-60 bg-white/95 backdrop-blur-md border-t border-ink/5 px-4 py-3 flex items-center gap-2"
        style="padding-bottom: calc(env(safe-area-inset-bottom) + 0.75rem)">

        <a href="tel:{{ $pet->phone }}" aria-label="تماس"
            class="shrink-0 grid place-items-center w-12 h-12 rounded-full bg-sand-100 text-ink active:scale-95 transition">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z" />
            </svg>
        </a>

        <a href="sms:{{ $pet->phone }}"
            class="flex-1 flex items-center justify-center gap-1.5 h-12 rounded-2xl bg-sand-100 text-ink font-bold text-sm active:scale-95 transition">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.5 8.5 0 1 1-3.8-7.1L21 3l-1.3 3.8c.8 1.3 1.3 2.9 1.3 4.7Z" />
            </svg>
            پیامک
        </a>

        <a href="tel:{{ $pet->phone }}"
            class="flex-[1.4] h-12 rounded-2xl text-white font-bold text-sm active:scale-95 transition flex items-center justify-center"
            :class="listingType === 'adoption' ? 'bg-saffron-500' : listingType === 'found' ? 'bg-pistachio-dark' :
                'bg-coral'"
            x-text="listingType === 'adoption' ? 'درخواست فرزندخواهی' : (listingType === 'found' ? 'فرزند منه، تماس بگیر' : 'من دیدمش، تماس بگیر'); console.log(listingType)">
        </a>
    </div>

</main>
