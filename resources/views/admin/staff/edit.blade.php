@extends('layouts.app_admin')

@section('title', $staff->name . __('common.Of Change'))
@php
    $menu = 'system';
    $subMenu = 'staff';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.staff.edit', $staff) }}
@endsection

@section('content')
    <div class="text-left mb-3">
        <a class="btn btn-secondary" href="{{ route('admin.staff.show', ['staff' => $staff]) }}">{{ __('common.Back') }}</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.staff.update', ['staff' => $staff]) }}">
        @method('PUT')
        @csrf
        <div class="card card-purple">
            <div class="card-body">
                <div class="form-group">
                    <div class="control-group" id="staffName">
                        <label class="col-sm-6 control-label">{{ __('staff.Name') }}</label>
                        <div class="col-sm-12">
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $staff->name) }}"
                                class="form-control"
                                maxlength="{{ config('const.maxlength.staffs.name') }}"
                            />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-group" id="staffName">
                        <label class="col-sm-6 control-label">{{ __('staff.EMail') }}</label>
                        <div class="col-sm-12">
                            <input
                                type="text"
                                name="email"
                                value="{{ old('email', $staff->email) }}"
                                class="form-control"
                                maxlength="{{ config('const.maxlength.staffs.email') }}"
                            />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-group" id="staffName">
                        <label class="col-sm-6 control-label">{{ __('staff.Role') }}</label>
                        <div class="col-sm-12">
                            <select
                                name="role"
                                class="form-control"
                            >
                                <option value="">未選択</option>
                                @foreach(App\Enums\AdminRole::cases() as $item)
                                    <option
                                        value="{{ $item->value }}"
                                        {{ ($item->value === old('role', $staff->role)) ? 'selected' : '' }}
                                    >{{ $item->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center  ">
                <button
                    class="btn btn-info"
                    type="submit"
                >
                    {{ __('common.Execute') }}
                </button>
            </div>
        </div>
    </form>

@endsection
