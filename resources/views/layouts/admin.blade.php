<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'پنل مدیریت' }} — ساس‌پت</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&family=Lalezar&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-bg-secondary font-sans text-text-body antialiased">
    @php
        $links = [
            ['admin.dashboard', 'داشبورد', 'M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10'],
            ['admin.users', 'کاربران', 'M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 21a8 8 0 0116 0'],
            ['admin.pets', 'گزارش‌ها', 'M9 12h6M9 16h6M7 3h7l5 5v13H7z'],
        ];
    @endphp

    <div class="min-h-screen md:flex">
        <aside class="border-b border-border-custom bg-bg-main md:sticky md:top-0 md:h-screen md:w-64 md:shrink-0 md:border-b-0 md:border-l">
            <div class="flex items-center justify-between gap-3 px-5 py-4 md:flex-col md:items-stretch md:gap-6 md:py-6">
                <div class="font-display text-2xl text-text-title">ساس‌پت <span class="text-sm text-primary">مدیریت</span></div>

                <nav class="flex gap-1 text-sm font-semibold md:flex-col">
                    @foreach ($links as [$routeName, $label, $icon])
                        @php $active = request()->routeIs($routeName); @endphp
                        <a href="{{ route($routeName) }}" wire:navigate
                            class="flex items-center gap-2 rounded-xl px-3 py-2.5 transition {{ $active ? 'bg-primary text-white shadow-sm' : 'text-text-body hover:bg-bg-secondary hover:text-text-title' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="{{ $icon }}" />
                            </svg>
                            <span class="max-md:hidden">{{ $label }}</span>
                        </a>
                    @endforeach
                </nav>

                <a href="{{ route('home') }}"
                    class="text-xs font-semibold text-text-muted transition hover:text-primary md:mt-auto">بازگشت به سایت</a>
            </div>
        </aside>

        <main class="min-w-0 flex-1 px-5 py-8 md:px-10">
            <div class="mx-auto max-w-5xl">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>

</html>
