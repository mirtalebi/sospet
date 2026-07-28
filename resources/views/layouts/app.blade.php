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

<body class="flex justify-center min-h-screen antialiased bg-bg-secondary selection:bg-primary/20">

    <div x-data="{ sidebarOpen: false }"
        class="relative w-full max-w-md bg-bg-main flex flex-col min-h-screen shadow-2xl overflow-hidden ring-1 ring-border-custom">

        <!-- Header -->
        <header
            class="flex items-center justify-between px-5 py-2 bg-gradient-to-b from-primary/5 to-transparent shrink-0">
            {{-- <button @click="sidebarOpen = true"
                class="w-10 h-10 rounded-full bg-bg-main flex items-center justify-center border border-border-custom text-text-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5">
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button> --}}
            <div class="flex items-center gap-1.5">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center"
                    style="background: rgb(231, 244, 246);"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                        height="16" viewBox="0 0 24 24" fill="none" stroke="#1298AE" stroke-width="2.3"
                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-paw-print md:hidden"
                        aria-hidden="true">
                        <circle cx="11" cy="4" r="2"></circle>
                        <circle cx="18" cy="8" r="2"></circle>
                        <circle cx="20" cy="16" r="2"></circle>
                        <path
                            d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z">
                        </path>
                    </svg><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="#1298AE" stroke-width="2.3" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-paw-print hidden md:block" aria-hidden="true">
                        <circle cx="11" cy="4" r="2"></circle>
                        <circle cx="18" cy="8" r="2"></circle>
                        <circle cx="20" cy="16" r="2"></circle>
                        <path
                            d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z">
                        </path>
                    </svg></div>

                <span class="font-display text-2xl text-text-title tracking-wide">ردپا</span>
            </div>

            @auth
                <a href="/report" wire:navigate
                    class="px-5 h-10 rounded-full bg-cta flex items-center justify-center border border-border-custom text-white text-sm relative">
                    ثبت آگهی
                </a>
            @else
                <button @click="$dispatch('open-auth')"
                    class="px-5 h-10 rounded-full bg-cta flex items-center justify-center border border-border-custom text-white text-sm relative">
                    ثبت آگهی
                </button>
            @endauth
        </header>

        <!-- Dynamic Slot Content -->
        {{ $slot }}

        @php
            $isHome = request()->routeIs('home') || request()->is('/');
            $isProfile = request()->is('profile');
            $isReport = request()->is('report');
            $navLinkClass = fn($active = false) => $active
                ? 'flex flex-col items-center gap-1 text-primary font-bold transition-transform active:scale-95'
                : 'flex flex-col items-center gap-1 text-text-muted hover:text-text-title font-medium transition-transform active:scale-95';
        @endphp

        <!-- Navigation Bar -->
        <div class="fixed bottom-5 inset-x-0 flex justify-center z-40 px-4">
            <nav
                class="bg-bg-main/90 backdrop-blur-md border border-border-custom px-4 py-1.5 shadow-lg flex items-center gap-6 rounded-full">

                <!-- Home Link -->
                <a href="/"
                    class="flex flex-col items-center transition-transform active:scale-95 {{ $navLinkClass($isHome) }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                    </svg>
                    <span class="text-[9px] font-bold mt-0.5">خانه</span>
                </a>

                <!-- Compact Center Action (Reporter) -->
                @auth
                    <a href="/report" wire:navigate
                        class="w-9 h-9 {{ $isReport ? 'bg-primary-hover' : 'bg-primary' }} text-white rounded-full flex items-center justify-center shadow-sm hover:bg-primary-hover transition-all active:scale-90">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.8">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                    </a>
                @else
                    <button @click="$dispatch('open-auth')"
                        class="w-9 h-9 bg-primary text-white rounded-full flex items-center justify-center shadow-sm hover:bg-primary-hover transition-all active:scale-90">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.8">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                    </button>
                @endauth

                <!-- Profile Link -->
                <a href="/profile" wire:navigate
                    class="flex flex-col items-center transition-transform active:scale-95 {{ $navLinkClass($isProfile) }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span class="text-[9px] font-medium mt-0.5">پروفایل</span>
                </a>

            </nav>
        </div>

        <!-- Sidebar / Drawer Drawer Overlay and Content -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
            <div @click="sidebarOpen = false" x-show="sidebarOpen"
                x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-text-title/40 backdrop-blur-sm"></div>

            <div class="absolute inset-y-0 start-0 max-w-xs w-full bg-bg-main flex flex-col p-6 shadow-xl"
                x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">

                <div class="flex items-center justify-between pb-6 border-b border-border-custom">
                    <span class="font-display text-xl text-primary font-bold">منوی ناوبری</span>
                    <button @click="sidebarOpen = false" class="text-text-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>
                </div>

                <nav class="flex flex-col gap-1 text-sm font-medium mt-4">
                    <a href="#" class="px-3 py-3 rounded-xl bg-primary/10 text-primary font-bold">خانه</a>
                    <a href="#" class="px-3 py-3 rounded-xl text-text-title hover:bg-bg-secondary">جستجوی
                        حیوانات</a>
                    <a href="#" class="px-3 py-3 rounded-xl text-text-title hover:bg-bg-secondary">درباره ما</a>

                    <div class="mt-auto flex flex-col gap-2.5 pt-6">
                        @auth
                            <div class="bg-primary/5 border border-primary/10 rounded-xl p-3 flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-full bg-primary text-white font-bold flex items-center justify-center text-sm">
                                    {{ mb_substr(auth()->user()->first_name, 0, 1) }}
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-text-title">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-text-body" dir="ltr">{{ auth()->user()->phone }}</p>
                                </div>
                            </div>
                            <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="w-full bg-bg-secondary text-danger-custom font-semibold text-xs py-2.5 rounded-xl border border-border-custom hover:bg-danger-custom/10 transition-colors">
                                خروج از حساب
                            </button>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf
                            </form>
                        @else
                            <button @click="sidebarOpen = false; $dispatch('open-auth')"
                                class="w-full bg-primary text-white font-bold text-sm py-3 rounded-xl hover:bg-primary-hover transition-colors">
                                ورود به حساب کاربری
                            </button>
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
