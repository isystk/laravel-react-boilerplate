@extends('layouts.admin')
@section('title', '画像一覧')
@section('mainMenu', 'system')
@section('subMenu', 'photo')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.photo') }}
@endsection

@section('content')
    <div class="card card-purple">
        <div class="card-header">
            <h3 class="card-title">検索条件</h3>
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
                           class="col-sm-2 col-form-label">ファイル名</label>
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
                           class="col-sm-2 col-form-label">種別</label>
                    <div class="col-sm-4">
                        <select name="fileType"
                                id="fileType"
                                class="form-select">
                            <option value="">未選択</option>
                            @foreach (App\Enums\ImageType::cases() as $item)
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
                           class="col-sm-2 col-form-label">未参照のみ</label>
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
                        class="btn btn-secondary">検索</button>
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
                    <h3 class="card-title">検索結果</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-responsive">
                        <thead>
                            <tr>
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['type', '種別'],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['file_name', 'ファイル名'],
                                ])
                                <th>画像</th>
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['created_at', '登録日時'],
                                ])
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($images as $image)
                                <tr>
                                    <td>{{ $image->type?->label() ?? '' }}</td>
                                    <td>
                                        @if (!is_null($image->getUsedUrl()))
                                            <a href="{{ $image->getUsedUrl() }}">
                                                {{ $image->file_name }}
                                            </a>
                                        @else
                                            {{ $image->file_name }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (!is_null($image->getUsedUrl()))
                                            <a href="{{ $image->getUsedUrl() }}">
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
                                                @if (!Auth::user()->role->isHighManager() || !$image->isUsed()) disabled="disabled" @endif>削除する
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
