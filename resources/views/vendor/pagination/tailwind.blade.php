@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="mt-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            {{-- Left: showing text --}}
            <div class="text-sm text-muted text-center text-sm-start">
                @if ($paginator->firstItem())
                    Showing
                    <span class="fw-medium">{{ $paginator->firstItem() }}</span>
                    to
                    <span class="fw-medium">{{ $paginator->lastItem() }}</span>
                    of
                    <span class="fw-medium">{{ $paginator->total() }}</span>
                    results
                @else
                    Showing {{ $paginator->count() }} results
                @endif
            </div>

            {{-- Right: pagination (Bootstrap pagination component) --}}
            <ul class="pagination mb-0 justify-content-center justify-content-sm-end">
                {{-- First --}}
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->onFirstPage() ? '#' : $paginator->url(1) }}" aria-label="First">⟪</a>
                </li>

                {{-- Previous --}}
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->onFirstPage() ? '#' : $paginator->previousPageUrl() }}" aria-label="Previous">⟨</a>
                </li>

                {{-- Page Links --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $paginator->hasMorePages() ? $paginator->nextPageUrl() : '#' }}" aria-label="Next">⟩</a>
                </li>

                {{-- Last --}}
                <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $paginator->hasMorePages() ? $paginator->url($paginator->lastPage()) : '#' }}" aria-label="Last">⟫</a>
                </li>
            </ul>
        </div>
    </nav>
@endif
