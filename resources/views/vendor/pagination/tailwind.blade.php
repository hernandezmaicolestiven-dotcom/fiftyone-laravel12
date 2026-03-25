@if ($paginator->hasPages())
@php
    $currentPage = $paginator->currentPage();
    $lastPage    = $paginator->lastPage();
    $from        = $paginator->firstItem();
    $to          = $paginator->lastItem();
    $total       = $paginator->total();
@endphp
<div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">

    {{-- Info --}}
    <p class="text-xs text-gray-400">
        Mostrando <span class="font-semibold text-gray-600">{{ $from }}</span>–<span class="font-semibold text-gray-600">{{ $to }}</span>
        de <span class="font-semibold text-gray-600">{{ $total }}</span> resultados
    </p>

    {{-- Controles --}}
    <div class="flex items-center gap-1">

        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 text-gray-300 cursor-not-allowed select-none">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 text-gray-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>
        @endif

        {{-- Páginas --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-9 h-9 text-gray-400 select-none">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $currentPage)
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-white text-xs font-bold shadow-sm"
                              style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 text-gray-600 text-xs font-medium hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Siguiente --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 text-gray-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 text-gray-300 cursor-not-allowed select-none">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </span>
        @endif

    </div>
</div>
@endif
