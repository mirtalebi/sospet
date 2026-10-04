<?php

use Livewire\Component;

new class extends Component {};
?>

<div class="flex min-h-screen flex-col bg-bg-secondary">
    <main class="flex-1 px-5 pb-32 pt-8 md:px-10 md:pt-12">
        <article class="mx-auto max-w-4xl">
            <p class="text-sm font-semibold text-primary">پیش‌نویس برای بازبینی حقوقی</p>
            <h1 class="mt-3 font-display text-3xl text-text-title md:text-4xl">قوانین و مقررات</h1>
            <p class="mt-5 max-w-3xl text-base leading-8 text-text-body">
                این متن پیش‌نویس شرایط استفاده از ساس‌پت است و پیش از انتشار نهایی باید با شرایط حقوقی و شیوه اداره
                سرویس تطبیق داده شود.
            </p>

            <div class="mt-8 divide-y divide-border-custom border-y border-border-custom">
                <section class="py-6">
                    <h2 class="font-display text-xl text-text-title">کارکرد ساس‌پت</h2>
                    <p class="mt-3 text-sm leading-7 text-text-body">
                        ساس‌پت بستری برای ثبت و مشاهده آگهی حیوانات گمشده، پیدا‌شده و سرپرستی است. آگهی‌ها را کاربران
                        ثبت می‌کنند؛
                        ساس‌پت پناهگاه، مرکز درمانی یا طرف معامله و واگذاری نیست و نمی‌تواند درستی هر آگهی یا نتیجه
                        ارتباط کاربران را تضمین کند.
                    </p>
                </section>
                <section class="py-6">
                    <h2 class="font-display text-xl text-text-title">مسئولیت ثبت آگهی</h2>
                    <p class="mt-3 text-sm leading-7 text-text-body">
                        با ثبت آگهی، مسئولیت دقت و به‌روز بودن اطلاعات، داشتن اجازه انتشار متن و تصویر، و رعایت حقوق
                        دیگران بر عهده شماست.
                        از ثبت اطلاعات نادرست، محتوای آزاردهنده یا اطلاعاتی که اجازه انتشار آن را ندارید خودداری کنید.
                    </p>
                </section>
                <section class="py-6">
                    <h2 class="font-display text-xl text-text-title">ارتباط و واگذاری</h2>
                    <p class="mt-3 text-sm leading-7 text-text-body">
                        بررسی هویت، وضعیت حیوان، شرایط نگهداری و جزئیات ملاقات یا واگذاری بر عهده خود کاربران است.
                        پیش از به‌اشتراک‌گذاشتن اطلاعات یا انجام ملاقات، احتیاط کنید و اطلاعات حساس را عمومی نکنید.
                    </p>
                </section>
                <section class="py-6">
                    <h2 class="font-display text-xl text-text-title">استفاده مسئولانه</h2>
                    <p class="mt-3 text-sm leading-7 text-text-body">
                        استفاده از ساس‌پت باید در چارچوب قوانین و با احترام به حیوانات و دیگر کاربران باشد. در صورت
                        مشاهده محتوای نادرست یا
                        مشکلی در استفاده از سرویس، موضوع را از طریق <a href="{{ route('support') }}" wire:navigate
                            class="font-semibold text-primary underline decoration-primary/40 underline-offset-4">پشتیبانی</a>
                        اطلاع دهید.
                    </p>
                </section>
            </div>
        </article>
    </main>

    @include('partials.site-footer')
</div>
