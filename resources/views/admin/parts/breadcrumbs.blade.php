@if (!empty($breadcrumbs))
    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($loop->last)
                <li class="breadcrumb-item active">{{ $breadcrumb['title'] }}</li>
            @else
                <li class="breadcrumb-item">
                    @if (!empty($breadcrumb['url']))
                        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                    @else
                        <span>{{ $breadcrumb['title'] }}</span>
                    @endif
                </li>
            @endif
        @endforeach
    </ol>
@endif
