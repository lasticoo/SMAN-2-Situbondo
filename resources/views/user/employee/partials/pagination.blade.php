@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center space-x-1.5 sm:space-x-2 text-xs sm:text-sm font-semibold">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" aria-label="@lang('pagination.previous')" class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-300 cursor-not-allowed">
                <i class="fas fa-chevron-left text-[10px]"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 hover:border-theme-primary active:scale-95 transition shadow-2xs">
                <i class="fas fa-chevron-left text-[10px]"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span aria-disabled="true" class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center text-gray-400 font-bold text-xs">
                    {{ $element }}
                </span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg bg-theme-primary text-white font-extrabold shadow-sm">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 hover:border-theme-primary active:scale-95 transition shadow-2xs font-medium">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 hover:border-theme-primary active:scale-95 transition shadow-2xs">
                <i class="fas fa-chevron-right text-[10px]"></i>
            </a>
        @else
            <span aria-disabled="true" aria-label="@lang('pagination.next')" class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-300 cursor-not-allowed">
                <i class="fas fa-chevron-right text-[10px]"></i>
            </span>
        @endif
    </nav>
@endif
