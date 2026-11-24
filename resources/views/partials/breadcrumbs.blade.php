@if (! empty($items))
    <nav aria-label="مسیر صفحه" class="mb-4">
        <ol class="flex flex-wrap items-center gap-1.5 text-[11px] font-medium text-zinc-400">
            @foreach ($items as $item)
                <li class="flex items-center gap-1.5">
                    @unless ($loop->first)
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                            aria-hidden="true">
                            <path d="M15 18l-6-6 6-6" />
                        </svg>
                    @endunless

                    @isset($item['url'])
                        <a href="{{ $item['url'] }}" wire:navigate class="transition-colors hover:text-primary">{{ $item['label'] }}</a>
                    @else
                        <span aria-current="page" class="text-zinc-500">{{ $item['label'] }}</span>
                    @endisset
                </li>
            @endforeach
        </ol>
    </nav>
@endif
