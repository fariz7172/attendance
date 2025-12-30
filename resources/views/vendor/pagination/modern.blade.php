@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="modern-pagination">
        
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="pagination-btn disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-btn" aria-label="@lang('pagination.previous')">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="pagination-numbers">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="pagination-dots" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-number active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-btn" aria-label="@lang('pagination.next')">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="pagination-btn disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif
    </nav>

    <style>
        .modern-pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 20px 0;
            font-family: inherit;
        }

        .pagination-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #fff;
            color: var(--color-text-muted, #64748b);
            border: 1px solid #e2e8f0;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .pagination-btn:not(.disabled):hover {
            background: #f8fafc;
            color: var(--color-primary, #4361ee);
            border-color: var(--color-primary, #4361ee);
            transform: translateY(-1px);
        }

        .pagination-btn.disabled {
            background: #f1f5f9;
            color: #cbd5e1;
            border-color: transparent;
            cursor: not-allowed;
            box-shadow: none;
        }

        .pagination-numbers {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            padding: 4px;
            border-radius: 12px;
        }

        .pagination-number {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 6px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--color-text-muted, #64748b);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination-number:hover:not(.active) {
            background: #fff;
            color: var(--color-primary, #4361ee);
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .pagination-number.active {
            background: var(--color-primary, #4361ee);
            color: #fff;
            box-shadow: 0 4px 6px -1px rgba(67, 97, 238, 0.3);
        }

        .pagination-dots {
            padding: 0 8px;
            color: #94a3b8;
            font-size: 14px;
        }
    </style>
@endif
