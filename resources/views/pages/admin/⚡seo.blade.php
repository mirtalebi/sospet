<?php

use App\Support\Seo\SeoManager;
use App\Support\Seo\SitemapBuilder;
use App\Support\Settings\SettingsRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::admin'), Title('سئو')] class extends Component {
    use WithFileUploads;

    private const EDITABLE_PAGES = [
        'home' => 'صفحه خانه',
        'about_us' => 'درباره ما',
        'support' => 'پشتیبانی',
        'terms' => 'قوانین و مقررات',
        'privacy' => 'حریم خصوصی',
    ];

    private const ROBOTS_OPTIONS = [
        'index,follow,max-image-preview:large',
        'index,follow',
        'noindex,follow',
        'noindex,nofollow',
    ];

    public string $siteName = '';

    public string $defaultTitle = '';

    public string $defaultDescription = '';

    public string $defaultRobots = 'index,follow,max-image-preview:large';

    public string $canonicalBase = '';

    public string $twitterHandle = '';

    public string $ga4MeasurementId = '';

    public string $gscVerification = '';

    public $ogImage = null;

    public ?string $currentOgImage = null;

    /** @var array<string, array{title: string, description: string}> */
    public array $pages = [];

    public function mount(SeoManager $seo, SettingsRepository $settings): void
    {
        $this->siteName = (string) $seo->value('site_name');
        $this->defaultTitle = (string) $seo->value('default_title');
        $this->defaultDescription = (string) $seo->value('default_description');
        $this->defaultRobots = (string) $seo->value('default_robots');
        $this->canonicalBase = (string) ($seo->raw('canonical_base_url') ?? '');
        $this->twitterHandle = (string) ($seo->raw('twitter_handle') ?? '');
        $this->ga4MeasurementId = (string) ($seo->raw('ga4_measurement_id') ?? '');
        $this->gscVerification = (string) ($seo->raw('gsc_verification') ?? '');
        $this->currentOgImage = $seo->mediaUrl($seo->raw('default_og_image'));

        $overrides = $settings->get('seo.page_overrides');
        $overrides = is_array($overrides) ? $overrides : [];

        foreach (array_keys(self::EDITABLE_PAGES) as $key) {
            $this->pages[$key] = [
                'title' => (string) ($overrides[$key]['title'] ?? ''),
                'description' => (string) ($overrides[$key]['description'] ?? ''),
            ];
        }
    }

    public function save(SettingsRepository $settings): void
    {
        $this->validate([
            'siteName' => ['required', 'string', 'max:60'],
            'defaultTitle' => ['required', 'string', 'max:70'],
            'defaultDescription' => ['required', 'string', 'max:320'],
            'defaultRobots' => ['required', Rule::in(self::ROBOTS_OPTIONS)],
            'canonicalBase' => ['nullable', 'url', 'max:255'],
            'twitterHandle' => ['nullable', 'regex:/^@?[A-Za-z0-9_]{1,15}$/'],
            'ga4MeasurementId' => ['nullable', 'regex:/^G-[A-Z0-9]{6,14}$/i'],
            'gscVerification' => ['nullable', 'string', 'max:120'],
            'ogImage' => ['nullable', 'image', 'max:4096'],
            'pages.*.title' => ['nullable', 'string', 'max:70'],
            'pages.*.description' => ['nullable', 'string', 'max:320'],
        ], [
            'ga4MeasurementId.regex' => 'شناسه اندازه‌گیری GA4 باید به شکل G-XXXXXXXXXX باشد.',
            'twitterHandle.regex' => 'نام کاربری توییتر می‌تواند شامل حروف انگلیسی، عدد و _ باشد.',
        ]);

        $values = [
            'seo.site_name' => trim($this->siteName),
            'seo.default_title' => trim($this->defaultTitle),
            'seo.default_description' => trim($this->defaultDescription),
            'seo.default_robots' => $this->defaultRobots,
            'seo.canonical_base_url' => filled($this->canonicalBase) ? rtrim(trim($this->canonicalBase), '/') : null,
            'seo.twitter_handle' => filled($this->twitterHandle) ? ltrim(trim($this->twitterHandle), '@') : null,
            'seo.ga4_measurement_id' => filled($this->ga4MeasurementId) ? trim($this->ga4MeasurementId) : null,
            'seo.gsc_verification' => filled($this->gscVerification) ? trim($this->gscVerification) : null,
            'seo.page_overrides' => $this->pageOverridesPayload(),
        ];

        if ($this->ogImage) {
            $previous = $settings->get('seo.default_og_image');

            $values['seo.default_og_image'] = $this->ogImage->store('seo', 'public');

            if (is_string($previous) && $previous !== '' && ! str_starts_with($previous, 'http')) {
                Storage::disk('public')->delete($previous);
            }
        }

        $settings->setMany($values);

        $this->currentOgImage = $settings->get('seo.default_og_image')
            ? app(SeoManager::class)->mediaUrl($settings->get('seo.default_og_image'))
            : null;
        $this->ogImage = null;

        session()->flash('seo_status', 'تنظیمات سئو ذخیره شد.');
    }

    public function removeOgImage(SettingsRepository $settings): void
    {
        $previous = $settings->get('seo.default_og_image');

        if (is_string($previous) && $previous !== '' && ! str_starts_with($previous, 'http')) {
            Storage::disk('public')->delete($previous);
        }

        $settings->set('seo.default_og_image', null);

        $this->currentOgImage = null;
        $this->ogImage = null;

        session()->flash('seo_status', 'تصویر پیش‌فرض حذف شد؛ تصویر جایگزین پیش‌فرض سایت استفاده می‌شود.');
    }

    public function regenerateSitemap(): void
    {
        app(SitemapBuilder::class)->forget();

        session()->flash('seo_status', 'نسخه سایت‌مپ بازسازی شد و در اولین درخواست به‌روز می‌شود.');
    }

    /**
     * @return array<string, array{title: string, description: string}>
     */
    private function pageOverridesPayload(): array
    {
        $overrides = [];

        foreach (array_keys(self::EDITABLE_PAGES) as $key) {
            $title = trim((string) ($this->pages[$key]['title'] ?? ''));
            $description = trim((string) ($this->pages[$key]['description'] ?? ''));

            if ($title === '' && $description === '') {
                continue;
            }

            $overrides[$key] = array_filter([
                'title' => $title !== '' ? $title : null,
                'description' => $description !== '' ? $description : null,
            ]);
        }

        return $overrides;
    }

    public function with(): array
    {
        $builder = app(SitemapBuilder::class);
        $counts = $builder->counts();

        return [
            'editablePages' => self::EDITABLE_PAGES,
            'robotsOptions' => self::ROBOTS_OPTIONS,
            'sitemapCounts' => $counts,
            'sitemapPetPages' => $builder->petPageCount(),
            'sitemapGeneratedAt' => $builder->generatedAt(),
            'sitemapIndexUrl' => route('sitemap.index'),
            'sitemapPagesUrl' => route('sitemap.pages'),
            'sitemapLocationsUrl' => route('sitemap.locations'),
            'robotsUrl' => route('robots'),
        ];
    }
};
?>

<div class="space-y-6">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="font-display text-3xl text-text-title">مدیریت سئو</h1>
            <p class="mt-1 text-sm text-text-body">تنظیمات پایه سئو، شبکه‌های اجتماعی، نقشه سایت و پیش‌نمایش گوگل.</p>
        </div>
        <a href="{{ $sitemapIndexUrl }}" target="_blank" rel="noopener"
            class="rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-xs font-bold text-text-title transition hover:border-primary/30 hover:text-primary">
            مشاهده sitemap.xml
        </a>
    </div>

    @if (session('seo_status'))
        <div class="rounded-2xl border border-success-custom/30 bg-success-custom/10 px-4 py-3 text-sm font-semibold text-success-custom">
            {{ session('seo_status') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        <!-- Global settings -->
        <section class="rounded-2xl border border-border-custom bg-bg-main p-5 shadow-sm md:p-6">
            <h2 class="font-bold text-lg text-text-title">تنظیمات عمومی</h2>
            <p class="mt-1 text-xs text-text-body">این مقادیر پیش‌فرض صفحه‌هایی هستند که تنظیم اختصاصی ندارند.</p>

            <div class="mt-5 grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-bold text-text-title" for="seo-site-name">نام سایت</label>
                    <input id="seo-site-name" type="text" wire:model.live.debounce.400ms="siteName"
                        class="w-full rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                    @error('siteName')
                        <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold text-text-title" for="seo-canonical">دامنه اصلی (Canonical Base)</label>
                    <input id="seo-canonical" type="url" wire:model.live.debounce.500ms="canonicalBase" dir="ltr" placeholder="https://sospet.ir"
                        class="w-full rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                    <p class="mt-1 text-[10.5px] text-text-muted">اگر خالی باشد، دامنه فعلی برنامه استفاده می‌شود.</p>
                    @error('canonicalBase')
                        <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <div class="mb-1.5 flex items-center justify-between gap-2">
                        <label class="text-xs font-bold text-text-title" for="seo-title">عنوان پیش‌فرض</label>
                        <span class="text-[10.5px] font-semibold {{ mb_strlen($defaultTitle) > 60 ? 'text-danger-custom' : 'text-text-muted' }}">
                            {{ mb_strlen($defaultTitle) }}/60
                        </span>
                    </div>
                    <input id="seo-title" type="text" wire:model.live.debounce.400ms="defaultTitle"
                        class="w-full rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                    @error('defaultTitle')
                        <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <div class="mb-1.5 flex items-center justify-between gap-2">
                        <label class="text-xs font-bold text-text-title" for="seo-description">توضیحات پیش‌فرض</label>
                        <span class="text-[10.5px] font-semibold {{ mb_strlen($defaultDescription) > 160 ? 'text-danger-custom' : 'text-text-muted' }}">
                            {{ mb_strlen($defaultDescription) }}/160
                        </span>
                    </div>
                    <textarea id="seo-description" rows="3" wire:model.live.debounce.400ms="defaultDescription"
                        class="w-full rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-sm leading-6 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
                    @error('defaultDescription')
                        <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold text-text-title" for="seo-robots">دستور Robots پیش‌فرض</label>
                    <select id="seo-robots" wire:model="defaultRobots"
                        class="w-full rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-sm outline-none focus:border-primary" dir="ltr">
                        @foreach ($robotsOptions as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('defaultRobots')
                        <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold text-text-title" for="seo-twitter">نام کاربری توییتر/X</label>
                    <input id="seo-twitter" type="text" wire:model="twitterHandle" dir="ltr" placeholder="@sospet"
                        class="w-full rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                    @error('twitterHandle')
                        <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold text-text-title" for="seo-ga4">شناسه اندازه‌گیری GA4</label>
                    <input id="seo-ga4" type="text" wire:model="ga4MeasurementId" dir="ltr" placeholder="G-XXXXXXXXXX"
                        class="w-full rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                    <p class="mt-1 text-[10.5px] text-text-muted">بدون رضایت کاربر، اسکریپت آنالیتیکس بارگذاری نمی‌شود.</p>
                    @error('ga4MeasurementId')
                        <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold text-text-title" for="seo-gsc">کد تأیید سرچ کنسول</label>
                    <input id="seo-gsc" type="text" wire:model="gscVerification" dir="ltr"
                        placeholder="google-site-verification"
                        class="w-full rounded-xl border border-border-custom bg-bg-main px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                    @error('gscVerification')
                        <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <!-- Google preview + OG image -->
        <section class="grid gap-6 md:grid-cols-2">
            <div class="rounded-2xl border border-border-custom bg-bg-main p-5 shadow-sm md:p-6">
                <h2 class="font-bold text-lg text-text-title">پیش‌نمایش گوگل</h2>
                <p class="mt-1 text-xs text-text-body">صفحه خانه با عنوان و توضیحات بالا این‌گونه در نتایج دیده می‌شود.</p>

                <div class="mt-4 rounded-xl border border-border-custom bg-white p-4">
                    <div class="flex items-center gap-2">
                        <span class="grid h-7 w-7 place-items-center rounded-full bg-primary text-[11px] font-bold text-white">
                            {{ mb_substr($siteName ?: 'س', 0, 1) }}
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-xs font-bold text-text-title">{{ $siteName }}</p>
                            <p class="truncate text-[10px] text-text-muted" dir="ltr">{{ parse_url($canonicalBase ?: config('app.url'), PHP_URL_HOST) }}</p>
                        </div>
                    </div>
                    <p class="mt-3 line-clamp-2 text-sm font-medium text-[#1a0dab]" dir="rtl">{{ $defaultTitle ?: 'عنوان صفحه' }}</p>
                    <p class="mt-1 line-clamp-2 text-[11.5px] leading-5 text-[#4d5156]" dir="rtl">{{ $defaultDescription ?: 'توضیحات صفحه در اینجا نمایش داده می‌شود.' }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-border-custom bg-bg-main p-5 shadow-sm md:p-6">
                <h2 class="font-bold text-lg text-text-title">تصویر پیش‌فرض اشتراک‌گذاری</h2>
                <p class="mt-1 text-xs text-text-body">برای OG و کارت‌های توییتر استفاده می‌شود. اندازه پیشنهادی ۱۲۰۰×۶۳۰ پیکسل.</p>

                <div class="mt-4 overflow-hidden rounded-xl border border-border-custom bg-bg-secondary">
                    @if ($ogImage)
                        <img src="{{ $ogImage->temporaryUrl() }}" alt="پیش‌نمایش تصویر" class="aspect-[1200/630] w-full object-cover">
                    @elseif ($currentOgImage)
                        <img src="{{ $currentOgImage }}" alt="تصویر پیش‌فرض فعلی" class="aspect-[1200/630] w-full object-cover">
                    @else
                        <div class="grid aspect-[1200/630] place-items-center text-xs text-text-muted">
                            تصویری تنظیم نشده است؛ از تصویر پیش‌فرض سایت استفاده می‌شود.
                        </div>
                    @endif
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <label class="cursor-pointer rounded-xl border border-border-custom bg-bg-secondary px-4 py-2.5 text-xs font-bold text-text-title transition hover:border-primary/30 hover:text-primary">
                        انتخاب تصویر
                        <input type="file" wire:model="ogImage" accept="image/*" class="hidden">
                    </label>

                    @if ($currentOgImage)
                        <button type="button" wire:click="removeOgImage" wire:confirm="تصویر پیش‌فرض حذف شود؟"
                            class="rounded-xl border border-danger-custom/30 px-4 py-2.5 text-xs font-bold text-danger-custom transition hover:bg-danger-custom hover:text-white">
                            حذف تصویر
                        </button>
                    @endif

                    <span wire:loading wire:target="ogImage" class="text-[11px] text-text-muted">در حال بارگذاری…</span>
                </div>
                @error('ogImage')
                    <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <!-- Static page overrides -->
        <section class="rounded-2xl border border-border-custom bg-bg-main p-5 shadow-sm md:p-6">
            <h2 class="font-bold text-lg text-text-title">عنوان و توضیحات صفحه‌های ثابت</h2>
            <p class="mt-1 text-xs text-text-body">خالی گذاشتن هر فیلد باعث استفاده از مقدار پیش‌فرض خودکار می‌شود.</p>

            <div class="mt-5 space-y-5">
                @foreach ($editablePages as $key => $label)
                    <div class="rounded-xl border border-border-custom bg-bg-secondary/50 p-4">
                        <h3 class="text-sm font-bold text-text-title">{{ $label }}</h3>

                        <div class="mt-3 grid gap-4 md:grid-cols-2">
                            <div>
                                <div class="mb-1 flex items-center justify-between">
                                    <label class="text-[11px] font-bold text-text-body" for="page-{{ $key }}-title">عنوان</label>
                                    <span class="text-[10px] text-text-muted">{{ mb_strlen($pages[$key]['title'] ?? '') }}/60</span>
                                </div>
                                <input id="page-{{ $key }}-title" type="text"
                                    wire:model.live.debounce.500ms="pages.{{ $key }}.title"
                                    class="w-full rounded-lg border border-border-custom bg-bg-main px-3 py-2 text-xs outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                @error("pages.{$key}.title")
                                    <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="mb-1 flex items-center justify-between">
                                    <label class="text-[11px] font-bold text-text-body" for="page-{{ $key }}-description">توضیحات</label>
                                    <span class="text-[10px] text-text-muted">{{ mb_strlen($pages[$key]['description'] ?? '') }}/160</span>
                                </div>
                                <input id="page-{{ $key }}-description" type="text"
                                    wire:model.live.debounce.500ms="pages.{{ $key }}.description"
                                    class="w-full rounded-lg border border-border-custom bg-bg-main px-3 py-2 text-xs outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                @error("pages.{$key}.description")
                                    <p class="mt-1 text-xs text-danger-custom">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="flex justify-end">
            <button type="submit" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 rounded-xl bg-primary px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-primary-hover disabled:opacity-60">
                <span wire:loading.remove wire:target="save">ذخیره تنظیمات</span>
                <span wire:loading wire:target="save">در حال ذخیره…</span>
            </button>
        </div>
    </form>

    <!-- Sitemap status -->
    <section class="rounded-2xl border border-border-custom bg-bg-main p-5 shadow-sm md:p-6">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="font-bold text-lg text-text-title">وضعیت سایت‌مپ</h2>
                <p class="mt-1 text-xs text-text-body">آدرس‌ها به‌صورت خودکار از آگهی‌های فعال و کامل ساخته می‌شوند.</p>
            </div>
            <button type="button" wire:click="regenerateSitemap"
                class="rounded-xl border border-primary/30 px-4 py-2.5 text-xs font-bold text-primary transition hover:bg-primary hover:text-white">
                بازسازی سایت‌مپ
            </button>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-3 md:grid-cols-4">
            <div class="rounded-xl border border-border-custom bg-bg-secondary/60 p-4">
                <p class="text-[10.5px] font-semibold text-text-muted">آگهی‌های قابل ایندکس</p>
                <p class="mt-1 text-2xl font-bold text-text-title">{{ number_format($sitemapCounts['pets']) }}</p>
            </div>
            <div class="rounded-xl border border-border-custom bg-bg-secondary/60 p-4">
                <p class="text-[10.5px] font-semibold text-text-muted">صفحه‌های شهر</p>
                <p class="mt-1 text-2xl font-bold text-text-title">{{ number_format($sitemapCounts['cities']) }}</p>
            </div>
            <div class="rounded-xl border border-border-custom bg-bg-secondary/60 p-4">
                <p class="text-[10.5px] font-semibold text-text-muted">صفحه‌های گونه حیوان</p>
                <p class="mt-1 text-2xl font-bold text-text-title">{{ number_format($sitemapCounts['species']) }}</p>
            </div>
            <div class="rounded-xl border border-border-custom bg-bg-secondary/60 p-4">
                <p class="text-[10.5px] font-semibold text-text-muted">فایل‌های آگهی</p>
                <p class="mt-1 text-2xl font-bold text-text-title">{{ number_format($sitemapPetPages) }}</p>
            </div>
        </div>

        <div class="mt-5 space-y-2 text-xs">
            <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-border-custom bg-bg-secondary/40 px-4 py-3">
                <span class="font-bold text-text-title" dir="ltr">/sitemap.xml</span>
                <a href="{{ $sitemapIndexUrl }}" target="_blank" rel="noopener" class="font-semibold text-primary hover:underline">باز کردن</a>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-border-custom bg-bg-secondary/40 px-4 py-3">
                <span class="font-bold text-text-title" dir="ltr">/sitemaps/pages.xml</span>
                <a href="{{ $sitemapPagesUrl }}" target="_blank" rel="noopener" class="font-semibold text-primary hover:underline">باز کردن</a>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-border-custom bg-bg-secondary/40 px-4 py-3">
                <span class="font-bold text-text-title" dir="ltr">/sitemaps/locations.xml</span>
                <a href="{{ $sitemapLocationsUrl }}" target="_blank" rel="noopener" class="font-semibold text-primary hover:underline">باز کردن</a>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-border-custom bg-bg-secondary/40 px-4 py-3">
                <span class="font-bold text-text-title" dir="ltr">/robots.txt</span>
                <a href="{{ $robotsUrl }}" target="_blank" rel="noopener" class="font-semibold text-primary hover:underline">باز کردن</a>
            </div>
        </div>

        @if ($sitemapGeneratedAt)
            <p class="mt-3 text-[10.5px] text-text-muted" dir="ltr">Last generated: {{ \Illuminate\Support\Carbon::parse($sitemapGeneratedAt)->toDayDateTimeString() }}</p>
        @endif

        <p class="mt-3 text-[10.5px] leading-5 text-text-muted">
            معیار ورود آگهی به سایت‌مپ: فعال بودن، داشتن حداقل یک تصویر، شهر مشخص، اسلاگ یکتا و غیرفعال نبودن noindex. آگهی‌های حل‌شده از سایت‌مپ حذف می‌شوند اما صفحه‌شان برای مراجعان قبلی باز می‌ماند.
        </p>
    </section>
</div>
