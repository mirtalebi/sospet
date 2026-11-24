<?php

namespace App\Support\Seo;

class SeoData
{
    /**
     * @param  array<int, array<string, mixed>>  $jsonLd
     */
    public function __construct(
        public string $title,
        public ?string $description = null,
        public ?string $canonical = null,
        public string $robots = 'index,follow,max-image-preview:large',
        public ?string $ogImage = null,
        public string $ogType = 'website',
        public array $jsonLd = [],
        public string $locale = 'fa',
        public string $ogLocale = 'fa_IR',
        public string $siteName = 'ساس‌پت',
        public ?string $twitterHandle = null,
        public ?string $ga4MeasurementId = null,
        public ?string $gscVerification = null,
        public ?string $preloadImage = null,
    ) {}

    public function isIndexable(): bool
    {
        return str_starts_with($this->robots, 'index');
    }
}
