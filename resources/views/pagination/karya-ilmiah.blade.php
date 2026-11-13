@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman">
        <div>
            @if ($paginator->onFirstPage())
                <span aria-disabled="true"><span>Sebelumnya</span></span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span aria-disabled="true"><span>{{ $element }}</span></span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"><span>{{ $page }}</span></span>
                        @else
                            <a href="{{ $url }}" aria-label="Ke halaman {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next">Selanjutnya</a>
            @else
                <span aria-disabled="true"><span>Selanjutnya</span></span>
            @endif
        </div>

        <p>Menampilkan {{ $paginator->firstItem() ?? 0 }} sampai {{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} data</p>
    </nav>
@endif
