@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman Berita" class="flex items-center justify-center space-x-1.5 sm:space-x-2 select-none">
        
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center text-xs font-bold text-slate-300 bg-slate-100/70 border border-slate-200/60 cursor-not-allowed" aria-disabled="true" aria-label="Sebelumnya">
                <i class="fas fa-chevron-left text-[11px]"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center text-xs font-bold text-slate-600 bg-white border border-slate-200/90 shadow-2xs hover:bg-slate-50 hover:text-theme-primary hover:border-theme-primary/40 transition-all duration-200 spring-hover" aria-label="Sebelumnya">
                <i class="fas fa-chevron-left text-[11px]"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="w-8 h-9 sm:w-9 sm:h-10 flex items-center justify-center text-xs font-bold text-slate-400" aria-disabled="true">
                    {{ $element }}
                </span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center text-xs sm:text-sm font-extrabold text-white bg-theme-primary border border-theme-primary shadow-md transform scale-105" aria-current="page">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center text-xs sm:text-sm font-bold text-slate-700 bg-white border border-slate-200/90 shadow-2xs hover:bg-slate-50 hover:text-theme-primary hover:border-theme-primary/40 transition-all duration-200 spring-hover">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center text-xs font-bold text-slate-600 bg-white border border-slate-200/90 shadow-2xs hover:bg-slate-50 hover:text-theme-primary hover:border-theme-primary/40 transition-all duration-200 spring-hover" aria-label="Selanjutnya">
                <i class="fas fa-chevron-right text-[11px]"></i>
            </a>
        @else
            <span class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center text-xs font-bold text-slate-300 bg-slate-100/70 border border-slate-200/60 cursor-not-allowed" aria-disabled="true" aria-label="Selanjutnya">
                <i class="fas fa-chevron-right text-[11px]"></i>
            </span>
        @endif

    </nav>
@endif
