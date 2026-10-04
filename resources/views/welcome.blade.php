<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>SOS pet — برای هر حیوونی، یه خونه</title>

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
                        tag: '0 18px 30px -12px rgba(194, 77, 28, 0.35)',
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

        /* the grommet hole on the signature "pet-tag" search bar */
        .tag-hole {
            position: absolute;
            inset-inline-start: 1.5rem;
            top: -0.55rem;
            width: 1.1rem;
            height: 1.1rem;
            border-radius: 9999px;
            background: #FBF5EC;
            box-shadow: inset 0 0 0 2.5px rgba(194, 77, 28, 0.45);
        }
    </style>
</head>

<body class="bg-stone-200 text-ink font-body antialiased" x-data="{ drawerOpen: false, sheetOpen: false, activeTab: 'all' }">

    <!-- ============ PHONE-FRAME WRAPPER (true edge-to-edge on mobile, framed preview on desktop) ============ -->
    <div class="min-h-screen sm:py-8 sm:flex sm:items-center sm:justify-center">
        <div
            class="relative mx-auto w-full max-w-md min-h-screen sm:min-h-[844px] bg-sand sm:rounded-[2.25rem] sm:shadow-2xl sm:ring-1 sm:ring-black/5 overflow-hidden flex flex-col">

            <!-- ============ HEADER ============ -->
            <header class="sticky top-0 z-30 flex items-center justify-between px-5 py-3.5 bg-sand/80 backdrop-blur-md">
                <button @click="drawerOpen = true" aria-label="باز کردن منو"
                    class="grid place-items-center w-10 h-10 rounded-full bg-white/70 text-ink active:scale-95 transition">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round">
                        <path d="M4 7h16M4 12h16M4 17h10" />
                    </svg>
                </button>

                <div class="flex items-center gap-1.5">
                    <span class="grid place-items-center w-7 h-7 rounded-full bg-saffron-500 text-white">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 14c-3.3 0-7 2.6-7 5.6 0 1.3 1 2.4 2.6 2.4 1.6 0 2.6-1 4.4-1 1.8 0 2.8 1 4.4 1 1.6 0 2.6-1.1 2.6-2.4 0-3-3.7-5.6-7-5.6Z" />
                            <circle cx="5.5" cy="11" r="2.3" />
                            <circle cx="18.5" cy="11" r="2.3" />
                            <circle cx="8.5" cy="5.8" r="2.1" />
                            <circle cx="15.5" cy="5.8" r="2.1" />
                        </svg>
                    </span>
                    <span class="font-brand font-extrabold text-lg text-ink tracking-tight">SOS Pet</span>
                </div>

                <button aria-label="اعلان‌ها"
                    class="relative grid place-items-center w-10 h-10 rounded-full bg-white/70 text-ink active:scale-95 transition">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8a6 6 0 1 0-12 0c0 3.2-1 5-2 7h16c-1-2-2-3.8-2-7Z" />
                        <path d="M9.5 19a2.5 2.5 0 0 0 5 0" />
                    </svg>
                    <span class="absolute top-1.5 end-1.5 w-2 h-2 rounded-full bg-coral ring-2 ring-sand"></span>
                </button>
            </header>

            <!-- ============ MAIN ============ -->
            <main class="flex-1 overflow-y-auto no-scrollbar pb-28">

                <!-- ===== HERO ===== -->
                <section
                    class="relative bg-gradient-to-b from-turmeric-300/70 via-saffron-100 to-sand px-5 pt-2 pb-12 rounded-b-[2.5rem]">

                    <span
                        class="inline-flex items-center gap-1.5 bg-white/80 text-ink-soft text-xs font-medium px-3 py-1.5 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-saffron-500"></span>
                        صدها حیوون منتظر یه خونه‌ن
                    </span>

                    <h1 class="font-display text-[2.1rem] leading-[1.35] text-ink mt-4">
                        برای هر حیوونی،<br><span class="text-saffron-600">یه خونه.</span>
                    </h1>
                    <p class="text-ink-soft text-sm leading-7 mt-2 max-w-[19rem]">
                        فرزندخواهی کن، میزبان موقت باش، یا کمک کن یه حیوون گمشده برگرده خونه.
                    </p>

                    <!-- decorative paw outlines, used sparingly -->
                    <svg class="absolute top-6 start-6 w-10 h-10 text-saffron-500/15 -rotate-12" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path
                            d="M12 14c-3.3 0-7 2.6-7 5.6 0 1.3 1 2.4 2.6 2.4 1.6 0 2.6-1 4.4-1 1.8 0 2.8 1 4.4 1 1.6 0 2.6-1.1 2.6-2.4 0-3-3.7-5.6-7-5.6Z" />
                        <circle cx="5.5" cy="11" r="2.3" />
                        <circle cx="18.5" cy="11" r="2.3" />
                        <circle cx="8.5" cy="5.8" r="2.1" />
                        <circle cx="15.5" cy="5.8" r="2.1" />
                    </svg>

                    <div class="relative mt-6 rounded-[1.75rem] overflow-hidden h-44 shadow-tag">
                        <img src="https://placedog.net/640/360?id=21" alt="سگی که منتظر فرزندخواهی است"
                            class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-ink/40 via-transparent to-transparent"></div>
                        <span
                            class="absolute bottom-3 end-3 bg-white/90 text-ink text-[11px] font-semibold px-2.5 py-1 rounded-full">رکسی
                            · ۴ ساله</span>
                    </div>

                    <!-- signature element: the search bar styled as a pet ID tag -->
                    <div
                        class="relative -mb-16 mt-5 bg-white rounded-[1.4rem] shadow-tag p-2 flex items-center gap-2 -rotate-1">
                        <span class="tag-hole" aria-hidden="true"></span>
                        <button type="button"
                            class="flex items-center gap-1 shrink-0 ps-3 pe-2.5 py-2 rounded-2xl bg-sand-100 text-ink-soft text-xs font-medium">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z" />
                                <circle cx="12" cy="9.5" r="2.3" />
                            </svg>
                            تهران
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <input type="text" placeholder="دنبال چی می‌گردی؟ گربه، سگ، خرگوش…"
                            class="flex-1 bg-transparent text-sm text-ink placeholder:text-ink-soft/70 outline-none px-1 min-w-0">
                        <button type="button" aria-label="جستجو"
                            class="grid place-items-center w-10 h-10 rounded-2xl bg-saffron-500 text-white active:scale-95 transition shrink-0">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.3" stroke-linecap="round">
                                <circle cx="11" cy="11" r="7" />
                                <path d="M21 21l-4.3-4.3" />
                            </svg>
                        </button>
                    </div>
                </section>

                <!-- ===== ACTION CATEGORIES ===== -->
                <section class="pt-20 px-5">
                    <h2 class="font-bold text-base text-ink mb-3">چی می‌خوای انجام بدی؟</h2>

                    <div
                        class="flex gap-3 overflow-x-auto no-scrollbar -mx-5 px-5 sm:grid sm:grid-cols-3 sm:gap-3 sm:overflow-visible sm:mx-0 sm:px-0">

                        <a href="#"
                            class="shrink-0 w-[12.5rem] sm:w-auto bg-saffron-50 border border-saffron-200 rounded-2xl p-4 flex flex-col gap-2.5">
                            <span class="grid place-items-center w-10 h-10 rounded-xl bg-saffron-500 text-white">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12 21s-7.5-4.7-10-9.3C0.3 8.4 1.7 5 5 4.3 7.3 3.8 9.6 5 12 8c2.4-3 4.7-4.2 7-3.7 3.3.7 4.7 4.1 3 7.4C19.5 16.3 12 21 12 21Z" />
                                </svg>
                            </span>
                            <h3 class="font-bold text-sm text-ink">فرزندخواهی</h3>
                            <p class="text-xs text-ink-soft leading-5">صاحب همیشگی یه دوست تازه شو</p>
                            <span class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-saffron-600">
                                شروع کن
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M15 18l-6-6 6-6" />
                                </svg>
                            </span>
                        </a>

                        <a href="#"
                            class="shrink-0 w-[12.5rem] sm:w-auto bg-pistachio-light border border-pistachio/40 rounded-2xl p-4 flex flex-col gap-2.5">
                            <span class="grid place-items-center w-10 h-10 rounded-xl bg-pistachio-dark text-white">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M4 11l8-7 8 7" />
                                    <path d="M6 10v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-9" />
                                    <path d="M12 21v-5" />
                                </svg>
                            </span>
                            <h3 class="font-bold text-sm text-ink">نگه‌داری موقت</h3>
                            <p class="text-xs text-ink-soft leading-5">چند هفته میزبانش باش تا خونه پیدا کنه</p>
                            <span
                                class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-pistachio-dark">
                                شروع کن
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M15 18l-6-6 6-6" />
                                </svg>
                            </span>
                        </a>

                        <a href="#"
                            class="shrink-0 w-[12.5rem] sm:w-auto bg-coral-light border border-coral/30 rounded-2xl p-4 flex flex-col gap-2.5 relative">
                            <span
                                class="absolute top-3 end-3 text-[10px] font-bold text-white bg-coral px-2 py-0.5 rounded-full">فوری</span>
                            <span class="grid place-items-center w-10 h-10 rounded-xl bg-coral text-white">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z" />
                                    <circle cx="12" cy="9.5" r="2.3" />
                                </svg>
                            </span>
                            <h3 class="font-bold text-sm text-ink">حیوون گمشده</h3>
                            <p class="text-xs text-ink-soft leading-5">گمش کردی یا یه جا دیدیش؟ خبر بده</p>
                            <span class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-coral-dark">
                                ثبت گزارش
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M15 18l-6-6 6-6" />
                                </svg>
                            </span>
                        </a>
                    </div>
                </section>

                <!-- ===== NEAR YOU ===== -->
                <section class="pt-8 px-5">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="font-bold text-base text-ink">نزدیک تو</h2>
                        <a href="#" class="text-xs font-semibold text-saffron-600 flex items-center gap-1">
                            مشاهده همه
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M15 18l-6-6 6-6" />
                            </svg>
                        </a>
                    </div>

                    <!-- segmented filter -->
                    <div class="inline-flex bg-sand-100 rounded-full p-1 mb-4 text-xs font-medium">
                        <button @click="activeTab='all'"
                            :class="activeTab === 'all' ? 'bg-white text-ink shadow-sm' : 'text-ink-soft'"
                            class="px-3.5 py-1.5 rounded-full transition">همه</button>
                        <button @click="activeTab='adopt'"
                            :class="activeTab === 'adopt' ? 'bg-white text-ink shadow-sm' : 'text-ink-soft'"
                            class="px-3.5 py-1.5 rounded-full transition">فرزندخواهی</button>
                        <button @click="activeTab='foster'"
                            :class="activeTab === 'foster' ? 'bg-white text-ink shadow-sm' : 'text-ink-soft'"
                            class="px-3.5 py-1.5 rounded-full transition">نگه‌داری موقت</button>
                    </div>

                    <div class="grid grid-cols-2 gap-3">

                        <template x-if="activeTab==='all' || activeTab==='adopt'">
                            <article class="bg-white rounded-2xl overflow-hidden ring-1 ring-black/5"
                                x-data="{ saved: false }">
                                <div class="relative h-28">
                                    <img src="https://placedog.net/300/300?id=23" class="w-full h-full object-cover"
                                        alt="لوسی، سگ پشمالو">
                                    <button @click="saved=!saved"
                                        class="absolute top-2 end-2 grid place-items-center w-7 h-7 rounded-full bg-white/85"
                                        :aria-pressed="saved" aria-label="ذخیره">
                                        <svg width="14" height="14" viewBox="0 0 24 24"
                                            :fill="saved ? '#D8503F' : 'none'" stroke="#D8503F" stroke-width="2">
                                            <path
                                                d="M12 21s-7.5-4.7-10-9.3C0.3 8.4 1.7 5 5 4.3 7.3 3.8 9.6 5 12 8c2.4-3 4.7-4.2 7-3.7 3.3.7 4.7 4.1 3 7.4C19.5 16.3 12 21 12 21Z" />
                                        </svg>
                                    </button>
                                    <span
                                        class="absolute bottom-2 start-2 bg-saffron-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">فرزندخواهی</span>
                                </div>
                                <div class="p-2.5">
                                    <h3 class="font-bold text-sm text-ink">لوسی</h3>
                                    <p class="text-[11px] text-ink-soft mt-0.5">سگ پشمالو · ۲ ساله</p>
                                    <p class="text-[11px] text-ink-soft/80 mt-1">سعادت‌آباد · ۱.۲ کیلومتر</p>
                                </div>
                            </article>
                        </template>

                        <template x-if="activeTab==='all' || activeTab==='foster'">
                            <article class="bg-white rounded-2xl overflow-hidden ring-1 ring-black/5"
                                x-data="{ saved: false }">
                                <div class="relative h-28">
                                    <img src="https://placekitten.com/300/301" class="w-full h-full object-cover"
                                        alt="میو، گربه پرشین">
                                    <button @click="saved=!saved"
                                        class="absolute top-2 end-2 grid place-items-center w-7 h-7 rounded-full bg-white/85"
                                        :aria-pressed="saved" aria-label="ذخیره">
                                        <svg width="14" height="14" viewBox="0 0 24 24"
                                            :fill="saved ? '#D8503F' : 'none'" stroke="#D8503F" stroke-width="2">
                                            <path
                                                d="M12 21s-7.5-4.7-10-9.3C0.3 8.4 1.7 5 5 4.3 7.3 3.8 9.6 5 12 8c2.4-3 4.7-4.2 7-3.7 3.3.7 4.7 4.1 3 7.4C19.5 16.3 12 21 12 21Z" />
                                        </svg>
                                    </button>
                                    <span
                                        class="absolute bottom-2 start-2 bg-pistachio-dark text-white text-[10px] font-bold px-2 py-0.5 rounded-full">نگه‌داری
                                        موقت</span>
                                </div>
                                <div class="p-2.5">
                                    <h3 class="font-bold text-sm text-ink">میو</h3>
                                    <p class="text-[11px] text-ink-soft mt-0.5">گربه پرشین · ۸ ماهه</p>
                                    <p class="text-[11px] text-ink-soft/80 mt-1">ونک · ۳ کیلومتر</p>
                                </div>
                            </article>
                        </template>

                        <template x-if="activeTab==='all' || activeTab==='adopt'">
                            <article class="bg-white rounded-2xl overflow-hidden ring-1 ring-black/5"
                                x-data="{ saved: false }">
                                <div class="relative h-28">
                                    <img src="https://placedog.net/300/300?id=37" class="w-full h-full object-cover"
                                        alt="رکسی، سگ ژرمن شپرد">
                                    <button @click="saved=!saved"
                                        class="absolute top-2 end-2 grid place-items-center w-7 h-7 rounded-full bg-white/85"
                                        :aria-pressed="saved" aria-label="ذخیره">
                                        <svg width="14" height="14" viewBox="0 0 24 24"
                                            :fill="saved ? '#D8503F' : 'none'" stroke="#D8503F" stroke-width="2">
                                            <path
                                                d="M12 21s-7.5-4.7-10-9.3C0.3 8.4 1.7 5 5 4.3 7.3 3.8 9.6 5 12 8c2.4-3 4.7-4.2 7-3.7 3.3.7 4.7 4.1 3 7.4C19.5 16.3 12 21 12 21Z" />
                                        </svg>
                                    </button>
                                    <span
                                        class="absolute bottom-2 start-2 bg-saffron-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">فرزندخواهی</span>
                                </div>
                                <div class="p-2.5">
                                    <h3 class="font-bold text-sm text-ink">رکسی</h3>
                                    <p class="text-[11px] text-ink-soft mt-0.5">ژرمن شپرد · ۴ ساله</p>
                                    <p class="text-[11px] text-ink-soft/80 mt-1">پاسداران · ۵ کیلومتر</p>
                                </div>
                            </article>
                        </template>

                        <template x-if="activeTab==='all' || activeTab==='foster'">
                            <article class="bg-white rounded-2xl overflow-hidden ring-1 ring-black/5"
                                x-data="{ saved: false }">
                                <div class="relative h-28">
                                    <img src="https://placedog.net/300/300?id=41" class="w-full h-full object-cover"
                                        alt="بارون، سگ پامرانین">
                                    <button @click="saved=!saved"
                                        class="absolute top-2 end-2 grid place-items-center w-7 h-7 rounded-full bg-white/85"
                                        :aria-pressed="saved" aria-label="ذخیره">
                                        <svg width="14" height="14" viewBox="0 0 24 24"
                                            :fill="saved ? '#D8503F' : 'none'" stroke="#D8503F" stroke-width="2">
                                            <path
                                                d="M12 21s-7.5-4.7-10-9.3C0.3 8.4 1.7 5 5 4.3 7.3 3.8 9.6 5 12 8c2.4-3 4.7-4.2 7-3.7 3.3.7 4.7 4.1 3 7.4C19.5 16.3 12 21 12 21Z" />
                                        </svg>
                                    </button>
                                    <span
                                        class="absolute bottom-2 start-2 bg-pistachio-dark text-white text-[10px] font-bold px-2 py-0.5 rounded-full">نگه‌داری
                                        موقت</span>
                                </div>
                                <div class="p-2.5">
                                    <h3 class="font-bold text-sm text-ink">بارون</h3>
                                    <p class="text-[11px] text-ink-soft mt-0.5">پامرانین · ۶ ماهه</p>
                                    <p class="text-[11px] text-ink-soft/80 mt-1">نیاوران · ۴.۵ کیلومتر</p>
                                </div>
                            </article>
                        </template>

                    </div>
                </section>

                <!-- ===== LOST PET ALERTS ===== -->
                <section class="pt-8 px-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <h2 class="font-bold text-base text-ink">گزارش‌های گمشدگی</h2>
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-coral-dark bg-coral-light px-2 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-coral animate-pulse"></span> زنده
                            </span>
                        </div>
                        <a href="#" class="text-xs font-semibold text-saffron-600">مشاهده همه</a>
                    </div>

                    <div class="flex flex-col gap-3">
                        <article class="flex items-center gap-3 bg-white rounded-2xl p-3 border-s-4 border-coral">
                            <img src="https://placekitten.com/100/100"
                                class="w-14 h-14 rounded-xl object-cover shrink-0" alt="گربه پرشین مشکی گمشده">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-ink">گربه پرشین مشکی</h3>
                                <p class="text-[11px] text-ink-soft mt-0.5">آخرین بازدید: پارک ملت · ۲ ساعت پیش</p>
                            </div>
                            <button
                                class="shrink-0 text-xs font-bold text-coral-dark border border-coral/40 rounded-full px-3 py-1.5">دیدمش</button>
                        </article>

                        <article class="flex items-center gap-3 bg-white rounded-2xl p-3 border-s-4 border-coral">
                            <img src="https://placedog.net/100/100?id=55"
                                class="w-14 h-14 rounded-xl object-cover shrink-0" alt="سگ پاکوتاه قهوه‌ای گمشده">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-ink">بارفی · سگ پاکوتاه قهوه‌ای</h3>
                                <p class="text-[11px] text-ink-soft mt-0.5">آخرین بازدید: میدان ونک · دیروز، ۱۸:۰۰</p>
                            </div>
                            <button
                                class="shrink-0 text-xs font-bold text-coral-dark border border-coral/40 rounded-full px-3 py-1.5">دیدمش</button>
                        </article>
                    </div>
                </section>

                <!-- ===== HOW IT WORKS ===== -->
                <section class="pt-9 px-5">
                    <h2 class="font-bold text-base text-ink mb-5">چطور کار می‌کنه؟</h2>
                    <ol class="relative ps-2">
                        <div class="absolute top-2 bottom-2 start-[1.15rem] w-px bg-saffron-200"></div>

                        <li class="relative flex gap-4 pb-6">
                            <span
                                class="relative z-10 grid place-items-center w-9 h-9 rounded-full bg-saffron-500 text-white font-bold text-sm shrink-0">۱</span>
                            <div>
                                <h3 class="font-bold text-sm text-ink">پروفایل حیوون رو ببین</h3>
                                <p class="text-xs text-ink-soft mt-1 leading-6">عکس، نژاد، سن و توضیحات کامل رو بخون.
                                </p>
                            </div>
                        </li>
                        <li class="relative flex gap-4 pb-6">
                            <span
                                class="relative z-10 grid place-items-center w-9 h-9 rounded-full bg-saffron-500 text-white font-bold text-sm shrink-0">۲</span>
                            <div>
                                <h3 class="font-bold text-sm text-ink">با صاحبش گفتگو کن</h3>
                                <p class="text-xs text-ink-soft mt-1 leading-6">مستقیم و رایگان پیام بده و سوالاتو
                                    بپرس.</p>
                            </div>
                        </li>
                        <li class="relative flex gap-4">
                            <span
                                class="relative z-10 grid place-items-center w-9 h-9 rounded-full bg-saffron-500 text-white font-bold text-sm shrink-0">۳</span>
                            <div>
                                <h3 class="font-bold text-sm text-ink">قرار بگذار و ببرش خونه</h3>
                                <p class="text-xs text-ink-soft mt-1 leading-6">وقتی مطمئن شدی، قرار ملاقات حضوری رو
                                    بگذار.</p>
                            </div>
                        </li>
                    </ol>
                </section>

                <!-- ===== STATS ===== -->
                <section class="pt-9 px-5">
                    <div class="bg-sand-100 rounded-2xl px-4 py-5">
                        <p class="text-xs font-semibold text-ink-soft mb-3">ساس‌پت تا امروز</p>
                        <div class="grid grid-cols-3 text-center gap-2">
                            <div>
                                <p class="font-display text-xl text-saffron-600">۱٬۲۰۰+</p>
                                <p class="text-[11px] text-ink-soft mt-0.5">فرزندخواهی موفق</p>
                            </div>
                            <div class="border-x border-ink/10">
                                <p class="font-display text-xl text-pistachio-dark">۳۰۰+</p>
                                <p class="text-[11px] text-ink-soft mt-0.5">نگه‌داری فعال</p>
                            </div>
                            <div>
                                <p class="font-display text-xl text-coral-dark">۸۵۰+</p>
                                <p class="text-[11px] text-ink-soft mt-0.5">بازگشت به خونه</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ===== CTA BANNER ===== -->
                <section class="pt-9 px-5">
                    <div
                        class="relative bg-gradient-to-l from-saffron-600 to-saffron-400 rounded-[1.75rem] p-5 overflow-hidden">
                        <svg class="absolute -bottom-4 -start-4 w-28 h-28 text-white/10" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M12 14c-3.3 0-7 2.6-7 5.6 0 1.3 1 2.4 2.6 2.4 1.6 0 2.6-1 4.4-1 1.8 0 2.8 1 4.4 1 1.6 0 2.6-1.1 2.6-2.4 0-3-3.7-5.6-7-5.6Z" />
                            <circle cx="5.5" cy="11" r="2.3" />
                            <circle cx="18.5" cy="11" r="2.3" />
                            <circle cx="8.5" cy="5.8" r="2.1" />
                            <circle cx="15.5" cy="5.8" r="2.1" />
                        </svg>
                        <h2 class="font-display text-xl text-white leading-relaxed relative z-10">حیوونی داری که به
                            خونه‌ی جدید نیاز داره؟</h2>
                        <p class="text-white/85 text-xs mt-1.5 mb-4 relative z-10">ثبت آگهی رایگانه و کمتر از ۲ دقیقه
                            طول می‌کشه.</p>
                        <button
                            class="relative z-10 bg-white text-saffron-600 font-bold text-sm px-4 py-2.5 rounded-xl active:scale-95 transition">ثبت
                            رایگان آگهی</button>
                    </div>
                </section>

                <!-- ===== FOOTER ===== -->
                <footer class="pt-10 px-5 pb-6">
                    <div class="flex items-center gap-1.5 mb-3">
                        <span class="grid place-items-center w-6 h-6 rounded-full bg-saffron-500 text-white">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 14c-3.3 0-7 2.6-7 5.6 0 1.3 1 2.4 2.6 2.4 1.6 0 2.6-1 4.4-1 1.8 0 2.8 1 4.4 1 1.6 0 2.6-1.1 2.6-2.4 0-3-3.7-5.6-7-5.6Z" />
                                <circle cx="5.5" cy="11" r="2.3" />
                                <circle cx="18.5" cy="11" r="2.3" />
                                <circle cx="8.5" cy="5.8" r="2.1" />
                                <circle cx="15.5" cy="5.8" r="2.1" />
                            </svg>
                        </span>
                        <span class="font-brand font-extrabold text-sm text-ink">SOS Pet</span>
                    </div>
                    <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs text-ink-soft mb-4">
                        <a href="{{ route('about-us') }}" wire:navigate class="hover:text-ink">درباره ساس‌پت</a>
                        <a href="{{ route('support') }}" wire:navigate class="hover:text-ink">پشتیبانی</a>
                        <a href="{{ route('terms') }}" wire:navigate class="hover:text-ink">قوانین و مقررات</a>
                        <a href="{{ route('privacy') }}" wire:navigate class="hover:text-ink">حریم خصوصی</a>
                    </div>
                    <p class="text-[11px] text-ink-soft/70">© ۱۴۰۵ SOS Pet — ساخته‌شده با ❤️ در ایران.</p>
                </footer>
            </main>

            <!-- ============ BOTTOM NAV ============ -->
            <nav class="absolute bottom-0 inset-x-0 z-30 bg-white/95 backdrop-blur-md border-t border-ink/5"
                style="padding-bottom: env(safe-area-inset-bottom)">
                <div class="relative grid grid-cols-5 items-center px-2 py-2.5">

                    <button class="flex flex-col items-center gap-1 text-saffron-600">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 11l8-7 8 7" />
                            <path d="M6 10v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-9" />
                        </svg>
                        <span class="text-[10px] font-semibold">خانه</span>
                    </button>

                    <button class="flex flex-col items-center gap-1 text-ink-soft">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <circle cx="11" cy="11" r="7" />
                            <path d="M21 21l-4.3-4.3" />
                        </svg>
                        <span class="text-[10px] font-medium">جستجو</span>
                    </button>

                    <div class="relative flex justify-center">
                        <button @click="sheetOpen = true" aria-label="ثبت آگهی جدید"
                            class="absolute -top-9 grid place-items-center w-14 h-14 rounded-full bg-saffron-500 text-white shadow-tag ring-4 ring-sand active:scale-95 transition">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                        </button>
                    </div>

                    <button class="relative flex flex-col items-center gap-1 text-ink-soft">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8a6 6 0 1 0-12 0c0 3.2-1 5-2 7h16c-1-2-2-3.8-2-7Z" />
                            <path d="M9.5 19a2.5 2.5 0 0 0 5 0" />
                        </svg>
                        <span class="absolute top-0 end-5 w-1.5 h-1.5 rounded-full bg-coral"></span>
                        <span class="text-[10px] font-medium">اعلان‌ها</span>
                    </button>

                    <button class="flex flex-col items-center gap-1 text-ink-soft">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="3.5" />
                            <path d="M5 20c0-3.3 3.1-6 7-6s7 2.7 7 6" />
                        </svg>
                        <span class="text-[10px] font-medium">پروفایل</span>
                    </button>
                </div>
            </nav>

            <!-- ============ FAB BOTTOM SHEET ============ -->
            <div x-cloak x-show="sheetOpen" class="absolute inset-0 z-40">
                <div x-show="sheetOpen" x-transition:enter="transition-opacity ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-in duration-150"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    @click="sheetOpen=false" class="absolute inset-0 bg-ink/40"></div>

                <div x-show="sheetOpen" x-transition:enter="transition ease-out duration-250"
                    x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0"
                    x-transition:leave-end="translate-y-full"
                    class="absolute bottom-0 inset-x-0 bg-white rounded-t-[1.75rem] p-5"
                    style="padding-bottom: calc(env(safe-area-inset-bottom) + 1.25rem)">
                    <div class="w-10 h-1.5 bg-ink/10 rounded-full mx-auto mb-5"></div>
                    <h3 class="font-bold text-sm text-ink mb-4">چی می‌خوای ثبت کنی؟</h3>

                    <div class="flex flex-col gap-2.5">
                        <a href="#" class="flex items-center gap-3 p-3 rounded-2xl bg-saffron-50">
                            <span
                                class="grid place-items-center w-10 h-10 rounded-xl bg-saffron-500 text-white shrink-0">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12 21s-7.5-4.7-10-9.3C0.3 8.4 1.7 5 5 4.3 7.3 3.8 9.6 5 12 8c2.4-3 4.7-4.2 7-3.7 3.3.7 4.7 4.1 3 7.4C19.5 16.3 12 21 12 21Z" />
                                </svg>
                            </span>
                            <span class="font-semibold text-sm text-ink">ثبت حیوون برای فرزندخواهی</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 p-3 rounded-2xl bg-pistachio-light">
                            <span
                                class="grid place-items-center w-10 h-10 rounded-xl bg-pistachio-dark text-white shrink-0">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M4 11l8-7 8 7" />
                                    <path d="M6 10v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-9" />
                                </svg>
                            </span>
                            <span class="font-semibold text-sm text-ink">ثبت برای نگه‌داری موقت</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 p-3 rounded-2xl bg-coral-light">
                            <span class="grid place-items-center w-10 h-10 rounded-xl bg-coral text-white shrink-0">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z" />
                                    <circle cx="12" cy="9.5" r="2.3" />
                                </svg>
                            </span>
                            <span class="font-semibold text-sm text-coral-dark">گزارش حیوون گمشده</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ============ DRAWER MENU ============ -->
            <div x-cloak x-show="drawerOpen" class="absolute inset-0 z-50">
                <div x-show="drawerOpen" x-transition:enter="transition-opacity ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-in duration-150"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    @click="drawerOpen=false" class="absolute inset-0 bg-ink/40"></div>

                <div x-show="drawerOpen" x-transition:enter="transition ease-out duration-250"
                    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="absolute top-0 end-0 h-full w-[78%] max-w-xs bg-white p-5 flex flex-col"
                    style="padding-top: calc(env(safe-area-inset-top) + 1.25rem)">

                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-1.5">
                            <span class="grid place-items-center w-7 h-7 rounded-full bg-saffron-500 text-white">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12 14c-3.3 0-7 2.6-7 5.6 0 1.3 1 2.4 2.6 2.4 1.6 0 2.6-1 4.4-1 1.8 0 2.8 1 4.4 1 1.6 0 2.6-1.1 2.6-2.4 0-3-3.7-5.6-7-5.6Z" />
                                    <circle cx="5.5" cy="11" r="2.3" />
                                    <circle cx="18.5" cy="11" r="2.3" />
                                    <circle cx="8.5" cy="5.8" r="2.1" />
                                    <circle cx="15.5" cy="5.8" r="2.1" />
                                </svg>
                            </span>
                            <span class="font-brand font-extrabold text-base text-ink">SOS Pet</span>
                        </div>
                        <button @click="drawerOpen=false" aria-label="بستن منو"
                            class="grid place-items-center w-9 h-9 rounded-full bg-sand-100 text-ink-soft">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                <path d="M6 6l12 12M18 6L6 18" />
                            </svg>
                        </button>
                    </div>

                    <nav class="flex flex-col gap-1 text-sm font-medium">
                        <a href="#"
                            class="px-3 py-3 rounded-xl bg-saffron-50 text-saffron-600 font-bold">خانه</a>
                        <a href="#" class="px-3 py-3 rounded-xl text-ink">جستجوی حیوانات</a>
                        <a href="#" class="px-3 py-3 rounded-xl text-ink">فرزندخواهی</a>
                        <a href="#" class="px-3 py-3 rounded-xl text-ink">نگه‌داری موقت</a>
                        <a href="#" class="px-3 py-3 rounded-xl text-ink">حیوانات گمشده</a>
                        <a href="#" class="px-3 py-3 rounded-xl text-ink">درباره ما</a>
                        <a href="#" class="px-3 py-3 rounded-xl text-ink">تماس با ما</a>
                    </nav>

                    <div class="mt-auto flex flex-col gap-2.5 pt-6">
                        <button class="w-full bg-saffron-500 text-white font-bold text-sm py-3 rounded-xl">ورود به حساب
                            کاربری</button>
                        <button class="w-full bg-sand-100 text-ink font-semibold text-sm py-3 rounded-xl">ساخت حساب
                            جدید</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
