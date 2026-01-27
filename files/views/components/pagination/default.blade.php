@php
$pageSwitcher ??= false;
@endphp

<nav role="pagination" aria-label="{{ __('Pagination Navigation') }}">
    @if ($paginator->hasPages())
    <x-shipyard.ui.button
        icon="chevron-left"
        pop="Poprzednia strona"
        :action="$paginator->onFirstPage() ? null : ($pageSwitcher ? 'none' : $paginator->previousPageUrl())"
        :onclick="$pageSwitcher ? $pageSwitcher.'('.($paginator->currentPage() - 1).')' : null"
    />
    @endif

    <div>
        @if ($paginator->hasPages())
        <div role="pages">
            @foreach ($elements as $element)
                @if (is_string($element))
                <span>...</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                    <x-shipyard.ui.button
                        :label="$page"
                        :action="$page == $paginator->currentPage() ? null : ($pageSwitcher ? 'none' : $url)"
                        :onclick="$pageSwitcher ? $pageSwitcher.'('.$page.')' : null"
                        class="{{ $page == $paginator->currentPage() ? 'active' : '' }}"
                    />
                    @endforeach
                @endif
            @endforeach
        </div>
        @endif

        <p role="pages-description">
            @if ($paginator->firstItem())
            {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }}
            @else
            {{ $paginator->count() }}
            @endif
            z {{ $paginator->total() }} wyników
        </p>
    </div>

    @if ($paginator->hasPages())
    <x-shipyard.ui.button
        icon="chevron-right"
        pop="Następna strona"
        :action="$paginator->hasMorePages() ? ($pageSwitcher ? 'none' : $paginator->nextPageUrl()) : null"
        :onclick="$pageSwitcher ? $pageSwitcher.'('.($paginator->currentPage() + 1).')' : null"
    />
    @endif
</nav>
