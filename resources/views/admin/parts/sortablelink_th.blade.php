@php
    $isAsc = request()->sort_name === $params[0] && request()->sort_direction === 'asc';
    $isDesc = request()->sort_name === $params[0] && request()->sort_direction === 'desc';
    $sortDirection = $isAsc ? 'desc' : 'asc';

    $query = array_merge(request()->all(), ['sort_name' => $params[0], 'sort_direction' => $sortDirection]);

    if (isset($route_params) && is_array($route_params)) {
        $query = array_merge($query, $route_params);
    }

    $url = route(Route::currentRouteName(), $query);
@endphp

<th class="sortable_th"
    data-url="{{ $url }}"
    onclick="window.location.href = $(this).data('url')">
    {!! $params[1] !!}
    <span class="sortable_arrow_asc {{ $isAsc ? 'enabled_arrow' : 'disabled_arrow' }}">↑</span>
    <span class="sortable_arrow_desc {{ $isDesc ? 'enabled_arrow' : 'disabled_arrow' }}">↓</span>
</th>
