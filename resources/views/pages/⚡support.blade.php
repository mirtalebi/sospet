<?php

use Livewire\Component;

new class extends Component {};
?>

<div class="flex min-h-screen flex-col bg-bg-secondary">
    <main class="flex-1 px-5 pb-32 pt-8 md:px-10 md:pt-12">
        <article class="mx-auto max-w-4xl">
            <p class="text-sm font-semibold text-primary">پاسخ‌گوی شما هستیم</p>
            <h1 class="mt-3 font-display text-3xl text-text-title md:text-4xl">پشتیبانی ساس‌پت</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-text-body">
                برای پرسش درباره استفاده از ساس‌پت، گزارش مشکل فنی یا اطلاع دادن درباره محتوای یک آگهی، از راه‌های زیر
                با ما تماس بگیرید.
            </p>

            <section class="mt-8 grid gap-6 border-y border-border-custom py-7 sm:grid-cols-2" aria-label="راه‌های تماس">
                <div>
                    <h2 class="text-sm font-semibold text-text-muted">تلفن پشتیبانی</h2>
                    <a href="tel:09133501310" dir="ltr"
                        class="mt-2 inline-block text-xl font-semibold text-text-title transition-colors hover:text-primary">09133501310</a>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-text-muted">رایانامه</h2>
                    <a href="mailto:info@sospet.ir" dir="ltr"
                        class="mt-2 inline-block text-xl font-semibold text-text-title transition-colors hover:text-primary">info@sospet.ir</a>
                </div>
            </section>

            <p class="mt-6 max-w-2xl text-sm leading-7 text-text-body">
                اگر درباره یک حیوان یا واگذاری آن پیگیری می‌کنید، اطلاعات تماس ثبت‌شده در همان آگهی را بررسی کنید؛
                تیم ساس‌پت در روند ملاقات یا واگذاری دخالت نمی‌کند.
            </p>
        </article>
    </main>

    @include('partials.site-footer')
</div>
