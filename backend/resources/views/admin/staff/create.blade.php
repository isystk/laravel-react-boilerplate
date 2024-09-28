@extends('layouts.app_admin')

@section('title', __('staff.Staff Regist'))
@php
    $menu = 'master';
    $subMenu = 'staff';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.staff.create') }}
@endsection

@section('content')
    <div class="text-left mb-3">
        <a class="btn btn-secondary" href="{{ route('admin.staff') }}">{{ __('common.Back') }}</a>
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
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.staff.store') }}">
        @csrf
        <div class="card card-purple">
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-6 control-label">{{ __('staff.Name') }}</label>
                    <div class="col-sm-12">
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control"
                            maxlength="{{ config('const.maxlength.staffs.name') }}"
                        />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-6 control-label">{{ __('staff.EMail') }}</label>
                    <div class="col-sm-12">
                        <input
                            type="text"
                            name="email"
                            value="{{ old('email') }}"
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
                                    {{ ($item->value === old('role')) ? 'selected' : '' }}
                                >{{ $item->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-6 control-label">{{ __('staff.Password') }}</label>
                    <div class="col-sm-12">
                        <input
                            type="password"
                            name="password"
                            value="{{ old('password') }}"
                            class="form-control"
                            maxlength="{{ config('const.maxlength.staffs.password') }}"
                        />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-6 control-label">{{ __('staff.PasswordConfirm') }}</label>
                    <div class="col-sm-12">
                        <input
                            type="password"
                            name="password_confirmation"
                            value="{{ old('password_confirmation') }}"
                            class="form-control"
                            maxlength="{{ config('const.maxlength.staffs.password') }}"
                        />
                    </div>
                </div>
            </div>
            <div class="card-footer text-center  ">
                <input class="btn btn-info" type="submit" value="{{__('common.Execute')}}" />
            </div>
        </div>
    </form>
@endsection
