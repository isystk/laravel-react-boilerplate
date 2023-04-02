@if (!config('breadcrumbs.hide') && count($breadcrumbs))
<div class="content-header">
  <div class="container-fluid">
      <div class="row mb-2">
          <div class="col-sm-6">
          </div>
          <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if (!$loop->last && empty($breadcrumb->literal))
                        <li class="breadcrumb-item">
                            @php
                                $url = null;
                                if (!empty($breadcrumb->url)) {
                                    $prefix = '';
                                    $params = $breadcrumb->params ?? [];
                                    if (!empty($breadcrumb->privilege)) {
                                        $prefix = $breadcrumb->privilege . '.';
                                    }
                                    $url = route(
                                      $prefix . $breadcrumb->url,
                                        $params
                                    );
                                }
                            @endphp
                            @if (!empty($url))
                                <a href="{{ $url }}">
                                    {{ $breadcrumb->title }}
                                </a>
                            @else
                                <span>
                                    {{ $breadcrumb->title }}
                                </span>
                            @endif
                        </li>
                    @else
                        <li class="breadcrumb-item active">
                            {{ $breadcrumb->title }}
                        </li>
                    @endif
                @endforeach
              </ol>
          </div>
      </div>
  </div>
</div>
@endif
