<th
    class="sortable_th"
    url="{{ route(
        Route::currentRouteName(),
        isset($route_params) && is_array($route_params)
            ? [
                ...request()->all(),
                ...[
                    ...$route_params,
                    'sort_name' => $params[0],
                    'sort_direction' => $params[0] === request()->sort_name && 'asc' === request()->sort_direction ? 'desc' : 'asc',
                ],
            ]
            : [
                ...request()->all(),
                ...[
                    'sort_name' => $params[0],
                    'sort_direction' => $params[0] === request()->sort_name && 'asc' === request()->sort_direction ? 'desc' : 'asc',
                ],
            ],
    ) }}"
>
    {!! $params[1] !!}
    <span
        class="sortable_arrow_asc {{ $params[0] === request()->sort_name && 'asc' === request()->sort_direction ? 'enabled_arrow' : 'disabled_arrow' }}"
    >↑</span>
    <span
        class="sortable_arrow_desc {{ $params[0] === request()->sort_name && 'desc' === request()->sort_direction ? 'enabled_arrow' : 'disabled_arrow' }}"
    >↓</span>
</th>
