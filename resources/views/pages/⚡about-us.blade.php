<?php

use Livewire\Component;

new class extends Component {};
?>

<div class="flex min-h-screen flex-col bg-bg-secondary">
    <main class="flex-1 px-5 pb-32 pt-8 md:px-10 md:pt-12">
        <article class="mx-auto max-w-4xl">
            <p class="text-sm font-semibold text-primary">ساس‌پت، برای پیدا شدن دوباره</p>
            <h1 class="mt-3 font-display text-3xl text-text-title md:text-4xl">درباره ساس‌پت</h1>

            <div class="mt-6 max-w-3xl space-y-4 text-base leading-8 text-text-body">
                <p>
                    ساس‌پت فضایی برای ثبت و پیدا کردن آگهی‌های حیوانات گمشده، حیوانات پیدا‌شده و سرپرستی است.
                    هدف ما این است که اطلاعات هر آگهی در دسترس افراد بیشتری قرار بگیرد و مسیر ارتباط میان
                    پیدا‌کننده، خانواده و سرپرست آینده کوتاه‌تر شود.
                </p>
                <p>
                    هر آگهی را فردی از جامعه ثبت می‌کند. لطفاً اطلاعات را با دقت وارد کنید و پیش از هر تصمیم،
                    جزئیات را مستقیم با ثبت‌کننده آگهی بررسی کنید. ساس‌پت پناهگاه حیوانات یا واسطه واگذاری نیست.
                </p>
            </div>

            <section class="mt-10 border-y border-border-custom py-7" aria-labelledby="about-purpose">
                <h2 id="about-purpose" class="font-display text-xl text-text-title">برای چه کنار همیم؟</h2>
                <ul class="mt-5 grid gap-5 text-sm leading-7 text-text-body sm:grid-cols-3">
                    <li><strong class="block text-text-title">پیدا کردن</strong>رساندن خبر حیوانات گمشده به افراد
                        نزدیک‌تر.</li>
                    <li><strong class="block text-text-title">خبر دادن</strong>کمک به پیوند دادن آگهی‌های حیوانات
                        پیدا‌شده با خانواده‌شان.</li>
                    <li><strong class="block text-text-title">سرپرستی</strong>معرفی حیواناتی که به دنبال خانه و سرپرست
                        مسئول هستند.</li>
                </ul>
            </section>

            <p class="mt-8 text-sm leading-7 text-text-body">
                پرسشی دارید یا می‌خواهید مشکلی را گزارش کنید؟ از صفحه
                <a href="{{ route('support') }}" wire:navigate
                    class="font-semibold text-primary underline decoration-primary/40 underline-offset-4">پشتیبانی</a>
                با ما در تماس باشید.
            </p>
        </article>
    </main>

    @include('partials.site-footer')
</div>
