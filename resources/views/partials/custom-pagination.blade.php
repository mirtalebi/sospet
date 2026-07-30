@if ($paginator->hasPages())
    <div class="flex items-center justify-center gap-2 mt-8" dir="rtl">

        {{-- Previous Page Button --}}
        @if ($paginator->onFirstPage())
            <span
                class="grid place-items-center w-10 h-10 rounded-xl bg-zinc-100 text-zinc-400 cursor-not-allowed border border-zinc-200/50">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </span>
        @else
            <button wire:click="previousPage('page')" wire:loading.attr="disabled" rel="prev"
                class="grid place-items-center w-10 h-10 rounded-xl bg-white border border-zinc-200 text-zinc-600 shadow-sm hover:bg-zinc-50 active:scale-95 transition">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>
        @endif

        {{-- Page Numbers Container --}}
        <div class="flex items-center gap-1 bg-white border border-zinc-200 p-1 rounded-xl shadow-sm">
            @foreach ($elements as $element)
                {{-- Ellipsis Separator --}}
                @if (is_string($element))
                    <span class="px-3 py-1.5 text-xs font-semibold text-zinc-400 select-none">{{ $element }}</span>
                @endif

                {{-- Links Array --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="grid place-items-center min-w-8 h-8 px-2.5 text-xs font-bold bg-zinc-950 text-white rounded-lg select-none">
                                {{ $page }}
                            </span>
                        @else
                            <button wire:click="gotoPage({{ $page }}, 'page')" wire:loading.attr="disabled"
                                class="grid place-items-center min-w-8 h-8 px-2.5 text-xs font-medium text-zinc-600 rounded-lg hover:bg-zinc-100 transition active:scale-95">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Button --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage('page')" wire:loading.attr="disabled" rel="next"
                class="grid place-items-center w-10 h-10 rounded-xl bg-white border border-zinc-200 text-zinc-600 shadow-sm hover:bg-zinc-50 active:scale-95 transition">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </button>
        @else
            <span
                class="grid place-items-center w-10 h-10 rounded-xl bg-zinc-100 text-zinc-400 cursor-not-allowed border border-zinc-200/50">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </span>
        @endif
    </div>
@endif
