<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>لوسی · پاوت</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&family=Lalezar&family=Poppins:wght@700;800&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sand: {
                            DEFAULT: '#FBF5EC',
                            100: '#F5E9D6'
                        },
                        ink: {
                            DEFAULT: '#2A1D14',
                            soft: '#7A6A59'
                        },
                        saffron: {
                            50: '#FFF7EC',
                            100: '#FEE9CC',
                            200: '#FBCE94',
                            300: '#F6AD5C',
                            400: '#EE832F',
                            500: '#E2622A',
                            600: '#C24D1C',
                            700: '#993D17'
                        },
                        turmeric: {
                            100: '#FDEFC9',
                            300: '#F8CB6B',
                            500: '#EFA13A'
                        },
                        pistachio: {
                            light: '#E8EFDC',
                            DEFAULT: '#7E9A63',
                            dark: '#5C7448'
                        },
                        coral: {
                            light: '#FBE6E2',
                            DEFAULT: '#D8503F',
                            dark: '#AC3B2D'
                        },
                    },
                    fontFamily: {
                        display: ['Lalezar', 'Vazirmatn', 'sans-serif'],
                        body: ['Vazirmatn', 'sans-serif'],
                        brand: ['Poppins', 'sans-serif'],
                    },
                    boxShadow: {
                        tag: '0 18px 30px -12px rgba(194, 77, 28, 0.35)'
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        html {
            font-family: 'Vazirmatn', sans-serif;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        *:focus-visible {
            outline: 2px solid #E2622A;
            outline-offset: 2px;
            border-radius: 4px;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body class="bg-stone-200 text-ink font-body antialiased">

    <div class="min-h-screen sm:py-8 sm:flex sm:items-center sm:justify-center">
        <div class="relative mx-auto w-full max-w-md min-h-screen sm:min-h-[844px] bg-sand sm:rounded-[2.25rem] sm:shadow-2xl sm:ring-1 sm:ring-black/5 overflow-hidden flex flex-col"
            x-data="{ listingType: 'adopt' }">

            <main class="flex-1 overflow-y-auto no-scrollbar pb-24">

                <!-- ===== IMAGE GALLERY ===== -->
                <div class="relative h-[21rem]" x-data="{ active: 0 }">
                    <div x-ref="track"
                        @scroll.debounce.100ms="active = Math.round($event.target.scrollLeft / $event.target.clientWidth)"
                        class="flex h-full overflow-x-auto no-scrollbar scroll-smooth snap-x snap-mandatory">
                        <img src="https://placedog.net/640/672?id=21"
                            class="w-full h-full object-cover shrink-0 snap-center" alt="لوسی - عکس ۱">
                        <img src="https://placedog.net/640/672?id=23"
                            class="w-full h-full object-cover shrink-0 snap-center" alt="لوسی - عکس ۲">
                        <img src="https://placedog.net/640/672?id=9"
                            class="w-full h-full object-cover shrink-0 snap-center" alt="لوسی - عکس ۳">
                        <img src="https://placedog.net/640/672?id=47"
                            class="w-full h-full object-cover shrink-0 snap-center" alt="لوسی - عکس ۴">
                    </div>

                    <div
                        class="absolute top-0 inset-x-0 h-24 bg-gradient-to-b from-ink/35 to-transparent pointer-events-none">
                    </div>

                    <!-- floating header buttons -->
                    <button aria-label="بازگشت"
                        class="absolute top-4 start-4 z-10 grid place-items-center w-10 h-10 rounded-full bg-white/85 text-ink backdrop-blur active:scale-95 transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6" />
                        </svg>
                    </button>
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

                    <!-- status badge -->
                    <span class="absolute bottom-4 start-4 z-10 text-white text-xs font-bold px-3 py-1.5 rounded-full"
                        :class="listingType === 'adopt' ? 'bg-saffron-500' : listingType==='foster' ? 'bg-pistachio-dark' :
                            'bg-coral'"
                        x-text="listingType==='adopt' ? 'فرزندخواهی' : listingType==='foster' ? 'نگه‌داری موقت' : 'گمشده'"></span>

                    <!-- photo counter -->
                    <span
                        class="absolute bottom-4 end-4 z-10 bg-ink/55 text-white text-[11px] font-semibold px-2.5 py-1 rounded-full"
                        x-text="(active+1) + ' / 4'"></span>

                    <!-- dots -->
                    <div class="absolute bottom-[3.6rem] inset-x-0 z-10 flex justify-center gap-1.5">
                        <button @click="$refs.track.scrollTo({left: $refs.track.clientWidth*0, behavior:'smooth'})"
                            :class="active === 0 ? 'w-4 bg-white' : 'w-1.5 bg-white/50'"
                            class="h-1.5 rounded-full transition-all" aria-label="عکس ۱"></button>
                        <button @click="$refs.track.scrollTo({left: $refs.track.clientWidth*1, behavior:'smooth'})"
                            :class="active === 1 ? 'w-4 bg-white' : 'w-1.5 bg-white/50'"
                            class="h-1.5 rounded-full transition-all" aria-label="عکس ۲"></button>
                        <button @click="$refs.track.scrollTo({left: $refs.track.clientWidth*2, behavior:'smooth'})"
                            :class="active === 2 ? 'w-4 bg-white' : 'w-1.5 bg-white/50'"
                            class="h-1.5 rounded-full transition-all" aria-label="عکس ۳"></button>
                        <button @click="$refs.track.scrollTo({left: $refs.track.clientWidth*3, behavior:'smooth'})"
                            :class="active === 3 ? 'w-4 bg-white' : 'w-1.5 bg-white/50'"
                            class="h-1.5 rounded-full transition-all" aria-label="عکس ۴"></button>
                    </div>
                </div>

                <!-- ===== DEMO PREVIEW SWITCHER (not part of the production UI — for reviewing the 3 listing states) ===== -->
                <div
                    class="mx-5 mt-3 flex items-center gap-2 rounded-xl border-2 border-dashed border-ink/15 bg-white/60 px-2.5 py-2 text-[11px] text-ink-soft">
                    <span class="shrink-0">🔧 پیش‌نمایش حالت آگهی:</span>
                    <div class="flex gap-1">
                        <button @click="listingType='adopt'"
                            :class="listingType === 'adopt' ? 'bg-saffron-500 text-white' : 'bg-sand-100 text-ink-soft'"
                            class="px-2 py-1 rounded-full font-medium">فرزندخواهی</button>
                        <button @click="listingType='foster'"
                            :class="listingType === 'foster' ? 'bg-pistachio-dark text-white' : 'bg-sand-100 text-ink-soft'"
                            class="px-2 py-1 rounded-full font-medium">نگه‌داری</button>
                        <button @click="listingType='lost'"
                            :class="listingType === 'lost' ? 'bg-coral text-white' : 'bg-sand-100 text-ink-soft'"
                            class="px-2 py-1 rounded-full font-medium">گمشده</button>
                    </div>
                </div>

                <!-- ===== TITLE BLOCK ===== -->
                <section class="px-5 pt-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h1 class="font-display text-2xl text-ink">لوسی</h1>
                            <p class="text-sm text-ink-soft mt-1">سگ پشمالو (مخلوط) · ۲ ساله · ماده ♀</p>
                        </div>
                        <div class="text-end shrink-0">
                            <p class="text-xs text-ink-soft" x-show="listingType!=='lost'">۳ روز پیش ثبت شده</p>
                            <p class="text-xs font-bold text-coral-dark" x-show="listingType==='lost'" x-cloak>۲ روز
                                گمشده</p>
                            <p class="text-xs text-ink-soft mt-1 flex items-center gap-1 justify-end">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z" />
                                    <circle cx="12" cy="9.5" r="2.3" />
                                </svg>
                                ۱.۲ کیلومتر
                            </p>
                        </div>
                    </div>
                </section>

                <!-- ===== LOST-PET ALERT ===== -->
                <section class="px-5 mt-4" x-show="listingType==='lost'" x-cloak>
                    <div class="bg-coral-light border border-coral/30 rounded-2xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="grid place-items-center w-7 h-7 rounded-full bg-coral text-white shrink-0">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 9v4M12 16.5h.01" />
                                    <path
                                        d="M10.3 4.3 2.6 18a1.8 1.8 0 0 0 1.6 2.7h15.6a1.8 1.8 0 0 0 1.6-2.7L13.7 4.3a1.8 1.8 0 0 0-3.4 0Z" />
                                </svg>
                            </span>
                            <h2 class="font-bold text-sm text-coral-dark">گمشده از ۲ روز پیش</h2>
                        </div>
                        <p class="text-xs text-ink leading-6">
                            آخرین بار <span class="font-semibold">سعادت‌آباد، نزدیک پارک نیاوران</span> دیده شده، حدود
                            ساعت ۱۸:۳۰. یقه‌ی قرمز با پلاک به گردنش بود و کمی ترسوست؛ اگه دیدیش لطفاً نزدیک نشو و فوراً
                            از همین صفحه خبر بده.
                        </p>
                        <p class="text-xs font-bold text-coral-dark mt-2.5">🎁 پاداش در صورت پیدا شدن: ۵۰۰ هزار تومان
                        </p>
                    </div>
                </section>

                <!-- ===== FOSTER INFO ===== -->
                <section class="px-5 mt-4" x-show="listingType==='foster'" x-cloak>
                    <div class="bg-pistachio-light border border-pistachio/30 rounded-2xl p-4 grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-[11px] text-ink-soft">مدت نگه‌داری مورد نیاز</p>
                            <p class="font-bold text-sm text-pistachio-dark mt-0.5">حدود ۴ هفته</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-ink-soft">دلیل نیاز به نگه‌داری</p>
                            <p class="font-bold text-sm text-pistachio-dark mt-0.5">دوره‌ی درمان دامپزشکی صاحب</p>
                        </div>
                    </div>
                </section>

                <!-- ===== QUICK FACTS ===== -->
                <section class="px-5 mt-5">
                    <h2 class="font-bold text-base text-ink mb-3">مشخصات</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <div class="bg-sand-100 rounded-xl p-2.5">
                            <p class="text-[10px] text-ink-soft">نوع</p>
                            <p class="font-bold text-sm text-ink mt-0.5">سگ</p>
                        </div>
                        <div class="bg-sand-100 rounded-xl p-2.5">
                            <p class="text-[10px] text-ink-soft">نژاد</p>
                            <p class="font-bold text-sm text-ink mt-0.5">پشمالو (مخلوط)</p>
                        </div>
                        <div class="bg-sand-100 rounded-xl p-2.5">
                            <p class="text-[10px] text-ink-soft">سن</p>
                            <p class="font-bold text-sm text-ink mt-0.5">۲ سال</p>
                        </div>
                        <div class="bg-sand-100 rounded-xl p-2.5">
                            <p class="text-[10px] text-ink-soft">جنسیت</p>
                            <p class="font-bold text-sm text-ink mt-0.5">ماده ♀</p>
                        </div>
                        <div class="bg-sand-100 rounded-xl p-2.5">
                            <p class="text-[10px] text-ink-soft">وزن</p>
                            <p class="font-bold text-sm text-ink mt-0.5">۱۸ کیلوگرم</p>
                        </div>
                        <div class="bg-sand-100 rounded-xl p-2.5">
                            <p class="text-[10px] text-ink-soft">اندازه</p>
                            <p class="font-bold text-sm text-ink mt-0.5">متوسط</p>
                        </div>
                        <div class="bg-sand-100 rounded-xl p-2.5">
                            <p class="text-[10px] text-ink-soft">عقیم‌سازی</p>
                            <p class="font-bold text-sm text-ink mt-0.5">انجام‌شده</p>
                        </div>
                        <div class="bg-sand-100 rounded-xl p-2.5">
                            <p class="text-[10px] text-ink-soft">واکسیناسیون</p>
                            <p class="font-bold text-sm text-ink mt-0.5">کامل</p>
                        </div>
                    </div>
                </section>

                <!-- ===== TAGS ===== -->
                <section class="px-5 mt-5">
                    <h2 class="font-bold text-base text-ink mb-3">ویژگی‌ها و نشانه‌ها</h2>
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-white ring-1 ring-ink/10 text-xs text-ink px-3 py-1.5 rounded-full">اهل با
                            بچه‌ها</span>
                        <span class="bg-white ring-1 ring-ink/10 text-xs text-ink px-3 py-1.5 rounded-full">اهل با
                            سگ‌های دیگه</span>
                        <span
                            class="bg-white ring-1 ring-ink/10 text-xs text-ink px-3 py-1.5 rounded-full">آموزش‌دیده</span>
                        <span
                            class="bg-white ring-1 ring-ink/10 text-xs text-ink px-3 py-1.5 rounded-full">پرانرژی</span>
                        <span class="bg-white ring-1 ring-ink/10 text-xs text-ink px-3 py-1.5 rounded-full">نیاز به
                            حیاط</span>
                        <span class="bg-white ring-1 ring-ink/10 text-xs text-ink px-3 py-1.5 rounded-full">یقه‌ی قرمز
                            با پلاک</span>
                    </div>
                </section>

                <!-- ===== DESCRIPTION ===== -->
                <section class="px-5 mt-5">
                    <h2 class="font-bold text-base text-ink mb-2">درباره‌ی لوسی</h2>

                    <p class="text-sm text-ink-soft leading-7" x-show="listingType==='adopt'" x-cloak>
                        لوسی دو سالشه و پر از انرژیه! با بچه‌ها و سگ‌های دیگه خیلی خوب رفتار کرده و چندتا فرمان پایه رو
                        هم بلده. به‌خاطر تغییر شرایط زندگیمون مجبور شدیم دنبال یه خونه‌ی جدید و پرانرژی براش پیدا کنیم.
                        اگه حیاط یا فضای باز برای دویدن داری، لوسی عاشقت می‌شه.
                    </p>
                    <p class="text-sm text-ink-soft leading-7" x-show="listingType==='foster'" x-cloak>
                        لوسی دو سالشه و پر از انرژیه. صاحبش برای یه دوره‌ی کوتاه درمان دامپزشکی نیاز به یه خونه‌ی موقت
                        داره؛ غذا و هزینه‌های لوسی توسط صاحبش پوشش داده می‌شه و فقط به یه خونه‌ی پرانرژی و امن نیاز
                        داره.
                    </p>
                    <p class="text-sm text-ink-soft leading-7" x-show="listingType==='lost'" x-cloak>
                        لوسی عصر دوشنبه از حیاط خونه فرار کرده و از اون موقع پیدا نشده. معمولاً نزدیک پارک‌ها و سطل‌های
                        زباله دیده می‌شه و از غریبه‌ها کمی می‌ترسه. اگه جایی دیدیش، سمتش نرو و فقط موقعیت رو از همین
                        صفحه گزارش کن.
                    </p>
                </section>

                <!-- ===== LOCATION ===== -->
                <section class="px-5 mt-5">
                    <h2 class="font-bold text-base text-ink mb-3"
                        x-text="listingType==='lost' ? 'آخرین مکان دیده‌شدن' : 'موقعیت تقریبی'"></h2>

                    <div class="rounded-2xl overflow-hidden ring-1 ring-ink/10">
                        <div class="relative h-28 bg-sand-100">
                            <svg class="absolute inset-0 w-full h-full" viewBox="0 0 320 110"
                                preserveAspectRatio="none">
                                <rect width="320" height="110" fill="#F5E9D6" />
                                <path d="M0 70 Q80 40 160 65 T320 50" stroke="#E7D2B0" stroke-width="6"
                                    fill="none" />
                                <path d="M0 30 Q100 55 180 25 T320 35" stroke="#E7D2B0" stroke-width="6"
                                    fill="none" />
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
                            <p class="font-semibold text-sm text-ink">سعادت‌آباد، تهران · ۱.۲ کیلومتر از شما</p>
                            <p class="text-xs text-ink-soft mt-1" x-show="listingType!=='lost'" x-cloak>آدرس دقیق بعد
                                از تایید درخواست نمایش داده می‌شود.</p>
                            <p class="text-xs text-ink-soft mt-1" x-show="listingType==='lost'" x-cloak>دوشنبه، ساعت
                                ۱۸:۳۰</p>
                        </div>
                    </div>
                </section>

                <!-- ===== OWNER CARD ===== -->
                <section class="px-5 mt-5">
                    <div class="bg-white rounded-2xl ring-1 ring-ink/10 p-3.5 flex items-center gap-3">
                        <img src="https://i.pravatar.cc/100?img=47" class="w-12 h-12 rounded-full object-cover"
                            alt="نگار رضایی">
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-sm text-ink flex items-center gap-1">
                                نگار رضایی
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="#E2622A">
                                    <path
                                        d="M12 2l2.4 2.1 3.1-.4 1 3 2.8 1.5-.7 3.1 1.7 2.7-2.4 2.1.4 3.1-3.1.6-1.7 2.7-3-1-3 1-1.7-2.7-3.1-.6.4-3.1-2.4-2.1 1.7-2.7-.7-3.1L6.5 4.7l3.1.4Z" />
                                    <path d="M9 12.5l2 2 4-4.5" stroke="#fff" stroke-width="1.8" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </p>
                            <p class="text-[11px] text-ink-soft mt-0.5"
                                x-text="listingType==='lost' ? 'گزارش‌دهنده · عضو از ۱۴۰۲' : 'ثبت‌کننده آگهی · عضو از ۱۴۰۲'">
                            </p>
                            <p class="text-[11px] text-ink-soft mt-0.5">⭐ ۴.۸ (۲۳ نظر) · پاسخ زیر ۱ ساعت</p>
                        </div>
                        <button
                            class="shrink-0 text-xs font-semibold text-saffron-600 border border-saffron-200 rounded-full px-3 py-1.5">پروفایل</button>
                    </div>
                </section>

                <!-- ===== SAFETY TIPS ===== -->
                <section class="px-5 mt-5">
                    <div class="bg-turmeric-100/60 rounded-2xl p-4">
                        <h3 class="font-bold text-xs text-ink mb-2 flex items-center gap-1.5">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#993D17"
                                stroke-width="2">
                                <path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7Z" />
                            </svg>
                            نکات امنیتی
                        </h3>
                        <ul class="text-xs text-ink-soft leading-6 list-disc ps-4 space-y-1">
                            <li>ملاقات اول رو در یه فضای عمومی یا محل نگه‌داری حیوون برگزار کن.</li>
                            <li>قبل از واریز هر مبلغی (مثل هزینه‌ی واکسیناسیون) با دقت بررسی کن.</li>
                            <li>از سلامت ظاهری حیوون قبل از انتقال نهایی اطمینان حاصل کن.</li>
                        </ul>
                    </div>
                </section>

                <!-- ===== SIMILAR PETS ===== -->
                <section class="pt-7 px-5">
                    <h2 class="font-bold text-base text-ink mb-3">حیوون‌های مشابه</h2>
                    <div class="flex gap-3 overflow-x-auto no-scrollbar -mx-5 px-5">
                        <article class="shrink-0 w-36 bg-white rounded-2xl overflow-hidden ring-1 ring-black/5">
                            <img src="https://placedog.net/300/300?id=37" class="w-full h-24 object-cover"
                                alt="رکسی">
                            <div class="p-2">
                                <h3 class="font-bold text-xs text-ink">رکسی</h3>
                                <p class="text-[10px] text-ink-soft mt-0.5">ژرمن شپرد · ۵ کیلومتر</p>
                            </div>
                        </article>
                        <article class="shrink-0 w-36 bg-white rounded-2xl overflow-hidden ring-1 ring-black/5">
                            <img src="https://placedog.net/300/300?id=41" class="w-full h-24 object-cover"
                                alt="بارون">
                            <div class="p-2">
                                <h3 class="font-bold text-xs text-ink">بارون</h3>
                                <p class="text-[10px] text-ink-soft mt-0.5">پامرانین · ۴.۵ کیلومتر</p>
                            </div>
                        </article>
                        <article class="shrink-0 w-36 bg-white rounded-2xl overflow-hidden ring-1 ring-black/5">
                            <img src="https://placekitten.com/300/301" class="w-full h-24 object-cover"
                                alt="میو">
                            <div class="p-2">
                                <h3 class="font-bold text-xs text-ink">میو</h3>
                                <p class="text-[10px] text-ink-soft mt-0.5">پرشین · ۳ کیلومتر</p>
                            </div>
                        </article>
                    </div>
                </section>

                <!-- ===== REPORT LINK ===== -->
                <section class="px-5 pt-6 pb-4">
                    <button class="flex items-center gap-1.5 text-xs text-ink-soft/80">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 21V4M5 4h12l-2.5 3.5L17 11H5" />
                        </svg>
                        گزارش این آگهی
                    </button>
                </section>
            </main>

            <!-- ===== STICKY ACTION BAR ===== -->
            <div class="absolute bottom-0 inset-x-0 z-30 bg-white/95 backdrop-blur-md border-t border-ink/5 px-4 py-3 flex items-center gap-2"
                style="padding-bottom: calc(env(safe-area-inset-bottom) + 0.75rem)">
                <button aria-label="تماس"
                    class="shrink-0 grid place-items-center w-12 h-12 rounded-full bg-sand-100 text-ink active:scale-95 transition">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z" />
                    </svg>
                </button>
                <button
                    class="flex-1 flex items-center justify-center gap-1.5 h-12 rounded-2xl bg-sand-100 text-ink font-bold text-sm active:scale-95 transition">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 11.5a8.5 8.5 0 1 1-3.8-7.1L21 3l-1.3 3.8c.8 1.3 1.3 2.9 1.3 4.7Z" />
                    </svg>
                    پیام
                </button>
                <button class="flex-[1.4] h-12 rounded-2xl text-white font-bold text-sm active:scale-95 transition"
                    :class="listingType === 'adopt' ? 'bg-saffron-500' : listingType==='foster' ? 'bg-pistachio-dark' :
                        'bg-coral'"
                    x-text="listingType==='adopt' ? 'درخواست فرزندخواهی' : listingType==='foster' ? 'اعلام آمادگی نگه‌داری' : 'من دیدمش، اطلاع بده'">
                </button>
            </div>

        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
