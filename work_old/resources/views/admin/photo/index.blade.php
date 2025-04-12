@extends('layouts.app_admin')

@section('title', __('photo.Photo List'))
@php
    $menu = 'system';
    $subMenu = 'photo';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.photo') }}
@endsection

@section('content')
    <div class="card card-purple">
        <div class="card-header">
            <h3 class="card-title">{{ __('common.Search Condition') }}</h3>
        </div>
        <form action="{{ route('admin.photo') }}" method="GET">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="form-group">
                    <div class="control-group" id="photoName">
                        <label class="col-sm-2 control-label">{{ __('photo.File Name') }}</label>
                        <div class="col-sm-4">
                            <input
                                type="text"
                                name="fileName"
                                value="{{ $request->fileName }}"
                                class="form-control"
                                maxlength="100"
                            />
                        </div>
                    </div>
                    <div class="control-group mt-3" id="userName">
                        <label class="col-sm-2 control-label">{{ __('photo.Type') }}</label>
                        <div class="col-sm-4">
                            <select
                                name="fileType"
                                class="form-control"
                            >
                                <option value="">未選択</option>
                                @foreach(App\Enums\PhotoType::cases() as $item)
                                    <option
                                        value="{{ $item->value }}"
                                        {{ ($item->value === (int)$request->fileType) ? 'selected' : '' }}
                                    >{{ $item->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-secondary">{{ __('common.Search') }}</button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.photo') }}" method="GET" id="pagingForm">
        <input type="hidden" name="fileName" value="{{ $request->fileName }}">
        <input type="hidden" name="fileType" value="{{ $request->fileType }}">
    </form>
    <div class="row">
        <div class="col-12">
            <div class="card card-purple">
                <div class="card-header">
                    <h3 class="card-title">{{ __('common.Search Result') }}</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>{{ __('photo.Type') }}</th>
                                <th>{{ __('photo.File Name') }}</th>
                                <th>{{ __('photo.Image') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($photos as $photo)
                            <tr>
                                <td>{{ $photo['type']?->label() ?? '' }}</td>
                                <td>{{ $photo['fileName'] }}</td>
                                <td>
                                    <img
                                        src="{{ asset('/uploads/' . $photo['fileName']) }}"
                                        alt=""
                                        width="100px"
                                    />
                                </td>
                                <td>
                                    <button
                                        class="btn btn-danger btn-sm js-deleteBtn"
                                        data-id="{{ $photo['fileName'] }}"
                                        @cannot('high-manager')
                                            disabled="disabled"
                                        @endcan
                                    >削除する</button>
                                    <form
                                        id="delete_{{ $photo['fileName'] }}"
                                        action="{{ route('admin.photo.destroy') }}"
                                        method="POST"
                                        style="display: none;"
                                    >
                                        @method('DELETE')
                                        @csrf
                                        <input type="hidden" name="fileName" value="{{ $photo['fileName'] }}"/>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer  ">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            // 削除確認用のダイアログを表示
            $('.js-deleteBtn').click(function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                if (confirm('本当に削除していいですか？')) {
                    $('#delete_' + id.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, '\\$&')).submit();
                }
            });
        });
    </script>
@endsection
