<footer class="mt-auto border-t border-border-custom bg-bg-main px-5 py-6 md:px-10 md:py-8">
    <div
        class="mx-auto flex max-w-6xl flex-col gap-4 text-sm text-text-body md:flex-row md:items-center md:justify-between">
        <p>© ۱۴۰۵ ساس‌پت. تمامی حقوق محفوظ است.</p>

        <nav aria-label="پیوندهای اطلاعاتی" class="flex flex-wrap gap-x-5 gap-y-3 text-xs font-medium text-text-muted">
            <a href="{{ route('lost-pets.index') }}" wire:navigate class="transition-colors hover:text-primary">حیوانات گمشده</a>
            <a href="{{ route('found-pets.index') }}" wire:navigate class="transition-colors hover:text-primary">حیوانات پیدا شده</a>
            <a href="{{ route('adoption.index') }}" wire:navigate class="transition-colors hover:text-primary">آگهی‌های واگذاری</a>
            <a href="{{ route('about-us') }}" wire:navigate class="transition-colors hover:text-primary">درباره ما</a>
            <a href="{{ route('support') }}" wire:navigate class="transition-colors hover:text-primary">پشتیبانی</a>
            <a href="{{ route('terms') }}" wire:navigate class="transition-colors hover:text-primary">قوانین و مقررات</a>
            <a href="{{ route('privacy') }}" wire:navigate class="transition-colors hover:text-primary">حریم خصوصی</a>
        </nav>
    </div>
</footer>
