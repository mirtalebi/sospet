<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Pawet — هر پنجه، یک خانه</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&family=Baloo+2:wght@600;700;800&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: {
          sans: ['Vazirmatn', 'sans-serif'],
          display: ['"Baloo 2"', 'Vazirmatn', 'sans-serif'],
        },
      }
    }
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<style>
  :root{
    --bone:#F8F4EC;
    --ink:#2B2420;
    --ink-soft:#7A6F62;
    --line:#EAE1D2;
    --amber:#D98826;
    --amber-soft:#FBEFDD;
    --teal:#2B6157;
    --teal-soft:#E4EEEB;
    --coral:#DC5B49;
    --coral-soft:#FBEAE6;
  }
  html, body { font-family: 'Vazirmatn', sans-serif; background:#EDE6D8; }
  .no-scrollbar::-webkit-scrollbar{ display:none; }
  .no-scrollbar{ -ms-overflow-style:none; scrollbar-width:none; }

  .phone-frame{
    width:100%;
    max-width:430px;
    height:100vh;
    max-height:932px;
  }
  @media (min-width:480px){
    .phone-frame{
      margin-top:18px;
      margin-bottom:18px;
      height:calc(100vh - 36px);
      border-radius:40px;
      box-shadow:0 30px 60px -20px rgba(43,36,32,.35), 0 0 0 10px #1c1814;
    }
  }

  [x-cloak]{ display:none !important; }

  @media (prefers-reduced-motion: no-preference){
    .rise{ opacity:0; animation: rise .55s ease forwards; }
    @keyframes rise{ from{ opacity:0; transform:translateY(10px);} to{ opacity:1; transform:translateY(0);} }
    .pulse-dot{ animation: pulseDot 1.8s ease-in-out infinite; }
    @keyframes pulseDot{ 0%,100%{ box-shadow:0 0 0 0 rgba(220,91,73,.45);} 50%{ box-shadow:0 0 0 6px rgba(220,91,73,0);} }
  }

  .focus-ring{ outline:none; }
  .focus-ring:focus-visible{ outline:2px solid var(--teal); outline-offset:2px; }
</style>
</head>

<body class="min-h-screen flex items-start justify-center sm:items-center">

<div class="phone-frame relative bg-[var(--bone)] overflow-hidden flex flex-col text-[var(--ink)]" x-data="pawetApp()">

  <!-- status bar -->
  <div class="flex items-center justify-between px-6 pt-3 pb-1 text-[11px] text-[var(--ink-soft)] shrink-0">
    <span>۹:۴۱</span>
    <div class="flex items-center gap-1.5">
      <svg width="15" height="11" viewBox="0 0 16 12" fill="currentColor"><rect x="0" y="7" width="3" height="5" rx="0.5"/><rect x="4.5" y="4.5" width="3" height="7.5" rx="0.5"/><rect x="9" y="2" width="3" height="10" rx="0.5"/><rect x="13.5" y="0" width="3" height="12" rx="0.5" opacity=".35"/></svg>
      <svg width="18" height="11" viewBox="0 0 22 12" fill="none" stroke="currentColor" stroke-width="1"><rect x="0.5" y="0.5" width="18" height="11" rx="2.5"/><rect x="2" y="2" width="15" height="8" rx="1.3" fill="currentColor" stroke="none"/><path d="M20 4v4" stroke-width="1.4" stroke-linecap="round"/></svg>
    </div>
  </div>

  <!-- app bar -->
  <header class="px-5 pt-2 pb-3 flex items-center justify-between shrink-0">
    <div class="flex items-center gap-2">
      <div class="w-9 h-9 rounded-xl bg-[var(--ink)] text-[var(--amber-soft)] flex items-center justify-center">
        <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
          <ellipse cx="12" cy="15.6" rx="5.4" ry="4.4"/>
          <ellipse cx="5.6" cy="9.2" rx="2.1" ry="2.7"/>
          <ellipse cx="10" cy="6.1" rx="2" ry="2.6"/>
          <ellipse cx="14" cy="6.1" rx="2" ry="2.6"/>
          <ellipse cx="18.4" cy="9.2" rx="2.1" ry="2.7"/>
        </svg>
      </div>
      <div class="leading-tight">
        <p class="font-display font-extrabold text-lg tracking-wide">Pawet</p>
        <p class="text-[11px] text-[var(--ink-soft)] -mt-0.5">هر پنجه، یک خانه</p>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <button class="focus-ring flex items-center gap-1 text-xs text-[var(--ink-soft)] border border-[var(--line)] rounded-full px-2.5 py-1.5">
        <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s6.5-6 6.5-11A6.5 6.5 0 0 0 5.5 10c0 5 6.5 11 6.5 11Z"/><circle cx="12" cy="10" r="2.2"/></svg>
        <span>تهران</span>
        <svg viewBox="0 0 24 24" class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
      <button class="focus-ring relative w-9 h-9 rounded-full border border-[var(--line)] flex items-center justify-center text-[var(--ink)]">
        <svg viewBox="0 0 24 24" class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17h14l-1.4-1.6A6 6 0 0 1 16 11.5V10a4 4 0 0 0-8 0v1.5a6 6 0 0 1-1.6 3.9L5 17Z"/><path d="M10 20a2 2 0 0 0 4 0"/></svg>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-[var(--coral)]"></span>
      </button>
    </div>
  </header>

  <!-- scrollable content -->
  <main class="flex-1 overflow-y-auto no-scrollbar px-5 pb-4">

    <!-- search -->
    <div class="rise flex items-center gap-2 bg-white border border-[var(--line)] rounded-2xl px-4 py-3 mt-1">
      <svg viewBox="0 0 24 24" class="w-4.5 h-4.5 text-[var(--ink-soft)]" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="20" y1="20" x2="15.4" y2="15.4"/></svg>
      <input type="text" placeholder="جستجوی نژاد، شهر یا نام..." class="focus-ring flex-1 bg-transparent text-sm placeholder:text-[var(--ink-soft)] outline-none">
    </div>

    <!-- greeting -->
    <p class="rise text-base font-semibold mt-5 mb-3" style="animation-delay:.05s">امروز دنبال چی هستی؟</p>

    <!-- three core actions -->
    <div class="grid grid-cols-3 gap-2.5">
      <button class="focus-ring rise bg-white border border-[var(--line)] rounded-2xl py-4 px-1.5 flex flex-col items-center gap-2 text-center active:scale-[.97] transition" style="animation-delay:.1s">
        <span class="w-10 h-10 rounded-full flex items-center justify-center" style="background:var(--amber-soft); color:var(--amber);">
          <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20.5s-7.5-4.6-9.8-9C.6 7.8 2 4 5.8 3.3c2.1-.4 4 .6 6.2 3 2.2-2.4 4.1-3.4 6.2-3C21.99 4 23.4 7.8 21.8 11.5c-2.3 4.4-9.8 9-9.8 9z"/></svg>
        </span>
        <span class="text-[12.5px] font-semibold leading-tight">فرزندخواهی</span>
        <span class="text-[10px] text-[var(--ink-soft)] leading-tight">یک دوست جدید پیدا کن</span>
      </button>

      <button class="focus-ring rise bg-white border border-[var(--line)] rounded-2xl py-4 px-1.5 flex flex-col items-center gap-2 text-center active:scale-[.97] transition" style="animation-delay:.15s">
        <span class="w-10 h-10 rounded-full flex items-center justify-center" style="background:var(--teal-soft); color:var(--teal);">
          <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11.2 12 4l9 7.2"/><path d="M5.5 9.8V19a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1V9.8"/><path d="M9.5 20v-5.5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1V20"/></svg>
        </span>
        <span class="text-[12.5px] font-semibold leading-tight">نگهداری موقت</span>
        <span class="text-[10px] text-[var(--ink-soft)] leading-tight">موقت کنارت نگهش دار</span>
      </button>

      <button class="focus-ring rise bg-white border border-[var(--line)] rounded-2xl py-4 px-1.5 flex flex-col items-center gap-2 text-center active:scale-[.97] transition" style="animation-delay:.2s">
        <span class="w-10 h-10 rounded-full flex items-center justify-center" style="background:var(--coral-soft); color:var(--coral);">
          <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.5s7-6.4 7-12A7 7 0 0 0 5 9.5c0 5.6 7 12 7 12Z"/><line x1="12" y1="7" x2="12" y2="10.6"/><circle cx="12" cy="13.4" r="0.9" fill="currentColor" stroke="none"/></svg>
        </span>
        <span class="text-[12.5px] font-semibold leading-tight">حیوان گمشده</span>
        <span class="text-[10px] text-[var(--ink-soft)] leading-tight">گزارش یا جست‌وجو کن</span>
      </button>
    </div>

    <!-- urgent lost banner -->
    <a href="#" class="focus-ring rise mt-4 flex items-center gap-3 rounded-2xl px-4 py-3" style="background:var(--coral-soft); animation-delay:.25s">
      <span class="relative w-2.5 h-2.5 rounded-full bg-[var(--coral)] pulse-dot shrink-0"></span>
      <div class="flex-1 min-w-0">
        <p class="text-[11px] font-medium" style="color:var(--coral)">هشدار نزدیک شما</p>
        <p class="text-[13px] font-semibold truncate">گربه دورنگ نزدیک شما گم شده</p>
        <p class="text-[11px] text-[var(--ink-soft)] mt-0.5">۴۵ دقیقه پیش · ۶۰۰ متری شما</p>
      </div>
      <svg viewBox="0 0 24 24" class="w-4 h-4 text-[var(--coral)] shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 6 9 12 15 18"/></svg>
    </a>

    <!-- near you -->
    <div class="flex items-center justify-between mt-6 mb-3">
      <p class="text-base font-semibold">نزدیک شما</p>
      <button class="focus-ring text-xs font-medium" style="color:var(--teal)">مشاهده همه</button>
    </div>

    <div class="flex gap-3 overflow-x-auto no-scrollbar -mx-5 px-5 pb-1" style="scroll-snap-type:x mandatory;">
      <!-- card 1 -->
      <div class="shrink-0 w-36 bg-white border border-[var(--line)] rounded-2xl overflow-hidden" style="scroll-snap-align:start">
        <div class="h-24 flex items-center justify-center" style="background:var(--amber-soft); color:var(--amber);">
          <svg viewBox="0 0 64 64" class="w-9 h-9" fill="currentColor"><path d="M14 22 22 10l4 10z"/><path d="M50 22 42 10l-4 10z"/><circle cx="32" cy="34" r="20"/><circle cx="25" cy="32" r="2.4" fill="white"/><circle cx="39" cy="32" r="2.4" fill="white"/></svg>
        </div>
        <div class="p-2.5">
          <p class="text-[13px] font-semibold">لوسی</p>
          <p class="text-[11px] text-[var(--ink-soft)] mt-0.5">گربه پرشین</p>
          <div class="flex items-center justify-between mt-2">
            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-md" style="background:var(--amber-soft); color:var(--amber);">فرزندخواهی</span>
            <span class="text-[10px] text-[var(--ink-soft)]">۸۰۰ متر</span>
          </div>
        </div>
      </div>

      <!-- card 2 -->
      <div class="shrink-0 w-36 bg-white border border-[var(--line)] rounded-2xl overflow-hidden" style="scroll-snap-align:start">
        <div class="h-24 flex items-center justify-center" style="background:var(--teal-soft); color:var(--teal);">
          <svg viewBox="0 0 64 64" class="w-9 h-9" fill="currentColor"><ellipse cx="16" cy="26" rx="7" ry="11" transform="rotate(-20 16 26)"/><ellipse cx="48" cy="26" rx="7" ry="11" transform="rotate(20 48 26)"/><circle cx="32" cy="36" r="18"/><circle cx="26" cy="33" r="2.2" fill="white"/><circle cx="38" cy="33" r="2.2" fill="white"/></svg>
        </div>
        <div class="p-2.5">
          <p class="text-[13px] font-semibold">تی‌تو</p>
          <p class="text-[11px] text-[var(--ink-soft)] mt-0.5">سگ پامرین</p>
          <div class="flex items-center justify-between mt-2">
            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-md" style="background:var(--teal-soft); color:var(--teal);">نگهداری موقت</span>
            <span class="text-[10px] text-[var(--ink-soft)]">۱.۵ کیلومتر</span>
          </div>
        </div>
      </div>

      <!-- card 3 -->
      <div class="shrink-0 w-36 bg-white border border-[var(--line)] rounded-2xl overflow-hidden" style="scroll-snap-align:start">
        <div class="h-24 flex items-center justify-center" style="background:var(--amber-soft); color:var(--amber);">
          <svg viewBox="0 0 64 64" class="w-9 h-9" fill="currentColor"><path d="M14 22 22 10l4 10z"/><path d="M50 22 42 10l-4 10z"/><circle cx="32" cy="34" r="20"/><circle cx="25" cy="32" r="2.4" fill="white"/><circle cx="39" cy="32" r="2.4" fill="white"/></svg>
        </div>
        <div class="p-2.5">
          <p class="text-[13px] font-semibold">موشی</p>
          <p class="text-[11px] text-[var(--ink-soft)] mt-0.5">گربه ایرانی</p>
          <div class="flex items-center justify-between mt-2">
            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-md" style="background:var(--amber-soft); color:var(--amber);">فرزندخواهی</span>
            <span class="text-[10px] text-[var(--ink-soft)]">۲ کیلومتر</span>
          </div>
        </div>
      </div>

      <!-- card 4 -->
      <div class="shrink-0 w-36 bg-white border border-[var(--line)] rounded-2xl overflow-hidden" style="scroll-snap-align:start">
        <div class="h-24 flex items-center justify-center" style="background:var(--teal-soft); color:var(--teal);">
          <svg viewBox="0 0 64 64" class="w-9 h-9" fill="currentColor"><ellipse cx="16" cy="26" rx="7" ry="11" transform="rotate(-20 16 26)"/><ellipse cx="48" cy="26" rx="7" ry="11" transform="rotate(20 48 26)"/><circle cx="32" cy="36" r="18"/><circle cx="26" cy="33" r="2.2" fill="white"/><circle cx="38" cy="33" r="2.2" fill="white"/></svg>
        </div>
        <div class="p-2.5">
          <p class="text-[13px] font-semibold">بارون</p>
          <p class="text-[11px] text-[var(--ink-soft)] mt-0.5">سگ ولگرد</p>
          <div class="flex items-center justify-between mt-2">
            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-md" style="background:var(--teal-soft); color:var(--teal);">نگهداری موقت</span>
            <span class="text-[10px] text-[var(--ink-soft)]">۲.۴ کیلومتر</span>
          </div>
        </div>
      </div>
    </div>

    <!-- latest listings -->
    <div class="flex items-center justify-between mt-6 mb-3">
      <p class="text-base font-semibold">تازه‌ترین آگهی‌ها</p>
      <button class="focus-ring text-xs font-medium" style="color:var(--teal)">مشاهده همه</button>
    </div>

    <div class="flex flex-col gap-2.5">
      <a href="#" class="focus-ring flex items-center gap-3 bg-white border border-[var(--line)] rounded-2xl p-3">
        <span class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background:var(--coral-soft); color:var(--coral);">
          <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.5s7-6.4 7-12A7 7 0 0 0 5 9.5c0 5.6 7 12 7 12Z"/><line x1="12" y1="7" x2="12" y2="10.6"/><circle cx="12" cy="13.4" r="0.9" fill="currentColor" stroke="none"/></svg>
        </span>
        <div class="flex-1 min-w-0">
          <p class="text-[13px] font-medium truncate">سگ نژاد ژرمن در پارک ملت گم شده</p>
          <p class="text-[11px] text-[var(--ink-soft)] mt-0.5">سعادت‌آباد، تهران · ۲ ساعت پیش</p>
        </div>
      </a>

      <a href="#" class="focus-ring flex items-center gap-3 bg-white border border-[var(--line)] rounded-2xl p-3">
        <span class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background:var(--amber-soft); color:var(--amber);">
          <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20.5s-7.5-4.6-9.8-9C.6 7.8 2 4 5.8 3.3c2.1-.4 4 .6 6.2 3 2.2-2.4 4.1-3.4 6.2-3C21.99 4 23.4 7.8 21.8 11.5c-2.3 4.4-9.8 9-9.8 9z"/></svg>
        </span>
        <div class="flex-1 min-w-0">
          <p class="text-[13px] font-medium truncate">۳ بچه‌گربه دو ماهه آماده فرزندخواهی</p>
          <p class="text-[11px] text-[var(--ink-soft)] mt-0.5">ونک، تهران · دیروز</p>
        </div>
      </a>

      <a href="#" class="focus-ring flex items-center gap-3 bg-white border border-[var(--line)] rounded-2xl p-3">
        <span class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background:var(--teal-soft); color:var(--teal);">
          <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11.2 12 4l9 7.2"/><path d="M5.5 9.8V19a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1V9.8"/></svg>
        </span>
        <div class="flex-1 min-w-0">
          <p class="text-[13px] font-medium truncate">سگ کوچک نیاز به نگهداری دو هفته‌ای</p>
          <p class="text-[11px] text-[var(--ink-soft)] mt-0.5">نیاوران، تهران · ۲ روز پیش</p>
        </div>
      </a>
    </div>

    <div class="h-2"></div>
  </main>

  <!-- backdrop for fab menu -->
  <div
    x-show="fabOpen"
    x-cloak
    x-transition.opacity
    @click="fabOpen=false"
    class="absolute inset-0 bg-[var(--ink)]/30 backdrop-blur-[2px] z-10">
  </div>

  <!-- bottom nav -->
  <nav class="relative z-20 shrink-0 bg-[var(--bone)] border-t border-[var(--line)] px-3 pt-2 pb-[max(0.6rem,env(safe-area-inset-bottom))]">
    <div class="grid grid-cols-5 items-end">

      <button class="focus-ring flex flex-col items-center gap-1 py-1" style="color:var(--ink)">
        <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11.2 12 4l9 7.2"/><path d="M5.5 9.8V19a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1V9.8"/></svg>
        <span class="text-[10px] font-medium">خانه</span>
      </button>

      <button class="focus-ring flex flex-col items-center gap-1 py-1 text-[var(--ink-soft)]">
        <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="20" y1="20" x2="15.4" y2="15.4"/></svg>
        <span class="text-[10px]">جستجو</span>
      </button>

      <!-- fab -->
      <div class="relative flex justify-center">

        <!-- fan items -->
        <div x-show="fabOpen" x-cloak x-transition class="absolute bottom-[60px] flex items-end gap-3">
          <a href="#" @click="fabOpen=false" class="focus-ring flex flex-col items-center gap-1">
            <span class="w-11 h-11 rounded-full flex items-center justify-center shadow-lg" style="background:var(--coral); color:white;">
              <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.5s7-6.4 7-12A7 7 0 0 0 5 9.5c0 5.6 7 12 7 12Z"/><circle cx="12" cy="9.5" r="2" fill="white" stroke="none"/></svg>
            </span>
            <span class="text-[9.5px] font-medium bg-white px-1.5 py-0.5 rounded-md shadow-sm whitespace-nowrap">ثبت گمشده</span>
          </a>
          <a href="#" @click="fabOpen=false" class="focus-ring flex flex-col items-center gap-1 -mb-3">
            <span class="w-12 h-12 rounded-full flex items-center justify-center shadow-lg" style="background:var(--amber); color:white;">
              <svg viewBox="0 0 24 24" class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20.5s-7.5-4.6-9.8-9C.6 7.8 2 4 5.8 3.3c2.1-.4 4 .6 6.2 3 2.2-2.4 4.1-3.4 6.2-3C21.99 4 23.4 7.8 21.8 11.5c-2.3 4.4-9.8 9-9.8 9z"/></svg>
            </span>
            <span class="text-[9.5px] font-medium bg-white px-1.5 py-0.5 rounded-md shadow-sm whitespace-nowrap">ثبت فرزندخواهی</span>
          </a>
          <a href="#" @click="fabOpen=false" class="focus-ring flex flex-col items-center gap-1">
            <span class="w-11 h-11 rounded-full flex items-center justify-center shadow-lg" style="background:var(--teal); color:white;">
              <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11.2 12 4l9 7.2"/><path d="M5.5 9.8V19a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1V9.8"/></svg>
            </span>
            <span class="text-[9.5px] font-medium bg-white px-1.5 py-0.5 rounded-md shadow-sm whitespace-nowrap">ثبت نگهداری</span>
          </a>
        </div>

        <button
          @click="fabOpen=!fabOpen"
          :class="fabOpen ? 'rotate-45 bg-[var(--coral)]' : 'bg-[var(--ink)]'"
          class="focus-ring -mt-7 w-14 h-14 rounded-full text-white flex items-center justify-center shadow-xl transition-transform duration-300">
          <svg viewBox="0 0 24 24" class="w-6 h-6" fill="currentColor">
            <ellipse cx="12" cy="15.6" rx="5.4" ry="4.4"/>
            <ellipse cx="5.6" cy="9.2" rx="2.1" ry="2.7"/>
            <ellipse cx="10" cy="6.1" rx="2" ry="2.6"/>
            <ellipse cx="14" cy="6.1" rx="2" ry="2.6"/>
            <ellipse cx="18.4" cy="9.2" rx="2.1" ry="2.7"/>
          </svg>
        </button>
      </div>

      <button class="focus-ring relative flex flex-col items-center gap-1 py-1 text-[var(--ink-soft)]">
        <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5.5A1.5 1.5 0 0 1 4.5 4h15A1.5 1.5 0 0 1 21 5.5V15a1.5 1.5 0 0 1-1.5 1.5H9l-4 3.5v-3.5H4.5A1.5 1.5 0 0 1 3 15Z"/></svg>
        <span class="text-[10px]">پیام‌ها</span>
        <span class="absolute -top-0.5 left-5 w-3.5 h-3.5 rounded-full bg-[var(--coral)] text-white text-[8px] flex items-center justify-center">۳</span>
      </button>

      <button class="focus-ring flex flex-col items-center gap-1 py-1 text-[var(--ink-soft)]">
        <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8.5" r="3.5"/><path d="M4.5 20c1.4-3.6 4.5-5.5 7.5-5.5s6.1 1.9 7.5 5.5"/></svg>
        <span class="text-[10px]">حساب من</span>
      </button>

    </div>
  </nav>

</div>

<script>
  function pawetApp(){
    return {
      fabOpen: false,
    }
  }
</script>

</body>
</html>