@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center items-center gap-1.5 sm:gap-2 mt-8 sm:mt-10">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl border border-slate-200 text-slate-300 cursor-not-allowed bg-slate-50 text-xs font-semibold">
                <i class="fas fa-chevron-left text-[10px]"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-theme-primary transition-all text-xs font-semibold shadow-2xs">
                <i class="fas fa-chevron-left text-[10px]"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center text-slate-400 text-xs font-bold">
                    {{ $element }}
                </span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl bg-theme-primary text-white font-bold text-xs sm:text-sm shadow-sm scale-105 transition-transform">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 hover:text-theme-primary transition-all text-xs sm:text-sm font-semibold">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-theme-primary transition-all text-xs font-semibold shadow-2xs">
                <i class="fas fa-chevron-right text-[10px]"></i>
            </a>
        @else
            <span class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl border border-slate-200 text-slate-300 cursor-not-allowed bg-slate-50 text-xs font-semibold">
                <i class="fas fa-chevron-right text-[10px]"></i>
            </span>
        @endif
    </nav>
@endif
