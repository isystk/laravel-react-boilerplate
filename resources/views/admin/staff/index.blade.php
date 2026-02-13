@extends('layouts.admin')
@section('title', __('staff.Staff List'))
@section('mainMenu', 'system')
@section('subMenu', 'staff')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.staff') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-body text-center">
            <div class="btn-container d-flex justify-content-between">
                <a href="{{ route('admin.staff.create') }}"
                   class="btn btn-primary m-auto"
                   @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>
                    {{ __('common.Regist') }}
                </a>
                <a href="{{ route('admin.staff.import') }}"
                   class="btn btn-primary position-absolute"
                   style="right: 20px"
                   @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>
                    {{ __('common.Import') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card card-purple">
        <div class="card-header">
            <h3 class="card-title">{{ __('common.Search Condition') }}</h3>
        </div>
        <form action="{{ route('admin.staff') }}"
              method="GET">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success"
                         role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="mb-3 row">
                    <label for="name"
                           class="col-sm-2 col-form-label">{{ __('staff.Name') }}</label>
                    <div class="col-sm-4">
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ request()->name }}"
                               class="form-control"
                               maxlength="{{ config('const.maxlength.admins.name') }}" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="email"
                           class="col-sm-2 col-form-label">{{ __('staff.EMail') }}</label>
                    <div class="col-sm-4">
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ request()->email }}"
                               class="form-control"
                               maxlength="{{ config('const.maxlength.admins.email') }}" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="role"
                           class="col-sm-2 col-form-label">{{ __('staff.Role') }}</label>
                    <div class="col-sm-4">
                        <select name="role"
                                id="role"
                                class="form-select">
                            <option value="">未選択</option>
                            @foreach (App\Enums\AdminRole::cases() as $item)
                                <option value="{{ $item->value }}"
                                        {{ $item->value == request()->role ? 'selected' : '' }}>{{ $item->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit"
                        class="btn btn-secondary">{{ __('common.Search') }}</button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.staff') }}"
          method="GET"
          id="pagingForm">
        <input type="hidden"
               name="name"
               value="{{ request()->name }}" />
        <input type="hidden"
               name="email"
               value="{{ request()->email }}" />
        <input type="hidden"
               name="role"
               value="{{ request()->role }}" />
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
                                    'params' => ['id', __('staff.ID')],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['name', __('staff.Name')],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['email', __('staff.EMail')],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['role', __('staff.Role')],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['created_at', __('common.Registration Date')],
                                ])
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($staffs as $staff)
                                <tr>
                                    <th>{{ $staff->id }}</th>
                                    <td>{{ $staff->name }}</td>
                                    <td>{{ $staff->email }}</td>
                                    <td>{{ $staff->role->label() }}</td>
                                    <td>{{ $staff->created_at }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                           href="{{ route('admin.staff.show', ['staff' => $staff]) }}">{{ __('common.Detail') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer  ">
                    {!! $staffs->links('admin.parts.pagination') !!}
                </div>
            </div>
        </div>
    </div>
@endsection
