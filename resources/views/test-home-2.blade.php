<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#FBF7F1">
    <title>Pawet | پاوت</title>

    <!-- فونت فارسی Vazirmatn -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS (Play CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        vazir: ['Vazirmatn', 'Tahoma', 'sans-serif']
                    },
                    colors: {
                        sand: {
                            50: '#FBF7F1',
                            100: '#F5EEE3',
                            200: '#EDE0CC'
                        },
                        ink: {
                            50: '#F6F5F3',
                            100: '#EBE8E3',
                            200: '#D9D4CB',
                            300: '#B9B2A8',
                            400: '#988F83',
                            500: '#7A7268',
                            600: '#5C564D',
                            700: '#433E37',
                            800: '#332E29',
                            900: '#241F1A'
                        },
                        turquoise: {
                            50: '#EAFBFA',
                            100: '#CFF5F3',
                            200: '#9FE8E4',
                            300: '#6FDAD4',
                            400: '#3DC2BB',
                            500: '#1FAFA8',
                            600: '#128F89',
                            700: '#0E726D',
                            800: '#0B5C58',
                            900: '#0A4F4B'
                        },
                        terracotta: {
                            50: '#FDF1EC',
                            100: '#FBE0D3',
                            200: '#F3C0A4',
                            300: '#E8A684',
                            400: '#DD8961',
                            500: '#D97644',
                            600: '#C1622D',
                            700: '#9C4D22',
                            800: '#7C3D1B'
                        },
                        sage: {
                            50: '#F1F6EF',
                            100: '#DEEBD9',
                            200: '#C2DBB9',
                            300: '#A9C9A0',
                            400: '#8DB983',
                            500: '#7FAB74',
                            600: '#5F8A56',
                            700: '#4A6E43'
                        }
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
            scroll-behavior: smooth;
        }

        ::selection {
            background: #128F89;
            color: #fff;
        }

        button,
        a,
        input {
            -webkit-tap-highlight-color: transparent;
        }

        :focus-visible {
            outline: 2px solid #128F89;
            outline-offset: 2px;
            border-radius: 6px;
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-ink-100 font-vazir text-ink-700 antialiased">

    <!--
  یادداشت پیاده‌سازی Livewire:
  این فایل یک پروتوتایپ تک‌فایلی برای نمایش طراحیه. در نسخه‌ی واقعی:
  - آرایه‌های lostPets / adoptPets / fosterPets از طریق props سمت سرور پر می‌شن، نه Alpine
  - x-model روی input جست‌وجو می‌تونه با wire:model.live جایگزین/ترکیب بشه
  - دکمه‌ی + پایین، به ۳ روتِ ثبت‌آگهی وصل می‌شه (wire:click="openPostFlow('lost')" و ...)
-->

    <div x-data="pawetApp()" x-cloak
        class="relative mx-auto flex h-[100dvh] w-full max-w-[440px] flex-col overflow-hidden bg-sand-50 sm:my-8 sm:h-[880px] sm:rounded-[2.75rem] sm:shadow-2xl sm:ring-8 sm:ring-ink-900/5">

        <!-- ===================== Header ===================== -->
        <header
            class="flex shrink-0 items-center justify-between border-b border-ink-900/5 bg-sand-50/95 px-4 py-3 backdrop-blur">
            <div class="flex items-center gap-2">
                <div
                    class="grid h-9 w-9 place-items-center rounded-xl bg-turquoise-600 text-white shadow-sm shadow-turquoise-600/30">
                    <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                        <ellipse cx="12" cy="16.2" rx="5" ry="4" />
                        <ellipse cx="5" cy="9" rx="2" ry="2.6" />
                        <ellipse cx="9.5" cy="5.3" rx="2" ry="2.6" />
                        <ellipse cx="14.5" cy="5.3" rx="2" ry="2.6" />
                        <ellipse cx="19" cy="9" rx="2" ry="2.6" />
                    </svg>
                </div>
                <span class="text-[17px] font-extrabold tracking-tight text-ink-900">Pawet</span>
            </div>

            <!-- شهر و پروفایل (چپ) -->
            <div class="flex items-center gap-2">
                <button
                    class="flex items-center gap-1 rounded-full bg-ink-900/5 px-2.5 py-1.5 text-xs font-bold text-ink-700 transition active:scale-95">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                        stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 text-turquoise-600">
                        <path d="M12 21s-7-7.2-7-12a7 7 0 1 1 14 0c0 4.8-7 12-7 12Z" />
                        <circle cx="12" cy="9" r="2.2" />
                    </svg>
                    تهران
                </button>
                <button aria-label="پروفایل"
                    class="grid h-9 w-9 place-items-center rounded-full bg-ink-900/5 text-ink-700 transition active:scale-95">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" class="h-4.5 w-4.5">
                        <circle cx="12" cy="8" r="3.4" />
                        <path d="M5 20c1.2-3.6 4-5.5 7-5.5s5.8 1.9 7 5.5" />
                    </svg>
                </button>
            </div>
        </header>

        <!-- ===================== محتوای قابل اسکرول ===================== -->
        <main class="flex-1 overflow-y-auto pb-6 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">

            <!-- Hero -->
            <section class="relative overflow-hidden px-4 pb-2 pt-5">
                <div
                    class="pointer-events-none absolute -left-12 -top-16 h-44 w-44 rounded-full bg-turquoise-100 blur-3xl">
                </div>

                <h1 class="relative text-[22px] font-extrabold text-ink-900">سلام 👋</h1>
                <p class="relative mt-1 text-sm text-ink-500">امروز دنبال چی می‌گردی؟</p>

                <!-- کنترل سه‌گانه: فرزندخواندگی / سرپرستی / گم‌شده -->
                <!-- در Livewire: می‌تونه با wire:click روی یک پراپرتی activeTab سمت سرور هم پیاده بشه -->
                <div class="relative mt-4 grid grid-cols-3 gap-1 rounded-2xl bg-ink-900/5 p-1">
                    <button @click="scrollTo('adopt')"
                        :class="activeTab === 'adopt' ? 'bg-white text-turquoise-700 shadow-sm' : 'text-ink-500'"
                        class="rounded-xl py-2 text-[12.5px] font-bold transition">فرزندخواندگی</button>
                    <button @click="scrollTo('foster')"
                        :class="activeTab === 'foster' ? 'bg-white text-sage-700 shadow-sm' : 'text-ink-500'"
                        class="rounded-xl py-2 text-[12.5px] font-bold transition">سرپرستی</button>
                    <button @click="scrollTo('lost')"
                        :class="activeTab === 'lost' ? 'bg-white text-terracotta-700 shadow-sm' : 'text-ink-500'"
                        class="rounded-xl py-2 text-[12.5px] font-bold transition">گم‌شده</button>
                </div>

                <!-- جست‌وجو -->
                <label
                    class="relative mt-3 flex items-center gap-2 rounded-full border border-ink-900/10 bg-white px-4 py-2.5 shadow-sm">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0 text-ink-400">
                        <circle cx="10.5" cy="10.5" r="6.5" />
                        <path d="M20 20l-4.8-4.8" />
                    </svg>
                    <input x-model="query" type="text" placeholder="نام، نژاد یا محله رو جست‌وجو کن…"
                        class="w-full bg-transparent text-sm text-ink-900 placeholder:text-ink-400 focus:outline-none">
                </label>
            </section>

            <!-- ===================== گم‌شده‌های این اطراف ===================== -->
            <section id="lost-section" class="mt-6 px-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="flex items-center gap-1.5 text-[15px] font-extrabold text-ink-900">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-terracotta-500 opacity-75"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-terracotta-600"></span>
                            </span>
                            گم‌شده‌های این اطراف
                        </h2>
                        <p class="mt-0.5 text-xs text-ink-500">کمک کن این دوستا برگردن خونه</p>
                    </div>
                    <button class="flex items-center gap-0.5 text-xs font-bold text-terracotta-600">
                        همه
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5">
                            <path d="M14 6l-6 6 6 6" />
                        </svg>
                    </button>
                </div>

                <div
                    class="-mx-4 mt-3 flex gap-3 overflow-x-auto px-4 pb-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    <template x-for="pet in lostPets" :key="pet.name">
                        <article class="w-[152px] shrink-0">
                            <div class="relative aspect-[4/5] overflow-hidden rounded-2xl bg-ink-900/5">
                                <img :src="pet.img" :alt="pet.name" loading="lazy"
                                    class="h-full w-full object-cover">
                                <span
                                    class="absolute right-2 top-2 inline-flex items-center gap-1 rounded-full bg-terracotta-600 px-2 py-1 text-[10px] font-bold text-white">
                                    <span class="relative flex h-1.5 w-1.5">
                                        <span
                                            class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-75"></span>
                                        <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-white"></span>
                                    </span>
                                    گم‌شده
                                </span>
                                <div
                                    class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent px-2 pb-2 pt-7">
                                    <p class="flex items-center gap-1 text-[11px] font-bold text-white">
                                        <svg viewBox="0 0 24 24" fill="currentColor" class="h-3 w-3 shrink-0">
                                            <path d="M12 21s-6-6.3-6-10.5A6 6 0 1 1 18 10.5C18 14.7 12 21 12 21Z" />
                                        </svg>
                                        <span x-text="pet.location"></span>
                                    </p>
                                </div>
                            </div>
                            <p class="mt-1.5 text-sm font-bold text-ink-900" x-text="pet.name"></p>
                            <p class="text-[11px] text-ink-500" x-text="pet.timeAgo + ' · ' + pet.distance"></p>
                        </article>
                    </template>
                </div>
            </section>

            <!-- ===================== آماده برای خونه‌ی جدید ===================== -->
            <section id="adopt-section" class="mt-7 px-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-[15px] font-extrabold text-ink-900">آماده برای خونه‌ی جدید</h2>
                        <p class="mt-0.5 text-xs text-ink-500">این دوستا منتظر یه خانواده‌ن</p>
                    </div>
                    <button class="flex items-center gap-0.5 text-xs font-bold text-turquoise-600">
                        همه
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5">
                            <path d="M14 6l-6 6 6 6" />
                        </svg>
                    </button>
                </div>

                <div
                    class="-mx-4 mt-3 flex gap-3 overflow-x-auto px-4 pb-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    <template x-for="pet in adoptPets" :key="pet.name">
                        <article class="w-[152px] shrink-0">
                            <div class="relative aspect-[4/5] overflow-hidden rounded-2xl bg-ink-900/5">
                                <img :src="pet.img" :alt="pet.name" loading="lazy"
                                    class="h-full w-full object-cover">
                                <button @click.stop="pet.liked = !pet.liked" :aria-pressed="pet.liked"
                                    class="absolute left-2 top-2 grid h-7 w-7 place-items-center rounded-full bg-white/90 text-ink-400 backdrop-blur transition active:scale-90"
                                    :class="pet.liked && 'text-terracotta-500'">
                                    <svg viewBox="0 0 24 24" :fill="pet.liked ? 'currentColor' : 'none'"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" class="h-3.5 w-3.5">
                                        <path
                                            d="M12 20s-7.5-4.6-9.8-9A5.5 5.5 0 0 1 12 5a5.5 5.5 0 0 1 9.8 6c-2.3 4.4-9.8 9-9.8 9Z" />
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-1.5 text-sm font-bold text-ink-900" x-text="pet.name"></p>
                            <p class="text-[11px] text-ink-500" x-text="pet.meta"></p>
                            <p class="text-[11px] text-turquoise-700" x-text="pet.place"></p>
                        </article>
                    </template>
                </div>
            </section>

            <!-- ===================== سرپرستی موقت لازم دارن ===================== -->
            <section id="foster-section" class="mt-7 px-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-[15px] font-extrabold text-ink-900">سرپرستی موقت لازم دارن</h2>
                        <p class="mt-0.5 text-xs text-ink-500">چند هفته مهمون خونه‌ت باش</p>
                    </div>
                    <button class="flex items-center gap-0.5 text-xs font-bold text-sage-700">
                        همه
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5">
                            <path d="M14 6l-6 6 6 6" />
                        </svg>
                    </button>
                </div>

                <div
                    class="-mx-4 mt-3 flex gap-3 overflow-x-auto px-4 pb-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    <template x-for="pet in fosterPets" :key="pet.name">
                        <article class="w-[152px] shrink-0">
                            <div class="relative aspect-[4/5] overflow-hidden rounded-2xl bg-ink-900/5">
                                <img :src="pet.img" :alt="pet.name" loading="lazy"
                                    class="h-full w-full object-cover">
                                <span
                                    class="absolute right-2 top-2 rounded-full bg-sage-600 px-2 py-1 text-[10px] font-bold text-white"
                                    x-text="pet.duration"></span>
                            </div>
                            <p class="mt-1.5 text-sm font-bold text-ink-900" x-text="pet.name"></p>
                            <p class="text-[11px] text-ink-500" x-text="pet.meta"></p>
                            <p class="text-[11px] text-sage-700" x-text="pet.note"></p>
                        </article>
                    </template>
                </div>
            </section>

            <p class="mb-2 mt-8 px-10 text-center text-xs leading-relaxed text-ink-400">
                🐾 پاوت یعنی هیچ حیوونی تنها نمونه
            </p>
        </main>

        <!-- ===================== نوار پایین ===================== -->
        <nav class="relative z-20 flex shrink-0 items-stretch justify-between border-t border-ink-900/5 bg-white/95 px-2 pt-1.5 backdrop-blur"
            style="padding-bottom:max(0.5rem, env(safe-area-inset-bottom))">
            <button class="flex flex-1 flex-col items-center gap-0.5 py-1.5 text-turquoise-600">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                    stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M4 11.5 12 4l8 7.5" />
                    <path d="M6 10v9a1 1 0 0 0 1 1h3v-5.5h4V20h3a1 1 0 0 0 1-1v-9" />
                </svg>
                <span class="text-[10px] font-bold">خانه</span>
            </button>

            <button class="flex flex-1 flex-col items-center gap-0.5 py-1.5 text-ink-400">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                    stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <circle cx="10.5" cy="10.5" r="6.5" />
                    <path d="M20 20l-4.8-4.8" />
                </svg>
                <span class="text-[10px] font-bold">جست‌وجو</span>
            </button>

            <div class="flex flex-1 items-start justify-center">
                <button @click="postSheetOpen = true" aria-label="ثبت آگهی"
                    class="-mt-7 grid h-14 w-14 place-items-center rounded-full bg-turquoise-600 text-white shadow-lg shadow-turquoise-600/40 ring-4 ring-sand-50 transition active:scale-95">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" class="h-6 w-6">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                </button>
            </div>

            <button class="relative flex flex-1 flex-col items-center gap-0.5 py-1.5 text-ink-400">
                <span class="relative">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                        stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <path
                            d="M21 11.5a8.4 8.4 0 0 1-8.5 8.5 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8A8.4 8.4 0 0 1 12.5 3 8.5 8.5 0 0 1 21 11.5Z" />
                    </svg>
                    <span class="absolute -left-0.5 -top-0.5 h-1.5 w-1.5 rounded-full bg-terracotta-500"></span>
                </span>
                <span class="text-[10px] font-bold">پیام‌ها</span>
            </button>

            <button class="flex flex-1 flex-col items-center gap-0.5 py-1.5 text-ink-400">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                    stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <circle cx="12" cy="8" r="3.4" />
                    <path d="M5 20c1.2-3.6 4-5.5 7-5.5s5.8 1.9 7 5.5" />
                </svg>
                <span class="text-[10px] font-bold">پروفایل</span>
            </button>
        </nav>

        <!-- ===================== Bottom sheet ثبت آگهی ===================== -->
        <!-- در Livewire: هر گزینه به یک روت/Component مجزای فرم ثبت‌آگهی وصل می‌شه -->
        <div x-show="postSheetOpen" x-cloak class="absolute inset-0 z-40">
            <div x-show="postSheetOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="postSheetOpen = false"
                class="absolute inset-0 bg-ink-900/40"></div>

            <div x-show="postSheetOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full" role="dialog" aria-modal="true"
                class="absolute inset-x-0 bottom-0 rounded-t-3xl bg-white p-4 pb-7 shadow-2xl">
                <div class="mx-auto mb-4 h-1 w-10 rounded-full bg-ink-900/10"></div>
                <h3 class="text-[16px] font-extrabold text-ink-900">چی می‌خوای ثبت کنی؟</h3>
                <p class="mb-4 mt-0.5 text-xs text-ink-500">یکی از گزینه‌ها رو انتخاب کن</p>

                <button
                    class="mb-2 flex w-full items-center gap-3 rounded-2xl border border-ink-900/10 p-3 text-right transition active:bg-sand-100">
                    <span
                        class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-turquoise-100 text-turquoise-700">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                            <ellipse cx="12" cy="16.2" rx="5" ry="4" />
                            <ellipse cx="5" cy="9" rx="2" ry="2.6" />
                            <ellipse cx="9.5" cy="5.3" rx="2" ry="2.6" />
                            <ellipse cx="14.5" cy="5.3" rx="2" ry="2.6" />
                            <ellipse cx="19" cy="9" rx="2" ry="2.6" />
                        </svg>
                    </span>
                    <span>
                        <span class="block text-sm font-bold text-ink-900">ثبت برای فرزندخواندگی</span>
                        <span class="block text-xs text-ink-500">حیوونی داری که دنبال خونه‌ی جدیده؟</span>
                    </span>
                </button>

                <button
                    class="mb-2 flex w-full items-center gap-3 rounded-2xl border border-ink-900/10 p-3 text-right transition active:bg-sand-100">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-sage-100 text-sage-700">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                            <path d="M3 11.5 12 4l9 7.5" />
                            <path d="M5 10v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-9" />
                            <path d="M9 20v-6h6v6" />
                        </svg>
                    </span>
                    <span>
                        <span class="block text-sm font-bold text-ink-900">ثبت سرپرستی موقت</span>
                        <span class="block text-xs text-ink-500">می‌تونی چند هفته موقت نگهش داری؟</span>
                    </span>
                </button>

                <button
                    class="mb-4 flex w-full items-center gap-3 rounded-2xl border border-ink-900/10 p-3 text-right transition active:bg-sand-100">
                    <span
                        class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-terracotta-100 text-terracotta-700">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                            <path d="M12 21s-7-7.2-7-12a7 7 0 1 1 14 0c0 4.8-7 12-7 12Z" />
                            <circle cx="12" cy="9" r="2.2" />
                        </svg>
                    </span>
                    <span>
                        <span class="block text-sm font-bold text-ink-900">گزارش حیوان گم‌شده</span>
                        <span class="block text-xs text-ink-500">حیوونت گم شده؟ سریع با عکس و موقعیت ثبتش کن</span>
                    </span>
                </button>

                <button @click="postSheetOpen = false"
                    class="w-full py-1 text-center text-sm font-bold text-ink-400">انصراف</button>
            </div>
        </div>

    </div>

    <!-- داده‌ی نمونه (در نسخه‌ی واقعی از سمت سرور/Livewire میاد) -->
    <script>
        function pawetApp() {
            return {
                activeTab: 'adopt',
                postSheetOpen: false,
                query: '',
                scrollTo(id) {
                    this.activeTab = id;
                    const el = document.getElementById(id + '-section');
                    if (el) el.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                },
                lostPets: [{
                        name: 'بل',
                        location: 'سعادت‌آباد',
                        distance: '۱٫۲ کیلومتری',
                        timeAgo: '۳ ساعت پیش',
                        img: 'https://placedog.net/500/600?id=15'
                    },
                    {
                        name: 'رکس',
                        location: 'ونک',
                        distance: '۲٫۵ کیلومتری',
                        timeAgo: 'دیروز',
                        img: 'https://placedog.net/500/600?id=22'
                    },
                    {
                        name: 'مولی',
                        location: 'تهرانپارس',
                        distance: '۴ کیلومتری',
                        timeAgo: '۶ ساعت پیش',
                        img: 'https://placedog.net/500/600?id=8'
                    },
                    {
                        name: 'بارون',
                        location: 'نیاوران',
                        distance: '۸۰۰ متری',
                        timeAgo: '۱ ساعت پیش',
                        img: 'https://placedog.net/500/600?id=33'
                    }
                ],
                adoptPets: [{
                        name: 'نون',
                        meta: 'گربه بالغ · ۲ ساله',
                        place: 'ونک، تهران',
                        liked: false,
                        img: 'https://placedog.net/500/600?id=4'
                    },
                    {
                        name: 'گردو',
                        meta: 'توله سگ · ۳ ماهه',
                        place: 'سعادت‌آباد، تهران',
                        liked: false,
                        img: 'https://placedog.net/500/600?id=11'
                    },
                    {
                        name: 'لوسی',
                        meta: 'سگ مخلوط‌نژاد · بالغ',
                        place: 'کرج',
                        liked: true,
                        img: 'https://placedog.net/500/600?id=27'
                    },
                    {
                        name: 'پشمک',
                        meta: 'گربه ایرانی · ۱ ساله',
                        place: 'اصفهان',
                        liked: false,
                        img: 'https://placedog.net/500/600?id=19'
                    }
                ],
                fosterPets: [{
                        name: 'تام',
                        duration: '۲ هفته',
                        meta: 'توله سگ',
                        note: 'تا پیدا شدن خانواده‌ی دائم',
                        img: 'https://placedog.net/500/600?id=40'
                    },
                    {
                        name: 'شیرین',
                        duration: '۱ ماه',
                        meta: 'گربه بالغ',
                        note: 'صاحبش موقتاً مسافرته',
                        img: 'https://placedog.net/500/600?id=6'
                    },
                    {
                        name: 'نوا',
                        duration: '۳ هفته',
                        meta: 'سگ سالمند',
                        note: 'نیاز به مراقبت ویژه داره',
                        img: 'https://placedog.net/500/600?id=44'
                    }
                ]
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>

</html>
