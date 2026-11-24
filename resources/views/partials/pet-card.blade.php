@php
    $image = $pet->images->first();
    [$typeLabel, $typeDot, $typeText] = match ($pet->type) {
        'adoption' => ['فرزندخواهی', 'bg-cta', 'text-cta'],
        'found' => ['پیدا شده', 'bg-success-custom', 'text-success-custom'],
        default => ['گم شده', 'bg-danger-custom', 'text-danger-custom'],
    };
@endphp

<a href="{{ route('pet-details', $pet) }}" wire:navigate
    class="group flex flex-col overflow-hidden rounded-[1.35rem] border border-border-custom bg-card shadow-[0_14px_35px_-30px_rgba(34,50,74,0.6)] transition duration-300 hover:-translate-y-0.5 hover:border-primary/25 hover:shadow-[0_22px_45px_-32px_rgba(34,50,74,0.6)]">

    <!-- Photo -->
    <div class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-[#EAF6F8] via-[#FBF6EC] to-[#FFF0EA]">
        <svg class="absolute inset-0 m-auto h-10 w-10 text-primary/25" viewBox="0 0 24 24" fill="currentColor"
            aria-hidden="true">
            <g>
                <ellipse cx="12" cy="5" rx="1.9" ry="2.4" />
                <ellipse cx="6.9" cy="8" rx="1.7" ry="2.2" transform="rotate(-22 6.9 8)" />
                <ellipse cx="17.1" cy="8" rx="1.7" ry="2.2" transform="rotate(22 17.1 8)" />
                <ellipse cx="4.5" cy="13.2" rx="1.5" ry="1.9" transform="rotate(-38 4.5 13.2)" />
                <ellipse cx="19.5" cy="13.2" rx="1.5" ry="1.9" transform="rotate(38 19.5 13.2)" />
                <path
                    d="M12 11.3c-2.5 0-4.6 1.6-5.5 3.8-.8 2 .4 4.2 2.6 4.6.9.2 1.9 0 2.9-.6.7-.4 1.4-.4 2.1 0 1 .6 2 .8 2.9.6 2.2-.4 3.4-2.6 2.6-4.6-.9-2.2-3-3.9-5.6-3.9Z" />
            </g>
        </svg>

        @if ($image)
            <img src="{{ $image->image_url }}" alt="{{ $pet->imageAlt() }}" loading="lazy" decoding="async"
                width="400" height="300" onerror="this.remove()"
                class="relative h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]">
        @endif

        <span
            class="absolute start-2 top-2 inline-flex items-center gap-1 rounded-full bg-white/90 px-2 py-1 text-[9px] font-extrabold {{ $typeText }} shadow-sm backdrop-blur-sm md:text-[10px]">
            <span class="h-1.5 w-1.5 rounded-full {{ $typeDot }}"></span>
            {{ $typeLabel }}
        </span>

        @isset($pet->distance_km)
            <span
                class="absolute end-2 top-2 rounded-full bg-white/90 px-2 py-1 text-[9px] font-bold text-success-custom shadow-sm backdrop-blur-sm">
                {{ number_format($pet->distance_km, 1) }} کیلومتر
            </span>
        @endisset
    </div>

    <!-- Info -->
    <div class="flex flex-1 flex-col gap-1 p-3 md:p-3.5">
        <h3
            class="line-clamp-1 text-[13px] font-bold text-text-title transition-colors group-hover:text-primary md:text-sm">
            {{ $pet->title }}
        </h3>

        <p class="line-clamp-1 text-[10.5px] text-text-body md:text-[11px]">
            {{ $pet->pet_type }}@if ($pet->breed)<span class="text-text-muted"> · {{ $pet->breed }}</span>@endif
        </p>

        <div class="mt-auto flex items-center justify-between gap-2 pt-1.5">
            <span
                class="inline-flex min-w-0 items-center gap-1 text-[10px] font-semibold text-text-muted md:text-[11px]">
                <svg class="shrink-0" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" aria-hidden="true">
                    <path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z" />
                    <circle cx="12" cy="9.5" r="2.3" />
                </svg>
                <span class="truncate">{{ $pet->city }}@if ($pet->area)، {{ $pet->area }}@endif</span>
            </span>

            <span
                class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-primary/10 text-primary transition group-hover:bg-primary group-hover:text-white">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
            </span>
        </div>
    </div>
</a>
