@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center gap-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span class="relative inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-slate-400 bg-slate-100 border border-slate-200 rounded-lg cursor-not-allowed" aria-hidden="true">
                    ← Sebelumnya
                </span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" class="relative inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition duration-200 hover:shadow-md">
                ← Sebelumnya
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="flex items-center gap-1">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true">
                        <span class="relative inline-flex items-center justify-center px-3 py-2 text-sm font-bold text-slate-500">{{ $element }}</span>
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        {{-- "First" Page Link --}}
                        @if ($page == 1)
                            @if ($paginator->currentPage() == 1)
                                <span aria-current="page" aria-label="@lang('pagination.page', ['page' => $page])">
                                    <span class="relative inline-flex items-center justify-center px-3 py-2 text-sm font-extrabold text-white bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg shadow-md shadow-blue-500/30">{{ $page }}</span>
                                </span>
                            @else
                                <a href="{{ $url }}" aria-label="@lang('pagination.go_to_page', ['page' => $page])" class="relative inline-flex items-center justify-center px-3 py-2 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg transition duration-200">
                                    {{ $page }}
                                </a>
                            @endif
                        @endif

                        {{-- "Last" Page Link --}}
                        @if ($page == $paginator->lastPage())
                            @if ($paginator->currentPage() == $paginator->lastPage())
                                <span aria-current="page" aria-label="@lang('pagination.page', ['page' => $page])">
                                    <span class="relative inline-flex items-center justify-center px-3 py-2 text-sm font-extrabold text-white bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg shadow-md shadow-blue-500/30">{{ $page }}</span>
                                </span>
                            @else
                                <a href="{{ $url }}" aria-label="@lang('pagination.go_to_page', ['page' => $page])" class="relative inline-flex items-center justify-center px-3 py-2 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg transition duration-200">
                                    {{ $page }}
                                </a>
                            @endif
                        @endif

                        {{-- "Middle" Page Links --}}
                        @if ($page > 1 && $page < $paginator->lastPage())
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" aria-label="@lang('pagination.page', ['page' => $page])">
                                    <span class="relative inline-flex items-center justify-center px-3 py-2 text-sm font-extrabold text-white bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg shadow-md shadow-blue-500/30">{{ $page }}</span>
                                </span>
                            @else
                                <a href="{{ $url }}" aria-label="@lang('pagination.go_to_page', ['page' => $page])" class="relative inline-flex items-center justify-center px-3 py-2 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg transition duration-200">
                                    {{ $page }}
                                </a>
                            @endif
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" class="relative inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition duration-200 hover:shadow-md">
                Selanjutnya →
            </a>
        @else
            <span aria-disabled="true" aria-label="@lang('pagination.next')">
                <span class="relative inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-slate-400 bg-slate-100 border border-slate-200 rounded-lg cursor-not-allowed" aria-hidden="true">
                    Selanjutnya →
                </span>
            </span>
        @endif
    </nav>
@endif
