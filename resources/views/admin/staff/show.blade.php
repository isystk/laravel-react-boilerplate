@extends('layouts.app_admin')

@section('title', $staff->name)
@php
    $menu = 'system';
    $subMenu = 'staff';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.staff.show', $staff) }}
@endsection

@section('content')
    <div class="text-left mb-3">
        <a class="btn btn-secondary" href="{{ route('admin.staff') }}">{{ __('common.Back') }}</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-purple">
        <div class="card-body">
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-2 control-label">{{ __('staff.Name') }}</label>
                    <div class="col-sm-4">
                        {{ $staff->name }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-2 control-label">{{ __('staff.EMail') }}</label>
                    <div class="col-sm-4">
                        {{ $staff->email }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-2 control-label">{{ __('staff.Role') }}</label>
                    <div class="col-sm-4">
                        {{ App\Enums\AdminRole::getLabel($staff->role) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center position-relative">
            <div class="d-inline-block">
                <div class="mx-auto">
                    <a
                        class="btn btn-info"
                        href="{{ route('admin.staff.edit', ['staff' => $staff ]) }}"
                        @cannot('high-manager')
                            disabled="disabled"
                        @endcan
                    >
                        {{ __('common.Change') }}
                    </a>
                </div>
            </div>
            <div class="d-inline-block position-absolute" style="right: 30px;">
                <form
                    method="POST"
                    action="{{ route('admin.staff.destroy', ['staff' => $staff ]) }}"
                    id="delete_{{ $staff->id }}"
                >
                    @method('DELETE')
                    @csrf
                    <button
                        class="btn btn-danger js-deleteBtn"
                        data-id="{{ $staff->id }}"
                        @cannot('high-manager')
                            disabled="disabled"
                        @endcan
                    >{{ __('common.Delete') }}</button>
                </form>
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
                    $('#delete_' + id).submit();
                }
            });
        });
    </script>
@endsection
