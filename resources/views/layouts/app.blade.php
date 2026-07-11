<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Pawet — برای هر حیوونی، یه خونه</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&family=Lalezar&family=Poppins:wght@700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="flex justify-center min-h-screen antialiased bg-sand-100 selection:bg-saffron-200">

    <div x-data="{ sidebarOpen: false }"
        class="relative w-full max-w-md bg-sand flex flex-col min-h-screen shadow-2xl overflow-hidden ring-1 ring-ink/5">

        <header
            class="flex items-center justify-between px-5 py-4 bg-gradient-to-b from-turmeric-300/10 to-transparent shrink-0">
            <button @click="sidebarOpen = true"
                class="w-10 h-10 rounded-full bg-white flex items-center justify-center border border-ink/5 text-ink">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5">
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
            <div class="flex items-center gap-1.5">
                <span class="font-display text-2xl text-saffron-500 tracking-wide">Pawet</span>
                <span class="text-xl">🐾</span>
            </div>
            <button
                class="w-10 h-10 rounded-full bg-white flex items-center justify-center border border-ink/5 text-ink relative">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" />
                </svg>
                <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-coral rounded-full ring-2 ring-white"></span>
            </button>
        </header>

        {{ $slot }}

        @php
            $isHome = request()->routeIs('home') || request()->is('/');
            $isProfile = request()->is('profile');
            $isReport = request()->is('report');
            $navLinkClass = fn($active = false) => $active
                ? 'flex flex-col items-center gap-1 text-saffron-500 font-bold transition-transform active:scale-95'
                : 'flex flex-col items-center gap-1 text-ink-soft hover:text-ink font-medium transition-transform active:scale-95';
        @endphp

        <nav
            class="fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-ink/5 px-4 pt-2.5 pb-safe shadow-nav flex justify-around items-center z-40 rounded-t-[1.8rem]">
            <a href="/" class="{{ $navLinkClass($isHome) }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                </svg>
                <span class="text-[10px] font-bold">خانه</span>
            </a>
            <a href="#" class="{{ $navLinkClass(false) }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.2">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <span class="text-[10px] font-medium">جستجو</span>
            </a>

            @auth
                <a href="/report" wire:navigate
                    class="relative -top-5 w-14 h-14 {{ $isReport ? 'bg-saffron-600' : 'bg-saffron-500' }} text-white rounded-full flex items-center justify-center shadow-lg shadow-saffron-500/30 border-4 border-sand hover:bg-saffron-600 transition-all active:scale-90 z-50">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="3">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                </a>
            @else
                <button @click="$dispatch('open-auth')"
                    class="relative -top-5 w-14 h-14 bg-saffron-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-saffron-500/30 border-4 border-sand hover:bg-saffron-600 transition-all active:scale-90 z-50">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="3">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                </button>
            @endauth

            <a href="#" class="{{ $navLinkClass(false) }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.2">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
                <span class="text-[10px] font-medium">نشان‌ها</span>
            </a>
            <a href="/profile" wire:navigate class="{{ $navLinkClass($isProfile) }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span class="text-[10px] font-medium">پروفایل</span>
            </a>
        </nav>

        <div x-show="sidebarOpen" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
            <div @click="sidebarOpen = false" x-show="sidebarOpen"
                x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-ink/40 backdrop-blur-sm"></div>
            <div class="absolute inset-y-0 start-0 max-w-xs w-full bg-sand flex flex-col p-6 shadow-xl"
                x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                <div class="flex items-center justify-between pb-6 border-b border-ink/5">
                    <span class="font-display text-xl text-saffron-600">منوی ناوبری</span>
                    <button @click="sidebarOpen = false" class="text-ink"><svg width="20" height="20"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg></button>
                </div>
                <nav class="flex flex-col gap-1 text-sm font-medium mt-4">
                    <a href="#" class="px-3 py-3 rounded-xl bg-saffron-50 text-saffron-600 font-bold">خانه</a>
                    <a href="#" class="px-3 py-3 rounded-xl text-ink">جستجوی حیوانات</a>
                    <a href="#" class="px-3 py-3 rounded-xl text-ink">درباره ما</a>

                    <div class="mt-auto flex flex-col gap-2.5 pt-6">
                        @auth
                            <div class="bg-saffron-50 border border-saffron-100 rounded-xl p-3 flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-full bg-saffron-500 text-white font-bold flex items-center justify-center text-sm">
                                    {{ mb_substr(auth()->user()->first_name, 0, 1) }}
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-ink">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-ink-soft" dir="ltr">{{ auth()->user()->phone }}</p>
                                </div>
                            </div>
                            <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="w-full bg-sand-100 text-coral-dark font-semibold text-xs py-2.5 rounded-xl">خروج از
                                حساب</button>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf
                            </form>
                        @else
                            <button @click="sidebarOpen = false; $dispatch('open-auth')"
                                class="w-full bg-saffron-500 text-white font-bold text-sm py-3 rounded-xl">ورود به حساب
                                کاربری</button>
                        @endauth
                    </div>
                </nav>
            </div>
        </div>

    </div>

    @livewireScripts
    <livewire:auth-modal />
</body>

</html>
