@if ($paginator->hasPages())
<nav role="pagination" aria-label="{{ __('Pagination Navigation') }}">
    <x-shipyard.ui.button
        icon="chevron-left"
        pop="Poprzednia strona"
        :action="$paginator->onFirstPage() ? null : $paginator->previousPageUrl()"
    />

    <div>
        <div role="pages">
            @foreach ($elements as $element)
                @if (is_string($element))
                <span>...</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                    <x-shipyard.ui.button
                        :label="$page"
                        :action="$page == $paginator->currentPage() ? null : $url"
                        class="{{ $page == $paginator->currentPage() ? 'active' : '' }}"
                    />
                    @endforeach
                @endif
            @endforeach
        </div>

        <p role="pages-description">
            @if ($paginator->firstItem())
            {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }}
            @else
            {{ $paginator->count() }}
            @endif
            z {{ $paginator->total() }} wyników
        </p>
    </div>

    <x-shipyard.ui.button
        icon="chevron-right"
        pop="Następna strona"
        :action="$paginator->hasMorePages() ? $paginator->nextPageUrl() : null"
    />
</nav>
@endif
