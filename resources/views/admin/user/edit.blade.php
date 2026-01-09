@extends('layouts.admin')
@section('title', $user->name . __('common.Of Change'))
@section('mainMenu', 'user')
@section('subMenu', 'user')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.user.edit', $user) }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a class="btn btn-secondary"
           href="{{ route('admin.user.show', ['user' => $user]) }}">{{ __('common.Back') }}</a>
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
                  action="{{ route('admin.user.update', ['user' => $user]) }}">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label for="name"
                           class="form-label">{{ __('user.Name') }}</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $user->name) }}"
                           class="form-control"
                           maxlength="{{ config('const.maxlength.users.name') }}" />
                </div>

                <div class="mb-3">
                    <label for="email"
                           class="form-label">{{ __('user.EMail') }}</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email', $user->email) }}"
                           class="form-control"
                           maxlength="{{ config('const.maxlength.users.email') }}" />
                </div>
                <div class="card-footer text-center  ">
                    <input class="btn btn-primary"
                           type="submit"
                           value="{{ __('common.Execute') }}" />
                </div>
            </form>
        </div>
    </div>
@endsection
