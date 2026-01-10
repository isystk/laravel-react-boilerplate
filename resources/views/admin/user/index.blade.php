@extends('layouts.admin')
@section('title', __('user.User List'))
@section('mainMenu', 'user')
@section('subMenu', 'user')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.user') }}
@endsection

@section('content')

    <div class="card card-purple">
        <div class="card-header">
            <h3 class="card-title">{{ __('common.Search Condition') }}</h3>
        </div>
        <form action="{{ route('admin.user') }}"
              method="GET">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success"
                         role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="mb-3 row">
                    <label for="search_user_name"
                           class="col-sm-2 col-form-label">{{ __('user.Name') }}</label>
                    <div class="col-sm-4">
                        <input type="text"
                               name="name"
                               id="search_user_name"
                               value="{{ request()->name }}"
                               class="form-control"
                               maxlength="{{ config('const.maxlength.users.name') }}" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="search_user_email"
                           class="col-sm-2 col-form-label">{{ __('user.EMail') }}</label>
                    <div class="col-sm-8">
                        <input type="email"
                               name="email"
                               id="search_user_email"
                               value="{{ request()->email }}"
                               class="form-control"
                               maxlength="{{ config('const.maxlength.users.email') }}" />
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit"
                        class="btn btn-secondary">{{ __('common.Search') }}</button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.user') }}"
          method="GET"
          id="pagingForm">
        <input type="hidden"
               name="name"
               value="{{ request()->name }}" />
        <input type="hidden"
               name="email"
               value="{{ request()->email }}" />
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
                                @include('admin.common.sortablelink_th', [
                                    'params' => ['id', __('user.ID')],
                                ])
                                @include('admin.common.sortablelink_th', [
                                    'params' => ['name', __('user.Name')],
                                ])
                                @include('admin.common.sortablelink_th', [
                                    'params' => ['email', __('user.EMail')],
                                ])
                                @include('admin.common.sortablelink_th', [
                                    'params' => ['created_at', __('common.Registration Date')],
                                ])
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th>{{ $user->id }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                           href="{{ route('admin.user.show', ['user' => $user]) }}">{{ __('common.Detail') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer  ">
                    {!! $users->links('admin.common.pagination') !!}
                </div>
            </div>
        </div>
    </div>
@endsection
