@extends('layouts.admin')
@section('title', $user->name)
@section('mainMenu', 'user')
@section('subMenu', 'user')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.user.show', $user) }}
@endsection

@section('content')
    <div class="text-left mb-3">
        <a
            class="btn btn-secondary"
            href="{{ route('admin.user') }}"
        >{{ __('common.Back') }}</a>
    </div>

    <div class="card card-purple">
        <div class="card-body">
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-2 control-label">{{ __('user.Name') }}</label>
                    <div class="col-sm-4">
                        {{ $user -> name }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-2 control-label">{{ __('user.EMail') }}</label>
                    <div class="col-sm-4">
                        {{ $user -> email }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center position-relative">
            <div class="d-inline-block">
                <div class="mx-auto">
                    <a
                        class="btn btn-info"
                        href="{{ route('admin.user.edit', ['user' => $user ]) }}"
                        @cannot('high-manager')
                            disabled="disabled"
                        @endcan
                    >
                        {{ __('common.Change') }}
                    </a>
                </div>
            </div>
            <div
                class="d-inline-block position-absolute"
                style="right: 30px;"
            >
                <form
                    method="POST"
                    action="{{ route('admin.user.destroy', ['user' => $user ]) }}"
                    id="delete_{{ $user->id }}"
                >
                    @method('DELETE')
                    @csrf
                    <button
                        class="btn btn-danger js-deleteBtn"
                        data-id="{{ $user->id }}"
                        @cannot('high-manager')
                            disabled="disabled"
                        @endcan
                    >
                        {{ __('common.Delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @vite('resources/assets/admin/js/pages/user/show.js')
@endsection
