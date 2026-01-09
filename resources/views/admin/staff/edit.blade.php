@extends('layouts.admin')
@section('title', $staff->name . __('common.Of Change'))
@section('mainMenu', 'system')
@section('subMenu', 'staff')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.staff.edit', $staff) }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a class="btn btn-secondary"
           href="{{ route('admin.staff.show', ['staff' => $staff]) }}">{{ __('common.Back') }}</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success"
             role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="m-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-purple">
        <div class="card-body">
            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ route('admin.staff.update', ['staff' => $staff]) }}">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="name"
                           class="form-label">{{ __('staff.Name') }}</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $staff->name) }}"
                           class="form-control"
                           maxlength="{{ config('const.maxlength.staffs.name') }}" />
                </div>

                <div class="form-group">
                    <label for="email"
                           class="form-label">{{ __('staff.EMail') }}</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email', $staff->email) }}"
                           class="form-control"
                           maxlength="{{ config('const.maxlength.staffs.email') }}" />
                </div>

                <div class="form-group">
                    <label for="role"
                           class="form-label">{{ __('staff.Role') }}</label>
                    <select name="role"
                            id="role"
                            class="form-select">
                        <option value="">未選択</option>
                        @foreach (App\Enums\AdminRole::cases() as $item)
                            <option value="{{ $item->value }}"
                                    {{ $item->value === old('role', $staff->role->value) ? 'selected' : '' }}>
                                {{ $item->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="card-footer text-center  ">
                    <button class="btn btn-primary"
                            type="submit">
                        {{ __('common.Execute') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
