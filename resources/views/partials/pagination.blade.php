@if ($paginator->hasPages())
    <nav>
        <style>
            .page-link:hover:not(.disabled) {
                color: #0056b3 !important;
                background-color: #e9ecef !important;
                border-color: #adb5bd !important;
                text-decoration: none !important;
            }
            .page-item.disabled .page-link {
                cursor: not-allowed !important;
            }
        </style>
        <ul class="pagination pagination-sm mb-0" style="flex-wrap: wrap;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true" style="color: #6c757d !important; background-color: #f8f9fa !important; border-color: #dee2e6 !important;">
                        <i class="fas fa-angle-left"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" style="color: #007bff !important; background-color: #ffffff !important; border-color: #dee2e6 !important;">
                        <i class="fas fa-angle-left"></i>
                    </a>
                </li>
            @endif

            {{-- Page Numbers with Icons --}}
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                @if ($i == $paginator->currentPage())
                    <li class="page-item active" aria-current="page">
                        <span class="page-link" style="color: #ffffff !important; background-color: #007bff !important; border-color: #007bff !important;">
                            <i class="fas fa-file me-1"></i>{{ $i }}
                        </span>
                    </li>
                @elseif ($i == 1 || $i == $paginator->lastPage() || ($i >= $paginator->currentPage() - 1 && $i <= $paginator->currentPage() + 1))
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($i) }}" style="color: #007bff !important; background-color: #ffffff !important; border-color: #dee2e6 !important;">
                            <i class="fas fa-file me-1"></i>{{ $i }}
                        </a>
                    </li>
                @elseif ($i == $paginator->currentPage() - 2 || $i == $paginator->currentPage() + 2)
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" style="color: #6c757d !important; background-color: #f8f9fa !important; border-color: #dee2e6 !important;">
                            <i class="fas fa-ellipsis-h"></i>
                        </span>
                    </li>
                @endif
            @endfor

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" style="color: #007bff !important; background-color: #ffffff !important; border-color: #dee2e6 !important;">
                        <i class="fas fa-angle-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true" style="color: #6c757d !important; background-color: #f8f9fa !important; border-color: #dee2e6 !important;">
                        <i class="fas fa-angle-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
