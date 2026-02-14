@extends('layouts.admin')
@section('title', __('photo.Photo List'))
@section('mainMenu', 'system')
@section('subMenu', 'photo')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.photo') }}
@endsection

@section('content')
    <div class="card card-purple">
        <div class="card-header">
            <h3 class="card-title">{{ __('common.Search Condition') }}</h3>
        </div>
        <form action="{{ route('admin.photo') }}"
              method="GET">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success"
                         role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="mb-3 row">
                    <label for="fileName"
                           class="col-sm-2 col-form-label">{{ __('photo.File Name') }}</label>
                    <div class="col-sm-4">
                        <input type="text"
                               name="fileName"
                               id="fileName"
                               value="{{ request()->fileName }}"
                               class="form-control"
                               maxlength="100" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="fileType"
                           class="col-sm-2 col-form-label">{{ __('photo.Type') }}</label>
                    <div class="col-sm-4">
                        <select name="fileType"
                                id="fileType"
                                class="form-select">
                            <option value="">{{ __('common.Unselected') ?? '未選択' }}</option>
                            @foreach (App\Enums\PhotoType::cases() as $item)
                                <option value="{{ $item->value }}"
                                        {{ $item->value === (int) request()->fileType ? 'selected' : '' }}>
                                    {{ $item->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="unusedOnly"
                           class="col-sm-2 col-form-label">{{ __('photo.Unused Only') ?? '未参照のみ' }}</label>
                    <div class="col-sm-4">
                        <div class="form-check mt-2">
                            <input type="checkbox"
                                   name="unusedOnly"
                                   id="unusedOnly"
                                   value="1"
                                   class="form-check-input"
                                   {{ request()->unusedOnly ? 'checked' : '' }} />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit"
                        class="btn btn-secondary">{{ __('common.Search') }}</button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.photo') }}"
          method="GET"
          id="pagingForm">
        <input type="hidden"
               name="fileName"
               value="{{ request()->fileName }}">
        <input type="hidden"
               name="fileType"
               value="{{ request()->fileType }}">
        <input type="hidden"
               name="unusedOnly"
               value="{{ request()->unusedOnly }}">
    </form>
    <div class="row">
        <div class="col-12">
            <div class="card card-purple">
                <div class="card-header">
                    <h3 class="card-title">{{ __('common.Search Result') }}</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-responsive">
                        <thead>
                            <tr>
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['type', __('photo.Type')],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['file_name', __('photo.File Name')],
                                ])
                                <th>{{ __('photo.Image') }}</th>
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['created_at', __('common.Registration Date')],
                                ])
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($images as $image)
                                @php
                                    $usedByUrl = null;
                                    if ($image->used_by_stock_id) {
                                        $usedByUrl = route('admin.stock.show', ['stock' => $image->used_by_stock_id]);
                                    } elseif ($image->used_by_contact_id) {
                                        $usedByUrl = route('admin.contact.show', ['contactForm' => $image->used_by_contact_id]);
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $image->type?->label() ?? '' }}</td>
                                    <td>
                                        @if($usedByUrl)
                                            <a href="{{ $usedByUrl }}">
                                                {{ $image->file_name }}
                                            </a>
                                        @else
                                            {{ $image->file_name }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($usedByUrl)
                                            <a href="{{ $usedByUrl }}">
                                                <img src="{{ $image->getImageUrl() }}"
                                                     alt="{{ $image->file_name }}"
                                                     width="100px" />
                                            </a>
                                        @else
                                            <img src="{{ $image->getImageUrl() }}"
                                                 alt="{{ $image->file_name }}"
                                                 width="100px" />
                                        @endif
                                    </td>
                                    <td>{{ $image->created_at }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm js-deleteBtn"
                                                data-id="{{ $image->id }}"
                                                @if (!Auth::user()->role->isHighManager() || $image->used_by_stock_id || $image->used_by_contact_id) disabled="disabled" @endif>削除する
                                        </button>
                                        <form id="delete_{{ $image->id }}"
                                              action="{{ route('admin.photo.destroy') }}"
                                              method="POST"
                                              style="display: none;">
                                            @method('DELETE')
                                            @csrf
                                            <input type="hidden"
                                                   name="imageId"
                                                   value="{{ $image->id }}" />
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer  ">
                    {!! $images->links('admin.parts.pagination') !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/assets/admin/js/pages/photo/index.js')
@endsection
