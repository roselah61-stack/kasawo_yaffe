@if ($paginator->hasPages())
    <nav class="pager" aria-label="Pages">
        <a class="chip {{ $paginator->onFirstPage() ? 'disabled' : '' }}" href="{{ $paginator->previousPageUrl() }}">← Prev</a>
        @foreach ($elements as $element)
            @if (is_string($element))<span class="chip disabled">…</span>@endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <a class="chip {{ $page == $paginator->currentPage() ? 'on' : '' }}" href="{{ $url }}">{{ $page }}</a>
                @endforeach
            @endif
        @endforeach
        <a class="chip {{ $paginator->hasMorePages() ? '' : 'disabled' }}" href="{{ $paginator->nextPageUrl() }}">Next →</a>
    </nav>
@endif
