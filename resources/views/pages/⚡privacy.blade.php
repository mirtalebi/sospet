<?php

use Livewire\Component;

new class extends Component {};
?>

<div class="flex min-h-screen flex-col bg-bg-secondary">
    <main class="flex-1 px-5 pb-32 pt-8 md:px-10 md:pt-12">
        <article class="mx-auto max-w-4xl">
            <p class="text-sm font-semibold text-primary">پیش‌نویس برای بازبینی حقوقی</p>
            <h1 class="mt-3 font-display text-3xl text-text-title md:text-4xl">حریم خصوصی</h1>
            <p class="mt-5 max-w-3xl text-base leading-8 text-text-body">
                این پیش‌نویس درباره اطلاعاتی است که برای حساب کاربری و آگهی‌ها در ساس‌پت وارد می‌کنید. جزئیات نگهداری و
                دسترسی به داده‌ها
                باید پیش از انتشار نهایی توسط مسئول سرویس تکمیل و بازبینی شود.
            </p>

            <div class="mt-8 divide-y divide-border-custom border-y border-border-custom">
                <section class="py-6">
                    <h2 class="font-display text-xl text-text-title">چه اطلاعاتی ثبت می‌شود؟</h2>
                    <p class="mt-3 text-sm leading-7 text-text-body">
                        اطلاعات حساب مانند نام و شماره همراه، اطلاعاتی که برای آگهی وارد می‌کنید مانند شماره تماس، شهر و
                        محدوده، توضیحات و
                        در صورت افزودن، مختصات مکانی و تصویر حیوان ممکن است در سامانه ثبت شود.
                    </p>
                </section>
                <section class="py-6">
                    <h2 class="font-display text-xl text-text-title">چرا از اطلاعات استفاده می‌شود؟</h2>
                    <p class="mt-3 text-sm leading-7 text-text-body">
                        این اطلاعات برای فراهم کردن حساب کاربری و نمایش، جستجو و پیگیری آگهی‌های گمشده، پیدا‌شده و
                        سرپرستی به کار می‌رود.
                        اطلاعاتی که در متن یا جزئیات آگهی درج می‌کنید ممکن است برای بازدیدکنندگان همان آگهی قابل مشاهده
                        باشد.
                    </p>
                </section>
                <section class="py-6">
                    <h2 class="font-display text-xl text-text-title">پیش از ثبت آگهی</h2>
                    <p class="mt-3 text-sm leading-7 text-text-body">
                        فقط اطلاعاتی را منتشر کنید که با نمایش آن به دیگران موافقید. اگر نمی‌خواهید شماره تماس یا مکان
                        دقیقی عمومی باشد،
                        آن را در متن آگهی قرار ندهید و از اطلاعات محدوده‌ای استفاده کنید.
                    </p>
                </section>
                <section class="py-6">
                    <h2 class="font-display text-xl text-text-title">مدت نگهداری و پرسش‌ها</h2>
                    <p class="mt-3 text-sm leading-7 text-text-body">
                        مدت نگهداری اطلاعات، شیوه درخواست اصلاح یا حذف، و هرگونه دسترسی اشخاص ثالث باید توسط مسئول
                        ساس‌پت مشخص و در نسخه نهایی
                        این سیاست درج شود. برای پرسش درباره حریم خصوصی با <a href="mailto:info@sospet.ir"
                            class="font-semibold text-primary underline decoration-primary/40 underline-offset-4">info@sospet.ir</a>
                        تماس بگیرید.
                    </p>
                </section>
            </div>
        </article>
    </main>

    @include('partials.site-footer')
</div>
