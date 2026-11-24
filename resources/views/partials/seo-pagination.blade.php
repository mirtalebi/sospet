@if ($paginator->hasPages())
    <nav aria-label="صفحه‌بندی" class="flex items-center justify-center gap-2" dir="rtl">

        @if ($paginator->onFirstPage())
            <span
                class="grid h-10 w-10 place-items-center rounded-xl border border-zinc-200/60 bg-zinc-100 text-zinc-400">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" wire:navigate
                aria-label="صفحه قبل"
                class="grid h-10 w-10 place-items-center rounded-xl border border-border-custom bg-bg-main text-text-title shadow-sm transition hover:border-primary/30 hover:text-primary active:scale-95">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </a>
        @endif

        <div class="flex items-center gap-1 rounded-xl border border-border-custom bg-bg-main p-1 shadow-sm">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="select-none px-3 py-1.5 text-xs font-semibold text-text-muted">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="grid h-8 min-w-8 select-none place-items-center rounded-lg bg-primary px-2.5 text-xs font-bold text-white"
                                aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" wire:navigate
                                aria-label="صفحه {{ $page }}"
                                class="grid h-8 min-w-8 place-items-center rounded-lg px-2.5 text-xs font-medium text-text-body transition hover:bg-bg-secondary active:scale-95">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" wire:navigate
                aria-label="صفحه بعد"
                class="grid h-10 w-10 place-items-center rounded-xl border border-border-custom bg-bg-main text-text-title shadow-sm transition hover:border-primary/30 hover:text-primary active:scale-95">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </a>
        @else
            <span
                class="grid h-10 w-10 place-items-center rounded-xl border border-zinc-200/60 bg-zinc-100 text-zinc-400">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </span>
        @endif
    </nav>
@endif
