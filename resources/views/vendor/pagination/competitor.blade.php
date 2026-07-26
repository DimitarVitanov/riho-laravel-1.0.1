@if ($paginator->hasPages())
<nav aria-label="Pagination">
    <ul class="pagination" style="display:flex;list-style:none;padding:0;margin:0;justify-content:center;gap:0">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true" style="margin:0">
                <span class="page-link" style="display:flex;align-items:center;justify-content:center;min-width:42px;height:42px;border:1px solid #dfe3e8;background:#f5f6f8;color:#a8afb9;font-weight:700;font-size:18px;text-decoration:none">&lsaquo;</span>
            </li>
        @else
            <li class="page-item" style="margin:0">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display:flex;align-items:center;justify-content:center;min-width:42px;height:42px;border:1px solid #dfe3e8;background:#fff;color:#202733!important;font-weight:700;font-size:18px;text-decoration:none">&lsaquo;</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled" aria-disabled="true" style="margin:0">
                    <span class="page-link" style="display:flex;align-items:center;justify-content:center;min-width:42px;height:42px;border:1px solid #dfe3e8;background:#f5f6f8;color:#a8afb9;font-weight:700;font-size:14px;text-decoration:none">{{ $element }}</span>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page" style="margin:0">
                            <span class="page-link" style="display:flex;align-items:center;justify-content:center;min-width:42px;height:42px;border:1px solid #1677ff;background:#1677ff;color:#fff;font-weight:700;font-size:14px;text-decoration:none">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item" style="margin:0">
                            <a class="page-link" href="{{ $url }}" style="display:flex;align-items:center;justify-content:center;min-width:42px;height:42px;border:1px solid #dfe3e8;background:#fff;color:#202733!important;font-weight:700;font-size:14px;text-decoration:none">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item" style="margin:0">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" style="display:flex;align-items:center;justify-content:center;min-width:42px;height:42px;border:1px solid #dfe3e8;background:#fff;color:#202733!important;font-weight:700;font-size:18px;text-decoration:none">&rsaquo;</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true" style="margin:0">
                <span class="page-link" style="display:flex;align-items:center;justify-content:center;min-width:42px;height:42px;border:1px solid #dfe3e8;background:#f5f6f8;color:#a8afb9;font-weight:700;font-size:18px;text-decoration:none">&rsaquo;</span>
            </li>
        @endif
    </ul>
</nav>
@endif
