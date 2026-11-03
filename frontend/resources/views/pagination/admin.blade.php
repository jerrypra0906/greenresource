@if ($paginator->hasPages())
    <nav class="admin-pagination" role="navigation" aria-label="Pagination">
        <p class="admin-pagination-summary">
            Showing
            <span class="admin-pagination-summary-strong">{{ $paginator->firstItem() }}</span>
            to
            <span class="admin-pagination-summary-strong">{{ $paginator->lastItem() }}</span>
            of
            <span class="admin-pagination-summary-strong">{{ $paginator->total() }}</span>
            results
        </p>

        <ul class="admin-pagination-list">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="admin-pagination-link admin-pagination-link--disabled" aria-disabled="true">Previous</span>
                </li>
            @else
                <li>
                    <a class="admin-pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
                </li>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span class="admin-pagination-ellipsis" aria-hidden="true">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="admin-pagination-link admin-pagination-link--active" aria-current="page">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a class="admin-pagination-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a class="admin-pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
                </li>
            @else
                <li>
                    <span class="admin-pagination-link admin-pagination-link--disabled" aria-disabled="true">Next</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
