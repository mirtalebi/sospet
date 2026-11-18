<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>SOS Pet — برای هر حیوونی، یه خونه</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&family=Lalezar&family=Poppins:wght@700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<!-- Cleaned body tag without the extra background and text utility classes -->

<body class="">

    <div x-data="{ sidebarOpen: false }"
        class="relative w-full  bg-bg-main flex flex-col min-h-screen md:min-h-[85vh] overflow-hidden">

        <!-- Header -->
        <header class="relative z-30 shrink-0 px-4 pt-3 md:px-6 md:pt-4">
            <div
                class="mx-auto flex max-w-6xl items-center justify-between gap-2 rounded-[1.6rem] border border-border-custom/80 bg-bg-main/85 px-3 py-2.5 shadow-[0_18px_40px_-28px_rgba(34,50,74,0.45)] backdrop-blur-xl md:rounded-full md:px-5 md:py-3">

                <!-- Brand -->
                <div class="flex min-w-0 items-center gap-2.5">
                    <button type="button" @click="sidebarOpen = true" aria-label="باز کردن منوی سایت"
                        class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl border border-border-custom bg-bg-secondary text-text-title transition hover:border-primary/40 hover:text-primary active:scale-95">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2" stroke-linecap="round">
                            <path d="M4 7h16M4 12h10M4 17h16" />
                        </svg>
                    </button>

                    <a href="{{ route('home') }}" wire:navigate class="group flex min-w-0 items-center gap-2.5">
                        <span
                            class="relative grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-primary to-[#1B7C8C] text-white shadow-md shadow-primary/25 transition group-hover:-rotate-6 md:h-11 md:w-11">
                            <svg width="21" height="21" viewBox="0 0 24 24" aria-hidden="true">
                                <g fill="currentColor">
                                    <ellipse cx="12" cy="5" rx="1.9" ry="2.4" />
                                    <ellipse cx="6.9" cy="8" rx="1.7" ry="2.2" transform="rotate(-22 6.9 8)" />
                                    <ellipse cx="17.1" cy="8" rx="1.7" ry="2.2" transform="rotate(22 17.1 8)" />
                                    <ellipse cx="4.5" cy="13.2" rx="1.5" ry="1.9" transform="rotate(-38 4.5 13.2)" />
                                    <ellipse cx="19.5" cy="13.2" rx="1.5" ry="1.9" transform="rotate(38 19.5 13.2)" />
                                    <path
                                        d="M12 11.3c-2.5 0-4.6 1.6-5.5 3.8-.8 2 .4 4.2 2.6 4.6.9.2 1.9 0 2.9-.6.7-.4 1.4-.4 2.1 0 1 .6 2 .8 2.9.6 2.2-.4 3.4-2.6 2.6-4.6-.9-2.2-3-3.9-5.6-3.9Z" />
                                </g>
                            </svg>
                            <span
                                class="absolute -end-1 -top-1 grid h-4 w-4 place-items-center rounded-full bg-cta text-[9px] leading-none text-white ring-2 ring-bg-main">♥</span>
                        </span>
                        <span class="min-w-0">
                            <span
                                class="block font-display text-xl leading-none tracking-wide text-text-title md:text-2xl">ساس‌پت</span>
                            <span
                                class="mt-0.5 hidden text-[10px] font-medium text-text-muted sm:block">برای هر حیوونی، یه خونه</span>
                        </span>
                    </a>
                </div>

                <!-- Desktop navigation -->
                <nav aria-label="ناوبری اصلی" class="hidden items-center gap-1 text-xs font-bold text-text-body md:flex">
                    <a href="{{ route('home') }}" wire:navigate
                        class="rounded-full px-3 py-2 transition hover:bg-bg-secondary hover:text-primary">خانه</a>
                    <a href="{{ route('match-pets', ['mode' => 'lost']) }}" wire:navigate
                        class="rounded-full px-3 py-2 transition hover:bg-bg-secondary hover:text-primary">حیوون گم‌شده</a>
                    <a href="{{ route('match-pets', ['mode' => 'found']) }}" wire:navigate
                        class="rounded-full px-3 py-2 transition hover:bg-bg-secondary hover:text-primary">حیوون پیدا‌شده</a>
                    <a href="{{ route('about-us') }}" wire:navigate
                        class="rounded-full px-3 py-2 transition hover:bg-bg-secondary hover:text-primary">درباره ما</a>
                </nav>

                <!-- Actions -->
                <div class="flex shrink-0 items-center gap-1.5">
                    @auth
                        <a href="/profile" wire:navigate aria-label="پروفایل من"
                            class="hidden h-10 w-10 place-items-center rounded-full border border-border-custom bg-bg-secondary text-xs font-bold text-primary transition hover:border-primary/40 sm:grid">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </a>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" title="پنل مدیریت" aria-label="پنل مدیریت"
                                class="grid h-10 w-10 place-items-center rounded-full border border-border-custom bg-bg-secondary text-primary transition hover:border-primary/40 hover:bg-primary/5">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7Z" />
                                    <path d="M9 12l2 2 4-4" />
                                </svg>
                            </a>
                        @endif
                        <a href="/report" wire:navigate
                            class="inline-flex h-10 items-center gap-1.5 rounded-full bg-cta px-4 text-xs font-bold text-white shadow-lg shadow-cta/25 transition hover:bg-cta-hover active:scale-95 md:h-11 md:px-5 md:text-sm">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.6" stroke-linecap="round">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            ثبت آگهی
                        </a>
                    @else
                        <button type="button" @click="$dispatch('open-auth')"
                            class="inline-flex h-10 items-center gap-1.5 rounded-full bg-cta px-4 text-xs font-bold text-white shadow-lg shadow-cta/25 transition hover:bg-cta-hover active:scale-95 md:h-11 md:px-5 md:text-sm">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.6" stroke-linecap="round">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            ثبت آگهی
                        </button>
                    @endauth
                </div>
            </div>
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
        <div class="fixed bottom-5 inset-x-1/2 flex justify-center z-40 px-4 md:bottom-8">
            <nav
                class="bg-bg-main/90 backdrop-blur-md border border-border-custom px-5 py-2 md:px-8 md:py-3 shadow-lg flex items-center gap-8 rounded-full">

                <!-- Home Link -->
                <a href="/"
                    class="flex flex-col items-center transition-transform active:scale-95 {{ $navLinkClass($isHome) }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                    </svg>
                    <span class="text-[10px] md:text-[11px] font-bold">خانه</span>
                </a>

                <!-- Compact Center Action -->
                @auth
                    <a href="/report" wire:navigate
                        class="w-10 h-10 {{ $isReport ? 'bg-primary-hover' : 'bg-primary' }} text-white rounded-full flex items-center justify-center shadow-sm hover:bg-primary-hover transition-all active:scale-90">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.8">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                    </a>
                @else
                    <button @click="$dispatch('open-auth')"
                        class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center shadow-sm hover:bg-primary-hover transition-all active:scale-90">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.8">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                    </button>
                @endauth

                <!-- Profile Link -->
                <a href="/profile" wire:navigate
                    class="flex flex-col items-center transition-transform active:scale-95 {{ $navLinkClass($isProfile) }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span class="text-[10px] md:text-[11px] font-medium">پروفایل</span>
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
                    <a href="{{ route('home') }}" wire:navigate
                        class="px-3 py-3 rounded-xl bg-primary/10 text-primary font-bold">خانه</a>
                    <a href="{{ route('match-pets', ['mode' => 'lost']) }}" wire:navigate
                        class="px-3 py-3 rounded-xl text-text-title hover:bg-bg-secondary">حیوون‌های گم‌شده</a>
                    <a href="{{ route('match-pets', ['mode' => 'found']) }}" wire:navigate
                        class="px-3 py-3 rounded-xl text-text-title hover:bg-bg-secondary">حیوون‌های پیدا‌شده</a>
                    <a href="{{ route('about-us') }}" wire:navigate
                        class="px-3 py-3 rounded-xl text-text-title hover:bg-bg-secondary">درباره ما</a>

                    <div class="mt-auto flex flex-col gap-2.5 pt-6">
                        @auth
                            <div class="bg-primary/5 border border-primary/10 rounded-xl p-3 flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-full bg-primary text-white font-bold flex items-center justify-center text-sm">
                                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-text-title">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-text-body" dir="ltr">{{ auth()->user()->mobile }}</p>
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
