<title>{{ $seo->title }}</title>

@if (filled($seo->description))
    <meta name="description" content="{{ $seo->description }}">
@endif

<meta name="robots" content="{{ $seo->robots }}">

@if (filled($seo->canonical))
    <link rel="canonical" href="{{ $seo->canonical }}">
    <link rel="alternate" hreflang="fa-IR" href="{{ $seo->canonical }}">
    <link rel="alternate" hreflang="x-default" href="{{ $seo->canonical }}">
@endif

@if (filled($seo->gscVerification))
    <meta name="google-site-verification" content="{{ $seo->gscVerification }}">
@endif

<meta property="og:type" content="{{ $seo->ogType }}">
<meta property="og:site_name" content="{{ $seo->siteName }}">
<meta property="og:title" content="{{ $seo->title }}">
<meta property="og:locale" content="{{ $seo->ogLocale }}">

@if (filled($seo->description))
    <meta property="og:description" content="{{ $seo->description }}">
@endif

@if (filled($seo->canonical))
    <meta property="og:url" content="{{ $seo->canonical }}">
@endif

@if (filled($seo->ogImage))
    <meta property="og:image" content="{{ $seo->ogImage }}">
    <meta property="og:image:alt" content="{{ $seo->title }}">
@endif

<meta name="twitter:card" content="{{ filled($seo->ogImage) ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $seo->title }}">

@if (filled($seo->description))
    <meta name="twitter:description" content="{{ $seo->description }}">
@endif

@if (filled($seo->ogImage))
    <meta name="twitter:image" content="{{ $seo->ogImage }}">
@endif

@if (filled($seo->twitterHandle))
    <meta name="twitter:site" content="{{ $seo->twitterHandle }}">
@endif

@if (filled($seo->preloadImage))
    <link rel="preload" as="image" href="{{ $seo->preloadImage }}" fetchpriority="high">
@endif

@foreach ($seo->jsonLd as $schema)
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
@endforeach
