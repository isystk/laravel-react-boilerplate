@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center pagination-md">
            {{-- Previous Page Link --}}
            @cannot('update', Model::class)
            @endcannot
            @if ($paginator->onFirstPage())
                <li
                    class="page-item disabled"
                    aria-disabled="true"
                    aria-label="@lang('pagination.previous')"
                >
                    <span
                        class="page-link"
                        aria-hidden="true"
                    >&lt;</span>
                </li>
            @else
                <li class="page-item">
                    <a
                        class="page-link"
                        href="{{ $paginator->previousPageUrl() }}{{ count(request()->all())
                            ? (function ($list) {
                                unset($list['page']);
                                return '&' . http_build_query($list);
                            })(request()->all())
                            : '' }}"
                        rel="prev"
                        aria-label="@lang('pagination.previous')"
                    >&lt;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li
                        class="page-item disabled"
                        aria-disabled="true"
                    >
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li
                                class="page-item active"
                                aria-current="page"
                            >
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a
                                    class="page-link"
                                    href="{{ $url }}{{ count(request()->all())
                                        ? (function ($list) {
                                            unset($list['page']);
                                            return '&' . http_build_query($list);
                                        })(request()->all())
                                        : '' }}"
                                >
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a
                        class="page-link"
                        href="{{ $paginator->nextPageUrl() }}{{ count(request()->all())
                            ? (function ($list) {
                                unset($list['page']);
                                return '&' . http_build_query($list);
                            })(request()->all())
                            : '' }}"
                        rel="next"
                        aria-label="@lang('pagination.next')"
                    >&gt;</a>
                </li>
            @else
                <li
                    class="page-item disabled"
                    aria-disabled="true"
                    aria-label="@lang('pagination.next')"
                >
                    <span
                        class="page-link"
                        aria-hidden="true"
                    >&gt;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
